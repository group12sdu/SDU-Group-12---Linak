/* My desk presets */
const standingPreset = document.getElementById("standing-preset");
const sittingPreset = document.getElementById("sitting-preset");
const currentDeskHeight = document.getElementById("current-desk-height");
const deskStatusMessage = document.getElementById("desk-status-message");

standingPreset.addEventListener("click", function () {
    currentDeskHeight.textContent = "112 cm";
    deskStatusMessage.textContent =
        "Standing preset selected. Hardware control is not connected.";
});

sittingPreset.addEventListener("click", function () {
    currentDeskHeight.textContent = "74 cm";
    deskStatusMessage.textContent =
        "Sitting preset selected. Hardware control is not connected.";
});

/* Desk height controls */
document.querySelectorAll("#my-desk-view tbody tr").forEach(function (row) {
    const heightCell = row.querySelector(".desk-height");
    const modeLabel = row.querySelector(".desk-mode");
    const standingButton = row.querySelector(".desk-standing");
    const sittingButton = row.querySelector(".desk-sitting");

    standingButton.addEventListener("click", function () {
        heightCell.textContent = "112 cm";
        modeLabel.textContent = "Standing";
        modeLabel.classList.add("is-standing");
    });

    sittingButton.addEventListener("click", function () {
        heightCell.textContent = "74 cm";
        modeLabel.textContent = "Sitting";
        modeLabel.classList.remove("is-standing");
    });
});
/* Dashboard summary */

const connectedDeskCount = document.getElementById(
    "connected-desk-count"
);
const onlineDeskCount = document.getElementById(
    "online-desk-count"
);

function updateDeskSummary() {
    const deskRows = document.querySelectorAll(
        "#my-desk-view tbody tr"
    );

    const numberOfDesks = deskRows.length;

    connectedDeskCount.textContent = numberOfDesks;
    onlineDeskCount.textContent = numberOfDesks;
}

document.addEventListener("desksUpdated", updateDeskSummary);

updateDeskSummary();