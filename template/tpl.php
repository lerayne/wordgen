<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Therronian</title>
	<link href="template/tpl_style.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" language="javascript" src="lib/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" language="javascript" src="lib/functions.js"></script>
	<script type="text/javascript" language="javascript">

		orunes = {};
		txt = {};
		<?

		$i = 0;
		foreach ($runes as $val):
			$filepath = "./images/runes/rune_".hexdec($val['hexcode']).".gif";

//			logs($filepath);
//			logs(file_exists($filepath) ? 'true' : 'false');

			echo "orunes[{$i}]={num:{$i}, hex:'{$val['hexcode']}', mono:'{$val['print']}', glas:{$val['open']}, "
				."rus:'{$val['rus_sound']}', mlit:'{$val['val_symbol']}', mcom:'{$val['val_common']}', "
				."mmag:'{$val['val_magic']}', file:'". (file_exists($filepath) ? $filepath : '') . "'};\n";
			$i++;
		endforeach;

		foreach ($text as $name => $str):
			echo "txt['{$name}'] = '{$str}';";
		endforeach;
		?>

	</script>
	<script type="text/javascript" language="javascript" src="lib/engine.js"></script>
	<link rel="shortcut icon" href="images/icon<?= $test ?>.png"/>

</head>

<body>


<div id="load_page_throbber">&nbsp;</div>

<!--<pre>
<?/* print_r ($GLOBALS) */?>
</pre>
-->

