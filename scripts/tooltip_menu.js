/* This notice must be untouched at all times.
Copyright (c) 2002-2008 Walter Zorn. All rights reserved.

tooltip.js	 v. 5.1

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Created 1.12.2002 by Walter Zorn (Web: http://www.walterzorn.com )
Last modified: 10.4.2008

Easy-to-use cross-browser tooltips.
Just include the script at the beginning of the <body> section, and invoke
Tip('Tooltip text') from the desired HTML onmouseover eventhandlers,
and UnTip(), usually from the onmouseout eventhandlers, to hide the tip.
No container DIV required.
By default, width and height of tooltips are automatically adapted to content.
Is even capable of dynamically converting arbitrary HTML elements to tooltips
by calling m_TagToTip('ID_of_HTML_element_to_be_converted') instead of Tip(),
which means you can put important, search-engine-relevant stuff into tooltips.
Appearance of tooltips can be individually config_menuured
via commands passed to Tip() or m_TagToTip().

Tab Width: 4
LICENSE: LGPL

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License (LGPL) as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

For more details on the GNU Lesser General Public License,
see http://www.gnu.org/copyleft/lesser.html
*/

var config_menu = new Object();


//===================  GLOBAL TOOPTIP config_menuURATION  =========================//
var  m_tt_Debug	= true		// false or true - recommended: false once you release your page to the public
var  m_tt_Enabled	= true		// Allows to (temporarily) suppress tooltips, e.g. by providing the user with a button that sets this global variable to false
var  TagsToTip	= true		// false or true - if true, HTML elements to be converted to tooltips via m_TagToTip() are automatically hidden;
							// if false, you should hide those HTML elements yourself

// For each of the following config_menu variables there exists a command, which is
// just the variablename in uppercase, to be passed to Tip() or m_TagToTip() to
// config_menuure tooltips individually. Individual commands override global
// config_menuuration. Order of commands is arbitrary.
// Example: onmouseover="Tip('Tooltip text', LEFT, true, BGCOLOR, '#FF9900', FADEIN, 400)"

config_menu. Above			= false 	// false or true - tooltip above mousepointer
config_menu. BgColor 		= '#dedede' // Background colour (HTML colour value, in quotes)
config_menu. BgImg			= ''		// Path to background image, none if empty string ''
config_menu. BorderColor		= '#444444'
config_menu. BorderStyle		= 'solid'	// Any permitted CSS value, but I recommend 'solid', 'dotted' or 'dashed'
config_menu. BorderWidth		= 1
config_menu. CenterMouse		= false 	// false or true - center the tip horizontally below (or above) the mousepointer
config_menu. ClickClose		= false 	// false or true - close tooltip if the user clicks somewhere
config_menu. ClickSticky		= false		// false or true - make tooltip sticky if user left-clicks on the hovered element while the tooltip is active
config_menu. CloseBtn		= true 	// false or true - closebutton in titlebar
config_menu. CloseBtnColors	= ['#444444', '#FFFFFF', '#333333', '#FFFFFF']	  // [Background, text, hovered background, hovered text] - use empty strings '' to inherit title colours
config_menu. CloseBtnText	= '&nbsp;X&nbsp;'	// Close button text (may also be an image tag)
config_menu. CopyContent		= false		// When converting a HTML element to a tooltip, copy only the element's content, rather than converting the element by its own
config_menu. Delay			= 3000		// Time span in ms until tooltip shows up
config_menu. Duration		= 0 		// Time span in ms after which the tooltip disappears; 0 for infinite duration, < 0 for delay in ms _after_ the onmouseout until the tooltip disappears
config_menu. FadeIn			= 200	 		// Fade-in duration in ms, e.g. 400; 0 for no animation
config_menu. FadeOut		= 100
config_menu. FadeInterval	= 35		// Duration of each fade step in ms (recommended: 30) - shorter is smoother but causes more CPU-load
config_menu. Fix				= [300,220]		// Fixated position - x- an y-oordinates in brackets, e.g. [210, 480], or null for no fixation
config_menu. FollowMouse		= false		// false or true - tooltip follows the mouse
config_menu. FontColor		= '#444444'
config_menu. FontFace		= 'Verdana,Geneva,sans-serif'
config_menu. FontSize		= '8pt' 	// E.g. '9pt' or '12px' - unit is mandatory
config_menu. FontWeight		= 'normal'	// 'normal' or 'bold';
config_menu. Height			= 400 		// Tooltip height; 0 for automatic adaption to tooltip content, < 0 (e.g. -100) for a maximum for automatic adaption
config_menu. JumpHorz		= true		// false or true - jump horizontally to other side of mouse if tooltip would extend past clientarea boundary
config_menu. JumpVert		= true		// false or true - jump vertically		"
config_menu. Left			= true 	// false or true - tooltip on the left of the mouse
config_menu. OffsetX			= 0		// Horizontal offset of left-top corner from mousepointer
config_menu. OffsetY			= 0 		// Vertical offset
config_menu. Opacity			= 90		// Integer between 0 and 100 - opacity of tooltip in percent
config_menu. Padding			= 3 		// Spacing between border and content
config_menu. Shadow			= true 	// false or true
config_menu. ShadowColor		= '#111111'
config_menu. ShadowWidth		= 3
config_menu. Sticky			= true 	// false or true - fixate tip, ie. don't follow the mouse and don't hide on mouseout
config_menu. TextAlign		= 'left'	// 'left', 'right' or 'justify'
config_menu. Title			= ''		// Default title text applied to all tips (no default title: empty string '')
config_menu. TitleAlign		= 'left'	// 'left' or 'right' - text alignment inside the title bar
config_menu. TitleBgColor	= ''		// If empty string '', BorderColor will be used
config_menu. TitleFontColor	= '#555555'	// Color of title text - if '', BgColor (of tooltip body) will be used
config_menu. TitleFontFace	= ''		// If '' use FontFace (boldified)
config_menu. TitleFontSize	= ''		// If '' use FontSize
config_menu. TitlePadding	= 2
config_menu. Width			= 600 		// Tooltip width; 0 for automatic adaption to tooltip content; < -1 (e.g. -240) for a maximum width for that automatic adaption;
									// -1: tooltip width confined to the width required for the titlebar
//=======  END OF TOOLTIP config_menu, DO NOT CHANGE ANYTHING BELOW  ==============//




