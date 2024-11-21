    <?php if ($this->session->flashdata('message')): ?>
      <div id="alert-message" class="alert alert-<?php echo $this->session->flashdata('message_type'); ?>">
          <?php echo $this->session->flashdata('message'); ?> 
          <span id="seconds-counter">0</span>s.
      </div>
      <?php  $this->session->set_userdata('alert_shown', false); ?>

        <script>
            // Initialisation des variables
            var secondsCounter = 0;
            var alertMessage = document.getElementById('alert-message');
            var counterElement = document.getElementById('seconds-counter');

            // Fonction pour mettre à jour le compteur de secondes
            function updateCounter() {
                secondsCounter++;
                counterElement.textContent = secondsCounter;
            }

            // Mettre à jour le compteur toutes les secondes
            var interval = setInterval(updateCounter, 1000);

            // Masquer l'alerte après 10 secondes
            setTimeout(function() {
                alertMessage.style.transition = "opacity 1s ease-out";
                alertMessage.style.opacity = 0;
                setTimeout(function() {
                    alertMessage.style.display = 'none';
                    clearInterval(interval); // Arrêter le compteur
                }, 1000); // Masquer complètement après la transition
            }, 5000);
        </script>
    <?php endif; ?>