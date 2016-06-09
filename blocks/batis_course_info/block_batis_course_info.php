<?php
class block_batis_course_info extends block_base {
  public function init() {
     $this->title = get_string('batis_course_info', 'block_batis_course_info');
  }

	public function get_content(){
		global $USER, $COURSE;

    if ($this->content !== null){
      return $this->content;
    }
    $this->content = new stdClass;
    $this->content->text = '';

    if(!empty($this->config->iframe_src) && !empty($COURSE->idnumber) && !empty($USER->idnumber)) {
      $src  = $this->config->iframe_src;
      $src .= substr_count($src, '?') > 0 ? '':'?';
      $src .= '&courseidnumber=' . urlencode($COURSE->idnumber) . '&useridnumber=' . urlencode($USER->idnumber);
      $this->content->text = '<iframe class="batis-course-info" src="' . $src . '" ' . (!empty($this->config->iframe_attr) ? $this->config->iframe_attr : '') . ' ></iframe>';
    }

    return $this->content;
	}
	
	public function instance_allow_config() {
		return true;
	}
}