/* Employee administration */

const addEmployeeForm = document.getElementById("add-employee-form");
const employeesBody = document.getElementById("employees-body");
const submitEmployeeButton = addEmployeeForm.querySelector(
    'button[type="submit"]'
);

let employeeBeingEdited = null;

employeesBody.addEventListener("click", function (event) {
    const clickedButton = event.target;

    if (
        clickedButton.tagName !== "BUTTON" ||
        clickedButton.classList.contains("danger")
    ) {
        return;
    }

    const employeeRow = clickedButton.closest("tr");

    document.getElementById("employee-name").value =
        employeeRow.cells[1].textContent;

    document.getElementById("employee-username").value =
        employeeRow.cells[2].textContent;

    document.getElementById("employee-role").value =
        employeeRow.cells[3].textContent;

    document.getElementById("employee-standing").value =
        getHeightValue(employeeRow.cells[4].textContent);

    document.getElementById("employee-sitting").value =
        getHeightValue(employeeRow.cells[5].textContent);

    document.getElementById("employee-password").value = "";

    employeeBeingEdited = employeeRow;
    submitEmployeeButton.textContent = "Save changes";

    document.getElementById("employee-name").focus();
});

function getHeightValue(heightText) {
    if (heightText === "Not set") {
        return "";
    }

    return heightText.replace(" cm", "");
}

employeesBody.addEventListener("click", function (event) {
    if (!event.target.matches("button.danger")) {
        return;
    }

    const deleteButton = event.target;
    const employeeRow = deleteButton.closest("tr");
    const employeeName = employeeRow.cells[1].textContent;

    const confirmed = window.confirm(
        `Are you sure you want to delete ${employeeName}?`
    );

    if (!confirmed) {
        return;
    }

    employeeRow.remove();
});

addEmployeeForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const name = document.getElementById("employee-name").value.trim();
    const username = document
        .getElementById("employee-username")
        .value.trim();
    const password = document.getElementById("employee-password").value;
    const role = document.getElementById("employee-role").value;
    const standingHeight = document.getElementById("employee-standing").value;
    const sittingHeight = document.getElementById("employee-sitting").value;

    if (!name || !username || !password) {
        window.alert(
            "Name, username, and a new password are required."
        );
        return;
    }
    const usernameAlreadyExists = Array.from(employeesBody.rows).some(
        function (row) {
            const existingUsername = row.cells[2].textContent
                .trim()
                .toLowerCase();

            const isCurrentEmployee = row === employeeBeingEdited;

            return (
                !isCurrentEmployee &&
                existingUsername === username.toLowerCase()
            );
        }
    );

    if (usernameAlreadyExists) {
        window.alert(
            "That username already exists. Please choose another username."
        );
        return;
    }

    if (employeeBeingEdited) {
        updateEmployeeRow(
            employeeBeingEdited,
            name,
            username,
            role,
            standingHeight,
            sittingHeight
        );

        employeeBeingEdited = null;
        submitEmployeeButton.textContent = "Add employee";
        addEmployeeForm.reset();
        return;
    }

    const employeeId = employeesBody.rows.length + 1;
    const row = document.createElement("tr");

    const idCell = document.createElement("td");
    const nameCell = document.createElement("td");
    const usernameCell = document.createElement("td");
    const roleCell = document.createElement("td");
    const standingCell = document.createElement("td");
    const sittingCell = document.createElement("td");
    const actionsCell = document.createElement("td");

    idCell.textContent = employeeId;
    nameCell.textContent = name;
    usernameCell.textContent = username;
    roleCell.textContent = role;
    standingCell.textContent = standingHeight
        ? `${standingHeight} cm`
        : "Not set";
    sittingCell.textContent = sittingHeight
        ? `${sittingHeight} cm`
        : "Not set";

    const editButton = document.createElement("button");
    editButton.type = "button";
    editButton.textContent = "Edit";

    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.className = "danger";
    deleteButton.textContent = "Delete";

    actionsCell.appendChild(editButton);
    actionsCell.appendChild(deleteButton);

    row.appendChild(idCell);
    row.appendChild(nameCell);
    row.appendChild(usernameCell);
    row.appendChild(roleCell);
    row.appendChild(standingCell);
    row.appendChild(sittingCell);
    row.appendChild(actionsCell);

    employeesBody.appendChild(row);
    addEmployeeForm.reset();
});

function updateEmployeeRow(
    row,
    name,
    username,
    role,
    standingHeight,
    sittingHeight
) {
    row.cells[1].textContent = name;
    row.cells[2].textContent = username;
    row.cells[3].textContent = role;
    row.cells[4].textContent = standingHeight
        ? `${standingHeight} cm`
        : "Not set";
    row.cells[5].textContent = sittingHeight
        ? `${sittingHeight} cm`
        : "Not set";
}

addEmployeeForm.addEventListener("reset", function () {
    employeeBeingEdited = null;
    submitEmployeeButton.textContent = "Add employee";
});