<?

// функция дебага
function debug ($var) {
	echo '<pre>';
	print_r ($var);
	echo '</pre>';
}

// функция форматирования даты
function noseconds ($date) {
	$date = substr($date, 0, 16);
	return str_replace(' ', "&nbsp;", $date);
}

$to_log = '';
function logs($str){
	global $to_log;
	$to_log .= $str."\r\n";
}