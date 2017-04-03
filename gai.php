<?php

include('simplehtmldom/simple_html_dom.php');

$today = date("Y.m.d");


$array = range(0, 7);


foreach($array as $e){


$url = "http://gaiminsk.by/svodka?page=" . $e;

$html = file_get_html($url);



foreach($html->find('.report_block') as $val){
    $text = $val->plaintext;


$actual_day = substr($text, 0, 10);

preg_match("/^(.+?)\./", $actual_day, $only_day);
$true_day = $only_day[1] - 1;

preg_match("/\.[\s\S]*$/", $actual_day, $second_part);
$actual_day = $true_day . $second_part[0];
//print_r($actual_day);



preg_match("/За прошедшие сутки в городе Минске зарегистрировано (.*) дорожно-/", $text, $output_text);
$x = $output_text[0];
$total = preg_replace( '/[^0-9]/', '', $x );


preg_match("/За (минувшие|прошедшие) сутки в городе Минске задержан(.*) водител(я|ей|ь)(,|) управля/", $text, $output_text2);
$y = $output_text2[0];
$drunk = preg_replace( '/[^0-9]/', '', $y );


preg_match("/За превышение скоростных режимов движения привлечен(о|) к( административной|) ответственности (.*?) водител/", $text, $output_text3);
$z = $output_text3[0];
$skorost = preg_replace( '/[^0-9]/', '', $z );


preg_match("/За нарушение правил проезда пешеходных переходов привлечен(|о) к ответственности (.*?) водител/", $text, $output_text4);
$z1 = $output_text4[0];
$perechod = preg_replace( '/[^0-9]/', '', $z1 );
//print_r($perechod);

preg_match("/опьянения, (.*?) водител(я|ей|ь) не имел/", $text, $output_text5);
$z2 = $output_text5[0];
$bezprav = preg_replace( '/[^0-9]/', '', $z2 );


$itog = array(
    //array("Vsego", "DRUNK", "Skorost", "Pesh Perechody", "Date"),
    array($total, $drunk, $bezprav, $skorost, $perechod, $actual_day),

);

$itog1 = array(
    array("Vsego", "DRUNK", "Bez Prav", "Skorost", "Pesh Perechody", "Date"),
    array($total, $drunk, $bezprav, $skorost, $perechod, $actual_day),

);

$fp = fopen('/var/web/city/sites/default/files/gai/output/gai.csv', 'a+');
  foreach ($itog as $fields) {
      fputcsv($fp, $fields);
  }
fclose($fp);


$fp1 = fopen('/var/web/city/sites/default/files/gai/output/gai-today.csv', 'w');
foreach ($itog1 as $fields1) {
    fputcsv($fp1, $fields1);
}
fclose($fp1);

}

}

