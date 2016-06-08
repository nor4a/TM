<?php

//testwith: http://aquila-t.turiba.lv/login/index.php?U=turibadmin&P=E-Turib@M00dR00t
$U=@$_GET['U'];
$P=@$_GET['P'];


if ((strlen($U)>=1) && (strlen($P)>=1)){
  $frm = new \stdClass;
  $frm->username = $U;
  $frm->password = $P;
  $SESSION->wantsurl = 'http://aquila-t.turiba.lv/course/view.php?id=19';
}



