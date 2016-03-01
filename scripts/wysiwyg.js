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
    
    document.getElementById("editeur").focus();
}