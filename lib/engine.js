wgen = {
	collections:{}
}

Interface = function(){
	bind(this)

	this.body = $('body');

	this.btn = {};
	this.btn.saveWord = this.body.find('#saveWord');

	this.input = {}
	this.input.glyphscript = this.body.find('#glyphscript');
	this.input.translation = this.body.find('#translation');

	this.input.part = this.body.find('#parts');

	this.checkAll = this.body.find('#check_all');
	this.dictContainer = this.body.find('#dictContainer');
}

Interface.prototype = {
	fillDict:function(json){
		console.log(json)

		this.dictContainer.children().remove();
		wgen.collections.dictPage = {}

		for (var i in json){
			var row = wgen.collections.dictPage[i] = new DictRow(json[i])
			this.dictContainer.append(row.body)
		}
	}
}


PartSelect = function(selected){
	this.select = $('<select>');

	var list = ['adj', 'num', 'v', 'pron', 'adv', 'conj', 'prep', 'part', 'dpart'];

	for (var i in list){
		$('<option '+ (selected == list[i] ? 'selected="selected"' : '') +' value="'+ list[i] +'">'+ txt[list[i]] +'</option>').appendTo(this.select);
	}
}


DictRow = function(object){
	bind(this);

	this.body = $('<tr>');

	this.id = parseInt(object.word_id, 10);

	this.elements = {};

	this.elements.check =
		$('<input type="checkbox">')
			.appendTo($('<td>')
			.appendTo(this.body));

	this.elements.runescript = $('<td>').appendTo(this.body);
	this.elements.glyphscript = $('<td>').appendTo(this.body);
	this.elements.transcribtion = $('<td>').appendTo(this.body);
	this.elements.part = $('<td>').appendTo(this.body);
	this.elements.translation = $('<td>').appendTo(this.body);

	this.elements.actionsBar = $('<td>').appendTo(this.body);
	this.elements.mainActions = $('<span>').appendTo(this.elements.actionsBar);
	this.elements.actionControl = $('<span style="display:none">').appendTo(this.elements.actionsBar);

	this.elements.editBtn =
		$('<span class="link">Изменить</span>').click(this.edit)
			.appendTo(this.elements.mainActions);

	this.elements.deleteBtn =
		$('<span class="link">Удалить</span>').click(this.delete)
			.appendTo(this.elements.mainActions);

	this.fill(object);

}

DictRow.prototype = {

	fill:function(object){

		this.runescript = new Runescript(object.word)
		this.part = object.part;
		this.translation = object.transl;

		this.elements.runescript.children().remove();
		this.elements.runescript.append(this.runescript.getScriptElement());

		this.elements.glyphscript.text(this.runescript.glyphscript);
		this.elements.transcribtion.html(this.runescript.transcribtion);
		this.elements.part.text(txt[this.part]);
		this.elements.translation.html(this.translation);
	},

	edit:function(){
		this.elements.mainActions.hide();

		var editConfirm = $('<span class="link">OK</span>').appendTo(this.elements.actionControl).click(this.editConfirm);
		var editCancel = $('<span class="link">Отмена</span>').appendTo(this.elements.actionControl).click(this.editCancel);
		this.elements.actionControl.show();

		this.elements.glyphscript.attr('contenteditable', true);
		this.elements.translation.attr('contenteditable', true);

		this.elements.part.text('');

		this.partSelect = new PartSelect(this.part);
		this.elements.part.append(this.partSelect.select);
	},

	editExit:function(){
		this.elements.mainActions.show();
		this.elements.actionControl.children().remove();
		this.elements.actionControl.hide();
		this.elements.glyphscript.removeAttr('contenteditable');
		this.elements.translation.removeAttr('contenteditable');
	},

	editConfirm:function(){
		var data = {
			id: this.id,
			glyphs: this.elements.glyphscript.text(),
			part: this.partSelect.select.val(),
			translation: this.elements.translation.text()
		}

		if (data.glyphs.match(/^\s*$/)) wgen.error.alert('empty_word');
		else $.get('ajax.php?action=saveword', data, this.editDone);
	},

	editCancel:function(){
		this.partSelect.select.remove();
		this.elements.part.text(txt[this.part]);

		this.editExit();
	},

	editDone:function(){
		this.partSelect.select.remove();

		this.fill({
			word:this.elements.glyphscript.text(),
			part: this.partSelect.select.val(),
			transl: this.elements.translation.text()
		})

		this.editExit();
	},

	delete:function(){
		if (confirm('Точно удалить?')){
			$.get('ajax.php?action=deleteword', {id:this.id}, this.deleteDone);
		}
	},

	deleteDone:function(){
		this.body.fadeOut(400);
	}
}



ErrorReporter = function(){

}

