
const loginForm = document.getElementById("login-form");
const loginMessage = document.getElementById("login-message");
const logoutLink = document.getElementById("logout-link");

// Handle login form submission
loginForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document
        .getElementById("login-username")
        .value.trim();

    const password = document
        .getElementById("login-password")
        .value;

    if (!username || !password) {
        loginMessage.textContent =
            "Please enter both your username and password.";
        loginMessage.hidden = false;
        return;
    }

    loginMessage.hidden = true;
    window.location.hash = "my-desk-view";
});

// Handle logout
logoutLink.addEventListener("click", function () {
    loginForm.reset();
    loginMessage.hidden = true;
});

// Find the desk preset elements
const standingPreset = document.getElementById("standing-preset");
const sittingPreset = document.getElementById("sitting-preset");
const currentDeskHeight = document.getElementById("current-desk-height");
const deskStatusMessage = document.getElementById("desk-status-message");

// Handle standing preset
standingPreset.addEventListener("click", function () {
    currentDeskHeight.textContent = "112 cm";
    deskStatusMessage.textContent =
        "Standing preset selected. Hardware control is not connected.";
});

// Handle sitting preset
sittingPreset.addEventListener("click", function () {
    currentDeskHeight.textContent = "74 cm";
    deskStatusMessage.textContent =
        "Sitting preset selected. Hardware control is not connected.";
});
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

  