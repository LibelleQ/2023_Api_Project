
document.addEventListener("DOMContentLoaded", function () {
    const signinButton = document.getElementById("signin");

    signinButton.addEventListener("click", function () {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const userData = {
            username: username,
            password: password,
        };


        signinUser(userData);
    });

    function signinUser(userData) {
        const messageContainer = document.getElementById("message-container");
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
                const successMessage = document.createElement("p");
                successMessage.textContent = "User logged in successfully";
                messageContainer.appendChild(successMessage);
                localStorage.setItem("authToken", data.token);
            })
            .catch((error) => {
                const errorMessage = document.createElement("p");
                errorMessage.textContent = "Error logging in: " + error.message;
                messageContainer.appendChild(errorMessage);
            });
    }
});
