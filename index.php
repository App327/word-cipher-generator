<?php

header('Content-Type: text/html; charset=utf-8');

$result = '';
$stages = '';

$sym = [
 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
];

$morse = [
 '.-', '-...', '-.-.', '-..', '.', '..-.', '--.', '....', '..', '.---', '-.-', '.-..', '--', '-.', '---', '.--.', '--.-', '.-.', '...', '-', '..-', '...-', '.--', '-..-', '-.--', '--..'
];

if (!isset($_GET["w"]) || $_GET["w"] == '') {
 $result = 'Введите текст ↑';
 $stages = $result;
} else {
 $arr = str_split($_GET["w"]);
 $symOK = true;

 for ($i = 0; $i < count($arr); $i++) {
  $cur = $arr[$i];
  if (!in_array($cur, $sym)) {
   $symOK = false;
   break;
  }
 }

 $sOW = 'успех';
 if ($symOK == false) {
  $sOW = 'ошибка';
 }

 $stages .= '1) Проверка символов в слове › '.$sOW;

 if ($symOK) {
  $nextWords = '';
  for ($i = 0; $i < count($arr); $i++) {
   $s = $arr[$i];
   $w = array_search($s, $sym);
   $w++;
   $s0 = $sym[$w];
   $nextWords .= $s0;
  }
  $stages .= '<br />2) Смещение символов на 1 › '.$nextWords;

  $morseCode = '';
  for ($i = 0; $i < count($arr); $i++) {
   $s = $nextWords[$i];
   $pos = array_search($s, $sym);
   if ($pos > 26) {
    $pos = $pos - 26;
   }
   $mrs = $morse[$pos];
   $morseCode .= $mrs;
   if ($i != count($arr) - 1) {
    $morseCode .= ' ';
   }
  }
  $stages .= '<br />3) Конвертация в Азбуку Морзе › '.$morseCode;

  $msp = str_split($morseCode);

  $Numbers;
  for ($i = 0; $i < count($msp); $i++) {
   $s = $msp[$i];
   $n;
   if ($s == '.') {
    $n = 0;
   } elseif ($s == '-') {
    $n = 1;
   } else {
    $n = 2;
   }
   $Numbers .= $n;
  }
  $stages .= '<br />4) Конвертация в число › '.$Numbers;

  $salt1 = count($arr);
  $salt2 = rand(42, 138);
  $WithSalt = $salt1.'_'.$Numbers.'_'.$salt2;
  $stages .= '<br />5) Добавление «соли» (кол-во символов в начале и от 42 до 138 и случайное число в конце) › '.$WithSalt;

  $result = $WithSalt;
 } else {
  $result = '&lt;Слово содержит недопустимые символы.&gt;';
 }
}

echo '<!DOCTYPE html>
<html lang="ru" dir="ltr">
 <head>
  <meta charset="utf-8" />
  <title>Шифр</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <style>
body {
 font-family: sans-serif;
}

#sym-tbl {
 position: fixed;
 bottom: 0;
 left: 0;
 width: 100%;
 height: 80%;
 background: white;
 box-shadow: 0 0 7px grey;
}

#sym-tbl h2 {
 color: navy;
 padding-left: 10px;
 padding-right: 10px;
 width: calc(100% - 20px);
}

.cl-stbl {
 position: absolute;
 top: 0;
 right: 0;
}

.st-btns {
 overflow-y: auto;
}

.s {
 width: 100%;
 overflow-x: auto;
}
  </style>
 </head>
 <body>
  <h1 style="color: navy;">Шифрование [v1.0]</h1>
  <p>Поддерживаемые символы: A–Z, a–z <button onclick="showSymbolTable()">Ω</button></p>

  <form action="/" method="GET">
   <label>Слово: <input type="text" name="w" id="wi" value="'.$_GET["w"].'" /></label>
   <input type="submit" value="шифр" />
  </form><br /><br />

  <div id="sym-tbl" style="display: none;">
   <h2>Таблица символов</h2>
   <button onclick="closeSymbolTable()" class="cl-stbl">&times;</button> <!-- × -->
   <div class="st-btns">
';

for ($i = 0; $i < count($sym); $i++) {
 $s = $sym[$i];
 echo '
    <button onclick="document.getElementById(\'wi\').value += \''.$s.'\'">'.$s.'</button>';
}

echo '
   </div>
  </div>

  <script type="text/javascript">
   let st = document.getElementById(\'sym-tbl\');

   function showSymbolTable() {
    st.style.display = \'block\';
   }
   function closeSymbolTable() {
    st.style.display = \'none\';
   }
  </script>
  <div class="s">
   <fieldset>
    <legend>Результат</legend>
    <p>'.$result.'</p>
   </fieldset>
  </div><br />
  <hr noshade color="silver" /><br />
  <div class="s">
   <fieldset>
    <legend>Этапы</legend>
    <p>'.$stages.'</p>
   </fieldset>
  </div>

 </body>
</html>';

?>