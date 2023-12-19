document.addEventListener("DOMContentLoaded", function () {
    // Chargez la liste des appartements dans le sélecteur
    loadApartmentOptions();

    // Ajoutez un écouteur d'événement au formulaire de modification
    const editForm = document.getElementById("apartment_edit");
    editForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche la soumission normale du formulaire

        // Récupérez les valeurs du formulaire
        const apartmentId = document.getElementById("apartment-id").value;
        const surfaceArea = document.getElementById("surface-area").value;
        const capacity = document.getElementById("capacity").value;
        const address = document.getElementById("address").value;
        const availabilityInput = document.getElementById("availability");
const availability = availabilityInput.checked;

        const nightPrice = document.getElementById("night-price").value;

        // Construisez l'objet avec les données du formulaire
        const apartmentData = {
            surface_area: surfaceArea,
            capacity: capacity,
            address: address,
            availability: availability,
            night_price: nightPrice
        };

        // Envoyez les données au serveur pour la mise à jour (vous devez implémenter cette fonction)
        updateApartment(apartmentId, apartmentData);
    });
});

function loadApartmentOptions() {
    fetch("http://localhost:8082/index.php/apartments")
        .then((res) => res.json())
        .then((apartments) => {
            const apartmentIdSelect = document.getElementById("apartment-id");

            // Supprimez les options existantes
            apartmentIdSelect.innerHTML = "";

            // Ajoutez les options basées sur les appartements disponibles
            apartments.forEach((apartment) => {
                const option = document.createElement("option");
                option.value = apartment.id;
                option.textContent = `${apartment.address} - ${apartment.surface_area}m²`;
                apartmentIdSelect.appendChild(option);
            });

            // Chargez les informations de l'appartement sélectionné lorsqu'il est modifié
            apartmentIdSelect.addEventListener("change", function () {
                const selectedApartmentId = this.value;
                loadApartmentInfo(selectedApartmentId);
            });
        })
        .catch((err) => console.log(err));
}

function loadApartmentInfo(apartmentId) {
    // Chargez les informations de l'appartement en fonction de son ID (vous devez implémenter cette fonction)
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`)
        .then((res) => res.json())
        .then((apartment) => {
            // Remplissez les champs du formulaire avec les informations de l'appartement
            document.getElementById("surface-area").value = apartment.surface_area;
            document.getElementById("capacity").value = apartment.capacity;
            document.getElementById("address").value = apartment.address;
            document.getElementById("availability").value = apartment.availability.toString();
            document.getElementById("night-price").value = apartment.night_price;
        })
        .catch((err) => console.log(err));
}

function updateApartment(apartmentId, apartmentData) {
    // Envoyez les données au serveur pour la mise à jour (vous devez implémenter cette fonction)
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(apartmentData),
    })
        .then((response) => {
            if (!response.ok) {
                // Si la réponse du serveur n'est pas OK, lancez une exception avec le statut
                throw new Error("HTTP error, status = " + response.status);
            }
            // Si tout va bien, retournez la réponse JSON
            return response.json();
        })
        .then((data) => {
            // Traitez la réponse du serveur (par exemple, affichez un message de succès)
            console.log("Apartment updated successfully:", data);
        })
        .catch((error) => {
            // Traitez les erreurs (par exemple, affichez un message d'erreur)
            console.error("Error updating apartment:", error.message);
        });
}
