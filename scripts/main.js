function s(obj) {
    if (dce(obj).style.visibility == 'hidden' || dce(obj).style.visibility == ''){
        dce(obj).style.visibility = 'visible';
        dce(obj).style.position = 'relative';
    } else {
        dce(obj).style.visibility = 'hidden';
        dce(obj).style.position = 'fixed';
    }
}

function load_modal(id, cur="./") {
    var el;
    
    if(dce(id)) {
        $("#"+id).modal("show");
        return;
    } else {
        var request = new XMLHttpRequest();
        request.open("GET", cur+"modales/"+id+".html", true);
        request.onreadystatechange = function() {
            if(request.readyState == 4 && request.status == 200) {
                el = document.createElement("div");
                el.id = id;
                el.setAttribute("role", "dialog");
                el.setAttribute("tabindex", "-1");
                el.setAttribute("aria-labelledby", id+"Label");
                el.setAttribute("class", "modal fade");
                el.innerHTML = request.responseText;
                document.body.appendChild(el);
                $("#"+id).modal("show");
            }
        }
        request.send();
    }
}

function dce(obj) { return document.getElementById(obj.toString()); }