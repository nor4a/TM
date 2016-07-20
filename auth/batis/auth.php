<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Nologin authentication login - prevents user login.
 *
 * @package auth_nologin
 * @author Petr Skoda
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for no authentication - disabled user.
 */
class auth_plugin_batis extends auth_plugin_base {


    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'batis';
    }

    /**
     * Old syntax of class constructor for backward compatibility.
     */
    public function auth_plugin_batis() {
        self::__construct();
    }



    public function pre_loginpage_hook() {
		//========================================================
		//TODO - decrypt the token and get the username
 	  
		$AuthString=@$_GET['AuthString'];
		$signature = base64_url_decode($AuthString);
		
		
		$PublicKey = file_get_contents('Keys/cert.pem');
		$pKeyId = openssl_get_publickey($PublicKey);
		$InfoSign = '';
		openssl_public_decrypt($signature, $InfoSign, $pKeyId);
		openssl_free_key($pKeyId);
		
		$InfoSign = base64_url_decode($InfoSign);
		
		$SimbCount = (int)substr($InfoSign,0,3);
		$username = substr($InfoSign,3,$SimbCount);
		$InfoSign = substr($InfoSign,$SimbCount + 3);
		
		$SimbCount = (int)substr($InfoSign,0,3);
		$PSW = substr($InfoSign,3,$SimbCount);
		$InfoSign = substr($InfoSign,$SimbCount + 3);
		
		$SimbCount = (int)substr($InfoSign,0,3);
		$Timestamp = substr($InfoSign,3,$SimbCount);
		$InfoSign = substr($InfoSign,$SimbCount + 3);
		
		$SimbCount = (int)substr($InfoSign,0,3);
		$Role = substr($InfoSign,3,$SimbCount);

		
		//Chek passed auth string creation time
		$date = new DateTime();
		$CTimestamp = $date->getTimestamp();
		if($CTimestamp - $Timestamp > 20000){
			return false;
		}
		
		//Chek passed user
		global $DB;
		if($user = $DB->get_record('user', array('username' => $username))){
			complete_user_login($user);
			return true;
		}
		return false;
    }
	//========================================================
	

    /**
     * Do not allow any login.
     *
     */
    function user_login($username, $password) {
        return false;
    }

    /**
     * No password updates.
     */
    function user_update_password($user, $newpassword) {
        return false;
    }

    function prevent_local_passwords() {
        // just in case, we do not want to loose the passwords
        return false;
    }

    /**
     * No external data sync.
     *
     * @return bool
     */
    function is_internal() {
        //we do not know if it was internal or external originally
        return true;
    }

    /**
     * No changing of password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * No password resetting.
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }
	
	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_,', '+/='));
	}
	
	
}


