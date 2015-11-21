function switch_r_and_l(){
    var tmp = document.getElementById("r").style.position;
    document.getElementById("r").style.position = document.getElementById("l").style.position;
    document.getElementById("l").style.position = tmp;
}