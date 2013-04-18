// универсальная функция получения случайного целого от from до to
function GetRand (from, to){
	from = parseInt(from);
	to = parseInt(to);
	return Math.floor(Math.random()*(to-from+0.999999999999999))+from;
}

// универсальная функция вызова элемента по ID 
function ID(str){
	return document.all ? document.all[str] : document.getElementById(str);
}

// выдает реальный вычесленный стиль
function CompStyle(id) {
	return document.defaultView ? document.defaultView.getComputedStyle(ID(id), null) : ID(id).currentStyle;
}

// функция получения случайного элемента массива 
function ArrayRand (array) {
	return array[GetRand(0, array.length-1)];
}

// Создать массив с ключами в виде занчений исходного массива
function ArrayKeys(arr) {
	var obj = {};
	for (var i in arr) {obj[arr[i]]='';}
	return obj;
}

// Если в массиве arr есть значение needle - возвращает true
function inArray(arr, needle) {
  var obj = ArrayKeys(arr);
  if (needle in obj) return true;
  else return false;
}

function ExtractHash (obj, key, val) {
	hash = {};
	for (var i in obj) {
		hash[obj[i].key] = obj[i].val;
	}
	return hash;
}

bind = function (context, funcNames) {
	/*
	 * если funcNames:
	 * 	строка - указанная функция привязывается к контексту класса по ее имени
	 * 	массив строк - все функции, перечисленные по именам, привязываются к контексту класса
	 *	не подан - все функции прототипа класса привязываются к контексту класса
	 * */

	// собственно, функция привязки к контексту
	var _bind = function (funcName) {
		if (typeof Object.bind == 'function') {

			context[funcName] = context[funcName].bind(context);

		} else {

			//context[funcName] = $.proxy(context, funcName);
			context[funcName] = function () {
				return context[funcName].apply(context, arguments);
			}
		}
	}

	switch (typeof funcNames) {
		// второй аргумент вообще не подан - привязываем все функции
		case 'undefined':

			for (var memberName in context) {
				if (typeof context[memberName] == 'function') _bind(memberName);
			}

			break;
		// подан массив - привязываем все его элементы к контексту
		case 'object':

			for (var i = 0; i < funcNames.length; i++) _bind(funcNames[i]);

			break;
		// подана строка - привязываем одну функцию
		case 'string':
		default:

			_bind(funcNames);
	}

}

exportPHPlog = function(){
	var log = $('#php_log').text();
	if (!log.match(/$\s*^/)) console.log('php log:\n',log);
}