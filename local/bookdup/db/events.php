<?php 

$observers = [];
foreach (array('\mod_book\event\chapter_created' => 'created',
               '\mod_book\event\chapter_deleted' => 'deleted',  
               '\mod_book\event\chapter_updated' => 'updated'
              ) as $eventname => $function) {
  $observers [] = array(
      'eventname'   => $eventname,
      'callback'    => 'local_bookdup\BookDup::' . $function,
      'includefile' => 'local/bookdup/classes/BookDup.php',
  );
}