ErrorReporter.prototype = {
	alert:function(code){
		alert(code)
	}
}



Dict = function(){
	bind(this)

	wgen.if.btn.saveWord.click(this.save);
}

Dict.prototype = {
	save:function(){

		var data = {
			glyphs: wgen.if.input.glyphscript.val(),
			part: wgen.if.input.part.val(),
			translation: wgen.if.input.translation.val()
		}

		if (data.glyphs.match(/^\s*$/)) wgen.error.alert('empty_word');
		else $.get('ajax.php?action=saveword', data, this.done);
	},

	get:function(){
		$.get('ajax.php?action=getdict', this.done);
	},

	done:function(data, textStatus, jqXHR){

//		console.log('return from ajax:`', data, '`');
		if (!data.match(/^\s*$/)){
			this.lastResult = $.parseJSON(data);
			wgen.if.fillDict(this.lastResult);
		}
	}
}

// вызывается при установке или снятии флага на руне
function Check(el) {
	ID('check_all').checked = ID('check_all_list').checked = false;
	ID('rune_'+el.title).checked = ID('listrune_'+el.title).checked = el.checked;
}

// установка флага на всех
function CheckAll(el) {
	var test = ID('check_all').checked = ID('check_all_list').checked = el.checked;
	for (var i in orunes) ID('rune_'+orunes[i].hex).checked = ID('listrune_'+orunes[i].hex).checked = test;
}

// смена описания
function toggleType() {ID('page').className = (ID('show_common').checked) ? 'mean_common' : 'mean_magic';}

// подсветка
function hl(hex) {
	ID('runebox_'+hex).className += ' hl'; 
	ID('listrow_'+hex).className += ' hl';
}

function unl(hex) {
	ID('runebox_'+hex).className = ID('runebox_'+hex).className.replace(' hl', '');
	ID('listrow_'+hex).className = ID('runebox_'+hex).className.replace(' hl', '');
}



// формирование слова
function Seed() {

	var word = new Array(), checked_glas = new Array(), checked_soglas = new Array(), g = 0, s = 0;
	
	// создание/обнуление массивов гласных и согласных включенных рун 
	for (var i in orunes) {
		if (ID('rune_'+orunes[i].hex).checked) {
			if (orunes[i].glas) checked_glas[g++] = orunes[i].num; else checked_soglas[s++] = orunes[i].num;
		}
	}
	
	// если соблюдены условия: больше двух рун, минимум 1 гласная руна
	if (checked_glas.length > 0 & (checked_glas.length+checked_soglas.length) > 1) {
		
		//считываем количество слогов из полей формы и случайным образом определяем их кол-во в текущем слове
		var sylls_num = GetRand(ID("syll_from").value, ID("syll_to").value);
		// для каждого слога:
		for (var i=0, j=0; i<sylls_num; i++) {
			// выбираем вариант слога
			switch (GetRand(1,8)){
				case 1: case 2: case 3: //(в 37.5% - 2-звуковый слог гласный/согласный)
					word[j++] = ArrayRand(checked_glas);
					word[j++] = ArrayRand(checked_soglas);
				break;
				case 4: case 5: case 6: //(в 37.5% - 2-звуковый слог согласный/гласный)
					word[j++] = ArrayRand(checked_soglas);
					word[j++] = ArrayRand(checked_glas);
				break;
				case 7: //(в 12.5% - 3-звуковый слог согласный/гласный/согласный)
					word[j++] = ArrayRand(checked_soglas);
					word[j++] = ArrayRand(checked_glas);
					word[j++] = ArrayRand(checked_soglas);
				break;
				case 8: //(в 12.5% - 3-звуковый слог гласный/согласный/гласный)
					word[j++] = ArrayRand(checked_glas);
					word[j++] = ArrayRand(checked_soglas);
					word[j++] = ArrayRand(checked_glas);
				break;
			}
		}
		var transl = '';
		for (var i in word) transl += orunes[word[i]].mono;
		wgen.if.input.glyphscript.val(transl);
		Fill();		
	} 
	else alert ("Заданы неверные условия!\nВозможные проблемы:\n• Ни одной гласной руны\n• Меньше двух рун");
}




