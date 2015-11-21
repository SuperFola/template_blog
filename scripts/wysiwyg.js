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
    document.execCommand(name, false, argument);
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

function refresh_html(){
    document.getElementById("resultat").value = document.getElementById("editeur").innerHTML;
}