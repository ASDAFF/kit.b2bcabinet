/*
 * Copyright (c) 2017. Sergey Danilkin.
 */


function deleteBuyer(id, path, sessid)
{

    var xhr = new XMLHttpRequest();
    xhr.open("POST", path.replace('?id=#ID#', '')+'?del_id='+id+'&sessid='+sessid);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();
    document.getElementById("buyer-"+id).remove();
}