<div id="page" class="mean_none">
	<form id="main">

		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td id="tables_panel">

					<div id="tabs">
						<?
						foreach (array('runetable', 'runelist', 'vocabulary') as $val):
							echo "\t\t\t<span onclick='switchTab(this)' id='{$val}'>{$text[$val]}</span>\n\t\t\t<input type='radio'"
								. (($val == 'runetable') ? " checked='checked'" : '') . " name='tabsbtn' id='{$val}_radio' />\n";
						endforeach;
						?>

						<div id="meanings_cont" style="float:right">
							Показывать значения:
							<input type="radio" name="meanings" id="show_common" checked="checked"
								   onclick="toggleType()"/> общие
							<input type="radio" name="meanings" id="show_magic" onclick="toggleType()"/> магические
						</div>

						<div style="clear:both;"></div>
					</div>

					<div id="overflow" class="overflow">
						<div class="tab" id="runetable_block">

							<div class='runetable examp'>
								<input type='checkbox' onClick='CheckAll(this)' id='check_all'/><span class='sub'>[здесь: выбрать все]</span>
								<img src='rune.php?0xffff'> Назв<i>а</i>ние (звучание)
								[<b>символ</b>] <span class='sub'<b>Буквальный перевод</b></span><br/>

								<div class="m_com meaning">Общее значение</div>
								<div class="m_mag meaning">Магическое значение</div>
							</div>

							<?
							foreach ($runes as $val):

								$filepath = "./images/runes/rune_".hexdec($val['hexcode']).".gif";
								$runesrc = file_exists($filepath) ? $filepath : 'rune.php?0x'.$val['hexcode'];

								echo "<div class='runetable' id='runebox_" . $val['hexcode'] . "'>
                <input type='checkbox' onClick='Check(this)' id='rune_" . $val['hexcode'] . "' title='" . $val['hexcode'] . "' />
                <img onclick='insert(\"" . $val['print'] . "\", \"output\")' src='{$runesrc}'> "
								. $val['value'] . " (" . $val['lat_sound'] . "/" . $val['rus_sound'] . ") [<b>" . $val['print'] . "</b>] <span class='sub'><b>"
								. $val['val_symbol'] . "</b></span><br /><div class='m_com meaning'>" . $val['val_common']
								. "</div><div class='m_mag meaning'>" . $val['val_magic'] . "</div></div>";
							endforeach;
							?>
						</div>

						<div class="tab" id="runelist_block">

							<table class="brief" width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<th><input type="checkbox" id="check_all_list" onclick='CheckAll(this)'/></th>
									<th><img src="rune.php?0xffff"></th>
									<th>HEX</th>
									<th>Название</th>
									<th>Звучание<br>(лат\рус)</th>
									<th>Символ</th>
									<th>Досл.<br>перевод</th>
									<th>Общее значение</th>
									<th>Магическое значение</th>
								</tr>
								<?
								foreach ($runes as $val):
									echo "<tr id='listrow_" . $val['hexcode'] . "'><td>
					<input type='checkbox' id='listrune_" . $val['hexcode'] . "' onclick='Check(this)' title='" . $val['hexcode'] . "' />
					</td><td><img onclick='insert(\"" . $val['print'] . "\", \"output\")' src='rune.php?0x" . $val['hexcode'] . "'></td>
                    <td class='upper' align='center'>" . strtoupper($val['hexcode']) . "</td><td>" . $val['value'] . "</td>
					<td class='capit'>" . $val['lat_sound'] . " / " . $val['rus_sound'] . "</td><td class='list_symbol'>"
										. $val['print'] . "</td><td>" . $val['val_symbol'] . "</td><td>" . $val['val_common']
										. "</td><td>" . $val['val_magic'] . "</td></tr>";
								endforeach;
								?>
							</table>

						</div>

						<div class="tab" id="vocabulary_block">

							<table class="brief" width="100%" border="0" cellspacing="0" cellpadding="0">
								<thead>
								<tr>
									<th><input type="checkbox" id="check_all_words" onclick=''/></th>
									<th><img src="rune.php?0xffff"></th>
									<th>Глифскрипт</th>
									<th>Транскрибция</th>
									<th>Часть речи</th>
									<th>Перевод</th>
									<th>Действия</th>
								</tr>
								</thead>
								<tbody id="dictContainer">

								</tbody>
								<?
								/*foreach ($words as $val):
									echo "<tr>
					<td>
						<input type='checkbox' onclick=''/>
					</td>
					<td></td>
					<td>" . $val['word'] . "</td>
					<td>" . $text[$val['part']] . "</td>
					<td>" . $val['transl'] . "</td>
					<td></td>
					</tr>";
								endforeach;*/
								?>
							</table>

						</div>

					</div>

				</td>
				<td id="gen_panel">

					<div id="conditions">
						Использовать от <input id="syll_from" type="text" size="1" value="1">
						до <input id="syll_to" type="text" size="1" value="3"> слогов
					</div>
					<input class="genbutton" type="button" value="Создать слово" onclick="Seed()"/>
					<embed class="svg_arrow" src="images/arrow.svg" type="image/svg+xml"
						   pluginspage="http://www.adobe.com/svg/viewer/install/"/>
					<div id="word">
						<div id="runescript"></div>
						<input id="glyphscript" type="text" id="output" onblur="Fill()" onkeyup="Fill()"/>
						<span id="transcrib"></span>
					</div>
					<table id="meanings" border="0" cellpadding="0" cellspacing="0">
					</table>
					<embed class="svg_arrow" src="images/arrow.svg" type="image/svg+xml"
						   pluginspage="http://www.adobe.com/svg/viewer/install/"/>
					<input class="genbutton" id="voc_dialog_btn" type="button" value="Записать в словарь"
						   onclick="toggleVocDialog()"/>

					<div id="voc_dialog">
						<br/>
						<select style=" width:100%" id="parts">
							<? echo
							'<option value="n">' . $text['n'] . '</option>
                <option value="adj">' . $text['adj'] . '</option>
                <option value="num">' . $text['num'] . '</option>
                <option value="v">' . $text['v'] . '</option>
                <option value="pron">' . $text['pron'] . '</option>
                <option value="adv">' . $text['adv'] . '</option>
                <option value="conj">' . $text['conj'] . '</option>
                <option value="prep">' . $text['prep'] . '</option>
                <option value="part">' . $text['part'] . '</option>
                <option value="dpart">' . $text['dpart'] . '</option>';
							?>
						</select>
						<br/>

						<label><?= $text['translation']?><br><textarea id="translation"></textarea></label>
						<input id="saveWord" class="genbutton" type="button" value="Создать запись"/>
					</div>

				</td>
			</tr>
		</table>

	</form>
</div>

<div style="display:none" id="php_log"><?= $to_log ?></div>

</body>
</html>