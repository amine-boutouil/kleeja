/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Uncle Jim :: http://jdstiles.com/javamain.html */

function convert(f) {
  f.kb.value=Math.round(f.byte.value/1024*100000)/100000
  f.mb.value=Math.round(f.byte.value/1048576*100000)/100000
  f.gb.value=Math.round(f.byte.value/1073741824*100000)/100000
}

function convertkb(f) {
  f.byte.value=Math.round(f.kb.value*1024*100000)/100000
  f.mb.value=Math.round(f.kb.value/1024*100000)/100000
  f.gb.value=Math.round(f.kb.value/1048576*100000)/100000
}

function convertmb(f) {
  f.byte.value=Math.round(f.mb.value*1048576*100000)/100000
  f.kb.value=Math.round(f.mb.value*1024*100000)/100000
  f.gb.value=Math.round(f.mb.value/1024*100000)/100000
}

function convertgb(f) {
  f.byte.value=Math.round(f.gb.value*1073741824*100000)/100000
  f.kb.value=Math.round(f.gb.value*1048576*100000)/100000
  f.mb.value=Math.round(f.gb.value*1024*100000)/100000
}

