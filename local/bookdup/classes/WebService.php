<?php

require_once($CFG->libdir . "/externallib.php");

class local_bookdup_webservice extends external_api {
   
  public static function list_all() {
    global $DB;

    if($records = $DB->get_records('bookdup_target_courses')){
      $ret = array();
      foreach($records as $row){
        $ret[] = json_decode(json_encode($row), true);
      }
      return $ret;
    }else{
      return array();
    }
    
    //;json_encode($record->id);
  }
  public static function list_all_parameters() {
    return new external_function_parameters(
        array(
           //if I had any parameters, they would be described here. But I don't have any, so this array is empty.
        )
    );
  }
  /**
   * Returns description of method result value
   * @return external_description
   */
  public static function list_all_returns() {
    return new external_multiple_structure(
        new external_single_structure(
          array(
                 'id' => new external_value(PARAM_INT, 'id'),
                 'course' => new external_value(PARAM_INT, 'source course id'),
                 'target_course' => new external_value(PARAM_INT, 'target course id'),
               ), 'pairs'
              )
    );
  }  

  public static function create($course, $target_course) {
    global $DB, $CFG;

    $params = array(
        'course'         => $course,
        'target_course'  => $target_course,
    );

    self::validate_parameters(self::create_parameters(), $params);

    if(!($p = $DB->get_record('bookdup_target_courses', array('course' => $course, 'target_course' => $target_course)))){
      $p = new \stdClass();
    }
    foreach($params as $n => $v) {
      $p->$n = $v;
    }
    if(!isset($p->id)) {
      $p->id = $DB->insert_record('bookdup_target_courses', $p, TRUE);
    }
    return array(array('id' => $p->id));
  }

  public static function create_parameters() {
    return new external_function_parameters(
        array(
              'course' => new external_value(PARAM_INT, 'course', VALUE_REQUIRED, 1),
              'target_course' => new external_value(PARAM_INT, 'target course', VALUE_REQUIRED, 1),
            )
    );
  }
  /**
   * Returns description of method result value
   * @return external_description
   */
  public static function create_returns() {
    return new external_multiple_structure(
        new external_single_structure(
          array(
                 'id' => new external_value(PARAM_INT, 'id'),
               ), 'id'
              )
    );
  }  

  /**
   * Summary of delete
   * @param mixed $id - the record id
   * @return array
   */
  public static function delete($id) {
    global $DB;

    $params = array(
      'id' => $id,
     );

    self::validate_parameters(self::delete_parameters(), $params);

    if($r = $DB->get_record('bookdup_target_courses', array('id' => $id))){
      $DB->delete_records('bookdup_target_courses',   array('id' => $r->id));
      return array(array('id' => $r->id));
    }

    return array(array('id' => 0));
  }

  public static function delete_parameters() {
    return new external_function_parameters(
        array(
              'id' => new external_value(PARAM_INT, 'record id', VALUE_REQUIRED, 1),
              )
    );
  }
  /**
   * Returns description of method result value
   * @return external_description
   */
  public static function delete_returns() {
    return new external_multiple_structure(
        new external_single_structure(
          array(
                 'id' => new external_value(PARAM_INT, 'id'),
               ), 'id'
              )
    );
  }  
  //DELETE STUDY PROGRAMS END----------------------------------------------------------------------------------------

}

