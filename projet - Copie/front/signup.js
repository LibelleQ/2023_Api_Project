document.addEventListener("DOMContentLoaded", function () {
    const signupButton = document.getElementById("apartment");
  
    signupButton.addEventListener("click", function () {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;
      const role = document.getElementById("role").value;
      const userData = {
        username: username,
        password: password,
        role: role,
      };
      createUser(userData);
    });
  
    function createUser(userData) {
      const messageContainer = document.getElementById("message-container");
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
            throw new Error("HTTP error, status = " + response.status);
          }
          return response.json();
        })
        .then((data) => {
          const successMessage = document.createElement("p");
          successMessage.textContent = "User created successfully";
          messageContainer.appendChild(successMessage);
        })
        .catch((error) => {
          const errorMessage = document.createElement("p");
          errorMessage.textContent = "Error creating user: " + error.message;
          messageContainer.appendChild(errorMessage);
        });
    }
  });
  