//=====================  PUBLIC  =============================================//
function TipMenu()
{
	m_tt_Tip(arguments, null);
}
function m_TagToTip()
{   
	var t2t = m_tt_GetElt(arguments[0]);
	if(t2t)
		m_tt_Tip(arguments, t2t);
}
function UnTipMenu()
{
	m_tt_OpReHref();
	if(m_tt_aV[DURATION] < 0)
		m_tt_tDurt.Timer("m_tt_HideInit()", -m_tt_aV[DURATION], true);
	else if(!(m_tt_aV[STICKY] && (m_tt_iState & 0x2)))
		m_tt_HideInit();
}

//==================  PUBLIC PLUGIN API	 =====================================//
// Extension eventhandlers currently supported:
// OnLoadconfig_menu, OnCreateContentString, OnSubDivsCreated, OnShow, OnMoveBefore,
// OnMoveAfter, OnHideInit, OnHide, OnKill

var m_tt_aElt = new Array(10), // Container DIV, outer title & body DIVs, inner title & body TDs, closebutton SPAN, shadow DIVs, and IFRAME to cover windowed elements in IE
m_tt_aV = new Array(),	// Caches and enumerates config_menu data for currently active tooltip
m_tt_sContent,			// Inner tooltip text or HTML
m_tt_scrlX = 0, m_tt_scrlY = 0,
m_tt_musX, m_tt_musY,
m_tt_over,
m_tt_x, m_tt_y, m_tt_w, m_tt_h; // Position, width and height of currently displayed tooltip

function m_m_tt_Extension()
{
	m_tt_Extm_CmdEnum();
	m_tt_aExt[m_tt_aExt.length] = this;
	return this;
}
function m_m_tt_SetTipPos(x, y)
{
	var css = m_tt_aElt[0].style;

	m_tt_x = x;
	m_tt_y = y;
	css.left = x + "px";
	css.top = y + "px";
	if(m_tt_ie56)
	{
		var ifrm = m_tt_aElt[m_tt_aElt.length - 1];
		if(ifrm)
		{
			ifrm.style.left = css.left;
			ifrm.style.top = css.top;
		}
	}
}
function m_tt_HideInit()
{
	if(m_tt_iState)
	{
		m_tt_ExtCallFncs(0, "HideInit");
		m_tt_iState &= ~0x4;
		if(m_tt_flagOpa && m_tt_aV[FADEOUT])
		{
			m_tt_tFade.EndTimer();
			if(m_tt_opa)
			{
				var n = Math.round(m_tt_aV[FADEOUT] / (m_tt_aV[FADEINTERVAL] * (m_tt_aV[OPACITY] / m_tt_opa)));
				m_tt_Fade(m_tt_opa, m_tt_opa, 0, n);
				return;
			}
		}
		m_tt_tHide.Timer("m_tt_Hide();", 1, false);
	}
}
function m_tt_Hide()
{
	if(m_tt_db && m_tt_iState)
	{
		m_tt_OpReHref();
		if(m_tt_iState & 0x2)
		{
			m_tt_aElt[0].style.visibility = "hidden";
			m_tt_ExtCallFncs(0, "Hide");
		}
		m_tt_tShow.EndTimer();
		m_tt_tHide.EndTimer();
		m_tt_tDurt.EndTimer();
		m_tt_tFade.EndTimer();
		if(!m_tt_op && !m_tt_ie)
		{
			m_tt_tWaitMov.EndTimer();
			m_tt_bWait = false;
		}
		if(m_tt_aV[CLICKCLOSE] || m_tt_aV[CLICKSTICKY])
			m_tt_RemEvtFnc(document, "mouseup", m_tt_OnLClick);
		m_tt_ExtCallFncs(0, "Kill");
		// In case of a m_TagToTip tooltip, hide converted DOM node and
		// re-insert it into document
		if(m_tt_t2t && !m_tt_aV[COPYCONTENT])
		{
			m_tt_t2t.style.display = "none";
			m_tt_MovDomNode(m_tt_t2t, m_tt_aElt[6], m_tt_t2tDad);
		}
		m_tt_iState = 0;
		m_tt_over = null;
		m_tt_ResetMainDiv();
		if(m_tt_aElt[m_tt_aElt.length - 1])
			m_tt_aElt[m_tt_aElt.length - 1].style.display = "none";
	}
}
function m_tt_GetElt(id)
{
	return(document.getElementById ? document.getElementById(id)
			: document.all ? document.all[id]
			: null);
}
function m_tt_GetDivW(el)
{
	return(el ? (el.offsetWidth || el.style.pixelWidth || 0) : 0);
}
function m_tt_GetDivH(el)
{
	return(el ? (el.offsetHeight || el.style.pixelHeight || 0) : 0);
}
function m_tt_GetScrollX()
{
	return(window.pageXOffset || (m_tt_db ? (m_tt_db.scrollLeft || 0) : 0));
}
function m_tt_GetScrollY()
{
	return(window.pageYOffset || (m_tt_db ? (m_tt_db.scrollTop || 0) : 0));
}
function m_tt_GetClientW()
{
	return(document.body && (typeof(document.body.clientWidth) != m_tt_u) ? document.body.clientWidth
			: (typeof(window.innerWidth) != m_tt_u) ? window.innerWidth
			: m_tt_db ? (m_tt_db.clientWidth || 0)
			: 0);
}
function m_tt_GetClientH()
{
	// Exactly this order seems to yield correct values in all major browsers
	return(document.body && (typeof(document.body.clientHeight) != m_tt_u) ? document.body.clientHeight
			: (typeof(window.innerHeight) != m_tt_u) ? window.innerHeight
			: m_tt_db ? (m_tt_db.clientHeight || 0)
			: 0);
}
function m_tt_GetEvtX(e)
{
	return (e ? ((typeof(e.pageX) != m_tt_u) ? e.pageX : (e.clientX + m_tt_scrlX)) : 0);
}
function m_tt_GetEvtY(e)
{
	return (e ? ((typeof(e.pageY) != m_tt_u) ? e.pageY : (e.clientY + m_tt_scrlY)) : 0);
}
function m_tt_AddEvtFnc(el, sEvt, PFnc)
{
	if(el)
	{
		if(el.addEventListener)
			el.addEventListener(sEvt, PFnc, false);
		else
			el.attachEvent("on" + sEvt, PFnc);
	}
}
function m_tt_RemEvtFnc(el, sEvt, PFnc)
{
	if(el)
	{
		if(el.removeEventListener)
			el.removeEventListener(sEvt, PFnc, false);
		else
			el.detachEvent("on" + sEvt, PFnc);
	}
}

