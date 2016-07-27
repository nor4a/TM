<?php
require_once('../../../config.php');
$domainname = $CFG->wwwroot;

//enter token from /admin/settings.php?section=webservicetokens for BookDup web services
$token = '85af446346eeb8dc3013b0c01da9384a';

$tests = array(
  'local_bookdup_list'   => array(),
  'local_bookdup_create' => array(11246, 11247), //course and target_course ids
  'local_bookdup_delete' => array(1), //the record id returned by local_bookdup_list
);
 

if(!empty($CFG->testing)) {
  foreach($tests as $functionname => $data) {
    ///// XML-RPC CALL
    header('Content-Type: text/plain');
    $serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
    require_once('./curl.php');
    $curl = new curl;//(array('debug'=>true));

    $post = xmlrpc_encode_request($functionname,$data);
    $resp_plain = $curl->post($serverurl, $post);
    $resp = xmlrpc_decode($resp_plain);

    if(empty($curl->errno)){ 
      echo 'Took ' . $curl->info['total_time'] . ' seconds to send a request to ' . $curl->info['url'] . PHP_EOL; 
      echo $post . PHP_EOL;
    } else { 
      echo 'Curl error: ' . $curl->error;   
    } 

    echo PHP_EOL . 'Response:  '  . PHP_EOL;    
    print_r($resp_plain);
    print_r($resp);
  }
}