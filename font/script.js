document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('searchForm');
    const searchResults = document.getElementById('searchResults');

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const searchTerm = document.getElementById('searchTerm').value;
        fetch('api.php?searchTerm=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => console.error('Erreur lors de la requête AJAX:', error));
    });

    function displayResults(data) {
        searchResults.innerHTML = '';
        displayResultsForTable('Utilisateurs', data.utilisateurs);
        displayResultsForTable('Appartements', data.appartements);
        displayResultsForTable('Réservations', data.reservations);
    }

    function displayResultsForTable(tableName, results) {
        searchResults.innerHTML += `<h3>${tableName}</h3>`;

        if (results.length === 0) {
            searchResults.innerHTML += '<p>Aucun résultat trouvé.</p>';
        } else {
            results.forEach(result => {
                const resultString = Object.keys(result).map(key => `${key}: ${result[key]}`).join(', ');
                searchResults.innerHTML += `<p>${resultString}</p>`;
            });
        }
    }
});
