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
    
    document.getElementById("editeur").focus();
}