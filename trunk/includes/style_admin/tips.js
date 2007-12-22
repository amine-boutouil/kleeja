
/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Saul Salvatierra :: http://myarea.com.sapo.pt
with help from Ultimater :: http://ultimiacian.tripod.com  */

var theObj="";

function toolTip(text,me) {
  theObj=me;
  theObj.onmousemove=updatePos;
  document.getElementById('toolTipBox').innerHTML=text;
  document.getElementById('toolTipBox').style.display="block";
  window.onscroll=updatePos;
}

function updatePos() {
  var ev=arguments[0]?arguments[0]:event;
  var x=ev.clientX;
  var y=ev.clientY;
  diffX=24;
  diffY=0;
  document.getElementById('toolTipBox').style.top  = y-2+diffY+document.body.scrollTop+ "px";
  document.getElementById('toolTipBox').style.left = x-2+diffX+document.body.scrollLeft+"px";
  theObj.onmouseout=hideMe;
}
function hideMe() {
  document.getElementById('toolTipBox').style.display="none";
}
