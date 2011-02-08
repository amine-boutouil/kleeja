/** ------------------------------------------------
	^_^ i love jquery
	package:	Kleeja  copyright(c)2007 (http://www.kleeja.com)
	-------------------------------------------------
	$Id: global.js 1537 2010-07-23 01:39:34Z ALTAR3Q $
	-------------------------------------------------
**/
jQuery(document).ready(function($){

	// Scroll Bar
	$('#ScrollBar').scrollFollow();	
	
    // custom checkboxes
	$('input[type=checkbox]').checkbox();
	
	// Tooltip
	$('.HTI a img').qtip({
			position: {my: 'bottom right',at: 'top center', adjust: { x:2,  y : 3 , screen: 'flip'}},
			show: {delay: 0},style: {classes: 'ui-tooltip-plain', tip: true}
	});
	$('.seTooltip a').qtip({
		position: {	my: 'bottom right',	at: 'top right',target: 'mouse',adjust: { y :-7, screen: 'flip'}},
		style: {classes: 'ui-tooltip-light',tip: true}
	});
	$('#ktipTop a img').qtip({
		position: {target: 'mouse',adjust: {x: 7, y: 7, screen: 'flip'}},
		style: {classes: 'ui-tooltip-dark',tip: true}
	});
	
	// System Messages
	$('.notification').hover(function() {
		$(this).css('cursor','pointer');
 	}, function() {
		$(this).css('cursor','auto');
	});
	$('.notification span').click(function() {
		$(this).parent().fadeOut(800);
    });
	$('.notification').click(function() {
        $(this).fadeOut(800);
    });
	
});