function getDiv( id ) {
if (document.all) {
return document.all[id].style;
} else {
return document.getElementById(id).style;
}
return 0;
}
function showMenu() {
var dv = getDiv("navmenu");
if (dv) {
dv.visibility = "visible";
}
return 0;
}
function hideMenu() {
var dv = getDiv("navmenu");
if (dv) {
dv.visibility = "hidden";
}
return 0;
}
var menuTimer = 0;
function sT() {
menuTimer = setTimeout("hideMenu()",1000);
}
function cT() {
clearTimeout(menuTimer);
}
window.onload = hideMenu;