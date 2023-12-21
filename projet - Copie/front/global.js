document.addEventListener("DOMContentLoaded", function () {

    function getLoggedInUser() {
      const userData = localStorage.getItem("user");
      return userData ? JSON.parse(userData) : null;
    }
    function updateMenu() {
      const user = getLoggedInUser();
      const menuContainer = document.getElementById("menu-container");
  
      if (user && user.username) {
        menuContainer.innerHTML = `
          <li><a href="#">Mon Compte (${user.username})</a></li>
          <li><a href="#" id="logout-link">Déconnexion</a></li>
        `;

        document.getElementById("logout-link").addEventListener("click", logout);
      } else {
        menuContainer.innerHTML = `<li><a href="signup.html">Connexion/Inscription</a></li>`;
      }
    }
    function logout() {
      localStorage.removeItem("user");
      updateMenu();
    }
    updateMenu();
  });
  