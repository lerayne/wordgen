<?

header ("Content-type: image/gif");

$image = imagecreate(22, 22);
$color_black = imagecolorallocate ($image, 0,0,0);
$color_bg = imagecolorallocate ($image, 240, 240, 240);
imagecolortransparent($image, $color_bg);
imagefill($image, 0, 0, $color_bg);

$code = $_SERVER['QUERY_STRING']+0;
if (!$code) $code = 0x0000;

// код руны сравнивается с нужным битом (его номер совпадает со степенью двойки - от 0 до F)
if ($code & pow(2,0)) {
	imageline($image, 0,0, 0,11, $color_black);
	imageline($image, 1,0, 1,11, $color_black);
}
if ($code & pow(2,1)) {
	imageline($image, 0,1, 10,11, $color_black);
	imageline($image, 0,0, 10,10, $color_black);
	imageline($image, 1,0, 10,9, $color_black);
}
if ($code & pow(2,2)) {
	imageline($image, 0,9, 9,0, $color_black);
	imageline($image, 0,10, 10,0, $color_black);
	imageline($image, 1,10, 10,1, $color_black);
}
if ($code & pow(2,3)) {
	imageline($image, 10,0, 10,11, $color_black);
	imageline($image, 11,0, 11,11, $color_black);
}
if ($code & pow(2,4)) {
	imageline($image, 11,1, 20,10, $color_black);
	imageline($image, 11,0, 21,10, $color_black);
	imageline($image, 12,0, 21,9, $color_black);
}
if ($code & pow(2,5)) {
	imageline($image, 20,0, 11,9, $color_black);
	imageline($image, 21,0, 11,10, $color_black);
	imageline($image, 21,1, 11,11, $color_black);
}
if ($code & pow(2,6)) {
	imageline($image, 20,0, 20,11, $color_black);
	imageline($image, 21,0, 21,11, $color_black);
}
if ($code & pow(2,7)) {
	imageline($image, 0,12, 0,21, $color_black);
	imageline($image, 1,12, 1,21, $color_black);
}
if ($code & pow(2,8)) {
	imageline($image, 0,12, 9,21, $color_black);
	imageline($image, 0,11, 10,21, $color_black);
	imageline($image, 1,11, 10,20, $color_black);
}
if ($code & pow(2,9)) {
	imageline($image, 0,20, 10,10, $color_black);
	imageline($image, 0,21, 10,11, $color_black);
	imageline($image, 1,21, 10,12, $color_black);
}
if ($code & pow(2,0xA)) {
	imageline($image, 10,12, 10,21, $color_black);
	imageline($image, 11,12, 11,21, $color_black);
}
if ($code & pow(2,0xB)) {
	imageline($image, 12,11, 21,20, $color_black);
	imageline($image, 11,11, 21,21, $color_black);
	imageline($image, 11,12, 20,21, $color_black);
}
if ($code & pow(2,0xC)) {
	imageline($image, 11,20, 20,11, $color_black);
	imageline($image, 11,21, 21,11, $color_black);
	imageline($image, 12,21, 21,12, $color_black);
}
if ($code & pow(2,0xD)) {
	imageline($image, 20,12, 20,21, $color_black);
	imageline($image, 21,12, 21,21, $color_black);
}
if ($code & pow(2,0xE)) {
	imageline($image, 0,10, 10,10, $color_black);
	imageline($image, 0,11, 10,11, $color_black);
}
if ($code & pow(2,0xF)) {
	imageline($image, 10,10, 20,10, $color_black);
	imageline($image, 10,11, 20,11, $color_black);
}

$filepath = './images/runes/rune_'.$code.'.gif';
if (!file_exists($filepath)) {
	imagegif($image, $filepath);

	$log = fopen('./log/rune.php.txt', 'a');
	fwrite($log, date('Y.m.d H:i:s')." - rune file created ({$filepath})\r\n");
}

imagegif($image);
?>