
function AddFileInput()
{
	var counter = document.getElementById("files_counter").value;
	var table = document.getElementById("files_table_"+counter);

	document.getElementById("files_counter").value = ++counter;
	table.innerHTML += '<input name="FILE_'+counter+'" size="30" type="file"><br /><span id="files_table_'+counter+'"></span>';
}


function SupQuoteMessage()
{
	var selection;
	if (document.getSelection)
	{
		selection = document.getSelection();
		selection = selection.replace(/\r\n\r\n/gi, "_newstringhere_");
		selection = selection.replace(/\r\n/gi, " ");
		selection = selection.replace(/  /gi, "");
		selection = selection.replace(/_newstringhere_/gi, "\r\n\r\n");
	}
	else
	{
		selection = document.selection.createRange().text;
	}

	if (selection!="")
		document.forms["support_edit"].elements["MESSAGE"].value += "<QUOTE>"+selection+"</QUOTE>\n";
}

var QUOTE_open = 0;
var CODE_open = 0;
var B_open = 0;
var I_open = 0;
var U_open = 0;

var myAgent   = navigator.userAgent.toLowerCase();
var myVersion = parseInt(navigator.appVersion);
var myVersion = parseInt(navigator.appVersion);
var is_ie  = ((myAgent.indexOf("msie") != -1)  && (myAgent.indexOf("opera") == -1));
var is_nav = ((myAgent.indexOf('mozilla')!=-1) && (myAgent.indexOf('spoofer')==-1)
 && (myAgent.indexOf('compatible') == -1) && (myAgent.indexOf('opera')==-1)
 && (myAgent.indexOf('webtv')==-1) && (myAgent.indexOf('hotjava')==-1));

var is_win = ((myAgent.indexOf("win")!=-1) || (myAgent.indexOf("16bit")!=-1));
var is_mac = (myAgent.indexOf("mac")!=-1);


function insert_tag(thetag, objTextarea)
{
	var tagOpen = eval(thetag + "_open");
	if (tagOpen == 0)
	{
		if (DoInsert(objTextarea, "<"+thetag+">", "</"+thetag+">"))
		{
			eval(thetag + "_open = 1");
			eval("document.forms['support_edit'].elements['"+thetag+"'].value += '*'");
			//eval("document.form1." + thetag + ".value += '*'");
		}
	}
	else
	{
		DoInsert(objTextarea, "</"+thetag+">", "");
		//eval("document.form1." + thetag + ".value = ' " + eval(thetag + "_title") + " '");

		var buttonText = eval("document.forms['support_edit'].elements['"+thetag+"'].value");
		eval("document.forms['support_edit'].elements['"+thetag+"'].value = '"+(buttonText.slice(0,-1))+"'");

		eval(thetag + "_open = 0");
	}
}

function mozillaWr(textarea, open, close)
{
	var selLength = textarea.textLength;
	var selStart = textarea.selectionStart;
	var selEnd = textarea.selectionEnd;
	
	if (selEnd == 1 || selEnd == 2)
	selEnd = selLength;

	var s1 = (textarea.value).substring(0,selStart);
	var s2 = (textarea.value).substring(selStart, selEnd)
	var s3 = (textarea.value).substring(selEnd, selLength);
	textarea.value = s1 + open + s2 + close + s3;

	textarea.selectionEnd = 0;
	textarea.selectionStart = selEnd + open.length + close.length;
	return;
}


function DoInsert(objTextarea, Tag, closeTag)
{
	var isOpen = false;

	//if (closeTag=="")
		//isOpen = true;

	if ( myVersion >= 4 && is_ie && is_win && objTextarea.isTextEdit)
	{
		objTextarea.focus();
		var sel = document.selection;
		var rng = sel.createRange();
		rng.colapse;
		if ((sel.type=="Text" || sel.type=="None") && rng != null)
		{
			if (closeTag!="")
			{
				if (rng.text.length > 0) 
					Tag += rng.text + closeTag; 
				else
					isOpen = true;
			}
			rng.text = Tag;
		}
	}
	else
	{
		if (is_nav && document.getElementById)
		{
			if (closeTag!="" && objTextarea.selectionEnd > objTextarea.selectionStart)
			{
				mozillaWr(objTextarea, Tag, closeTag);
				isOpen = false;
			}
			else
			{
				mozillaWr(objTextarea, Tag, '');
				isOpen = true;
			}
		}
		else
		{
			objTextarea.value += Tag;
			isOpen = true;
		}

		//isOpen = true;
		//objTextarea.value += Tag;
	}



	objTextarea.focus();
	return isOpen;
}