//======================  PRIVATE  ===========================================//
var m_tt_aExt = new Array(),	// Array of extension objects

m_tt_db, m_tt_op, m_tt_ie, m_tt_ie56, m_tt_bBoxOld,	// Browser flags
m_tt_body,
m_tt_ovr_,				// HTML element the mouse is currently over
m_tt_flagOpa, 			// Opacity support: 1=IE, 2=Khtml, 3=KHTML, 4=Moz, 5=W3C
m_tt_maxPosX, m_tt_maxPosY,
m_tt_iState = 0,			// Tooltip active |= 1, shown |= 2, move with mouse |= 4
m_tt_opa, 				// Currently applied opacity
m_tt_bJmpVert, m_tt_bJmpHorz,// Tip temporarily on other side of mouse
m_tt_t2t, m_tt_t2tDad,		// Tag converted to tip, and its parent element in the document
m_tt_elDeHref,			// The tag from which we've removed the href attribute
// Timer
m_tt_tShow = new Number(0), m_tt_tHide = new Number(0), m_tt_tDurt = new Number(0),
m_tt_tFade = new Number(0), m_tt_tWaitMov = new Number(0),
m_tt_bWait = false,
m_tt_u = "undefined";


function m_tt_Init()
{
	m_tt_Mkm_CmdEnum();
	// Send old browsers instantly to hell
	if(!m_tt_Browser() || !m_tt_MkMainDiv())
		return;
	m_tt_IsW3cBox();
	m_tt_OpaSupport();
	m_tt_AddEvtFnc(window, "scroll", m_tt_OnScrl);
	// IE doesn't fire onscroll event when switching to fullscreen;
	// fix suggested by Yoav Karpeles 14.2.2008
	m_tt_AddEvtFnc(window, "resize", m_tt_OnScrl);
	m_tt_AddEvtFnc(document, "mousemove", m_tt_Move);
	// In Debug mode we search for m_TagToTip() calls in order to notify
	// the user if they've forgotten to set the TagsToTip config_menu flag
	if(TagsToTip || m_tt_Debug)
		m_tt_SetOnloadFnc();
	// Ensure the tip be hidden when the page unloads
	m_tt_AddEvtFnc(window, "unload", m_tt_Hide);
}
// Creates command names by translating config_menu variable names to upper case
function m_tt_Mkm_CmdEnum()
{
	var n = 0;
	for(var i in config_menu)
		eval("window." + i.toString().toUpperCase() + " = " + n++);
	m_tt_aV.length = n;
}
function m_tt_Browser()
{
	var n, nv, n6, w3c;

	n = navigator.userAgent.toLowerCase(),
	nv = navigator.appVersion;
	m_tt_op = (document.defaultView && typeof(eval("w" + "indow" + "." + "o" + "p" + "er" + "a")) != m_tt_u);
	m_tt_ie = n.indexOf("msie") != -1 && document.all && !m_tt_op;
	if(m_tt_ie)
	{
		var ieOld = (!document.compatMode || document.compatMode == "BackCompat");
		m_tt_db = !ieOld ? document.documentElement : (document.body || null);
		if(m_tt_db)
			m_tt_ie56 = parseFloat(nv.substring(nv.indexOf("MSIE") + 5)) >= 5.5
					&& typeof document.body.style.maxHeight == m_tt_u;
	}
	else
	{
		m_tt_db = document.documentElement || document.body ||
				(document.getElementsByTagName ? document.getElementsByTagName("body")[0]
				: null);
		if(!m_tt_op)
		{
			n6 = document.defaultView && typeof document.defaultView.getComputedStyle != m_tt_u;
			w3c = !n6 && document.getElementById;
		}
	}
	m_tt_body = (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
				: (document.body || null));
	if(m_tt_ie || n6 || m_tt_op || w3c)
	{
		if(m_tt_body && m_tt_db)
		{
			if(document.attachEvent || document.addEventListener)
				return true;
		}
		else
			m_tt_Err("wz_tooltip.js must be included INSIDE the body section,"
					+ " immediately after the opening <body> tag.", false);
	}
	m_tt_db = null;
	return false;
}
function m_tt_MkMainDiv()
{
	// Create the tooltip DIV
	if(m_tt_body.insertAdjacentHTML)
		m_tt_body.insertAdjacentHTML("afterBegin", m_tt_MkMainDivHtm());
	else if(typeof m_tt_body.innerHTML != m_tt_u && document.createElement && m_tt_body.appendChild)
		m_tt_body.appendChild(m_tt_MkMainDivDom());
	if(window.m_tt_GetMainDivRefs /* FireFox Alzheimer */ && m_tt_GetMainDivRefs())
		return true;
	m_tt_db = null;
	return false;
}
function m_tt_MkMainDivHtm()
{
	return('<div id="WzTtDiV"></div>' +
			(m_tt_ie56 ? ('<iframe id="WzTtIfRm" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>')
			: ''));
}
function m_tt_MkMainDivDom()
{
	var el = document.createElement("div");
	if(el)
		el.id = "WzTtDiV";
	return el;
}
function m_tt_GetMainDivRefs()
{
	m_tt_aElt[0] = m_tt_GetElt("WzTtDiV");
	if(m_tt_ie56 && m_tt_aElt[0])
	{
		m_tt_aElt[m_tt_aElt.length - 1] = m_tt_GetElt("WzTtIfRm");
		if(!m_tt_aElt[m_tt_aElt.length - 1])
			m_tt_aElt[0] = null;
	}
	if(m_tt_aElt[0])
	{
		var css = m_tt_aElt[0].style;

		css.visibility = "hidden";
		css.position = "absolute";
		css.overflow = "hidden";
		return true;
	}
	return false;
}
function m_tt_ResetMainDiv()
{
	var w = (window.screen && screen.width) ? screen.width : 10000;

	m_m_tt_SetTipPos(-w, 0);
	m_tt_aElt[0].innerHTML = "";
	m_tt_aElt[0].style.width = (w - 1) + "px";
	m_tt_h = 0;
}
function m_tt_IsW3cBox()
{
	var css = m_tt_aElt[0].style;

	css.padding = "10px";
	css.width = "40px";
	m_tt_bBoxOld = (m_tt_GetDivW(m_tt_aElt[0]) == 40);
	css.padding = "0px";
	m_tt_ResetMainDiv();
}
function m_tt_OpaSupport()
{
	var css = m_tt_body.style;

	m_tt_flagOpa = (typeof(css.filter) != m_tt_u) ? 1
				: (typeof(css.KhtmlOpacity) != m_tt_u) ? 2
				: (typeof(css.KHTMLOpacity) != m_tt_u) ? 3
				: (typeof(css.MozOpacity) != m_tt_u) ? 4
				: (typeof(css.opacity) != m_tt_u) ? 5
				: 0;
}
// Ported from http://dean.edwards.name/weblog/2006/06/again/
// (Dean Edwards et al.)
function m_tt_SetOnloadFnc()
{
	m_tt_AddEvtFnc(document, "DOMContentLoaded", m_tt_HideSrcTags);
	m_tt_AddEvtFnc(window, "load", m_tt_HideSrcTags);
	if(m_tt_body.attachEvent)
		m_tt_body.attachEvent("onreadystatechange",
			function() {
				if(m_tt_body.readyState == "complete")
					m_tt_HideSrcTags();
			} );
	if(/WebKit|KHTML/i.test(navigator.userAgent))
	{
		var t = setInterval(function() {
					if(/loaded|complete/.test(document.readyState))
					{
						clearInterval(t);
						m_tt_HideSrcTags();
					}
				}, 10);
	}
}
function m_tt_HideSrcTags()
{
	if(!window.m_tt_HideSrcTags || window.m_tt_HideSrcTags.done)
		return;
	window.m_tt_HideSrcTags.done = true;
	if(!m_tt_HideSrcTagsRecurs(m_tt_body))
		m_tt_Err("There are HTML elements to be converted to tooltips.\nIf you"
				+ " want these HTML elements to be automatically hidden, you"
				+ " must edit wz_tooltip.js, and set TagsToTip in the global"
				+ " tooltip config_menuuration to true.", true);
}
function m_tt_HideSrcTagsRecurs(dad)
{
	var ovr, asT2t;
	// Walk the DOM tree for tags that have an onmouseover or onclick attribute
	// containing a m_TagToTip('...') call.
	// (.childNodes first since .children is bugous in Safari)
	var a = dad.childNodes || dad.children || null;

	for(var i = a ? a.length : 0; i;)
	{--i;
		if(!m_tt_HideSrcTagsRecurs(a[i]))
			return false;
		ovr = a[i].getAttribute ? (a[i].getAttribute("onmouseover") || a[i].getAttribute("onclick"))
				: (typeof a[i].onmouseover == "function") ? (a[i].onmouseover || a[i].onclick)
				: null;
		if(ovr)
		{
			asT2t = ovr.toString().match(/m_TagToTip\s*\(\s*'[^'.]+'\s*[\),]/);
			if(asT2t && asT2t.length)
			{
				if(!m_tt_HideSrcTag(asT2t[0]))
					return false;
			}
		}
	}
	return true;
}
function m_tt_HideSrcTag(sT2t)
{
	var id, el;

	// The ID passed to the found m_TagToTip() call identifies an HTML element
	// to be converted to a tooltip, so hide that element
	id = sT2t.replace(/.+'([^'.]+)'.+/, "$1");
	el = m_tt_GetElt(id);
	if(el)
	{
		if(m_tt_Debug && !TagsToTip)
			return false;
		else
			el.style.display = "none";
	}
	else
		m_tt_Err("Invalid ID\n'" + id + "'\npassed to m_TagToTip()."
				+ " There exists no HTML element with that ID.", true);
	return true;
}
function m_tt_Tip(arg, t2t)
{
	if(!m_tt_db)
		return;
	if(m_tt_iState)
		m_tt_Hide();
	if(!m_tt_Enabled)
		return;
	m_tt_t2t = t2t;
	if(!m_tt_Readm_Cmds(arg))
		return;
	m_tt_iState = 0x1 | 0x4;
	m_tt_Adaptconfig_menu1();
	m_tt_MkTipContent(arg);
	m_tt_MkTipSubDivs();
	m_tt_FormatTip();
	m_tt_bJmpVert = false;
	m_tt_bJmpHorz = false;
	m_tt_maxPosX = m_tt_GetClientW() + m_tt_scrlX - m_tt_w - 1;
	m_tt_maxPosY = m_tt_GetClientH() + m_tt_scrlY - m_tt_h - 1;
	m_tt_Adaptconfig_menu2();
	// Ensure the tip be shown and positioned before the first onmousemove
	m_tt_OverInit();
	m_tt_ShowInit();
	m_tt_Move();
}
function m_tt_Readm_Cmds(a)
{
	var i;

	// First load the global config_menu values, to initialize also values
	// for which no command is passed
	i = 0;
	for(var j in config_menu)
		m_tt_aV[i++] = config_menu[j];
	// Then replace each cached config_menu value for which a command is
	// passed (ensure the # of command args plus value args be even)
	if(a.length & 1)
	{
		for(i = a.length - 1; i > 0; i -= 2)
			m_tt_aV[a[i - 1]] = a[i];
		return true;
	}
	m_tt_Err("Incorrect call of Tip() or m_TagToTip().\n"
			+ "Each command must be followed by a value.", true);
	return false;
}
function m_tt_Adaptconfig_menu1()
{
	m_tt_ExtCallFncs(0, "Loadconfig_menu");
	// Inherit unspecified title formattings from body
	if(!m_tt_aV[TITLEBGCOLOR].length)
		m_tt_aV[TITLEBGCOLOR] = m_tt_aV[BORDERCOLOR];
	if(!m_tt_aV[TITLEFONTCOLOR].length)
		m_tt_aV[TITLEFONTCOLOR] = m_tt_aV[BGCOLOR];
	if(!m_tt_aV[TITLEFONTFACE].length)
		m_tt_aV[TITLEFONTFACE] = m_tt_aV[FONTFACE];
	if(!m_tt_aV[TITLEFONTSIZE].length)
		m_tt_aV[TITLEFONTSIZE] = m_tt_aV[FONTSIZE];
	if(m_tt_aV[CLOSEBTN])
	{
		// Use title colours for non-specified closebutton colours
		if(!m_tt_aV[CLOSEBTNCOLORS])
			m_tt_aV[CLOSEBTNCOLORS] = new Array("", "", "", "");
		for(var i = 4; i;)
		{--i;
			if(!m_tt_aV[CLOSEBTNCOLORS][i].length)
				m_tt_aV[CLOSEBTNCOLORS][i] = (i & 1) ? m_tt_aV[TITLEFONTCOLOR] : m_tt_aV[TITLEBGCOLOR];
		}
		// Enforce titlebar be shown
		if(!m_tt_aV[TITLE].length)
			m_tt_aV[TITLE] = " ";
	}
	// Circumvents broken display of images and fade-in flicker in Geckos < 1.8
	if(m_tt_aV[OPACITY] == 100 && typeof m_tt_aElt[0].style.MozOpacity != m_tt_u && !Array.every)
		m_tt_aV[OPACITY] = 99;
	// Smartly shorten the delay for fade-in tooltips
	if(m_tt_aV[FADEIN] && m_tt_flagOpa && m_tt_aV[DELAY] > 100)
		m_tt_aV[DELAY] = Math.max(m_tt_aV[DELAY] - m_tt_aV[FADEIN], 100);
}
function m_tt_Adaptconfig_menu2()
{
	if(m_tt_aV[CENTERMOUSE])
	{
		m_tt_aV[OFFSETX] -= ((m_tt_w - (m_tt_aV[SHADOW] ? m_tt_aV[SHADOWWIDTH] : 0)) >> 1);
		m_tt_aV[JUMPHORZ] = false;
	}
}
// Expose content globally so extensions can modify it
function m_tt_MkTipContent(a)
{
	if(m_tt_t2t)
	{
		if(m_tt_aV[COPYCONTENT])
			m_tt_sContent = m_tt_t2t.innerHTML;
		else
			m_tt_sContent = "";
	}
	else
		m_tt_sContent = a[0];
	m_tt_ExtCallFncs(0, "CreateContentString");
}
function m_tt_MkTipSubDivs()
{
	var sCss = 'position:relative;margin:0px;padding:0px;border-width:0px;left:0px;top:0px;line-height:normal;width:auto;',
	sTbTrTd = ' cellspacing="0" cellpadding="0" border="0" style="' + sCss + '"><tbody style="' + sCss + '"><tr><td ';

	m_tt_aElt[0].innerHTML =
		(''
		+ (m_tt_aV[TITLE].length ?
			('<div id="WzTiTl" style="position:relative;z-index:1;"><center>'
			+ '<table id="WzTiTlTb"' + sTbTrTd + 'id="WzTiTlI" style="' + sCss + '">'
			+ m_tt_aV[TITLE]
			+ '</td>'
			+ (m_tt_aV[CLOSEBTN] ?
				('<td align="right" style="' + sCss
				+ 'text-align:right;">'
				+ '<span id="WzClOsE" style="position:relative;left:2px;padding-left:2px;padding-right:2px;'
				+ 'cursor:' + (m_tt_ie ? 'hand' : 'pointer')
				+ ';" onmouseover="m_tt_OnCloseBtnOver(1)" onmouseout="m_tt_OnCloseBtnOver(0)" onclick="m_tt_HideInit()">'
				+ m_tt_aV[CLOSEBTNTEXT]
				+ '</span></td>')
				: '')
			+ '</tr></tbody></table></center></div>')
			: '')
		+ '<div id="WzBoDy" style="position:relative;z-index:0;"><center>'
		+ '<table' + sTbTrTd + 'id="WzBoDyI" style="' + sCss + '">'
		+ m_tt_sContent
		+ '</td></tr></tbody></table></center></div>'
		+ (m_tt_aV[SHADOW]
			? ('<div id="WzTtShDwR" style="position:absolute;overflow:hidden;"></div>'
				+ '<div id="WzTtShDwB" style="position:relative;overflow:hidden;"></div>')
			: '')
		);
	m_tt_GetSubDivRefs();
	// Convert DOM node to tip
	if(m_tt_t2t && !m_tt_aV[COPYCONTENT])
	{
		// Store the tag's parent element so we can restore that DOM branch
		// once the tooltip is hidden
		m_tt_t2tDad = m_tt_t2t.parentNode || m_tt_t2t.parentElement || m_tt_t2t.offsetParent || null;
		if(m_tt_t2tDad)
		{
			m_tt_MovDomNode(m_tt_t2t, m_tt_t2tDad, m_tt_aElt[6]);
			m_tt_t2t.style.display = "block";
		}
	}
	m_tt_ExtCallFncs(0, "SubDivsCreated");
}
function m_tt_GetSubDivRefs()
{
	var aId = new Array("WzTiTl", "WzTiTlTb", "WzTiTlI", "WzClOsE", "WzBoDy", "WzBoDyI", "WzTtShDwB", "WzTtShDwR");

	for(var i = aId.length; i; --i)
		m_tt_aElt[i] = m_tt_GetElt(aId[i - 1]);
}
function m_tt_FormatTip()
{
	var css, w, h, pad = m_tt_aV[PADDING], padT, wBrd = m_tt_aV[BORDERWIDTH],
	iOffY, iOffSh, iAdd = (pad + wBrd) << 1;
	
	//--------- Title DIV ----------
	if(m_tt_aV[TITLE].length)
	{
		padT = m_tt_aV[TITLEPADDING];
		css = m_tt_aElt[1].style;
		css.background = m_tt_aV[TITLEBGCOLOR];
		css.paddingTop = css.paddingBottom = padT + "px";
		css.paddingLeft = css.paddingRight = (padT + 2) + "px";
		css = m_tt_aElt[3].style;
		css.color = m_tt_aV[TITLEFONTCOLOR];
		if (m_tt_aV[WIDTH] == -1)
			css.whiteSpace = "nowrap";
		css.fontFamily = m_tt_aV[TITLEFONTFACE];
		css.fontSize = m_tt_aV[TITLEFONTSIZE];
		css.fontWeight = "bold";
		css.textAlign = m_tt_aV[TITLEALIGN];
		// Close button DIV
		if(m_tt_aElt[4])
		{
			css = m_tt_aElt[4].style;
			css.background = m_tt_aV[CLOSEBTNCOLORS][0];
			css.color = m_tt_aV[CLOSEBTNCOLORS][1];
			css.fontFamily = m_tt_aV[TITLEFONTFACE];
			css.fontSize = m_tt_aV[TITLEFONTSIZE];
			css.fontWeight = "bold";
		}
		if(m_tt_aV[WIDTH] > 0)
			m_tt_w = m_tt_aV[WIDTH];
		else
		{
			m_tt_w = m_tt_GetDivW(m_tt_aElt[3]) + m_tt_GetDivW(m_tt_aElt[4]);
			// Some spacing between title DIV and closebutton
			if(m_tt_aElt[4])
				m_tt_w += pad;
			// Restrict auto width to max width
			if(m_tt_aV[WIDTH] < -1 && m_tt_w > -m_tt_aV[WIDTH])
				m_tt_w = -m_tt_aV[WIDTH];
		}
		// Ensure the top border of the body DIV be covered by the title DIV
		iOffY = -wBrd;
	}
	else
	{
		m_tt_w = 0;
		iOffY = 0;
	}

	//-------- Body DIV ------------
	css = m_tt_aElt[5].style;
	css.top = iOffY + "px";
	if(wBrd)
	{
		css.borderColor = m_tt_aV[BORDERCOLOR];
		css.borderStyle = m_tt_aV[BORDERSTYLE];
		css.borderWidth = wBrd + "px";
	}
	if(m_tt_aV[BGCOLOR].length)
		css.background = m_tt_aV[BGCOLOR];
	if(m_tt_aV[BGIMG].length)
		css.backgroundImage = "url(" + m_tt_aV[BGIMG] + ")";
	css.padding = pad + "px";
	css.textAlign = m_tt_aV[TEXTALIGN];
	if(m_tt_aV[HEIGHT])
	{
		css.overflow = "auto";
		if(m_tt_aV[HEIGHT] > 0)
			css.height = (m_tt_aV[HEIGHT] + iAdd) + "px";
		else
			m_tt_h = iAdd - m_tt_aV[HEIGHT];
	}
	// TD inside body DIV
	css = m_tt_aElt[6].style;
	css.color = m_tt_aV[FONTCOLOR];
	css.fontFamily = m_tt_aV[FONTFACE];
	css.fontSize = m_tt_aV[FONTSIZE];
	css.fontWeight = m_tt_aV[FONTWEIGHT];
	css.background = "";
	css.textAlign = m_tt_aV[TEXTALIGN];
	if(m_tt_aV[WIDTH] > 0)
		w = m_tt_aV[WIDTH];
	// Width like title (if existent)
	else if(m_tt_aV[WIDTH] == -1 && m_tt_w)
		w = m_tt_w;
	else
	{
		// Measure width of the body's inner TD, as some browsers would expand
		// the container and outer body DIV to 100%
		w = m_tt_GetDivW(m_tt_aElt[6]);
		// Restrict auto width to max width
		if(m_tt_aV[WIDTH] < -1 && w > -m_tt_aV[WIDTH])
			w = -m_tt_aV[WIDTH];
	}
	if(w > m_tt_w)
		m_tt_w = w;
	m_tt_w += iAdd;

	//--------- Shadow DIVs ------------
	if(m_tt_aV[SHADOW])
	{
		m_tt_w += m_tt_aV[SHADOWWIDTH];
		iOffSh = Math.floor((m_tt_aV[SHADOWWIDTH] * 4) / 3);
		// Bottom shadow
		css = m_tt_aElt[7].style;
		css.top = iOffY + "px";
		css.left = iOffSh + "px";
		css.width = (m_tt_w - iOffSh - m_tt_aV[SHADOWWIDTH]) + "px";
		css.height = m_tt_aV[SHADOWWIDTH] + "px";
		css.background = m_tt_aV[SHADOWCOLOR];
		// Right shadow
		css = m_tt_aElt[8].style;
		css.top = iOffSh + "px";
		css.left = (m_tt_w - m_tt_aV[SHADOWWIDTH]) + "px";
		css.width = m_tt_aV[SHADOWWIDTH] + "px";
		css.background = m_tt_aV[SHADOWCOLOR];
	}
	else
		iOffSh = 0;

	//-------- Container DIV -------
	m_tt_SetTipOpa(m_tt_aV[FADEIN] ? 0 : m_tt_aV[OPACITY]);
	m_tt_FixSize(iOffY, iOffSh);
}
// Fixate the size so it can't dynamically change while the tooltip is moving.
function m_tt_FixSize(iOffY, iOffSh)
{
	var wIn, wOut, h, add, pad = m_tt_aV[PADDING], wBrd = m_tt_aV[BORDERWIDTH], i;

	m_tt_aElt[0].style.width = m_tt_w + "px";
	m_tt_aElt[0].style.pixelWidth = m_tt_w;
	wOut = m_tt_w - ((m_tt_aV[SHADOW]) ? m_tt_aV[SHADOWWIDTH] : 0);
	// Body
	wIn = wOut;
	if(!m_tt_bBoxOld)
		wIn -= (pad + wBrd) << 1;
	m_tt_aElt[5].style.width = wIn + "px";
	// Title
	if(m_tt_aElt[1])
	{
		wIn = wOut - ((m_tt_aV[TITLEPADDING] + 2) << 1);
		if(!m_tt_bBoxOld)
			wOut = wIn;
		m_tt_aElt[1].style.width = wOut + "px";
		m_tt_aElt[2].style.width = wIn + "px";
	}
	// Max height specified
	if(m_tt_h)
	{
		h = m_tt_GetDivH(m_tt_aElt[5]);
		if(h > m_tt_h)
		{
			if(!m_tt_bBoxOld)
				m_tt_h -= (pad + wBrd) << 1;
			m_tt_aElt[5].style.height = m_tt_h + "px";
		}
	}
	m_tt_h = m_tt_GetDivH(m_tt_aElt[0]) + iOffY;
	// Right shadow
	if(m_tt_aElt[8])
		m_tt_aElt[8].style.height = (m_tt_h - iOffSh) + "px";
	i = m_tt_aElt.length - 1;
	if(m_tt_aElt[i])
	{
		m_tt_aElt[i].style.width = m_tt_w + "px";
		m_tt_aElt[i].style.height = m_tt_h + "px";
	}
}
function m_tt_DeAlt(el)
{
	var aKid;

	if(el)
	{
		if(el.alt)
			el.alt = "";
		if(el.title)
			el.title = "";
		aKid = el.childNodes || el.children || null;
		if(aKid)
		{
			for(var i = aKid.length; i;)
				m_tt_DeAlt(aKid[--i]);
		}
	}
}
// This hack removes the native tooltips over links in Opera
function m_tt_OpDeHref(el)
{
	if(!m_tt_op)
		return;
	if(m_tt_elDeHref)
		m_tt_OpReHref();
	while(el)
	{
		if(el.hasAttribute("href"))
		{
			el.t_href = el.getAttribute("href");
			el.t_stats = window.status;
			el.removeAttribute("href");
			el.style.cursor = "hand";
			m_tt_AddEvtFnc(el, "mousedown", m_tt_OpReHref);
			window.status = el.t_href;
			m_tt_elDeHref = el;
			break;
		}
		el = el.parentElement;
	}
}
function m_tt_OpReHref()
{
	if(m_tt_elDeHref)
	{
		m_tt_elDeHref.setAttribute("href", m_tt_elDeHref.t_href);
		m_tt_RemEvtFnc(m_tt_elDeHref, "mousedown", m_tt_OpReHref);
		window.status = m_tt_elDeHref.t_stats;
		m_tt_elDeHref = null;
	}
}
function m_tt_OverInit()
{
	if(window.event)
		m_tt_over = window.event.target || window.event.srcElement;
	else
		m_tt_over = m_tt_ovr_;
	m_tt_DeAlt(m_tt_over);
	m_tt_OpDeHref(m_tt_over);
}
function m_tt_ShowInit()
{
	m_tt_tShow.Timer("m_tt_Show()", m_tt_aV[DELAY], true);
	if(m_tt_aV[CLICKCLOSE] || m_tt_aV[CLICKSTICKY])
		m_tt_AddEvtFnc(document, "mouseup", m_tt_OnLClick);
}
function m_tt_Show()
{
	var css = m_tt_aElt[0].style;

	// Override the z-index of the topmost wz_dragdrop.js D&D item
	css.zIndex = Math.max((window.dd && dd.z) ? (dd.z + 2) : 0, 1010);
	if(m_tt_aV[STICKY] || !m_tt_aV[FOLLOWMOUSE])
		m_tt_iState &= ~0x4;
	if(m_tt_aV[DURATION] > 0)
		m_tt_tDurt.Timer("m_tt_HideInit()", m_tt_aV[DURATION], true);
	m_tt_ExtCallFncs(0, "Show")
	css.visibility = "visible";
	m_tt_iState |= 0x2;
	if(m_tt_aV[FADEIN])
		m_tt_Fade(0, 0, m_tt_aV[OPACITY], Math.round(m_tt_aV[FADEIN] / m_tt_aV[FADEINTERVAL]));
	m_tt_ShowIfrm();
}
function m_tt_ShowIfrm()
{
	if(m_tt_ie56)
	{
		var ifrm = m_tt_aElt[m_tt_aElt.length - 1];
		if(ifrm)
		{
			var css = ifrm.style;
			css.zIndex = m_tt_aElt[0].style.zIndex - 1;
			css.display = "block";
		}
	}
}
function m_tt_Move(e)
{
	if(e)
		m_tt_ovr_ = e.target || e.srcElement;
	e = e || window.event;
	if(e)
	{
		m_tt_musX = m_tt_GetEvtX(e);
		m_tt_musY = m_tt_GetEvtY(e);
	}
	if(m_tt_iState & 0x04)
	{
		// Prevent jam of mousemove events
		if(!m_tt_op && !m_tt_ie)
		{
			if(m_tt_bWait)
				return;
			m_tt_bWait = true;
			m_tt_tWaitMov.Timer("m_tt_bWait = false;", 1, true);
		}
		if(m_tt_aV[FIX])
		{
			var iY = m_tt_aV[FIX][1];
			// For a fixed tip to be positioned above the mouse, use the
			// bottom edge as anchor
			// (recommended by Christophe Rebeschini, 31.1.2008)
			if(m_tt_aV[ABOVE])
				iY -= m_tt_h;
			m_tt_iState &= ~0x4;
			m_m_tt_SetTipPos(m_tt_aV[FIX][0], m_tt_aV[FIX][1]);
		}
		else if(!m_tt_ExtCallFncs(e, "MoveBefore"))
			m_m_tt_SetTipPos(m_tt_Pos(0), m_tt_Pos(1));
		m_tt_ExtCallFncs([m_tt_musX, m_tt_musY], "MoveAfter")
	}
}
function m_tt_Pos(iDim)
{
	var iX, bJmpMode, m_CmdAlt, m_CmdOff, cx, iMax, iScrl, iMus, bJmp;

	// Map values according to dimension to calculate
	if(iDim)
	{
		bJmpMode = m_tt_aV[JUMPVERT];
		m_CmdAlt = ABOVE;
		m_CmdOff = OFFSETY;
		cx = m_tt_h;
		iMax = m_tt_maxPosY;
		iScrl = m_tt_scrlY;
		iMus = m_tt_musY;
		bJmp = m_tt_bJmpVert;
	}
	else
	{
		bJmpMode = m_tt_aV[JUMPHORZ];
		m_CmdAlt = LEFT;
		m_CmdOff = OFFSETX;
		cx = m_tt_w;
		iMax = m_tt_maxPosX;
		iScrl = m_tt_scrlX;
		iMus = m_tt_musX;
		bJmp = m_tt_bJmpHorz;
	}
	if(bJmpMode)
	{
		if(m_tt_aV[m_CmdAlt] && (!bJmp || m_tt_CalcPosAlt(iDim) >= iScrl + 16))
			iX = m_tt_PosAlt(iDim);
		else if(!m_tt_aV[m_CmdAlt] && bJmp && m_tt_CalcPosDef(iDim) > iMax - 16)
			iX = m_tt_PosAlt(iDim);
		else
			iX = m_tt_PosDef(iDim);
	}
	else
	{
		iX = iMus;
		if(m_tt_aV[m_CmdAlt])
			iX -= cx + m_tt_aV[m_CmdOff] - (m_tt_aV[SHADOW] ? m_tt_aV[SHADOWWIDTH] : 0);
		else
			iX += m_tt_aV[m_CmdOff];
	}
	// Prevent tip from extending past clientarea boundary
	if(iX > iMax)
		iX = bJmpMode ? m_tt_PosAlt(iDim) : iMax;
	// In case of insufficient space on both sides, ensure the left/upper part
	// of the tip be visible
	if(iX < iScrl)
		iX = bJmpMode ? m_tt_PosDef(iDim) : iScrl;
	return iX;
}
function m_tt_PosDef(iDim)
{
	if(iDim)
		m_tt_bJmpVert = m_tt_aV[ABOVE];
	else
		m_tt_bJmpHorz = m_tt_aV[LEFT];
	return m_tt_CalcPosDef(iDim);
}
function m_tt_PosAlt(iDim)
{
	if(iDim)
		m_tt_bJmpVert = !m_tt_aV[ABOVE];
	else
		m_tt_bJmpHorz = !m_tt_aV[LEFT];
	return m_tt_CalcPosAlt(iDim);
}
function m_tt_CalcPosDef(iDim)
{
	return iDim ? (m_tt_musY + m_tt_aV[OFFSETY]) : (m_tt_musX + m_tt_aV[OFFSETX]);
}
function m_tt_CalcPosAlt(iDim)
{
	var m_CmdOff = iDim ? OFFSETY : OFFSETX;
	var dx = m_tt_aV[m_CmdOff] - (m_tt_aV[SHADOW] ? m_tt_aV[SHADOWWIDTH] : 0);
	if(m_tt_aV[m_CmdOff] > 0 && dx <= 0)
		dx = 1;
	return((iDim ? (m_tt_musY - m_tt_h) : (m_tt_musX - m_tt_w)) - dx);
}
function m_tt_Fade(a, now, z, n)
{
	if(n)
	{
		now += Math.round((z - now) / n);
		if((z > a) ? (now >= z) : (now <= z))
			now = z;
		else
			m_tt_tFade.Timer("m_tt_Fade("
							+ a + "," + now + "," + z + "," + (n - 1)
							+ ")",
							m_tt_aV[FADEINTERVAL],
							true);
	}
	now ? m_tt_SetTipOpa(now) : m_tt_Hide();
}
function m_tt_SetTipOpa(opa)
{
	// To circumvent the opacity nesting flaws of IE, we set the opacity
	// for each sub-DIV separately, rather than for the container DIV.
	m_tt_SetOpa(m_tt_aElt[5], opa);
	if(m_tt_aElt[1])
		m_tt_SetOpa(m_tt_aElt[1], opa);
	if(m_tt_aV[SHADOW])
	{
		opa = Math.round(opa * 0.8);
		m_tt_SetOpa(m_tt_aElt[7], opa);
		m_tt_SetOpa(m_tt_aElt[8], opa);
	}
}
function m_tt_OnScrl()
{
	m_tt_scrlX = m_tt_GetScrollX();
	m_tt_scrlY = m_tt_GetScrollY();
}
function m_tt_OnCloseBtnOver(iOver)
{
	var css = m_tt_aElt[4].style;

	iOver <<= 1;
	css.background = m_tt_aV[CLOSEBTNCOLORS][iOver];
	css.color = m_tt_aV[CLOSEBTNCOLORS][iOver + 1];
}
function m_tt_OnLClick(e)
{
	//  Ignore right-clicks
	e = e || window.event;
	if(!((e.button && e.button & 2) || (e.which && e.which == 3)))
	{
		if(m_tt_aV[CLICKSTICKY] && (m_tt_iState & 0x4))
		{
			m_tt_aV[STICKY] = true;
			m_tt_iState &= ~0x4;
		}
		else if(m_tt_aV[CLICKCLOSE])
			m_tt_HideInit();
	}
}
function m_tt_Int(x)
{
	var y;

	return(isNaN(y = parseInt(x)) ? 0 : y);
}
Number.prototype.Timer = function(s, iT, bUrge)
{
	if(!this.value || bUrge)
		this.value = window.setTimeout(s, iT);
}
Number.prototype.EndTimer = function()
{
	if(this.value)
	{
		window.clearTimeout(this.value);
		this.value = 0;
	}
}
function m_tt_SetOpa(el, opa)
{
	var css = el.style;

	m_tt_opa = opa;
	if(m_tt_flagOpa == 1)
	{
		if(opa < 100)
		{
			// Hacks for bugs of IE:
			// 1.) Once a CSS filter has been applied, fonts are no longer
			// anti-aliased, so we store the previous 'non-filter' to be
			// able to restore it
			if(typeof(el.filtNo) == m_tt_u)
				el.filtNo = css.filter;
			// 2.) A DIV cannot be made visible in a single step if an
			// opacity < 100 has been applied while the DIV was hidden
			var bVis = css.visibility != "hidden";
			// 3.) In IE6, applying an opacity < 100 has no effect if the
			//	   element has no layout (position, size, zoom, ...)
			css.zoom = "100%";
			if(!bVis)
				css.visibility = "visible";
			css.filter = "alpha(opacity=" + opa + ")";
			if(!bVis)
				css.visibility = "hidden";
		}
		else if(typeof(el.filtNo) != m_tt_u)
			// Restore 'non-filter'
			css.filter = el.filtNo;
	}
	else
	{
		opa /= 100.0;
		switch(m_tt_flagOpa)
		{
		case 2:
			css.KhtmlOpacity = opa; break;
		case 3:
			css.KHTMLOpacity = opa; break;
		case 4:
			css.MozOpacity = opa; break;
		case 5:
			css.opacity = opa; break;
		}
	}
}
function m_tt_MovDomNode(el, dadFrom, dadTo)
{
	if(dadFrom)
		dadFrom.removeChild(el);
	if(dadTo)
		dadTo.appendChild(el);
}
function m_tt_Err(sErr, bIfDebug)
{
	if(m_tt_Debug || !bIfDebug)
		alert("Tooltip Script Error Message:\n\n" + sErr);
}

//============  EXTENSION (PLUGIN) MANAGER  ===============//
function m_tt_Extm_CmdEnum()
{
	var s;

	// Add new command(s) to the commands enum
	for(var i in config_menu)
	{
		s = "window." + i.toString().toUpperCase();
		if(eval("typeof(" + s + ") == m_tt_u"))
		{
			eval(s + " = " + m_tt_aV.length);
			m_tt_aV[m_tt_aV.length] = null;
		}
	}
}
function m_tt_ExtCallFncs(arg, sFnc)
{
	var b = false;
	for(var i = m_tt_aExt.length; i;)
	{--i;
		var fnc = m_tt_aExt[i]["On" + sFnc];
		// Call the method the extension has defined for this event
		if(fnc && fnc(arg))
			b = true;
	}
	return b;
}

m_tt_Init();
