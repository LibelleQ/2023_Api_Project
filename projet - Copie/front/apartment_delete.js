document.addEventListener("DOMContentLoaded", function () {
    loadApartmentOptions();
    const deleteForm = document.getElementById("apartment_delete");
    deleteForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const apartmentId = document.getElementById("apartment-id").value;
        deleteApartment(apartmentId);
    });
});

function loadApartmentOptions() {
    fetch("http://localhost:8082/index.php/apartments")
        .then((res) => res.json())
        .then((apartments) => {
            const apartmentIdSelect = document.getElementById("apartment-id");
            apartmentIdSelect.innerHTML = "";
            apartments.forEach((apartment) => {
                const option = document.createElement("option");
                option.value = apartment.id;
                option.textContent = `${apartment.address} - ${apartment.surface_area}m²`;
                apartmentIdSelect.appendChild(option);
            });
            apartmentIdSelect.addEventListener("change", function () {
                const selectedApartmentId = this.value;
                loadApartmentInfo(selectedApartmentId);
            });
        })
        .catch((err) => console.log(err));
}

function loadApartmentInfo(apartmentId) {
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`)
        .then((res) => res.json())
        .then((apartment) => {
            document.getElementById("surface-area").value = apartment.surface_area;
            document.getElementById("capacity").value = apartment.capacity;
            document.getElementById("address").value = apartment.address;
            document.getElementById("availability").value = apartment.availability.toString();
            document.getElementById("night-price").value = apartment.night_price;
        })
        .catch((err) => console.log(err));
}

function deleteApartment(apartmentId) {
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`, {
        method: "DELETE",
    })
        .then((res) => {
            if (!res.ok) {
                throw new Error("HTTP error, status = " + res.status);
            }
            return res.json();
        })
        .then((data) => {
            console.log("Apartment deleted successfully");
        })
        .catch((error) => {
            console.error("Error deleting apartment: " + error.message);
        });
}