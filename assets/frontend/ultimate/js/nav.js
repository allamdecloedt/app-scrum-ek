$(document).ready(function () {
  let lastScrollTop = 0;
  const navbar = document.querySelector(".sticky-nav");
  const navbarinner = document.querySelector("#navBar");
  const navbartoggle = document.querySelector(".toggle");
  const navbarHeight = navbar.offsetHeight; // Get the height of the navbar to hide it completely
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
      if (loginDropdown.classList.contains("display-none")) {
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
});

window.addEventListener("resize", function () {
  if (window.innerWidth > 992) {
    const navbar = document.querySelector("#navBar");

    if (navbar.classList.contains("show")) {
      navbar.classList.toggle("show");
    }
  }
});
