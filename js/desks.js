/* Desk administration */
const addDeskForm = document.getElementById("add-desk-form");
const adminDesksBody = document.getElementById("admin-desks-body");

adminDesksBody.addEventListener("change", function (event) {
    if (!event.target.matches('input[type="number"]')) {
        return;
    }

    const heightInput = event.target;

    if (!heightInput.checkValidity()) {
        heightInput.reportValidity();
        return;
    }

    const deskRow = heightInput.closest("tr");
    const heightCell = deskRow.cells[5];

    heightCell.textContent = `${heightInput.value} cm`;
});

function removeDesk(event) {
    const button = event.currentTarget;
    const row = button.closest("tr");
    const deskId = row.cells[0].textContent;

    const confirmed = window.confirm(
        `Are you sure you want to remove desk ${deskId}?`
    );

    if (!confirmed) {
        return;
    }

    row.remove();
    document.dispatchEvent(new Event("desksUpdated"));
}

document
    .querySelectorAll("#admin-desks-body button.danger")
    .forEach(function (button) {
        button.addEventListener("click", removeDesk);
    });

addDeskForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const building = document.getElementById("desk-building").value;
    const floor = document.getElementById("desk-floor").value;
    const room = document.getElementById("desk-room").value;
    const deskNumber = document.getElementById("desk-number").value;
    const deskId = `B${building}-F${floor}-R${room}-D${deskNumber}`;
    const deskAlreadyExists = Array.from(adminDesksBody.rows).some(
        function (row) {
            const existingDeskId = row.cells[0].textContent
                .trim()
                .toLowerCase();

            return existingDeskId === deskId.toLowerCase();
        }
    );

    if (deskAlreadyExists) {
        window.alert(
            "That desk already exists. Please enter a different location or desk number."
        );
        return;
    }

    const row = document.createElement("tr");
    const deskIdCell = document.createElement("td");
    const buildingCell = document.createElement("td");
    const floorCell = document.createElement("td");
    const roomCell = document.createElement("td");
    const deskNumberCell = document.createElement("td");
    const heightCell = document.createElement("td");
    const setHeightCell = document.createElement("td");
    const removeCell = document.createElement("td");
    const heightInput = document.createElement("input");
    const removeButton = document.createElement("button");

    deskIdCell.textContent = deskId;
    buildingCell.textContent = building;
    floorCell.textContent = floor;
    roomCell.textContent = room;
    deskNumberCell.textContent = deskNumber;
    heightCell.textContent = "72 cm";

    heightInput.type = "number";
    heightInput.value = "72";
    heightInput.min = "65";
    heightInput.max = "125";
    heightInput.setAttribute("aria-label", `Set height for ${deskId}`);

    removeButton.type = "button";
    removeButton.className = "danger";
    removeButton.textContent = "Remove";
    removeButton.addEventListener("click", removeDesk);

    setHeightCell.appendChild(heightInput);
    removeCell.appendChild(removeButton);

    row.appendChild(deskIdCell);
    row.appendChild(buildingCell);
    row.appendChild(floorCell);
    row.appendChild(roomCell);
    row.appendChild(deskNumberCell);
    row.appendChild(heightCell);
    row.appendChild(setHeightCell);
    row.appendChild(removeCell);

    adminDesksBody.appendChild(row);
    addDeskForm.reset();
    document.dispatchEvent(new Event("desksUpdated"));
});
