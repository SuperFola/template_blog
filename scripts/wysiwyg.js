function command(name, argument){
    switch(name){
        case "createLink":
            argument = prompt("Adresse pour le lien :");
            break;
        case "insertImage":
            argument = prompt("URL de l'image :");
            break;
    }
    if (typeof argument === 'undefined') {
        argument = '';
    }
    if (name != "insertImage"){
        document.execCommand(name, false, argument);
    }else{
        document.getElementById("editeur").innerHTML = document.getElementById("editeur").innerHTML + "<img src='" + argument + "' onclick='clickImg(this);'>";
    }
}

function show_resultat(){
    if (document.getElementById("resultat").style.visibility != "visible"){
        document.getElementById("resultat").style.visibility = "visible";
        document.getElementById("btn_html_refresh").style.visibility = "visible";
        document.getElementById("btn_html").value = "Cacher le HTML";
        document.getElementById("resultat").style.position = "relative";
        document.getElementById("resultat").value = document.getElementById("editeur").innerHTML;
    }else{
        document.getElementById("resultat").style.visibility = "hidden";
        document.getElementById("btn_html_refresh").style.visibility = "hidden";
        document.getElementById("btn_html").value = "Obtenir le HTML";
        document.getElementById("resultat").style.position = "absolute";
    }
}

function maj_html(){
    document.getElementById("editeur").innerHTML = document.getElementById("resultat").value;
}

function refresh_html(){
    document.getElementById("resultat").value = document.getElementById("editeur").innerHTML;
}

function show_resultat2(){
    if (document.getElementById("resultat2").style.visibility != "visible"){
        document.getElementById("resultat2").style.visibility = "visible";
        document.getElementById("btn_html_refresh2").style.visibility = "visible";
        document.getElementById("btn_html2").value = "Cacher le HTML";
        document.getElementById("resultat2").style.position = "relative";
        document.getElementById("resultat2").value = document.getElementById("editeur2").innerHTML;
    }else{
        document.getElementById("resultat2").style.visibility = "hidden";
        document.getElementById("btn_html_refresh2").style.visibility = "hidden";
        document.getElementById("btn_html2").value = "Obtenir le HTML";
        document.getElementById("resultat2").style.position = "absolute";
    }
}

function maj_html2(){
    document.getElementById("editeur2").innerHTML = document.getElementById("resultat2").value;
}

function refresh_html2(){
    document.getElementById("resultat2").value = document.getElementById("editeur2").innerHTML;
}