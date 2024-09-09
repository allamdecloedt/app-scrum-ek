<?php

// $lessons = $this->db->get_where('lesson', array('course_id' => $cours_id))->result_array();
        // Récupérer tous les cours associés à la classe sélectionnée
        $courses = $this->db->get_where('course', array('class_id' => $class_id))->result_array();
     
               // Si des cours sont trouvés, récupérer leurs leçons
               $lessons = [];
               if (!empty($courses)) {
                   foreach ($courses as $course) {
                       $course_lessons = $this->db->get_where('lesson', array('course_id' => $course['id']))->result_array();
                       if (!empty($course_lessons)) {
                           // Ajouter toutes les leçons dans une liste
                           foreach ($course_lessons as $lesson) {
                               $lessons[] = $lesson; // Ajoute chaque leçon à un tableau
                           }
                       }
                   }
               }
        

if (!empty($lessons)): ?>
    <?php foreach ($lessons as $lesson): ?>
        <option value="<?php echo $lesson['id']; ?>"><?php echo $lesson['title']; ?></option>
    <?php endforeach; ?>
<?php else: ?>
    <option value=""><?php echo get_phrase('no_section_found'); ?></option>
<?php endif; ?>