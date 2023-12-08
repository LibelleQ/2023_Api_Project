const div = document.querySelector("#data-to-replace");
const button = document.querySelector("#fetch-button");

function checkTodo(e) {
  const id = e.target.id.split("-")[1];
  fetch(`http://localhost:8081/index.php/todos/${id}`, {
    method: "PATCH",
    body: JSON.stringify({
      done: e.target.checked,
    }),
  }).then(() => fetchContent());
}

// On crée une fonction qui va permettre la mise à jour de notre element de la DOM
function fetchContent() {
  // On fait la requête vers l'API:
  // Protocole: http
  // hostname: localhost (alias 'api' de docker-compose inaccessible depuis le client)
  // port: 8081 (Exposé pour l'API)
  // chemin: index.php/hello (Pour n'avoir que "hello", il faut utiliser le rewrite engine de Apache)
  fetch("http://localhost:8081/index.php/todos")
    .then((res) => {
      // Parser le retour de la requête en JSON
      res.json().then((content) => {
        div.innerHTML = "";
        // Afficher le contenu du retour de la requête
        content.forEach((todo) => {
          div.appendChild(document.createElement("li")).innerHTML = ` 
                <div class="list-item">
                    <div>
                    ${todo.description}
                    </div>
                    <input class="todo-checkbox" type="checkbox" id="check-${
                      todo.id
                    }" ${todo.done === "t" ? "checked" : ""} />
                </div>
                `;
        });
        const checkboxes = document.querySelectorAll(".todo-checkbox");
        checkboxes.forEach((checkbox) => {
          checkbox.addEventListener("click", checkTodo);
        });
      });
    })

    // Afficher l'erreur s'il y en a une
    .catch((err) => console.log(err));
}

// Mettre la fonction sur le bouton pour le faire lancer l'appel API
fetchContent();
