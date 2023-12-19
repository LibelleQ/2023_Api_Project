// Signin.js
document.addEventListener("DOMContentLoaded", function () {
    const signinButton = document.getElementById("signin");

    signinButton.addEventListener("click", function () {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        // Validez les entrées (ajoutez vos propres règles de validation si nécessaire)

        // Créez un objet avec les données du formulaire
        const userData = {
            username: username,
            password: password,
        };

        // Envoyez les données au serveur (vous devez implémenter cette fonction)
        signinUser(userData);
    });

    function signinUser(userData) {
        const messageContainer = document.getElementById("message-container");
        // Effacez les messages précédents
        messageContainer.innerHTML = "";

        fetch("http://localhost:8082/index.php/signin", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(userData),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("HTTP error, status = " + response.status);
                }
                return response.json();
            })
            .then((data) => {
                // Traitez la réponse du serveur (par exemple, affichez un message de succès)
                const successMessage = document.createElement("p");
                successMessage.textContent = "User logged in successfully";
                messageContainer.appendChild(successMessage);

                // Stockez le token d'authentification dans le stockage local
                localStorage.setItem("authToken", data.token);

                // Redirigez l'utilisateur vers une autre page ou effectuez d'autres actions après la connexion réussie
                // window.location.href = "/dashboard";
            })
            .catch((error) => {
                const errorMessage = document.createElement("p");
                errorMessage.textContent = "Error logging in: " + error.message;
                messageContainer.appendChild(errorMessage);
            });
    }
});
