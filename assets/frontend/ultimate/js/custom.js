document.addEventListener("DOMContentLoaded", function () {
  var left = document.querySelector(".left");
  var right = document.querySelector(".right");

  left.addEventListener("mouseenter", function () {
    left.style.width = "60%";
    left.style.fontSize = "3.5em";
  });

  right.addEventListener("mouseenter", function () {
    right.style.width = "60%";
    left.style.width = "40%";
  });

  // Optional: Revert to 50% width for both on mouse leave
  left.addEventListener("mouseleave", function () {
    left.style.width = "50%";
    right.style.width = "50%";
  });

  right.addEventListener("mouseleave", function () {
    left.style.width = "50%";
    right.style.width = "50%";
  });
});




(function animation() {
  // General Variables
  var Particle, particleCount, particles, sketch, z;

  function calculateParticleCount() {
    if (window.innerWidth > 1200) {
      return (count = 300);
    } else if (window.innerWidth > 992) {
      return (count = 200);
    } else if (window.innerWidth > 768) {
      return (count = 100);
    } else {
      return (count = 50);
    }
  }

  //Coloring options for particles

  var hue = 267;
  var saturation = 100;
  var lightness = 80;
  var alpha = 0.4;
  //--------------------------------

  var sketch = Sketch.create({
      container: document.getElementById("intro-container"),
      autopause: false,
      retina: "auto",
      autoclear: true,
      setup: function () {},

      // This function is called on window resize to resize the canvas and render content correctly
      resize: function () {
        // Here you can adjust the canvas size if needed

        this.width = window.innerWidth;
        this.height = window.innerHeight;

        // Recalculate and update particles based on new size

        this.resetGlobalStyles();

        console.log(particleCount);
      },
      draw: function () {
        // Drawing operations go here
      },

      // This function is resets the global styles of the canvas to ensure it renders correctly after being called
      resetGlobalStyles: function () {
        // Reset globalCompositeOperation and strokeStyle to maintain consistency
        this.globalCompositeOperation = "lighter";
        this.strokeStyle =
          "hsla(" +
          hue +
          "," +
          saturation +
          "%," +
          lightness +
          "%, " +
          alpha +
          ")";
      },
    }),
    particles = [];

  particleCount = calculateParticleCount();

  // Set the number of particles to be rendered

  //sets the mouse position to the center of the screen
  sketch.mouse.x = sketch.width / 2;
  //sets the mouse position to the center of the screen
  sketch.mouse.y = sketch.height / 2;

  // Particle Constructor
  Particle = function () {
    this.x = random(sketch.width);
    this.y = random(sketch.height, sketch.height * 2);
    this.vx = 0;
    this.vy = -random(1, 10) / 5;
    this.radius = this.baseRadius = 1;
    this.maxRadius = 10;
    this.threshold = 150;
    return (this.hue = random(500));
  };

  // Particle Prototype
  Particle.prototype = {
    update: function () {
      var dist, distx, disty, radius;
      // Determine Distance From Mouse
      distx = this.x - sketch.mouse.x;
      disty = this.y - sketch.mouse.y;
      dist = sqrt(distx * distx + disty * disty);

      // Set Radius
      if (dist < this.threshold) {
        radius =
          this.baseRadius +
          ((this.threshold - dist) / this.threshold) * this.maxRadius;
        this.radius = radius > this.maxRadius ? this.maxRadius : radius;
      } else {
        this.radius = this.baseRadius;
      }

      // Adjust velocity at which particles move

      //Horizontal velocity
      this.vx = 0;

      //Vertical velocity
      this.vy -= 0.0000000001;

      // Apply Velocity
      this.x += this.vx;
      this.y += this.vy;

      // Check Bounds
      if (
        this.x < -this.maxRadius ||
        this.x > sketch.width + this.maxRadius ||
        this.y < -this.maxRadius
      ) {
        this.x = random(sketch.width);
        this.y = random(sketch.height + this.maxRadius, sketch.height * 2);
        this.vx = 0;
        return (this.vy = -random(1, 10) / 5);
      }
    },
    render: function () {
      sketch.beginPath();
      sketch.arc(this.x, this.y, this.radius, 0, TWO_PI);
      sketch.closePath();
      sketch.fillStyle = "hsla(" + this.hue + ", 20%, 100%, .4)";
      sketch.fill();
      return sketch.stroke();
    },
  };

  // Create Particles
  z = calculateParticleCount();

  while (z--) {
    particles.push(new Particle());
  }

  // Sketch Clear
  sketch.clear = function () {
    return sketch.clearRect(0, 0, sketch.width, sketch.height);
  };

  // Sketch Update
  sketch.update = function () {
    var i, results;
    i = particles.length;
    results = [];
    while (i--) {
      results.push(particles[i].update());
    }
    return results;
  };

  // Sketch Draw
  sketch.draw = function () {
    var i, results;
    i = particles.length;
    results = [];
    while (i--) {
      results.push(particles[i].render());
    }
    return results;
  };

  function onMouseEnter(event) {
    sketch.mouse.x = event.pageX - sketch.container.offsetLeft;
    sketch.mouse.y = event.pageY - sketch.container.offsetTop;
  }

  // Function to handle mouse leaving the container
  // Set mouse position to an "inactive" state, e.g., far off-screen
  function onMouseLeave() {
    sketch.mouse.x = Number.NEGATIVE_INFINITY;
    sketch.mouse.y = Number.NEGATIVE_INFINITY;
  }

  var container = document.getElementById("intro-container");

  container.addEventListener("mouseenter", onMouseEnter);
  container.addEventListener("mouseleave", onMouseLeave);
  window.addEventListener("resize", sketch.resize());

  var canvas = container.querySelector("canvas");
  canvas.classList.add("intro-canvas");
}).call(this);




$(document).ready(function(){
  $(".owl-carousel").owlCarousel({
    center: true,
    loop:true,
    margin: 50,
    autoplay:true,
    nav:true,
    autoWidth:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:3
        }
    }
});
});


