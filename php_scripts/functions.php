<?

function make_gos_number($number){
  $search[]='а';
  $search[]='в';
  $search[]='е';
  $search[]='к';
  $search[]='м';
  $search[]='н';
  $search[]='о';
  $search[]='р';
  $search[]='с';
  $search[]='т';
  $search[]='у';
  $search[]='х';
  $search[]='a';
  $search[]='b';
  $search[]='e';
  $search[]='k';
  $search[]='m';
  $search[]='h';
  $search[]='o';
  $search[]='p';
  $search[]='c';
  $search[]='t';
  $search[]='y';
  $search[]='x';
  $search[]='A';
  $search[]='B';
  $search[]='E';
  $search[]='K';
  $search[]='M';
  $search[]='H';
  $search[]='O';
  $search[]='P';
  $search[]='C';
  $search[]='T';
  $search[]='Y';
  $search[]='X';

  for($b = 1; $b <= 3; $b++){
    $replace[]='А';
    $replace[]='В';
    $replace[]='Е';
    $replace[]='К';
    $replace[]='М';
    $replace[]='Н';
    $replace[]='О';
    $replace[]='Р';
    $replace[]='С';
    $replace[]='Т';
    $replace[]='У';
    $replace[]='Х';
  }

  $number = str_replace($search, $replace, $number);

  $sym = preg_replace("/[^А-Я]*/", "", $number);
  $number = preg_replace("/[\D]*/", "", $number);

  if(mb_strlen($number) == 4){
    $number = mb_substr($number, 0, 3)."0".mb_substr($number, 3, 1);
  }

  $ret = mb_substr($sym,0,1).mb_substr($number,0,3).mb_substr($sym,1,2)." ".mb_substr($number,3);

  return $ret;
}

function determine_color($color){
  $find["черн"] = "#000000";
  $find["бел"] = "#ffffff";
  $find["красн"] = "#ff0000";
  $find["зел"] = "#00ff00";
  $find["син"] = "#0000ff";
  $find["сер"] = "#aaaaaa";

  foreach ($find as $key => $value) {
    if(mb_stripos($color, $key) !== false){
      $res = $value;
    }
  }
  return $res;
}

function word_num($noumber, $word){
  if($noumber % 100 >=11 && $noumber % 100 <=14){
    return $word[2];
  }elseif($noumber % 10 == 1){
    return $word[0];
  }elseif($noumber % 10 >= 2 && $noumber % 10 <= 4){
    return $word[1];
  }else{
    return $word[2];
  }
}

$functions_include = 1;
?>
