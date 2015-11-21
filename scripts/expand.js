function e(id, id2, s){
    document.getElementById(id2).style.height = s;
    document.getElementById(id).style.height = document.getElementById(id2).style.height;
}