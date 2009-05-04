<!--
//Ajax užkraunam puslapį
function load(page,id) {
    document.getElementById(id).innerHTML = 'Krauname...<br /><img src="/images/loading.gif" />';
    new Ajax.Updater(id,page, {asynchronous:true, evalScripts:true});
    return false;
}

//Ajax žinutė
// message('Klaida','Jūs neteisingai nurodėte elpašto adresą','error','tooltip');
function message(title,text,type,id) {
	$(id).innerHTML = "<table style=\"margin-bottom: 10px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td class=\"" + type + "\"><b>" + title + "</b><br />" + text + "</td></tr></table>";
	Effect.Pulsate($(id));
}

/*
google char, change only "chd" value
http://chart.apis.google.com/chart?cht=p&chs=250x100&chl=Hello|World&chco=B22D28&chd=s:hW
var valueArray = new Array(0,1,4,4,6,11,14,17,23,28,33,36,43,59,65);
var maxValue = 70; 
encode(valueArray,maxValue);
*/
function encode(valueArray,maxValue) {
	var simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	var chartData = ['s:'];
	  for (var i = 0; i < valueArray.length; i++) {
		 var currentValue = valueArray[i];
		 if (!isNaN(currentValue) && currentValue >= 0) {
		 chartData.push(simpleEncoding.charAt(Math.round((simpleEncoding.length-1) * currentValue / maxValue)));
		 }
			else {
			chartData.push('_');
			}
	  }
	return chartData.join('');
}
-->