document.addEventListener("DOMContentLoaded", function () {
    // Fonction pour récupérer l'utilisateur connecté depuis le stockage local
    function getLoggedInUser() {
      const userData = localStorage.getItem("user");
      return userData ? JSON.parse(userData) : null;
    }
  
    // Fonction pour afficher le menu en fonction de la connexion
    function updateMenu() {
      const user = getLoggedInUser();
      const menuContainer = document.getElementById("menu-container");
  
      if (user && user.username) {
        // Utilisateur connecté
        menuContainer.innerHTML = `
          <li><a href="#">Mon Compte (${user.username})</a></li>
          <li><a href="#" id="logout-link">Déconnexion</a></li>
        `;
        // Ajouter un gestionnaire d'événements pour la déconnexion
        document.getElementById("logout-link").addEventListener("click", logout);
      } else {
        // Utilisateur non connecté
        menuContainer.innerHTML = `<li><a href="signup.html">Connexion/Inscription</a></li>`;
      }
    }
  
    // Fonction de déconnexion
    function logout() {
      // Supprimer les informations de l'utilisateur lors de la déconnexion
      localStorage.removeItem("user");
      // Mettre à jour le menu
      updateMenu();
    }
  
    // Appeler la fonction pour mettre à jour le menu lors du chargement de la page
    updateMenu();
  });
  