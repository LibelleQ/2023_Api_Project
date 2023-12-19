const div = document.querySelector("#data-to-replace");

function toggleApartmentAvailability(e) {
  const id = e.target.id.split("-")[1];
  fetch(`http://localhost:8082/index.php/apartments/${id}`, {
    method: "PATCH",
    body: JSON.stringify({
      availability: e.target.checked,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then(() => fetchApartments())
    .catch((err) => console.log(err));
}

function fetchApartments() {
  fetch("http://localhost:8082/index.php/apartments")
    .then((res) => res.json())
    .then((apartments) => {
      div.innerHTML = "";
      const table = document.createElement("table");
      table.innerHTML = `
        <thead>
          <tr>
            <th>Address</th>
            <th>Surface Area (m²)</th>
            <th>Capacity</th>
            <th>Availability</th>
            <th>Night Price</th>
          </tr>
        </thead>
        <tbody></tbody>
      `;

      const tbody = table.querySelector("tbody");

      apartments.forEach((apartment) => {
        const row = tbody.insertRow();
        row.innerHTML = `
          <td>${apartment.address}</td>
          <td>${apartment.surface_area}</td>
          <td>${apartment.capacity}</td>
          <td>${apartment.availability ? "Available" : "Not Available"}</td>
          <td>${apartment.night_price}</td>
        `;
      });

      div.appendChild(table);
    })
    .catch((err) => console.log(err));
}


// Charger la liste des appartements au chargement de la page
fetchApartments();
