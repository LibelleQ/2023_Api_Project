
document.addEventListener("DOMContentLoaded", function () {
    loadApartmentOptions();
    const startDateInput = document.getElementById("start-date");
    const endDateInput = document.getElementById("end-date");
  
    startDateInput.addEventListener("change", updatePrice);
    endDateInput.addEventListener("change", updatePrice);
  

    const reservationForm = document.getElementById("reservation-form");
    reservationForm.addEventListener("submit", function (event) {
      event.preventDefault();
      submitReservationForm();
    });
});

let totalPrice; 

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
      })
      .catch((err) => console.log(err));
  }

  
  function updatePrice() {
    const startDate = new Date(document.getElementById("start-date").value);
    const endDate = new Date(document.getElementById("end-date").value);
    const apartmentId = document.getElementById("apartment-id").value;
    fetch(`http://localhost:8082/index.php/apartments/${apartmentId}`)
        .then((res) => res.json())
        .then((apartment) => {
            const timeDifference = endDate - startDate;
            const numberOfNights = Math.ceil(timeDifference / (1000 * 3600 * 24));
            totalPrice = numberOfNights * apartment.night_price;
            document.getElementById("priceDisplay").textContent = totalPrice.toFixed(2) + " €";
        })
        .catch((err) => console.log(err));
}

function submitReservationForm() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;
    const customer_id = document.getElementById("customer-id").value;
    const apartment_id = document.getElementById("apartment-id").value;
    const price = totalPrice;
    const reservationData = {
        start_date: startDate,
        end_date: endDate,
        customer_id: customer_id,
        apartment_id: apartment_id,
        price: price,
    };

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
        const messageContainer = document.getElementById("message-container");
        const successMessage = document.createElement("p");
        successMessage.textContent = "Reservation created successfully";
        messageContainer.appendChild(successMessage);
      })
      .catch((error) => {
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

  