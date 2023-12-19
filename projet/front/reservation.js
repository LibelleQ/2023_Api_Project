// reservation.js

document.addEventListener("DOMContentLoaded", function () {
    // ... votre code existant
  
    // Chargez les options de l'ID de l'appartement
    loadApartmentOptions();
  
    // Ajoutez des écouteurs d'événements aux champs de date
    const startDateInput = document.getElementById("start-date");
    const endDateInput = document.getElementById("end-date");
  
    startDateInput.addEventListener("change", updatePrice);
    endDateInput.addEventListener("change", updatePrice);
  

    const reservationForm = document.getElementById("reservation-form");
    reservationForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Empêche la soumission normale du formulaire
  
      // Appel de la fonction pour envoyer la réservation
      submitReservationForm();
    });
});

let totalPrice; // Variable globale pour stocker le prix calculé

  function loadApartmentOptions() {
    // Utilisez une requête fetch pour obtenir la liste des appartements disponibles depuis votre API
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
      })
      .catch((err) => console.log(err));
  }

  
  function updatePrice() {
    // Récupérez la date de début et de fin
    const startDate = new Date(document.getElementById("start-date").value);
    const endDate = new Date(document.getElementById("end-date").value);

    // Récupérez la night_price de l'appartement sélectionné
    const apartmentId = document.getElementById("apartment-id").value;
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`)
        .then((res) => res.json())
        .then((apartment) => {
            // Calculez le nombre de nuits entre la date de début et la date de fin
            const timeDifference = endDate - startDate;
            const numberOfNights = Math.ceil(timeDifference / (1000 * 3600 * 24));

            // Calculez le prix total
            totalPrice = numberOfNights * apartment.night_price;

            // Mettez à jour le contenu de l'élément span pour afficher le prix
            document.getElementById("priceDisplay").textContent = totalPrice.toFixed(2) + " €";
        })
        .catch((err) => console.log(err));
}

function submitReservationForm() {
    // Récupérez les valeurs du formulaire
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;
    const customer_id = document.getElementById("customer-id").value;
    const apartment_id = document.getElementById("apartment-id").value;

    // Utilisez la variable globale totalPrice pour le prix
    const price = totalPrice;

    // Créez un objet avec les données de la réservation
    const reservationData = {
        start_date: startDate,
        end_date: endDate,
        customer_id: customer_id,
        apartment_id: apartment_id,
        price: price,
    };
  
    // Envoyez les données au serveur (vous devez implémenter cette fonction)
    sendReservationData(reservationData);
  }
  
  function sendReservationData(reservationData) {
    fetch("http://localhost:8082/index.php/reservations", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(reservationData),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error submitting reservation: " + response.statusText);
        }
        return response.json();
      })
      .then((data) => {
        // Traitez la réponse du serveur (par exemple, affichez un message de succès)
        const messageContainer = document.getElementById("message-container");
        const successMessage = document.createElement("p");
        successMessage.textContent = "Reservation created successfully";
        messageContainer.appendChild(successMessage);
      })
      .catch((error) => {
        // Traitez les erreurs (par exemple, affichez un message d'erreur)
        const messageContainer = document.getElementById("message-container");
        if (messageContainer) {
            const errorMessage = document.createElement("p");
            errorMessage.textContent = "Error creating reservation: " + error.message;
            messageContainer.appendChild(errorMessage);
        } else {
            console.error("Error container is null:", error);
        }
      });
  }

  