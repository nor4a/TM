<?php
namespace local_bookdup;

class BookDup {  

  public static function created(\mod_book\event\chapter_created $event) {
    self::duplicate($event);
  }
  public static function deleted(\mod_book\event\chapter_deleted $event) {
    self::duplicate($event);
  }
  public static function updated(\mod_book\event\chapter_updated $event) {
    self::duplicate($event);
  }

  public static function duplicate($event) {
    global $DB, $CFG;

    //get the book module id
    if($bookmoduleid = $DB->get_record('modules', array('name' => 'book'))){
      $bookmoduleid = $bookmoduleid->id;
    }
    
    //check if we are editing the 0 sectin book
    if(!($section = $DB->get_record('course_sections', array('course'  => $event->courseid, 'section' => 0)))) return; /// no 0 section - skip 
    if(!($book    = $DB->get_record('course_modules',  array('section' => $section->id,     'module'  => $bookmoduleid, 'course' => $event->courseid, 'id' => $event->contextinstanceid)))) return;          //section not found

    //we find all the target courses the book should be copied to. This table should be kept up to date and this functionality is out of this module scope.
    if($records = $DB->get_records('bookdup_target_courses',array('course'  => $event->courseid))){
      //make the course backup including only the book resource;    

      require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
      require_once($CFG->dirroot . '/backup/controller/backup_controller.class.php');
      require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

      //in case of backuping all the 0 section
      //$bc = new \backup_controller(\backup::TYPE_1SECTION, $section->id, \backup::FORMAT_MOODLE, \backup::INTERACTIVE_NO, \backup::MODE_IMPORT, get_config('local_wsrsusissync','import_user_id'));      

      //backup book activity
      $bc = new \backup_controller(\backup::TYPE_1ACTIVITY, $book->id, \backup::FORMAT_MOODLE, \backup::INTERACTIVE_NO, \backup::MODE_IMPORT, get_config('local_wsrsusissync','import_user_id'));
      $bc->execute_plan();
      $backup_dir = $bc->get_backupid();

      foreach($records as $row) {

        //get the 0 section id
        if(($section = $DB->get_record('course_sections', array('course'  =>$row->target_course, 'section'  => 0))) &&
           ($books  = $DB->get_records('course_modules',  array('section' => $section->id, 'module' => $bookmoduleid, 'course' => $row->target_course)))){          
          //delete all books within the target course 0 section
          foreach($books as $book) course_delete_module($book->id); 
        }        
        
        //import the backup - the import procedure will create the copy of the section so it will be in the 0 section of the course
        $controller = new \restore_controller($backup_dir, $row->target_course, \backup::INTERACTIVE_NO, \backup::MODE_IMPORT, get_config('local_bookdup','import_user_id'), \backup::TARGET_EXISTING_ADDING);
        $controller->execute_precheck();            
        $controller->execute_plan(); 
      }
    }
  } 
}
