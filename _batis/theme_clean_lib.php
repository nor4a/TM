<?php
if(strpos($_SERVER['REQUEST_URI'], '/course/view.php?id=') === 0 ||
   strpos($_SERVER['REQUEST_URI'], '/course/modedit.php?update=') === 0) {
  global $PAGE, $USER,$CFG;
  $cid = FALSE;
  if(!empty($_GET['update'])){
    global $DB;
    if($r = $DB->get_record('course_modules',array('id' => intval($_GET['update'])))) {
      if($r = $DB->get_record('course_sections',array('id' => $r->section, 'section' => 0))) {
        $cid = $r->course;
      }
    }
  }else{
    $cid = $_GET['id'];
  }
  if($cid) {
    $cContext = context_course::instance($cid);
    if(user_has_role_assignment($USER->id, 3, $cContext->id)){ //teacher role id = 3
      $PAGE->requires->js( new \moodle_url($CFG->wwwroot . '/_batis/js/disable-0-section-edit.js'));
    }
  }
  $PAGE->requires->js( new \moodle_url($CFG->wwwroot . '/_batis/js/disable-0-forum.js'));
}
