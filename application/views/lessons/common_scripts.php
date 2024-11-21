<script type="text/javascript">
//First sections
//saving the current progress and starting from the saved progress
var newProgress;
var savedProgress;
var currentProgress = '<?php echo lesson_progress($lesson_id); ?>';
var lessonType = '<?php echo $lesson_details['lesson_type']; ?>';
var videoProvider = '<?php echo $provider; ?>';
let remainingTime;
let timerInterval;
let currentQuestion = 1; // Variable pour suivre la question actuelle
let quizSubmitted = false;  // Drapeau pour vérifier si le quiz a été soumis


function markThisLessonAsCompleted(lesson_id) {
  $('#lesson_list_area').hide();
  $('#lesson_list_loader').show();
  var progress;
  // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
  var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
  var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
  if ($('input#'+lesson_id).is(':checked')) {
    progress = 1;
  }else{
    progress = 0;
  }
  $.ajax({
    type : 'POST',
    url : '<?php echo site_url('addons/courses/save_course_progress'); ?>',
    data : {lesson_id : lesson_id, progress : progress , [csrfName]: csrfHash},
    dataType: 'json',
    success : function(response){
      currentProgress = response.status;

      // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
      var newCsrfName = response.csrf.csrfName;
      var newCsrfHash = response.csrf.csrfHash;
      $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF

      $('#lesson_list_area').show();
      $('#lesson_list_loader').hide();
    }
  });
}


var timer = setInterval(function(){

  if (lessonType == 'video' && videoProvider == 'html5' && currentProgress != 1) {
    getCurrentTime();
  }
}, 1000);

$(document).ready(function() {
  if (lessonType == 'video' && videoProvider == 'html5') {
    var totalDuration = document.querySelector('#player').duration;

    if (currentProgress == 1 || currentProgress == totalDuration) {
      document.querySelector('#player').currentTime = 0;
    }else {
      document.querySelector('#player').currentTime = currentProgress;
    }
  }
});
var counter = 0;
player.on('canplay', event => {
  if (counter == 0) {
    if (currentProgress == 1) {
      document.querySelector('#player').currentTime = 0;
    }else{
      document.querySelector('#player').currentTime = currentProgress;
    }
  }
  counter++;
});

function getCurrentTime() {
  var lesson_id = '<?php echo $lesson_id; ?>';
  newProgress = document.querySelector('#player').currentTime;
  var totalDuration = document.querySelector('#player').duration;

  console.log('Current Progress is '+currentProgress);
  console.log('New Progress is '+newProgress);

  if (newProgress != savedProgress && newProgress > 0 && currentProgress != 1) {

    // if the user watches the entire video the lesson will be marked as seen automatically.
    if (totalDuration == newProgress) {
      newProgress = 1;
      $('input#'+lesson_id).prop('checked', true);
    }

    // update the video prgress here.
    $.ajax({
      type : 'POST',
      url : '<?php echo site_url('addons/courses/save_course_progress'); ?>',
      data : {lesson_id : lesson_id, progress : newProgress},
      success : function(response){
        savedProgress = response;
      }
    });
  }
}




//SECONDS SECTIONS
function toggleAccordionIcon(elem, section_id) {
  var accordion_section_ids = [];
  $(".accordion_icon").each(function(){ accordion_section_ids.push(this.id); });
  accordion_section_ids.forEach(function(item) {
    if (item === 'accordion_icon_'+section_id) {
      if ($('#'+item).html().trim() === '<i class="fa fa-plus"></i>') {
        $('#'+item).html('<i class="fa fa-minus"></i>')
      }else {
        $('#'+item).html('<i class="fa fa-plus"></i>')
      }
    }else{
      $('#'+item).html('<i class="fa fa-plus"></i>')
    }
  });
}

function checkCourseProgression() {
  $.ajax({
    url: '<?php echo site_url('home/check_course_progress/'.$course_id);?>',
    success: function(response)
    {
      if (parseInt(response) === 100) {
        $('#download_certificate_area').show();
        $('#certificate-alert-success').show();
        $('#certificate-alert-warning').hide();
      }else{
        $('#download_certificate_area').hide();
        $('#certificate-alert-success').hide();
        $('#certificate-alert-warning').show();
      }
      $('#progression').text(Math.round(response));
      $('#course_progress_area').attr('data-percent', Math.round(response));
      initProgressBar(Math.round(response));
    }
  });
}

