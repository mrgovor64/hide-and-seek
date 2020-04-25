<?php
  session_start();

  if(!$_GET[height]){
    $height = 60;
  }else{
    $height = $_GET[height];
  }
  if(!$_GET[width]){
    $width = 170;
  }else{
    $width = $_GET[width];
  }
  if(!$_GET[size]){
    $size = 25;
  }else{
    $size = $_GET[size];
  }

  $font = "../fonts/verdana.ttf";

  $number = rand(10, 100);
  $number_1 = rand(1, $number-1);
  $number_2 = $number-$number_1;
  $_SESSION['captcha'] = $number_1 + $number_2;

  $text = "$number_1 + $number_2";

  $image = imagecreatetruecolor($width, $height);
  $color = imagecolorallocate($image, rand(1, 150), rand(1, 150), rand(1, 150));
  $white = imagecolorallocate($image, 255, 255, 255);
  imagefilledrectangle($image, 0, 0, $width, $height, $white);

  $box = imagettfbbox($size,0,$font,$text);
  $width_text = max(array($box[0],$box[2],$box[4],$box[6])) - min(array($box[0],$box[2],$box[4],$box[6]));
  $height_text = max(array($box[1],$box[3],$box[5],$box[7])) - min(array($box[1],$box[3],$box[5],$box[7]));
  //imagettftext ($image, $size, 0, $width/2-$width_text/2, 695, $black, $font, $nap[$key]);

  imagettftext ($image, $size, 0, $width/2 - $width_text/2, $height - $height/2 + $height_text/2, $color, $font, $text);
//echo "$width $width_text<br />$height $height_text<br />";
//echo $height/2-$height_text/2;
  header("Content-type: image/png");
  imagepng($image);
?>
