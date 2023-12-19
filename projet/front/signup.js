document.addEventListener("DOMContentLoaded", function () {
    const signupButton = document.getElementById("apartment");
  
    signupButton.addEventListener("click", function () {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;
      const role = document.getElementById("role").value;
  
      // Validez les entrées (ajoutez vos propres règles de validation si nécessaire)
  
      // Créez un objet avec les données du formulaire
      const userData = {
        username: username,
        password: password,
        role: role,
      };
  
      // Envoyez les données au serveur (vous devez implémenter cette fonction)
      createUser(userData);
    });
  
    function createUser(userData) {
      const messageContainer = document.getElementById("message-container");
      // Effacez les messages précédents
      messageContainer.innerHTML = "";
  
      fetch("http://localhost:8082/index.php/users", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(userData),
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
          const successMessage = document.createElement("p");
          successMessage.textContent = "User created successfully";
          messageContainer.appendChild(successMessage);
        })
        .catch((error) => {
          // Traitez les erreurs (par exemple, affichez un message d'erreur)
          const errorMessage = document.createElement("p");
          errorMessage.textContent = "Error creating user: " + error.message;
          messageContainer.appendChild(errorMessage);
        });
    }
  });
  