//SuckerTree Horizontal Menu (Sept 14th, 06)
//By Dynamic Drive: http://www.dynamicdrive.com/style/

var menuids=["SystemMenu", "MainMenu"] //Enter id(s) of SuckerTree UL menus, separated by commas
var _currentSuckerMenuItem, _lastSuckerMenuItem;

function buildsubmenus_horizontal(){
for (var i=0; i<menuids.length; i++){
  if ( document.getElementById(menuids[i]) == null) continue; // checking for null if menu doesn't exists
  var ultags=document.getElementById(menuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
		if (ultags[t].parentNode.parentNode.id==menuids[i]){ //if this is a first level submenu
			ultags[t].style.top=ultags[t].parentNode.offsetHeight+-1+"px" //dynamically position first level submenus to be height of main menu item
			ultags[t].parentNode.getElementsByTagName("a")[0].className="mainfoldericon"
		}
		else{ //else if this is a sub level menu (ul)
 		  ultags[t].style.left=ultags[t-1].getElementsByTagName("a")[0].offsetWidth+"px" //position menu to the right of menu item that activated it
    	          ultags[t].parentNode.getElementsByTagName("a")[0].className="subfoldericon"
    		}
    ultags[t].parentNode.onmouseover=function(){
      this.getElementsByTagName("ul")[0].style.display="block";

      _currentSuckerMenuItem = this.getElementsByTagName("ul")[0];
      if(_lastSuckerMenuItem == _currentSuckerMenuItem) // is the same, simply show it
        this.getElementsByTagName("ul")[0].style.visibility="visible";
        else
       _lastSuckerMenuItem = _currentSuckerMenuItem;

      setTimeout(SuckerMenuShowFunction, 10);
    }
    ultags[t].parentNode.onclick=function(){
      return false;
    }
    ultags[t].parentNode.onmouseout=function(){
      this.getElementsByTagName("ul")[0].style.visibility="hidden";
      this.getElementsByTagName("ul")[0].style.display="none";
      _currentSuckerMenuItem = null;
    }

    }
  }
}

function SuckerMenuShowFunction(){

    if(!_currentSuckerMenuItem || !_currentSuckerMenuItem.parentNode) return;

    var w = document.body.clientWidth || window.innerWidth || document.documentElement.clientWidth;
    var positionInfo = _currentSuckerMenuItem.parentNode.getBoundingClientRect();
    var left = positionInfo.left + _currentSuckerMenuItem.parentNode.scrollLeft;

    var positionInfo = _currentSuckerMenuItem.getBoundingClientRect();
    var width = positionInfo.width;

    if(!width){
      // for <IE9
      width = _currentSuckerMenuItem.offsetWidth;
    }

    if(left + width > w){
      _currentSuckerMenuItem.style.left = (w - (width + left)) + 'px';
    }

    _currentSuckerMenuItem.style.visibility="visible";
    _currentSuckerMenuItem = null;
}

if (window.addEventListener)
window.addEventListener("load", buildsubmenus_horizontal, false)
else if (window.attachEvent)
window.attachEvent("onload", buildsubmenus_horizontal)
