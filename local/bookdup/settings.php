<?php
/////////////////////////////////////////////////////
// GLOBAL SETTINGS
////////////////////////////////////////////////////
defined( 'MOODLE_INTERNAL' ) || die;
global $CFG,$DB;

if ($hassiteconfig && isset($ADMIN)) {    
  $temp = new admin_settingpage('bookdupsettings', get_string('pluginname', 'local_bookdup'));  
  
  $temp->add( new admin_setting_configtext('local_bookdup/import_user_id', 
                                         get_string('import_user_id', 'local_bookdup'), 
                                         '', 
                                         '', PARAM_INT, 10));   
  $ADMIN->add('localplugins', $temp);
}