// установка руноскрипта и транскрибции из монозаписи
function Fill() {
	
	// обнуляем таблицу подсказок
	ID('meanings').innerHTML = '';
	ID('runescript').innerHTML = '';
	ID('transcrib').innerHTML = '';
	
	// вычитываем глифскрипт ...
	var word = wgen.if.input.glyphscript.val().split('');
	
	// ... и превращаем массив моносимволов в массив гекс-кодов
	for (var k in word) {
		// находим эту букву в таблице
		for (var n in orunes) if (word[k] == orunes[n].mono) {
			word[k] = n; // превращаем букву в ключ массива orunes
				
			//заполняем таблицу подсказок
			ID('meanings').innerHTML += "<tr onmouseover='hl(\""+orunes[n].hex+"\")' onmouseout='unl(\""+orunes[n].hex+"\")'>"
			+"<td width='55'><img class='small' src='rune.php?0x"+orunes[n].hex+"'/> ("+orunes[n].rus+") - </td>"
			+"<td> <span class='m_com'><b>"+orunes[n].mlit+"</b>, "+orunes[n].mcom+"</span><span class='m_mag'>"+orunes[n].mmag+"</span></td></tr>\n";
			
			// Транскрибция: если в транскрибции есть запятая
			if (orunes[n].rus.indexOf(',') >= 0) {
				// разделить строку по запятой        // выяснить какую транскрибцию использовать     // убрать пробелы
				ID('transcrib').innerHTML += orunes[n].rus.split(',')[(k==0 || orunes[word[k-1]].glas==0) ?0 :1].replace(' ', '');
			} else ID('transcrib').innerHTML += orunes[n].rus;
			
			// заполняем руноскрипт
			ID('runescript').innerHTML += "<img src='rune.php?0x"+orunes[n].hex+"' "
			+"onmouseover='hl(\""+orunes[n].hex+"\")' onmouseout='unl(\""+orunes[n].hex+"\")'>";
		}
	}
}


function getIndex(object, needle, field){
	for (var i in object){
		if (object[i][field] == needle) return i;
	}

	return -1;
}


var Runescript = function(glyphscript){
	bind(this);

	this.runes = [];

	this.glyphscript = glyphscript;
	this.transcribtion = ''

	for (var i = 0; i < glyphscript.length; i++) {
		var glyph = glyphscript[i];
		var runeIndex = getIndex(orunes, glyph, 'mono');

		if (runeIndex >= 0) {
			var rune = this.runes[i] = orunes[runeIndex];

			// если есть варианты транскрибций
			if (rune.rus.indexOf(',') >= 0) {

				// если буква первая, или предыдущая буква - согласная, берем первое звучание
				var which = (i == 0 || this.runes[i-1].glas == 0) ? 0 : 1;

				this.transcribtion += rune.rus.split(',')[which].replace(' ', '');

			} else this.transcribtion += rune.rus;
		}
	}
}

Runescript.prototype = {
	getScriptElement:function(){
		var element = $('<span class="script">');
		for (var i in this.runes){
			var runepath = this.runes[i].file ? this.runes[i].file : 'rune.php?0x'+ this.runes[i].hex;
			element.append($('<img class="script-rune" src="'+ runepath +'">'))
		}
		return element;
	}
}


function insert(symbol, fieldId) {
	var oldValue = ID(fieldId).value;
	var selectionStart = ID(fieldId).selectionStart;
	var firstPart = oldValue.substring(0, selectionStart);
	var secondPart = oldValue.substring(ID(fieldId).selectionEnd);
	ID(fieldId).value = firstPart + symbol + secondPart;
	ID(fieldId).focus();
	ID(fieldId).selectionStart = selectionStart + symbol.length;
	ID(fieldId).selectionEnd = ID(fieldId).selectionStart;
	Fill();
}

function switchTab(el) {
	
	if (el) ID(el.id+'_radio').checked = true;
	
	var children = ID('tabs').childNodes;
	
	for (i in children) if (children[i].nodeName == 'INPUT') {
		var tabId = children[i].id.replace('_radio', '');
		ID(tabId+'_block').style.display = (children[i].checked) ? 'block' : 'none' ;
		ID(tabId).className = (children[i].checked) ? 'active' : '' ;
	}
}

function winResize() {
	var height = document.documentElement.clientHeight;
	ID('gen_panel').style.height = height+"px";
	ID('tables_panel').style.height = height+"px";
	ID('overflow').style.height = (height - ID('tabs').offsetHeight)+"px";
}

function toggleVocDialog() {
	if (CompStyle('voc_dialog').display == 'none') {
		ID('voc_dialog').style.display = 'block';
		ID('voc_dialog_btn').value = 'Отмена';
	} else {
		ID('voc_dialog').style.display = 'none';
		ID('voc_dialog_btn').value = 'Записать в словарь';
	}
}

// вызываается при загрузке страницы
function Init() {

	wgen.if = new Interface();
	wgen.error = new ErrorReporter();

	Fill();
	switchTab();
	ID('load_page_throbber').style.display = 'none';
	toggleType();
	winResize();

	wgen.dict = new Dict();
	wgen.dict.get();

	wgen.if.checkAll.click();

	exportPHPlog();
}

$(Init)

window.onresize = winResize;