// РЎвЂљРЎР‚Р В°Р Р…РЎРѓР В»Р С‘РЎвЂљР С‘РЎР‚Р В°РЎвЂ Р С‘РЎРЏ

//var TRANSLIT_title = "<?=GetMessage("SUP_TRANSLIT")?>";
var TRANSLIT_way = 0;

var smallEngLettersReg = new Array(/e'/g, /ch/g, /sh/g, /yo/g, /jo/g, /zh/g, /yu/g, /ju/g, /ya/g, /ja/g, /a/g, /b/g, /v/g, /g/g, /d/g, /e/g, /z/g, /i/g, /j/g, /k/g, /l/g, /m/g, /n/g, /o/g, /p/g, /r/g, /s/g, /t/g, /u/g, /f/g, /h/g, /c/g, /w/g, /~/g, /y/g, /'/g);
var capitEngLettersReg = new Array( /E'/g, /CH/g, /SH/g, /YO/g, /JO/g, /ZH/g, /YU/g, /JU/g, /YA/g, /JA/g, /A/g, /B/g, /V/g, /G/g, /D/g, /E/g, /Z/g, /I/g, /J/g, /K/g, /L/g, /M/g, /N/g, /O/g, /P/g, /R/g, /S/g, /T/g, /U/g, /F/g, /H/g, /C/g, /W/g, /~/g, /Y/g, /'/g);
var smallRusLetters = new Array("РЎРЊ", "РЎвЂЎ", "РЎв‚¬", "РЎвЂ�", "РЎвЂ�", "Р В¶", "РЎР‹", "РЎР‹", "РЎРЏ", "РЎРЏ", "Р В°", "Р В±", "Р Р†", "Р С–", "Р Т‘", "Р Вµ", "Р В·", "Р С‘", "Р в„–", "Р С”", "Р В»", "Р С�", "Р Р…", "Р С•", "Р С—", "РЎР‚", "РЎРѓ", "РЎвЂљ", "РЎС“", "РЎвЂћ", "РЎвЂ¦", "РЎвЂ ", "РЎвЂ°", "РЎР‰", "РЎвЂ№", "РЎРЉ");
var capitRusLetters = new Array( "Р В­", "Р В§", "Р РЃ", "Р Рѓ", "Р Рѓ", "Р вЂ“", "Р В®", "Р В®", "\Р Р‡", "\Р Р‡", "Р С’", "Р вЂ�", "Р вЂ™", "Р вЂњ", "Р вЂќ", "Р вЂў", "Р вЂ”", "Р ?", "Р в„ў", "Р С™", "Р вЂє", "Р Сљ", "Р Сњ", "Р С›", "Р Сџ", "Р В ", "Р РЋ", "Р Сћ", "Р Р€", "Р В¤", "Р Тђ", "Р В¦", "Р В©", "Р Р„", "Р В«", "Р В¬");

var smallEngLetters = new Array("e", "ch", "sh", "yo", "jo", "zh", "yu", "ju", "ya", "ja", "a", "b", "v", "g", "d", "e", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "w", "~", "y", "'");
var capitEngLetters = new Array("E", "CH", "SH", "YO", "JO", "ZH", "YU", "JU", "YA", "JA", "A", "B", "V", "G", "D", "E", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "W", "~", "Y", "'");
var smallRusLettersReg = new Array(/РЎРЊ/g, /РЎвЂЎ/g, /РЎв‚¬/g, /РЎвЂ�/g, /РЎвЂ�/g,/Р В¶/g, /РЎР‹/g, /РЎР‹/g, /РЎРЏ/g, /РЎРЏ/g, /Р В°/g, /Р В±/g, /Р Р†/g, /Р С–/g, /Р Т‘/g, /Р Вµ/g, /Р В·/g, /Р С‘/g, /Р в„–/g, /Р С”/g, /Р В»/g, /Р С�/g, /Р Р…/g, /Р С•/g, /Р С—/g, /РЎР‚/g, /РЎРѓ/g, /РЎвЂљ/g, /РЎС“/g, /РЎвЂћ/g, /РЎвЂ¦/g, /РЎвЂ /g, /РЎвЂ°/g, /РЎР‰/g, /РЎвЂ№/g, /РЎРЉ/ );
var capitRusLettersReg = new Array(/Р В­/g, /Р В§/g, /Р РЃ/g, /Р Рѓ/g, /Р Рѓ/g, /Р вЂ“/g, /Р В®/g, /Р В®/g, /Р Р‡/g, /Р Р‡/g, /Р С’/g, /Р вЂ�/g, /Р вЂ™/g, /Р вЂњ/g, /Р вЂќ/g, /Р вЂў/g, /Р вЂ”/g, /Р ?/g, /Р в„ў/g, /Р С™/g, /Р вЂє/g, /Р Сљ/g, /Р Сњ/g, /Р С›/g, /Р Сџ/g, /Р В /g, /Р РЋ/g, /Р Сћ/g, /Р Р€/g, /Р В¤/g, /Р Тђ/g, /Р В¦/g, /Р В©/g, /Р Р„/g, /Р В«/g, /Р В¬/);

