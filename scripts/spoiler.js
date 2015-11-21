function s(obj) {
    if (document.getElementById(obj.toString()).style.visibility == 'hidden' || document.getElementById(obj.toString()).style.visibility == ''){
        document.getElementById(obj.toString()).style.visibility = 'visible';
        document.getElementById(obj.toString()).style.position = 'relative';
    }else{
        document.getElementById(obj.toString()).style.visibility = 'hidden';
        document.getElementById(obj.toString()).style.position = 'absolute';
    }
}