$(document).on('click', '.support-form__close', function(){
	$('#CLOSE').val('Y');
	$('#supportForm').submit();
});
$(document).on('click', '.support-form__footer__option', function(){
	if(!$(this).hasClass('support-form__footer__option__active'))
	{
		$('.support-form__footer__option').removeClass('support-form__footer__option__active');
		$(this).addClass('support-form__footer__option__active');
		$('#MARK_ID').val($(this).data('mark'));
	}
});

function newInputInit(input) {
    var label = input.nextElementSibling,
        labelVal = label.innerHTML;

    input.addEventListener('change', function (e) {
        var fileName = '';
        if (this.files && this.files.length > 1)
            fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
        else
            fileName = e.target.value.split('\\').pop();

        if (fileName) {
            label.innerHTML = fileName;
        }
    });
}

function AddFileInput(mess,choose,chooseNo){
	let counter = document.getElementById("files_counter").value;
	let tableWrapper = document.querySelector(".index_appeals-add_more_files");

	if (document.getElementById("files_counter").value < 5) {
		$(tableWrapper).append(
			'<div class="files_table" id="files_table_' + counter + '">' +
				'<div class="media-body">' +
					'<div class="upload-file">' +
						'<input type="file" name="FILE_'+ counter +'" size="30" class="input-file" data-fouc style="">' +
						'<span class="filename">' + file_not_selected_text + '</span>' +
						'<span class="action btn btn_b2b">' + choose_file_text + '</span>' +
					'</div>' +
				'</div>' +
			'</div>'
		);

		let input = document.querySelector(".input-file[name=FILE_" + counter + "]");

		if (input) {
			newInputInit(input);
		}

		if (counter >= 5) {
			document.querySelector(".add-more-files").style.display = "none";
		}

		document.getElementById("files_counter").value = ++counter;
	}
}


function SupQuoteMessage(id)
{
	var selection;
	if (document.getSelection)
	{
		var selection = "" + document.getSelection();
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
	{
		document.forms["support_edit"].elements["MESSAGE"].value += "<QUOTE>"+selection+"</QUOTE>\n";
	}
	else
	{
		var el = document.getElementById(id);
		var textData = (el.innerText) ? el.innerText : el.textContent;
		if(el)
		{
			var str = textData
			str = str.replace(/\r\n\r\n/gi, "_newstringhere_");
			str = str.replace(/\r\n/gi, " ");
			str = str.replace(/<br[^>]*>/gi, "");
			str = str.replace(/<\/p[^>]*>/gi, "\r\n");
			str = str.replace(/<li[^>]*>/gi, "\r\n");
			str = str.replace(/<[^>]*>/gi, " ");
			str = str.replace(/  /gi, "");
			str = str.replace(/_newstringhere_/gi, "\r\n");
			document.forms["support_edit"].elements["MESSAGE"].value += "<QUOTE>"+str+"</QUOTE>\n";
		}
	}
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
