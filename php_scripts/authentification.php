<?
if ($_COOKIE[id] && $_COOKIE[hash]){
  $check_login = mysql_query("SELECT * FROM accounts WHERE id = '$_COOKIE[id]' LIMIT 1", $bdconnect) or die("Таблица 2 не найдена!!!");
  $check_login = mysql_fetch_array($check_login);

  if(sha1("ycsm".$check_login[hash]) != $_COOKIE[hash]){
    $goto = "<meta http-equiv=Refresh content=\"0; url=/exit\">\n";
    $errorauth = 1;
    $_SESSION['comeback'] = $_SERVER['REQUEST_URI'];
  }else{
    $iam = mysql_query("SELECT * FROM accounts WHERE id = '$_COOKIE[id]'", $bdconnect) or die("Таблица 3 не найдена!!!");
    $iam = mysql_fetch_assoc($iam);
  }
}

if ($_COOKIE[p_uniq] && $_COOKIE[pass]){

  $check_login = mysql_query("SELECT * FROM players WHERE p_uniq = '$_COOKIE[p_uniq]' LIMIT 1", $bdconnect) or die("Таблица 2 не найдена!!!");
  $check_login = mysql_fetch_array($check_login);

  if($check_login[pass] != $_COOKIE[pass]){
    $goto = "<meta http-equiv=Refresh content=\"0; url=/?exit=1\">\n";
    $errorauth = 1;
    $_SESSION['comeback'] = $_SERVER['REQUEST_URI'];
  }else{
    $iam_player = mysql_query("SELECT * FROM players LEFT JOIN cars_marks USING (car_mark) LEFT JOIN games USING (game) WHERE players.p_uniq = '$_COOKIE[p_uniq]'", $bdconnect) or die("Таблица 3 не найдена!!!");
    $iam_player = mysql_fetch_assoc($iam_player);
  }
}
?>
