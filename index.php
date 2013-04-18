<?
require "init.php";

$runes = $db->query('assoc', 'SELECT * FROM thl_runes ORDER BY rune_id ASC');
$words = $db->query('assoc', 'SELECT * FROM thl_words ORDER BY word ASC');
$special_symbols = array('ɐ','ƨ','ɛ','ʃ','ʦ','ʧ','æ','ü','ʒ','ї','ʤ','ʣ','ħ');

if (strpos($_SERVER['REQUEST_URI'], 'test')) $test = '_test';

header ("Content-Type: text/html;charset=utf-8");

//error_reporting(E_ALL);

$agent = strtolower(getenv(HTTP_USER_AGENT));

# if (preg_match('/(mobile\ssafari|android)/', $agent)) $mobile = '_js_mobile';

require 'template/tpl'.$mobile.'.php';

