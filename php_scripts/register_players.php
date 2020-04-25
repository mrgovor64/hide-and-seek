<?
session_start();
include "../config.php";
include "authentification.php";
include "functions.php";

if($_POST[game]){
  $ar = array('error'=>array(),'success'=>array());
  function check_input($var, $text){
    if(!$_POST[$var]){
      return_error($var,$text);
    }
  }

  function return_error($var, $text){
    global $error,$ar;
    $ar['error'][] = array('name' => $var,'text' => $text);
    $error++;
  }
  function return_success($var, $text){
    global $error,$ar;
    $ar['success'][] = array('name' => $var,'text' => $text);
  }

  $_POST[team_name] = htmlspecialchars(trim($_POST[team_name]));
  $_POST[car_mark] = htmlspecialchars(trim($_POST[car_mark]));
  $_POST[car_model] = htmlspecialchars(trim($_POST[car_model]));
  $_POST[car_color] = htmlspecialchars(trim($_POST[car_color]));
  $_POST[car_number] = htmlspecialchars(trim($_POST[car_number]));
  $_POST[mobile_number] = htmlspecialchars(trim($_POST[mobile_number]));

  $error = 0;

  if($_POST[captcha] != $_SESSION[captcha]){
    return_error("captcha","Пример решен не верно");
  }

  if(!$_POST[team_name]){
    return_error("team_name","Укажите название команды");
  }else{
    $check = mysql_query("SELECT * FROM players WHERE team_name = '$_POST[team_name]' AND game = '$_POST[game]'",$bdconnect) or return_error('','error 2');
    $check = mysql_fetch_assoc($check);
    if($check[p_uniq]){
      return_error("team_name","Такая команда уже зарегитрирована на эту игру");
    }
  }


  if(!$_POST[car_mark]){
    return_error("car_mark","Укажите марку машины");
  }
  if(!$_POST[car_model]){
    return_error("car_model","Укажите модель машины");
  }
  if(!$_POST[car_color]){
    return_error("car_color","Укажите цвет машины");
  }
  if(!$_POST[mobile_number]){
    return_error("mobile_number","Укажите номер телефона");
  }else{
    $check = mysql_query("SELECT * FROM players WHERE mobile_number = '$_POST[mobile_number]' AND game = '$_POST[game]'",$bdconnect) or return_error('','error 2');
    $check = mysql_fetch_assoc($check);
    if($check[p_uniq]){
      return_error("mobile_number","Этот номер уже зарегитрирован на эту игру");
    }
  }

  if(!$_POST[car_number]){
    return_error("car_number","Укажите ГОС номер машины");
  }else{
    if(
      preg_match("/^[\d]{4,6}$/",preg_replace("/[\D]*/","",$_POST[car_number])) &&
      strlen(preg_replace("/[^a-zA-Zа-яА-Я]*/u","",$_POST[car_number])) == 3 &&
      preg_match("/^[авекмнорстухАВЕКМНОРСТУХabekmhopctyxABEKMHOPCTYX\d\s]*$/u", $_POST[car_number])){
          $_POST[car_number] = make_gos_number($_POST[car_number]);
    }else{
      return_error("car_number","ГОС номер машины указан не верно");
    }
  }

  if(!$error){
    $pass = rand(1000,9999);
    $ip = ip2long($_SERVER['REMOTE_ADDR']);
    mysql_query("INSERT INTO players (p_uniq,pass,car_mark,car_model,car_color,car_number,team_name,mobile_number,game,date_reg,ip_reg,player_status) VALUES (NULL,'$pass','$_POST[car_mark]','$_POST[car_model]','$_POST[car_color]','$_POST[car_number]','$_POST[team_name]','$_POST[mobile_number]',$_POST[game],$time,$ip,0)", $bdconnect);
    $p_uniq = mysql_query("SELECT LAST_INSERT_id()",$bdconnect);
    $p_uniq = mysql_fetch_array($p_uniq);
    $ar[success][p_uniq] = $p_uniq[0];
    $ar[success][password] = $pass;
  }

  echo json_encode($ar, JSON_UNESCAPED_UNICODE);
}

?>
