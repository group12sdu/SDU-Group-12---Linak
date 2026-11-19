/* Cleaning administration */

const cleaningSettingsForm = document.getElementById(
    "cleaning-settings-form"
);
const cleaningStatus = document.getElementById("cleaning-status");
const startCleaningButton = document.getElementById(
    "start-cleaning-button"
);
const endCleaningButton = document.getElementById(
    "end-cleaning-button"
);
const cleaningEnabled = document.getElementById("cleaning-enabled");
const cleaningTime = document.getElementById("cleaning-time");
const cleaningUseMax = document.getElementById("cleaning-use-max");
const cleaningHeight = document.getElementById("cleaning-height");

let cleaningSettings = {
    enabled: cleaningEnabled.checked,
    time: cleaningTime.value,
    useMaximumHeight: cleaningUseMax.checked,
    fixedHeight: cleaningHeight.value
};
let cleaningTimer = null;
startCleaningButton.addEventListener("click", function () {
    const targetHeight = cleaningSettings.useMaximumHeight
        ? "maximum height"
        : `${cleaningSettings.fixedHeight} cm`;

    startCleaningButton.disabled = true;
    endCleaningButton.disabled = true;

    cleaningStatus.textContent =
        `Status: Moving desks to ${targetHeight}...`;

    cleaningTimer = window.setTimeout(function () {
        cleaningStatus.textContent =
            `Status: Active. Desks are at ${targetHeight}.`;

        endCleaningButton.disabled = false;
    }, 3000);
});

cleaningSettingsForm.addEventListener("submit", function (event) {
    event.preventDefault();

    if (!cleaningUseMax.checked && !cleaningHeight.checkValidity()) {
        cleaningHeight.reportValidity();
        return;
    }

    cleaningSettings = {
        enabled: cleaningEnabled.checked,
        time: cleaningTime.value,
        useMaximumHeight: cleaningUseMax.checked,
        fixedHeight: cleaningHeight.value
    };

    cleaningStatus.textContent =
        "Status: Cleaning preset saved. Cleaning mode is inactive.";
});

startCleaningButton.addEventListener("click", function () {
    const targetHeight = cleaningSettings.useMaximumHeight
        ? "maximum height"
        : `${cleaningSettings.fixedHeight} cm`;

    cleaningStatus.textContent =
        `Status: Active. Desks are moving to ${targetHeight}.`;
});

endCleaningButton.addEventListener("click", function () {
    if (cleaningTimer !== null) {
        window.clearTimeout(cleaningTimer);
        cleaningTimer = null;
    }

    startCleaningButton.disabled = false;
    endCleaningButton.disabled = true;

    cleaningStatus.textContent =
        "Status: Inactive. Desk heights restored.";
});