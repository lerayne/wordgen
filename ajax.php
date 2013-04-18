<?php
/**
 * Created by JetBrains PhpStorm.
 * User: M. Yegorov
 * Date: 3/29/13
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */

require "init.php";

$action = $_GET['action'] ? $_GET['action'] : '';

switch ($action):

	case 'saveword':

		if ($_REQUEST['id']) {
			$db->query('UPDATE thl_words SET word="'.$_REQUEST['glyphs'].'", part="'.$_REQUEST['part'].'", transl="'.$_REQUEST['translation'].'" WHERE word_id = '.$_REQUEST['id']);
		} else
			$db->query('INSERT INTO thl_words (word, part, transl) VALUES("'.$_REQUEST['glyphs'].'", "'.$_REQUEST['part'].'", "'.$_REQUEST['translation'].'")');

	break;

	case 'deleteword':
		$db->query('UPDATE thl_words SET deleted=1 WHERE word_id = '.$_REQUEST['id']);
	break;

endswitch;

echo json_encode($db->query('assoc', 'SELECT * FROM thl_words WHERE deleted IS NULL ORDER BY word ASC'));