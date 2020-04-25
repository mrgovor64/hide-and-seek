<?
session_start();
include "../config.php";
include "authentification.php";
//include "functions.php";


$ar = array('error'=>array(),'success'=>array());
$error = 0;

function return_error($var, $text){
  global $error,$ar;
  $ar['error'][] = array('name' => $var,'text' => $text);
  $error++;
}
function return_success($var, $text){
  global $error,$ar;
  $ar['success'][] = array('name' => $var,'text' => $text);
}

$_POST[login] = htmlspecialchars(trim($_POST[login]));
$_POST[mobile_number] = htmlspecialchars(trim($_POST[mobile_number]));


if(!$_POST[login]){
  return_error("login", "Введите логин");
}elseif(!preg_match("/[a-zA-Z]+[a-zA-Z\_\-\d]*/u",$_POST[login]) || mb_strlen($_POST[login]) < 4){
  return_error("login", "Логин не может быть короче 4 символов, начинаться с латинской буквы и состоять из латинских букв, цифр и '_','-'");
}else{
  $check = mysql_query("SELECT * FROM accounts WHERE login='$_POST[login]'",$bdconnect) or return_error('','error 1');
  $check = mysql_fetch_assoc($check);
  if($check[id]){
    return_error("login", "Этот логин уже зарегистрирован");
  }
}

if(!$_POST[first_password]){
  return_error("first_password", "Введите пароль");
}elseif(mb_strlen($_POST[first_password]) < 4){
  return_error("first_password", "Пароль не может быть короче 4 символов");
}

if(!$_POST[second_password]){
  return_error("second_password", "Введите повторение пароля");
}elseif($_POST[first_password] != $_POST[second_password]){
  return_error("second_password", "Пароли не совпадают");
}

if(!$_POST[mobile_number]){
  return_error("mobile_number", "Введите номер телефона");
}else{
  $check = mysql_query("SELECT * FROM accounts WHERE account_mobile_number='$_POST[mobile_number]'",$bdconnect) or return_error('','error 2');
  $check = mysql_fetch_assoc($check);
  if($check[id]){
    return_error("mobile_number", "Этот номер уже зарегистрирован");
  }
}

if($_POST[captcha] != $_SESSION[captcha]){
  return_error("captcha", "Пример решен не верно");
}


if(!$error){
  $ip = ip2long($_SERVER['REMOTE_ADDR']);
  mysql_query("INSERT INTO accounts (login,hash,account_mobile_number,account_date_reg) VALUES ('$_POST[login]','".sha1($_POST[first_password])."','$_POST[mobile_number]',$time)", $bdconnect) or return_error('','error 3 '.mysql_error());

  $check = mysql_query("SELECT * FROM hash WHERE text = '$_POST[first_password]'",$bdconnect) or return_error('','error 4');
  $check = mysql_fetch_assoc($check);
  if(!$check[text]){
    mysql_query("INSERT INTO hash (text,hash) VALUES ('$_POST[first_password]','".sha1($_POST[first_password])."')") or return_error('','error 5');
  }
}

echo json_encode($ar, JSON_UNESCAPED_UNICODE);

?>
