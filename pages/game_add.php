<?
if(!$iam_player[admin] && !$iam[account_admin]){
  include "pages/login.php";
}else{ ?>
    <h1>Ближайшие игры</h1>
    <table border="1" id="table_games">
      <tr>
        <td>Игра</td>
        <td>Время начала</td>
        <td>Статус</td>
      </tr>
      <?
      $games = mysql_query("SELECT * FROM games WHERE game_status < '2' ORDER BY game_date_start",$bdconnect) or die("Error 123!");
      while ($game = mysql_fetch_assoc($games)) {
        echo "<tr>";
        echo '<td><a href="/game/add/'.$game[game].'">Ссылка</a></td>';
        echo "<td>".date("d.m.Y H:i",$game[game_date_start])."</td>";
        echo "<td>".$game[game_status]."</td>";
        echo "</tr>";
      }
      ?>
    </table>
<? } ?>
