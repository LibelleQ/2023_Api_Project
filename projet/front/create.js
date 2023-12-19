const button = document.querySelector("#createApartment");

const surfaceAreaInput = document.querySelector("#surface_area");
const capacityInput = document.querySelector("#capacity");
const addressInput = document.querySelector("#address");
const availabilityInput = document.querySelector("#availability");
const nightPriceInput = document.querySelector("#night_price");
const errorContainer = document.querySelector("#error-container");

button.addEventListener("click", () => {
  fetch("http://localhost:8082/index.php/apartments", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      surface_area: surfaceAreaInput.value,
      capacity: capacityInput.value,
      address: addressInput.value,
      availability: availabilityInput.value === "true",      
      night_price: nightPriceInput.value,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
      } else {
        errorContainer.innerHTML = data.message;
        window.history.pushState({}, "", `?error=${encodeURIComponent(data.message)}`);
      }
    })
    .catch((error) => {
      console.error("Error during apartment creation:", error);
    });
});
