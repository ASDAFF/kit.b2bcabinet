var allGagdgetHoldersSC = [];
function getGadgetHolderSC(id)
{
    return allGagdgetHoldersSC[id];
}

function SCGadget(gadgetHolderID, allGadgets)
{
    var _this = this;

    BX.addCustomEvent('onAjaxFailure', function(status){
        if (status == 'auth')
        {
            top.location = top.location.href;
        }
    });

    _this.gadgetHolderID = gadgetHolderID;
    _this.allGadgets = allGadgets;
    allGagdgetHoldersSC[_this.gadgetHolderID] = _this;

    _this.menuItems = [];
    if (arGDGroups !== undefined)
    {
        for(var gr_id in arGDGroups)
        {
            if (arGDGroups.hasOwnProperty(gr_id))
            {
                var items = [];
                for(var _gid in arGDGroups[gr_id]['GADGETS'])
                {
                    if (arGDGroups[gr_id]['GADGETS'].hasOwnProperty(_gid))
                    {
                        var gid = arGDGroups[gr_id]['GADGETS'][_gid];
                        for(var i in _this.allGadgets)
                        {
                            if(_this.allGadgets[i]['ID'].toUpperCase() == gid.toUpperCase())
                            {
                                _this.allGadgets[i]['ONCLICK'] = "getGadgetHolderSC('"+_this.gadgetHolderID+"').Add('"+_this.allGadgets[i]['ID']+"')";
                                items[items.length] = _this.allGadgets[i];
                                break;
                            }
                        }
                    }
                }
            }

            _this.menuItems[gr_id] =
                {
                    'ID': gr_id,
                    'TEXT':	'<div class="widget_button"><div class="widgets_cabinet_title">' + arGDGroups[gr_id]['NAME'] + '</div><div' +
                    ' class="widgets_cabinet_descr">' + arGDGroups[gr_id]['DESCRIPTION']+'</div></div>',
                    'MENU': items
                };
        }
    }

    // Recalc gadgets positions
    _this.gdList = Array();
    _this.gdCols = Array();
    _this.__GDList = function()
    {
        _this.gdList = Array();
        _this.gdCols = Array();
        var GDHolder = document.getElementById("GDHolder_"+_this.gadgetHolderID).children;

        var childElements, l, el, i;
        for(i=0; i < GDHolder.length; i++)
        {
            if(GDHolder[i].id.substring(0, 1) == 's')
            {
                l = Array();
                childElements = GDHolder[i].childNodes;
                for(el in childElements)
                {
                    if (childElements.hasOwnProperty(el))
                    {
                        if(!childElements[el])
                            continue;
                        if(childElements[el].tagName && (childElements[el].tagName.toUpperCase() == 'TABLE' || childElements[el].tagName.toUpperCase() == 'DIV') && childElements[el].id.substring(0, 1) == 't')
                        {
                            l[l.length] = childElements[el];
                        }
                    }
                }
                _this.gdList[_this.gdCols.length] = l;
                GDHolder[i].realPos =jsUtils.GetRealPos(GDHolder[i]);
                _this.gdCols[_this.gdCols.length] = GDHolder[i];
            }
        }
    };

    // Drag'n'drop start
    _this.gdDrag = false;
    _this.mousePos = {x: 0, y: 0};
    _this.zind = 0;

    _this.tmpDiv = false;

    _this.DragStart = function(n, e)
    {
        if(e)
        {
            if(e.srcElement && e.srcElement.tagName.toLowerCase() == 'a')
                return false;

            if(e.originalTarget && e.originalTarget.tagName.toLowerCase() == 'a')
                return false;
        }

        _this.__GDList();
        var t = BX('t' + n);
        var tablePos = jsUtils.GetRealPos(t);
        var d = BX('d' + n);

        d.style.display = 'block';
        d.width = t.offsetWidth+'px';
        d.style.height = t.offsetHeight+'px';

        t.style.position = 'absolute';
        t.style.width = d.offsetWidth + 'px';
        t.style.height = d.offsetHeight + 'px';
        t.style.border = '1px solid #777777';
        _this.zind = t.style.zIndex;
        t.style.zIndex = '10000';
        t.style.left = (tablePos["left"] + 20) + 'px';
        t.style.top = tablePos["top"] + 'px';

        t.style.MozOpacity = 0.60;
        t.style.opacity = 0.60;
        t.style.filter = 'gray() alpha(opacity=60)';

        _this.gdDrag = n;

        _this.tmpDiv = document.createElement("DIV");
        _this.tmpDiv.style.display = "none";
        _this.tmpDiv.innerHTML = '';
        t.parentNode.insertBefore(_this.tmpDiv, t);

        document.body.appendChild(t);

        _this.mousePos.x = e.clientX + document.body.scrollLeft;
        _this.mousePos.y = e.clientY + document.body.scrollTop;
        
        if(typeof changeWidgetWidth == 'function')
        {
        	changeWidgetWidth();
        }
        return false;
    };

    // Drag'n'drop move
    _this.onMouseMove = function(e)
    {

        if(_this.gdDrag === false)
            return;

        var t = document.getElementById('t'+_this.gdDrag);

        var x = e.clientX + document.body.scrollLeft;
        var y = e.clientY + document.body.scrollTop;

        t.style.left = parseInt(t.style.left) + x - _this.mousePos.x + 'px';
        t.style.top =  parseInt(t.style.top) + y - _this.mousePos.y + 'px';

        var rRealPos = jsUtils.GetRealPos(t), c, i, te, el = false, mm;
        var center = rRealPos.left + (rRealPos.right - rRealPos.left)/2, center2 = rRealPos.top + (rRealPos.bottom - rRealPos.top)/2;
        for(i=0; i<_this.gdCols.length; i++)
        {
            c = _this.gdCols[i].realPos;
            if(c.left <= center && c.right >= center)
            {
                for(te in _this.gdList[i])
                {
                    if (_this.gdList[i].hasOwnProperty(te))
                    {
                        if(_this.gdList[i][te].id == t.id)
                            mm = jsUtils.GetRealPos(document.getElementById('d'+_this.gdDrag));
                        else
                            mm = jsUtils.GetRealPos(_this.gdList[i][te]);
                        if(center2 < mm.bottom)
                        {
                            el = _this.gdList[i][te];

                            _this.tmpDiv = document.createElement("DIV");
                            _this.tmpDiv.style.display = "none";
                            _this.tmpDiv.innerHTML = '';

                            if(_this.gdList[i][te].id == t.id)
                                document.getElementById('d'+_this.gdDrag).parentNode.insertBefore(_this.tmpDiv, document.getElementById('d'+_this.gdDrag));
                            else
                                el.parentNode.insertBefore(_this.tmpDiv, el);

                            break;
                        }
                    }
                }

                if(!el)
                {
                    el = 'last';
                }

                break;
            }
        }

        if(el)
        {
            var d = document.getElementById('d'+_this.gdDrag);
            d.parentNode.removeChild(d);
            if(el=='last')
                _this.gdCols[i].appendChild(d);
            else
                _this.tmpDiv.parentNode.insertBefore(d, _this.tmpDiv);
        }

        _this.mousePos.x = x;
        _this.mousePos.y = y;
        
        if(typeof changeWidgetWidth == 'function')
        {
        	changeWidgetWidth();
        }
    };

    // Drag'n'drop end
    _this.onMouseUp = function(e)
    {
        if(_this.gdDrag === false)
            return;

        var t = BX('t' + _this.gdDrag);

        t.style.MozOpacity = 1;
        t.style.opacity = 1;
        t.style.filter = '';
        t.style.position = 'static';
        t.style.border = '0px';
        t.style.width = '';
        t.style.height = '';
        t.style.zIndex = _this.zind;

        var d = BX('d' + _this.gdDrag);
        d.style.display = 'none';

        t.parentNode.removeChild(t);
        d.parentNode.insertBefore(t, d);

        _this.gdDrag = false;

        if(!_this.sendWait)
        {
            _this.sendWait = true;
            setTimeout("getGadgetHolderSC('" + _this.gadgetHolderID + "').SendUpdatedInfo();", 1000);
        }
        
        if(typeof changeWidgetWidth == 'function')
        {
        	changeWidgetWidth();
        }
    };

    // Create gadgets position string
    _this.GetPosString = function()
    {
        var GDHolder = document.getElementById("GDHolder_"+_this.gadgetHolderID).children;
        var childElements, el, i;
        var result = '', column=-1, row=0;
        for(i=0; i < GDHolder.length; i++)
        {
            if(GDHolder[i].id.substring(0, 1) == 's')
            {
                column++;
                row=0;
                childElements = GDHolder[i].childNodes;
                for(el in childElements)
                {
                    if (childElements.hasOwnProperty(el))
                    {
                        if(!childElements[el])
                            continue;
                        if(childElements[el].tagName && (childElements[el].tagName.toUpperCase() == 'TABLE' || childElements[el].tagName.toUpperCase() == 'DIV') && childElements[el].id.substring(0, 1) == 't')
                        {
                            result = result+'&POS['+column+']['+row+']='+encodeURIComponent(childElements[el].id.substring(1)) + (childElements[el].className.indexOf(" gdhided")>0?"*H":"");
                            row++;
                        }
                    }
                }
            }
        }

        return result;
    };

    _this.GetPos = function()
    {
        var GDHolder = document.getElementById("GDHolder_"+_this.gadgetHolderID).children;
        var childElements, el, i;
        var POS = [], column=-1, row=0;

        for(i=0; i < GDHolder.length; i++)
        {
            if(GDHolder[i].id.substring(0, 1) == 's')
            {
                column++;
                row=0;
                childElements = GDHolder[i].childNodes;
                for(el in childElements)
                {
                    if (childElements.hasOwnProperty(el))
                    {
                        if(!childElements[el])
                            continue;
                        if(childElements[el].tagName && (childElements[el].tagName.toUpperCase() == 'TABLE' || childElements[el].tagName.toUpperCase() == 'DIV') && childElements[el].id.substring(0, 1) == 't')
                        {
                            if (typeof (POS[column]) == 'undefined')
                            {
                                POS[column] = [];
                            }

                            POS[column][row] = childElements[el].id.substring(1) + (childElements[el].className.indexOf(" gdhided")>0 ? "*H" : "");

                            row++;
                        }
                    }
                }
            }
        }

        return POS;
    };

    _this.SendUpdatedInfo = function(param)
    {
        param = param || "update_position";

        if (!!_this.sendUpdate || _this.gdDrag !== false)
        {
            setTimeout("getGadgetHolderSC('" + _this.gadgetHolderID + "').SendUpdatedInfo('" + param + "');", 500);
            return;
        }

        _this.sendUpdate = true;
        _this.sendWait = false;

        BX.ajax({
            url: updateURL,
            method: 'POST',
            dataType: 'html',
            data: {
                "sessid": BX.bitrix_sessid(),
                "gd_ajax": _this.gadgetHolderID,
                "gd_ajax_action": param,
                "POS": _this.GetPos()
            },
            onsuccess: function(data) {
                _this.sendUpdate = false;
                if(param == 'clear_settings')
                {
                    window.location = window.location;
                }
            },
            onfailure: function(data) {
                _this.sendUpdate = false;
                alert(langGDError1);
            }
        });
    };

    _this.Add = function(id)
    {
        var frm = document.getElementById("GDHolderForm_" + _this.gadgetHolderID);
        frm["gid"].value = id;
        frm["action"].value = "add";
        frm.submit();
    };

    _this.UpdSettings = function(id)
    {
        var frm = document.getElementById("GDHolderForm_" + _this.gadgetHolderID);
        frm["gid"].value = id;
        frm["action"].value = "update";

        function __AddField(elmName, elmValue)
        {
            var elm;

            if(
                typeof(elmValue) == 'object'
                || elmValue instanceof Array
            )
            {
                for(var r in elmValue)
                {
                    if (elmValue.hasOwnProperty(r))
                    {
                        elm = document.createElement("INPUT");
                        elm.type = "hidden";
                        elm.name = "settings["+elmName+"][]";
                        elm.value = elmValue[r];
                        frm.appendChild(elm);
                    }
                }
            }
            else
            {
                elm = document.createElement("INPUT");
                elm.type = "hidden";
                elm.name = "settings["+elmName+"]";
                elm.value = elmValue;
                frm.appendChild(elm);
            }
        }

        var dSet = document.getElementById("dset"+id);
        var el, res = '';
        for(var i=0; i<dSet._inputs.length; i++)
        {
            el = document.getElementById(id + '_' + dSet._inputs[i]);
            if(el)
            {
                if(el.tagName.toUpperCase() == 'SELECT' && el.multiple)
                {
                    var selectedOptions = [];
                    for (var k=0; k<el.options.length; k++)
                        if (el.options[k].selected)
                            selectedOptions.push(el.options[k].value);
                    __AddField(dSet._inputs[i], selectedOptions);
                }
                else if(el.tagName.toUpperCase()=='INPUT' && el.type.toUpperCase()=='CHECKBOX')
                    __AddField(dSet._inputs[i], (el.checked ? 'Y' : 'N'));
                else
                    __AddField(dSet._inputs[i] , el.value);
            }
        }

        frm.submit();
    };

    _this.SetForAll = function()
    {
        langGDConfirm = langGDConfirm1;

        if (arguments[0])
        {
            if (arguments[0] == 'SU')
                langGDConfirm = langGDConfirmUser;

            if (arguments[0] == 'SG')
                langGDConfirm = langGDConfirmGroup;
        }

        if(!confirm(langGDConfirm))
            return;

        _this.SendUpdatedInfo('save_default');
    };

    _this.ClearUserSettings = function()
    {
        _this.SendUpdatedInfo('clear_settings');
    };

    _this.ClearUserSettingsConfirm = function()
    {
        if(!confirm(langGDClearConfirm))
            return;

        _this.SendUpdatedInfo('clear_settings');
    };

    _this.Delete = function(id)
    {
        var t = document.getElementById('t'+id);
        if(t)
            t.parentNode.removeChild(t);

        var d = document.getElementById('d'+id);
        if(d)
            d.parentNode.removeChild(d);

        if(!_this.sendWait)
        {
            _this.sendWait = true;
            setTimeout("getGadgetHolderSC('"+_this.gadgetHolderID+"').SendUpdatedInfo();", 500);
        }

        if(typeof changeWidgetWidth == 'function')
        {
        	changeWidgetWidth();
        }
        
        
        return false;
    };

    _this.Hide = function(id, ob)
    {
        var t = document.getElementById('t'+id);
        if(!t)
            return;

        var classes = t.className.split(' ');

        var addClasses = '';
        for (var i = 0; i < classes.length; i++)
        {
            if(classes[i].search('sotbit') != -1)
            {
                addClasses += classes[i] + ' ';
            }
        }

        if(t.className.indexOf(" gdhided")>0)
            t.className = 'data-table-gadget '+addClasses;
        else
            t.className = 'data-table-gadget gdhided '+addClasses;

        if(!_this.sendWait)
        {
            _this.sendWait = true;
            setTimeout("getGadgetHolderSC('"+_this.gadgetHolderID+"').SendUpdatedInfo();", 500);
        }
        if(typeof changeWidgetWidth == 'function')
        {
        	changeWidgetWidth();
        }
        return false;
    };

    _this.CloseSettingsForm = function(id)
    {
        var dSet = document.getElementById("dset"+id);
        dSet.style.display = 'none';
    };

    SCGadget.prototype.ShowSettings = function(id, t)
    {
        var dS = document.getElementById("dset"+id);
        var is_selected = '';

        t = t || 'get_settings';
        _this = this;

        if(dS.style.display != 'none')
        {
            dS.style.display = 'none';
            if(typeof changeWidgetWidth == 'function')
            {
            	changeWidgetWidth();
            }
            return;
        }

        BX.ajax({
            url: updateURL,
            method: 'POST',
            dataType: 'html',
            data: {
                "sessid": BX.bitrix_sessid(),
                "gd_ajax": _this.gadgetHolderID,
                "gid": id,
                "gd_ajax_action": t
            },
            onsuccess: function(data)
            {
                var before = new Date().getTime();
                var dSet = document.getElementById("dset"+id);
                dSet.innerHTML = '';
                dSet._inputs = [];

                try
                {
                    eval('var gdObject = '+ data);
                }
                catch (e)
                {
                    return;
                }
                var param, param_id;
                var oEl;
                for(param_id in gdObject)
                {
                    if (gdObject.hasOwnProperty(param_id))
                    {
                        param = gdObject[param_id];
                        var str = '';
                        var input_id = id + '_' + param_id;

                        param["TYPE"] = param["TYPE"] || 'STRING';

                        if(!param["VALUE"] && param["DEFAULT"]!='undefined')
                            param["VALUE"] = param["DEFAULT"];

                        if(param["TYPE"]=="STRING")
                        {
                            str = '<span>'+param["NAME"] + ':</span><input type="text" id="' + input_id + '" size="40"' +
                                ' value="'+jsUtils.htmlspecialchars(param["VALUE"])+'">';
                        }
                        else if(param["TYPE"]=="LIST")
                        {
                            var aR = [];
                            for(var vid in param["VALUES"])
                            {
                                if (param["VALUES"].hasOwnProperty(vid))
                                {
                                    if (param["MULTIPLE"] == "Y")
                                    {
                                        is_selected = '';

                                        if(param["VALUE"] instanceof Array)
                                        {
                                            for (var k=0; k<param["VALUE"].length; k++)
                                            {
                                                if (param["VALUE"][k] == vid)
                                                {
                                                    is_selected = ' selected';
                                                    break;
                                                }
                                            }
                                        }
                                        else
                                            is_selected = (param["VALUE"] == vid ? ' selected' : '');
                                    }
                                    else
                                        is_selected = (param["VALUE"] == vid ? ' selected' : '');

                                    aR.push('<option value="' + vid+'" ' + is_selected + '>' + param["VALUES"][vid] + '</option>');
                                }
                            }
                            str = '<span>' + param["NAME"] + ':</span><select class="'+param["NAME"]+'-select" id="' + input_id + '" ' + (param["MULTIPLE"] == "Y" ? 'multiple="multiple"' : '') + '>' + aR.join("") + '</select>';

                        }
                        else if(param["TYPE"]=="CHECKBOX")
                        {
                            str = param["NAME"]+': <input type="checkbox" id="' + input_id + '" value="Y" '+(param["VALUE"]=='Y'?' checked':'')+'>';
                        }

                        oEl = document.createElement("DIV");
                        oEl.setAttribute('class', 'gdsettrow '+ param_id);
                        //oEl.className = "gdsettrow";
                        oEl.innerHTML = str;
                        dSet.appendChild(oEl);
                        dSet._inputs[dSet._inputs.length] = param_id;
                    }
                }

                oEl = document.createElement("DIV");
                oEl.setAttribute('class', 'gdsettrow button-row');
                //oEl.className = "gdsettrow";

                oEl.innerHTML = '<input type="button" class="button-ok" value="OK" onclick="getGadgetHolderSC(\''+_this.gadgetHolderID+'\').UpdSettings(\''+id+'\');"> <input type="button" class="button-cancel" value="'+langGDCancel+'" onclick="getGadgetHolderSC(\''+_this.gadgetHolderID+'\').CloseSettingsForm(\''+id+'\');">';
                dSet.appendChild(oEl);

                dSet.style.display = 'block';
                
                
                if(typeof changeWidgetWidth == 'function')
                {
                	changeWidgetWidth();
                }
            },
            onfailure: function(data) {
                alert(langGDError2);
            }
        });
        


        return false;
    };

    SCGadget.prototype.ShowAddGDMenu  = function(a)
    {
    	showAdd();

/*
        this.menu = new PopupMenu('gadgets_float_menu');
        this.menu.Create(1000);

        if(this.menu.IsVisible())
            return;

        this.menu.SetItems(this.menuItems);
        this.menu.BuildItems();

        var pos = jsUtils.GetRealPos(a);
        pos["bottom"]+=1;

        this.menu.PopupShow(pos);*/
    };

    jsUtils.addEvent(document.body, "mousemove", _this.onMouseMove);
    jsUtils.addEvent(document.body, "mouseup", _this.onMouseUp);
}

function showAdd()
{
	var element_widget = document.querySelectorAll(".widgets_cabinet");
    var body_widget = document.querySelector(".body_widgets_main");
    for(var i = 0; i < element_widget.length; i++) 
    {
        element_widget[i].classList.toggle("show_widgets");
    }   
    body_widget.classList.toggle("body_widgets");
    document.querySelector(".body_class").classList.toggle("stop-scrolling");
}


