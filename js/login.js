document.getElementById("toggle-password").addEventListener("click", function(e) {
  var passwordInput = document.getElementById("contrasena");
  if (passwordInput.type === "password") {
      passwordInput.type = "text";
      e.target.classList.add("fa-eye-slash");
      e.target.classList.remove("fa-eye");
  } else {
      passwordInput.type = "password";
      e.target.classList.add("fa-eye");
      e.target.classList.remove("fa-eye-slash");
  }
});

window.onload = function() {
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            var alertContainer = document.querySelector('.alert-container');
            if (alertContainer) {
                var loadingMessage = document.createElement('p');
                loadingMessage.innerText = 'Iniciando sesión, por favor espere...';
                loadingMessage.style.color = '#fff';
                alertContainer.innerHTML = '';
                alertContainer.appendChild(loadingMessage);
            }
        });
    }
};