function initProgressBar(dataPercent) {
  console.log("Data Percent" + dataPercent);
  var totalProgress, progress;
  const circles = document.querySelectorAll('.circular-progress');
  for(var i = 0; i < circles.length; i++) {
    totalProgress = circles[i].querySelector('circle').getAttribute('stroke-dasharray');
    progress = dataPercent;

    circles[i].querySelector('.bar').style['stroke-dashoffset'] = totalProgress * progress / 100;
  }
}





//THIRD SECTIONS
function toggle_lesson_view() {
    $('#lesson-container').toggleClass('justify-content-center');
    $("#video_player_area").toggleClass("order-md-1");
    $("#lesson_list_area").toggleClass("col-lg-5 order-md-1");
}





//FORTH SECTIONS
// JavaScript pour gérer le chronomètre
function getStarted(first_quiz_question) {
    $('#quiz-header').hide();
    $('#lesson-summary').hide();
    $('#question-number-' + first_quiz_question).show();
    currentQuestion = first_quiz_question; // Initialiser la première question
    startTimer(15); // Commencez le chronomètre avec 10 secondes ou toute autre durée
}

function showNextQuestion(next_question) {
    $('#question-number-' + (next_question - 1)).hide();
    $('#question-number-' + next_question).show();
    currentQuestion = next_question; // Mettre à jour la question actuelle
    startTimer(15); // Redémarrer le chronomètre pour la nouvelle question
}

function startTimer(duration) {
    // Effacer tout intervalle existant pour éviter les doublons
    if (timerInterval) {
        clearInterval(timerInterval);
    }

    // Initialiser le temps restant
    remainingTime = duration;

    // Démarrer le nouvel intervalle de mise à jour du chronomètre
    timerInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
    let minutes = Math.floor(remainingTime / 60);
    let seconds = remainingTime % 60;
    
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
 
    document.getElementById('timer' + currentQuestion).textContent = minutes + ':' + seconds;
    
    if (remainingTime <= 0) {
        clearInterval(timerInterval);
        
        // Si ce n'est pas la dernière question, passer à la question suivante
        if (currentQuestion < <?php echo count($quiz_questions->result_array()); ?>) {
           currentQuestion++; 
          showNextQuestion(currentQuestion); // Passe automatiquement à la question suivante
        } else {
            // Vérifier si le quiz n'a pas déjà été soumis
            if (!quizSubmitted) {
                quizSubmitted = true;  // Marquer le quiz comme soumis
                alert("Temps écoulé! Soumettez le quiz.");
                submitQuiz(); // Soumettez le quiz une seule fois
            }
        }
    } else {
        remainingTime--;
    }
}

function stopTimer() {
    clearInterval(timerInterval);
}

function submitQuiz() {
  quizSubmitted = true;
  var lesson_id = '<?php echo $lesson_id; ?>';
  document.getElementById(lesson_id).checked = true
  markThisLessonAsCompleted(lesson_id);
    $.ajax({
        url: '<?php echo site_url('addons/lessons/submit_quiz'); ?>',
        type: 'post',
        data: $('form#quiz_form').serialize(),
        success: function(response) {
            $('#quiz-body').hide();
            $('#quiz-result').html(response);
        }
    });
}
function check_result() {
  quizSubmitted = true;
  var lesson_id = '<?php echo $lesson_id; ?>';
  document.getElementById(lesson_id).checked = true
  markThisLessonAsCompleted(lesson_id);
    $.ajax({
        url: '<?php echo site_url('addons/lessons/check_result'); ?>',
        type: 'post',
        data: $('form#quiz_form').serialize(),
        success: function(response) {
            $('#quiz-body').hide();
            $('#quiz-result').html(response);
        }
    });
}
function enableNextButton(quizID) {
    $('#next-btn-'+quizID).prop('disabled', false);
}
</script>
