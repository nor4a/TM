<?php

function usage() {
    $script = $_SERVER['SCRIPT_NAME'];
    echo <<<END
  Syntax:
    php -f $script ldap-auth-plugin-name

  Where:
    ldap-auth-plugin-name is the name of the new LDAP auth plugin "clone"
 
    NOTE:  Only lowercase ASCII letters, numbers and underscores are
           allowed in the plugin name, and it has to start with a letter.
           But numbers and underscores are strongly discouraged by
           Moodle developers!

  Examples:
    php -f $script ldap2
    php -f $script myldap

END;
}

if ($_SERVER['argc'] < 2) {
    usage();
    exit;
}

define('CLI_SCRIPT', true);
try {
    require(dirname(__FILE__).'/config.php');
} catch (dml_connection_exception $e) {
    // Just continue, we don't need the database for this to work.
    echo "Continuing, even if database is not available\n";
}

try {
    $ldap_name = validate_param($_SERVER['argv'][1], PARAM_PLUGIN);
} catch (invalid_parameter_exception $e) {
    usage();
    exit;
}

$ldap_orig = $CFG->dirroot.'/auth/ldap';
$ldap_new  = $CFG->dirroot.'/auth/'.$ldap_name;
$ldap_orig_langfile  = $CFG->dirroot.'/auth/'.$ldap_name.'/lang/en/auth_ldap.php';
$ldap_new_langfile  = $CFG->dirroot.'/auth/'.$ldap_name.'/lang/en/auth_'.$ldap_name.'.php';
$patch_file_orig = $CFG->dirroot.'/ldapname.diff';

if (stristr(PHP_OS, 'win') && !stristr(PHP_OS, 'darwin')) {
	$ldap_orig = str_replace('/', '\\', $ldap_orig);
	$ldap_new = str_replace('/', '\\', $ldap_new);
	$ldap_orig_langfile = str_replace('/', '\\', $ldap_orig_langfile);
	$ldap_new_langfile = str_replace('/', '\\', $ldap_new_langfile);
	$patch_file_orig = str_replace('/', '\\', $patch_file_orig);
	system('xcopy "'.$ldap_orig.'" "'.$ldap_new.'" /S /E /I');
} else {
	system('cp -a "'.$ldap_orig.'" "'.$ldap_new.'"');
}
rename($ldap_orig_langfile, $ldap_new_langfile);

$patch_string = file_get_contents($patch_file_orig);
if (!$patch_string) {
    die ("Can't read input patch file");
}

$patch_string = str_replace('%%LDAPNAME%%', $ldap_name, $patch_string);

$patch_file_temp = tempnam(dirname(__FILE__), 'ldp');
if (stristr(PHP_OS, 'win') && !stristr(PHP_OS, 'darwin')) {
	$fh = fopen($patch_file_temp, 'wt');
} else {
	$fh = fopen($patch_file_temp, 'w');
}
if (!$fh) {
    die("Unable to create temp patch file. Exiting\n. Don't forget to remove $ldap_new directory");
}

$ret = fwrite($fh, $patch_string);
if (!$ret) {
    fclose($fh);
    unlink($patch_file_temp);
    die("Unable to write to temp patch file. Exiting\n. Don't forget to remove $ldap_new directory");
}

fclose($fh);
system ('patch -p0 < "'.$patch_file_temp.'" ');
unlink($patch_file_temp);

echo <<<END

=====================================================================
If you are using Internet Information Server (IIS) to run your Moodle
installation, please adjust the permissions of the
$ldap_new directory.

patch.exe for Windows removes some essential permissions from the
patched files, that make some of then unreadable by IIS. 
The simplest way to fix them is to use Windows Explorer to show the
properties of the $ldap_new directory, go to the
Security tab, click on the 'Advanced' button on the bottom right,
select the checkbox called 'Replace permission entries on all child
objects with entries shown here that apply to child objects', click
on the 'OK' button and confirm the dialog box.
=====================================================================

END;
