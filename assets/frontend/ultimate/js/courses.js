more = document.getElementById("more-button");
less = document.getElementById("less-button");
section = document.getElementById("category-section");

less.addEventListener("click", function () {
  more.style.display = "block";
  less.style.display = "none";
  section.style.height = "35px";
   section.style.maxWidth = "60%";

  
});

more.addEventListener("click", function () {
  less.style.display = "block";
  more.style.display = "none";
  section.style.height = "100%";
     section.style.maxWidth = "80%";

});


document.addEventListener("DOMContentLoaded", function () {
    // Retrieve all category links
    const categoryLinks = document.querySelectorAll("#category-section .category .option" );
    
    // Add event listeners to all category links
    categoryLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            // Remove the active class from all links
            categoryLinks.forEach(function (otherLink) {
                otherLink.classList.remove("active-cat");
            });
            
            // Add the active class to the clicked link
            this.classList.add("active-cat");
        });
    });
});


function updateDataAttribute() {
    var element = document.getElementById('img-bot');
    if (window.innerWidth <= 1000) {
        element.setAttribute('data-rellax-speed', '0.4');
    } else {
        element.setAttribute('data-rellax-speed', '1.5');
    }
}

// Run on initial load
updateDataAttribute();

// And re-run on window resize
window.addEventListener('resize', updateDataAttribute);