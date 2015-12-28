function clickImg(obj){
    // on fait apparaître la modale
    document.getElementById("modaleboxsombre").style.display = "block";
    
    // on remplit le contenu de la modale
    var $contenu = document.getElementById("modale-contenu");
    var $img = new Image();
    $img.src = obj.src;
    $contenu.innerHTML = "";
    $contenu.appendChild($img);
}