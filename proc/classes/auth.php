<?php

include(dirname(__FILE__)."/../../db.php");

class Auth {

    static public function get_admin_id(){
        return $_SESSION['id'];
    }

    static public function get_admin_nombre(){
        return $_SESSION['nombre'];
    }

	static public function connect(){
		mysql_pconnect("localhost", DB::db_username, DB::db_passwd)or die("cannot connect");
		mysql_select_db(DB::db_name)or die("cannot select DB");
	}

	static public function login($username,$passwd,$remember){
        Auth::connect();
		// if(session_id() == '') {
			// session_start();
		// }
		$username = stripslashes($username);
		$passwd = stripslashes($passwd);
		$username = mysql_real_escape_string($username);
		$passwd = mysql_real_escape_string($passwd);
		if(!isset($_COOKIE['cookname']) && !isset($_COOKIE['cookpass'])){
			$passwd=md5($passwd);
		}

		$result=mysql_query("SELECT * FROM admins WHERE email='" . $username . "' and secreto='" . $passwd . "'");

		if(mysql_num_rows($result)==1){

            $adminData = mysql_fetch_array($result);

			$_SESSION['accessGranted'] = 1;
            $_SESSION['id'] = $adminData['id'];
            $_SESSION['nombre'] = $adminData['nombre'];
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $passwd;
            $_SESSION['perm_pagos'] = $adminData['permiso_pagos'];
			if($remember==true){
				if(!isset($_COOKIE['cookname']) && !isset($_COOKIE['cookpass'])){
					setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
					setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
				}
			}
			return true;
		}else{
			return array("error"=>"Cuenta o password invalidos");
		}
	}

	static public function access_level(){
		 if(session_id() == '') {
			 session_start();
		 }

		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
			Auth::login($_COOKIE['cookname'],$_COOKIE['cookpass'],false);
		}
		if(isset($_SESSION['accessGranted']) && $_SESSION['accessGranted']==1){
			return 1;
		}else{
			return -1;
		}
	}

	static public function logout(){
		// if(session_id() == '') {
			// session_start();
		// }

		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
			setcookie("cookname", "", time()-60*60*24*100, "/");
			setcookie("cookpass", "", time()-60*60*24*100, "/");
		}

		unset($_SESSION['username']);
		unset($_SESSION['password']);
		unset($_SESSION['accessGranted']);
        unset($_SESSION['perm_pagos']);
        unset($_SESSION['id']);
        unset($_SESSION['nombre']);
		$_SESSION = array(); // reset session array
		session_destroy();   // destroy session.
		return true;
	}
}
