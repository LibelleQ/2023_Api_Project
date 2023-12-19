document.addEventListener("DOMContentLoaded", function () {
    // Chargez la liste des appartements dans le sélecteur
    loadApartmentOptions();

    // Ajoutez un écouteur d'événement au formulaire de suppression
    const deleteForm = document.getElementById("apartment_delete");
    deleteForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche la soumission normale du formulaire

        // Récupérez l'ID de l'appartement à supprimer
        const apartmentId = document.getElementById("apartment-id").value;

        // Envoyez une requête au serveur pour supprimer l'appartement
        deleteApartment(apartmentId);
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
            // Traitez la réponse du serveur (par exemple, affichez un message de succès)
            console.log("Apartment deleted successfully");
        })
        .catch((error) => {
            // Traitez les erreurs (par exemple, affichez un message d'erreur)
            console.error("Error deleting apartment: " + error.message);
        });
}