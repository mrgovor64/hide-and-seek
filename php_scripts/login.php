<?php
if (($_POST[auth_submit]) && (!$_COOKIE[p_uniq] || !$_COOKIE[pass]) && preg_match("/^[\d]+$/", $_POST[auth_login])){
	$checklogin = mysql_query("SELECT * FROM players WHERE p_uniq = '$_POST[auth_login]' LIMIT 1", $bdconnect) or die("Таблица 13 не найдена!!!");
	$checklogin = mysql_fetch_array($checklogin);

	if(!$checklogin[p_uniq] || $checklogin[pass] != $_POST[auth_pass]){
		$_SESSION[notification_error] = "Логин или пароль указанны не верно!";
	}

	if (!$_SESSION[notification_error]){
		setcookie ("p_uniq", $checklogin[p_uniq], time() + 86400 * 365, "/");
		setcookie ("pass", $checklogin[pass], time() + 86400 * 365, "/");
		$goto = "<meta http-equiv=Refresh content=\"0;\">\n";
	}
} elseif(($_POST[auth_submit]) && (!$_COOKIE[id] || !$_COOKIE[hash])){
	$checklogin = mysql_query("SELECT * FROM accounts WHERE login = '$_POST[auth_login]' LIMIT 1", $bdconnect) or die("Таблица 13 не найдена!!!");
	$checklogin = mysql_fetch_array($checklogin);

	if(!$checklogin[id] || $checklogin[hash] != sha1($_POST[auth_pass])){
		$_SESSION[notification_error] = "Логин или пароль указанны не верно!";
	}

	if (!$_SESSION[notification_error]){
		setcookie ("id", $checklogin[id], time() + 86400 * 365, "/");
		setcookie ("hash", sha1("ycsm".$checklogin[hash]), time() + 86400 * 365, "/");
		$goto = "<meta http-equiv=Refresh content=\"0;\">\n";
	}
}

if ($_GET[p_uniq] && $_GET[pass]){
	$checklogin = mysql_query("SELECT * FROM players WHERE p_uniq = '$_GET[p_uniq]' LIMIT 1", $bdconnect) or die("Таблица 13 не найдена!!!");
	$checklogin = mysql_fetch_array($checklogin);
	if($checklogin[p_uniq] && $checklogin[pass] == $_GET[pass]){
		setcookie ("p_uniq", $checklogin[p_uniq], time() + 86400 * 365, "/");
		setcookie ("pass", $checklogin[pass], time() + 86400 * 365, "/");
		$goto = "<meta http-equiv=Refresh content=\"0;URL=/\">\n";
	}
}

if ($_GET['page'] == 'exit' || $_GET['exit'] == 'exit'){
	setcookie ("p_uniq", "");
	setcookie ("pass", "");
	setcookie ("id", "");
	setcookie ("hash", "");
	$goto = "<meta http-equiv=Refresh content=\"0; url=/\">\n";
}

?>