// РЎРЊ, РЎвЂЎ, РЎв‚¬, РЎвЂ�, РЎвЂ�,Р В¶, РЎР‹, РЎР‹, РЎРЏ, РЎРЏ, Р В°, Р В±, Р Р†, Р С–, Р Т‘, Р Вµ, Р В·, Р С‘, Р в„–, Р С”, Р В», Р С�, Р Р…, Р С•, Р С—, РЎР‚, РЎРѓ, РЎвЂљ, РЎС“, РЎвЂћ, РЎвЂ¦, РЎвЂ , РЎвЂ°, РЎР‰, РЎвЂ№, РЎРЉ
// e, ch, sh, yo, jo, zh, yu, ju, ya, ja, a, b, v, g, d, e, z, i, j, k, l, m, n, o, p, r, s, t, u, f, h, c, w, ~, y, '

function translit(objTextarea)
{
	var i;
	var textbody = objTextarea.value;
	var selected = false;

	if (objTextarea.isTextEdit)
	{
		objTextarea.focus();
		var sel = document.selection;
		var rng = sel.createRange();
		rng.colapse;
		if (sel.type=="Text" && rng != null)
		{
			textbody = rng.text;
			selected = true;
		}
	}

	if (textbody)
	{
		if (TRANSLIT_way==0) // Р В»Р В°РЎвЂљР С‘Р Р…Р С‘РЎвЂ Р В° -> Р С”Р С‘РЎР‚Р С‘Р В»Р В»Р С‘РЎвЂ Р В°
		{
			for (i=0; i<smallEngLettersReg.length; i++) textbody = textbody.replace(smallEngLettersReg[i], smallRusLetters[i]);
			for (i=0; i<capitEngLettersReg.length; i++) textbody = textbody.replace(capitEngLettersReg[i], capitRusLetters[i]);
		}
		else // Р С”Р С‘РЎР‚Р С‘Р В»Р В»Р С‘РЎвЂ Р В° -> Р В»Р В°РЎвЂљР С‘Р Р…Р С‘РЎвЂ Р В°
		{
			for (i=0; i<smallRusLetters.length; i++) textbody = textbody.replace(smallRusLettersReg[i], smallEngLetters[i]);
			for (i=0; i<capitRusLetters.length; i++) textbody = textbody.replace(capitRusLettersReg[i], capitEngLetters[i]);
		}
		if (!selected) objTextarea.value = textbody;
		else rng.text = textbody;
	}

	if (TRANSLIT_way==0) // Р В»Р В°РЎвЂљР С‘Р Р…Р С‘РЎвЂ Р В° -> Р С”Р С‘РЎР‚Р С‘Р В»Р В»Р С‘РЎвЂ Р В°
	{
		//document.form1.TRANSLIT.value += " *";
		document.forms['support_edit'].elements['TRANSLIT'].value += " *";
		TRANSLIT_way = 1;
	}
	else // Р С”Р С‘РЎР‚Р С‘Р В»Р В»Р С‘РЎвЂ Р В° -> Р В»Р В°РЎвЂљР С‘Р Р…Р С‘РЎвЂ Р В°
	{
		//document.form1.TRANSLIT.value = TRANSLIT_title;
		document.forms['support_edit'].elements['TRANSLIT'].value = document.forms['support_edit'].elements['TRANSLIT'].value.slice(0,-2);
		TRANSLIT_way = 0;
	}
	objTextarea.focus();
}
