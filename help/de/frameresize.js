window.onload=function() {
  if(top.frames.length > 0) {
    if(document.getElementById("togglePlaceholder") == null) {
      var newElem = document.createElement("span");
      newElem.setAttribute("id", "togglePlaceholder");
      newElem.setAttribute("name", "togglePlaceholder");
      body = document.getElementsByTagName("body")[0];
      body.insertBefore(newElem, body.childNodes[0]);
    }
    setToggleUI();
  }
}

var HideTOC = "../images/hidetoc.gif";
var ShowTOC = "../images/showtoc.gif";

function setToggleUI() {
    var label = HideTOC;
    if (document.getElementById) {
        if (getCookie("frameHidden") == "true") {
            label = ShowTOC;
        }
        var newElem = document.createElement("input");
        newElem.setAttribute("type", "image");
        newElem.setAttribute("src", label);

        newElem.onclick = initiateToggle;
        document.getElementById("togglePlaceholder").appendChild(newElem);
    }
}
function initiateToggle(evt) {
    evt = (evt) ? evt : event;
    var elem = (evt.target) ? evt.target : evt.srcElement;
    if (elem.nodeType == 3) {
        elem = elem.parentNode;
    }
    parent.toggleFrame(elem);
}


/*
     Example File From "JavaScript and DHTML Cookbook"
     Published by O'Reilly & Associates
     Copyright 2003 Danny Goodman
*/

/* cookies.js */
/*
     Example File From "JavaScript and DHTML Cookbook"
     Published by O'Reilly & Associates
     Copyright 2003 Danny Goodman
*/

// utility function to retrieve a future expiration date in proper format;
// pass three integer parameters for the number of days, hours,
// and minutes from now you want the cookie to expire; all three
// parameters required, so use zeros where appropriate
function getExpDate(days, hours, minutes) {
    var expDate = new Date();
    if (typeof days == "number" && typeof hours == "number" && typeof hours == "number") {
        expDate.setDate(expDate.getDate() + parseInt(days));
        expDate.setHours(expDate.getHours() + parseInt(hours));
        expDate.setMinutes(expDate.getMinutes() + parseInt(minutes));
        return expDate.toGMTString();
    }
}

// utility function called by getCookie()
function getCookieVal(offset) {
    var endstr = document.cookie.indexOf (";", offset);
    if (endstr == -1) {
        endstr = document.cookie.length;
    }
    return unescape(document.cookie.substring(offset, endstr));
}

// primary function to retrieve cookie by name
function getCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) {
            return getCookieVal(j);
        }
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

// store cookie value with optional details as needed
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape (value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

// remove the cookie by setting ancient expiration date
function deleteCookie(name,path,domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}

var origCols;
function toggleFrame(elem) {
    if (origCols) {
        elem.src = HideTOC;
        setCookie("frameHidden", "false", getExpDate(180, 0, 0));
        showFrame();
    } else {
        elem.src = ShowTOC;
        setCookie("frameHidden", "true", getExpDate(180, 0, 0));
        hideFrame();
    }
}
function hideFrame() {
    var frameset = document.getElementById("masterFrameset");
    origCols = frameset.cols;
    frameset.cols = "0,70%";
    redrawFrame("masterFrameset");
}

function showFrame() {
    document.getElementById("masterFrameset").cols = origCols;
    origCols = null;
    redrawFrame("masterFrameset");
}

// set frame visibility based on previous cookie setting
function setFrameVis() {
    if (document.getElementById) {
        if (getCookie("frameHidden") == "true") {
            hideFrame()
        }
    }
}

function redrawFrame(frameid){
 if(document.getElementById(frameid).className == "forceRedraw")
  document.getElementById(frameid).className = "";
 else
  document.getElementById(frameid).className = "forceRedraw";


 document.getElementById(frameid).setAttribute(
   "style", "padding-left: 1;");

 window.setTimeout(function() {
                        document.getElementById(frameid).setAttribute(
                            "style", "padding-left: 0;");
                        });
}

