$(document).ready(function () {
  const navbar = document.querySelector(".sticky-nav");
  const userButton = document.querySelector(".user-section");
  const userDropdown = document.querySelector(".user-dropdown");
  const loginToggle = document.querySelector(".login-toggle");
  const loginDropdown = document.querySelector(".login-dropdown");

  window.addEventListener("scroll", function () {
    if (window.scrollY > 100) {
      navbar.classList.add("small-nav");
    } else {
      navbar.classList.remove("small-nav");
    }
  });

  if (document.querySelector(".user-section")) {
    userButton.addEventListener("click", function () {
      if (userDropdown.classList.contains("display-none")) {
        userDropdown.classList.toggle("display-none");

        setTimeout(() => {
          userDropdown.classList.toggle("show");
        }, 100);
      } else {
        userDropdown.classList.toggle("show");
        userDropdown.classList.toggle("display-none");
      }
    });
  }

  if (document.querySelector(".login-toggle")) {
    loginToggle.addEventListener("click", function () {
      if (registerDropdown.classList.contains("show")) {
        registerDropdown.classList.toggle("show");
        registerDropdown.classList.toggle("display-none");
      }

      if (loginDropdown.classList.contains("display-none")) {
        loginDropdown.classList.toggle("display-none");

        setTimeout(() => {
          loginDropdown.classList.toggle("show");
        }, 100);
      } else {
        loginDropdown.classList.toggle("show");
        setTimeout(() => {
          loginDropdown.classList.toggle("display-none");
        }, 100);
      }
    });
  }

  const registerToggle = document.querySelector(".register-link");
  const registerDropdown = document.querySelector(".register-dropdown");

  if (document.querySelector(".register-link")) {
    registerToggle.addEventListener("click", function () {
      if (registerDropdown.classList.contains("display-none")) {
        loginDropdown.classList.toggle("show");
        loginDropdown.classList.toggle("display-none");
        registerDropdown.classList.toggle("display-none");

        setTimeout(() => {
          registerDropdown.classList.toggle("show");
        }, 100);
      } else {
        registerDropdown.classList.toggle("show");
        registerDropdown.classList.toggle("display-none");
      }
    });
  }

  if (document.querySelector(".login-dropdown")) {
    const loginExitSvg = document.querySelector(".login-exit-svg");

    loginExitSvg.addEventListener("click", function () {
      loginDropdown.classList.toggle("show");
      setTimeout(() => {
        loginDropdown.classList.toggle("display-none");
      }, 100);
    });
  }

  const navbarInner = document.getElementById("navBar");
  const navbartoggle = document.getElementById("navToggle");

  navbartoggle.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default action if any
    event.stopPropagation(); // Stop event bubbling
    navbarInner.classList.toggle("collapse-nav");
    navbarInner.classList.toggle("show-nav");
    console.log("Navbar toggled");
  });

  const loginLink = document.querySelector(".login-link");

  if (document.querySelector(".login-link")) {
    loginLink.addEventListener("click", function () {
      if (loginDropdown.classList.contains("display-none")) {
        registerDropdown.classList.toggle("show");
        registerDropdown.classList.toggle("display-none");
        loginDropdown.classList.toggle("display-none");

        setTimeout(() => {
          loginDropdown.classList.toggle("show");
        }, 100);
      } else {
        loginDropdown.classList.toggle("show");
        loginDropdown.classList.toggle("display-none");
      }
    });
  }

  if (document.querySelector(".register-dropdown")) {
    const registerExitSvg = document.querySelector(".register-exit-svg");

    registerExitSvg.addEventListener("click", function () {
      registerDropdown.classList.toggle("show");

      setTimeout(() => {
        registerDropdown.classList.toggle("display-none");
      }, 100);
    });
  }
});






