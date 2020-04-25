<?
session_start();
include "../config.php";
include "authentification.php";

if($_POST[game]){
  $game = $_POST[game];
} else {
  $game = $iam_player[game];
}

if($_POST[read] || $_POST[last_message]){
  $ar = array();
  if($_POST[read] != -1 && !$_POST[last_message]){
    $limit = " LIMIT ".$_POST[read];
  } else {
    $limit = "";
  }

  if($_POST[last_message]){
    $where_add = "AND date_send > '$_POST[last_message]'";
  } else {
    $where_add = "";
  }

  $SELECT = "SELECT chat.*, players.team_name, players.car_number FROM chat LEFT JOIN players USING (p_uniq) WHERE chat.game = '$game' $where_add ORDER BY chat.date_send DESC $limit";
  $leters = mysql_query("SELECT * FROM ($SELECT) as mes ORDER BY mes.date_send" ,$bdconnect) or die('error 1 '.mysql_error());
  while($leter = mysql_fetch_assoc($leters)){
    $ar[] = array(
      'team_name' => $leter[team_name] ,
      'car_number' => $leter[car_number] ,
      'date_send' => date("H:i:s",$leter[date_send]) ,
      'text' => $leter[text]
    );
    $last_message = $leter[date_send];
  }

  $ar = array('last_message' => $last_message , 'messages' => $ar);

  echo json_encode($ar, JSON_UNESCAPED_UNICODE);
}

if($_POST[text]){
  $_POST[text] = htmlspecialchars($_POST[text]);
  if($iam_player[p_uniq]){
    $p_uniq = $iam_player[p_uniq];
  }elseif($iam[account_admin]){
    $p_uniq = 0;
  }
  if($_POST[text]){
    mysql_query("INSERT INTO chat (game,p_uniq,text,date_send) VALUES ($game,$p_uniq,'$_POST[text]',$time);", $bdconnect) or die(mysql_error());
  }
}

?>
