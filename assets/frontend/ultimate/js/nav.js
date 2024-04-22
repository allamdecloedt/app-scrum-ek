$(document).ready(function () {
  let lastScrollTop = 0;
  const navbar = document.querySelector(".sticky-nav");
  const navbarinner = document.querySelector("#navBar");
  const navbartoggle = document.querySelector(".toggle");
  const navbarHeight = navbar.offsetHeight; // Get the height of the navbar to hide it completely

  window.addEventListener("scroll", function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop && scrollTop > navbarHeight) {
      // Downscroll and only after scrolling past the navbar height
      navbar.style.top = -navbarHeight + "px"; // Hide the navbar

      if (navbarinner.classList.contains("show")) {
        navbartoggle.click();
      }
    } else if (
      scrollTop + window.innerHeight <
      document.documentElement.scrollHeight
    ) {
      // Check also that user isn't at the bottom of the page
      navbar.style.top = "0"; // Show the navbar
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Avoid negative values
  });
});

$(document).ready(function () {
  if (!window.location.pathname.endsWith("home")) {
    const navbar = document.querySelector(".sticky-nav");
  }
});


window.addEventListener("resize", function () {
    if (window.innerWidth > 992) {
        const navbar = document.querySelector("#navBar");
        if (navbar.classList.contains("show")) {
            navbar.classList.remove("show");
        }
    }
});