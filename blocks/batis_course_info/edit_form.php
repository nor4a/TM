<?php
 
class block_batis_course_info_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 		
      $mform->addElement('header', 'configheader', get_string('iframe', 'block_batis_course_info'));

      $mform->addElement('text', 'config_iframe_src', get_string('batis_link', 'block_batis_course_info'));
      $mform->setDefault('config_iframe_src', 'http://batis.turiba.lv');
      $mform->setType('config_iframe_src', PARAM_TEXT);

      $mform->addElement('text', 'config_iframe_attr', get_string('batis_iframe_attr', 'block_batis_course_info'));
      $mform->setDefault('config_iframe_attr', '');
      $mform->setType('config_iframe_attr', PARAM_TEXT);

    }
}
 
?>