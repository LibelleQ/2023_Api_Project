document.addEventListener("DOMContentLoaded", function () {
    loadApartmentOptions();
    const editForm = document.getElementById("apartment_edit");
    editForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const apartmentId = document.getElementById("apartment-id").value;
        const surfaceArea = document.getElementById("surface-area").value;
        const capacity = document.getElementById("capacity").value;
        const address = document.getElementById("address").value;
        const availabilityInput = document.getElementById("availability");
const availability = availabilityInput.checked;

        const nightPrice = document.getElementById("night-price").value;
        const apartmentData = {
            surface_area: surfaceArea,
            capacity: capacity,
            address: address,
            availability: availability,
            night_price: nightPrice
        };
        updateApartment(apartmentId, apartmentData);
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

function updateApartment(apartmentId, apartmentData) {
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(apartmentData),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("HTTP error, status = " + response.status);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Apartment updated successfully:", data);
        })
        .catch((error) => {
            console.error("Error updating apartment:", error.message);
        });
}
