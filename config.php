<?php
$bdip = "localhost";	// Ip к базе данных
$bdlogin = "_ycsm";		// Логин к базе данных
$bdpassword = "123";	// Пароль к базе данных
$bd = "_ycsm";			// База данных

date_default_timezone_set("Asia/Muscat");
$time = time();

//$yandex_metrika = "<!-- Yandex.Metrika counter --> <script type=\"text/javascript\"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter38207730 = new Ya.Metrika({ id:38207730, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName(\"script\")[0], s = d.createElement(\"script\"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = \"text/javascript\"; s.async = true; s.src = \"https://mc.yandex.ru/metrika/watch.js\"; if (w.opera == \"[object Opera]\") { d.addEventListener(\"DOMContentLoaded\", f, false); } else { f(); } })(document, window, \"yandex_metrika_callbacks\"); </script> <noscript><div><img src=\"https://mc.yandex.ru/watch/38207730\" style=\"position:absolute; left:-9999px;\" alt=\"\" /></div></noscript> <!-- /Yandex.Metrika counter -->";

$bdconnect = mysql_connect($bdip, $bdlogin, $bdpassword) or die ("База Данных не найдена!!!");
$sql = mysql_select_db($bd) or die ("Нет соединения с базой!!!");
mysql_query("SET NAMES utf8");
setlocale(LC_ALL, 'ru_RU.utf8');
mb_internal_encoding('utf-8');

?>
