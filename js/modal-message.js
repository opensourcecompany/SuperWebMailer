/************************************************************************************************************
*	DHTML modal dialog box
*
*	Created:						August, 26th, 2006
*	@class Purpose of class:		Display a modal dialog box on the screen.
*			
*	Css files used by this script:	modal-message.css
*
*	Demos of this class:			demo-modal-message-1.html
*
* 	Update log:
*
************************************************************************************************************/


/**
* @constructor
*/

DHTML_modalMessage = function()
{
	var url;								// url of modal message
	var htmlOfModalMessage;					// html of modal message
	
	var divs_transparentDiv;				// Transparent div covering page content
	var divs_content;						// Modal message div.
	var iframe;								// Iframe used in ie
	var layoutCss;							// Name of css file;
	var width;								// Width of message box
	var height;								// Height of message box
	
	var existingBodyOverFlowStyle;			// Existing body overflow css
	var existingHTMLOverFlowStyle = '';			// Existing HTML overflow css
	var dynContentObj;						// Reference to dynamic content object
	var cssClassOfMessageBox;				// Alternative css class of message box - in case you want a different appearance on one of them
	var shadowDivVisible;					// Shadow div visible ? 
	var shadowOffset; 						// X and Y offset of shadow(pixels from content box)
	var MSIE;
		
	this.url = '';							// Default url is blank
	this.htmlOfModalMessage = '';			// Default message is blank
	this.layoutCss = 'modal-message.css';	// Default CSS file
	this.height = 200;						// Default height of modal message
	this.width = 400;						// Default width of modal message
	this.cssClassOfMessageBox = false;		// Default alternative css class for the message box
	this.shadowDivVisible = true;			// Shadow div is visible by default
	this.shadowOffset = 5;					// Default shadow offset.
	this.MSIE = false;
	this.CloseOnEscape = true;		//
	this.defaultButtonOnReturnKey = false;		//
	//if(navigator.userAgent.indexOf('MSIE')>=0 && new Number(RegExp.$1) < 8) this.MSIE = true;
	
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
          if(new Number(RegExp.$1) > 8)
            this.MSIE = false; // no iframe here
        }

}

DHTML_modalMessage.prototype = {
	// {{{ setSource(urlOfSource)
    /**
     *	Set source of the modal dialog box
     * 	
     *
     * @public	
     */		
	setSource : function(urlOfSource)
	{
		this.url = urlOfSource;
		
	}	
	// }}}	
	,
	// {{{ setHtmlContent(newHtmlContent)
    /**
     *	Setting static HTML content for the modal dialog box.
     * 	
     *	@param String newHtmlContent = Static HTML content of box
     *
     * @public	
     */		
	setHtmlContent : function(newHtmlContent)
	{
		this.htmlOfModalMessage = newHtmlContent;
		
	}
	// }}}		
	,
	// {{{ setSize(width,height)
    /**
     *	Set the size of the modal dialog box
     * 	
     *	@param int width = width of box
     *	@param int height = height of box
     *
     * @public	
     */		
	setSize : function(width,height)
	{
		if(width)this.width = width;
		if(height)this.height = height;		
	}
	// }}}		
	,		
	// {{{ setCssClassMessageBox(newCssClass)
    /**
     *	Assign the message box to a new css class.(in case you wants a different appearance on one of them)
     * 	
     *	@param String newCssClass = Name of new css class (Pass false if you want to change back to default)
     *
     * @public	
     */		
	setCssClassMessageBox : function(newCssClass)
	{
		this.cssClassOfMessageBox = newCssClass;
		if(this.divs_content){
			if(this.cssClassOfMessageBox)
				this.divs_content.className=this.cssClassOfMessageBox;
			else
				this.divs_content.className='modalDialog_contentDiv';	
		}
					
	}
	// }}}		
	,	
	// {{{ setShadowOffset(newShadowOffset)
    /**
     *	Specify the size of shadow
     * 	
     *	@param Int newShadowOffset = Offset of shadow div(in pixels from message box - x and y)
     *
     * @public	
     */		
	setShadowOffset : function(newShadowOffset)
	{
		this.shadowOffset = newShadowOffset
					
	}
	// }}}		
	,	
	// {{{ display()
    /**
     *	Display the modal dialog box
     * 	
     *
     * @public	
     */		
	display : function()
	{
		if(!this.divs_transparentDiv){
			this.__createDivs();
		}	

		// Redisplaying divs
  this.divs_content.innerHTML = '';
		this.divs_transparentDiv.style.display='block';
		this.divs_content.style.display='block';
		this.divs_shadow.style.display='block';		
		if(this.MSIE)this.iframe.style.display='block';	
		this.__resizeDivs(1);

		/* Call the __resizeDivs method twice in case the css file has changed. The first execution of this method may not catch these changes */
		window.refToThisModalBoxObj = this;		
		setTimeout('window.refToThisModalBoxObj.__resizeDivs(0)',10);
		setTimeout('window.refToThisModalBoxObj.__resizeDivs(0)',100);

  if (document.documentElement.scrollLeft == 0 && document.body.scrollLeft == 0)
     document.documentElement.style.cssText = 'overflow-x: hidden;' + document.documentElement.style.cssText;

		this.__insertContent();	// Calling method which inserts content into the message div.

  if(this.CloseOnEscape == true) {
    function mmEscapefunction(e){ if ((e.which && e.which == 27) || (e.keyCode && e.keyCode == 27)) { try {window.refToThisModalBoxObj.removeEvent(window,'keydown', mmEscapefunction); window.refToThisModalBoxObj.removeEvent(window,'keypress', mmEscapefunction);} catch(E) {} window.refToThisModalBoxObj.close(); e.preventDefault ? e.preventDefault() : e.returnValue = false; e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true; return false; } };
    this.addEvent(window,'keydown', mmEscapefunction);
    this.addEvent(window,'keypress', mmEscapefunction);
  }

  if(this.defaultButtonOnReturnKey == true) {

    body = document.getElementsByTagName("body")[0];
    if(!body)
     body = window;

    this.addEvent(body,'keydown', function mmkeydown(e){

       if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)){

         var btn = document.getElementById('defaultBtn');
         if(btn) {
           btn.click();
           e.preventDefault ? e.preventDefault() : e.returnValue = false;
           e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
           try {window.refToThisModalBoxObj.removeEvent(body,'keydown', mmkeydown);} catch(E) {}
           return false;
         }
       }

     });

    this.addEvent(body,'keypress', function mmkeypress(e){
       if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)){

         var btn = document.getElementById('defaultBtn');
         if(btn) {
           btn.click();
           e.preventDefault ? e.preventDefault() : e.returnValue = false;
           e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
           try {window.refToThisModalBoxObj.removeEvent(body,'keypress', mmkeypress);} catch(E) {}
           return false;
         }
       }

     });

  }

	}
	// }}}		
	,
	// {{{ ()
    /**
     *	Display the modal dialog box
     * 	
     *
     * @public	
     */		
	setShadowDivVisible : function(visible)
	{
		this.shadowDivVisible = visible;
	}
	// }}}	
	,
	// {{{ close()
    /**
     *	Close the modal dialog box
     * 	
     *
     * @public	
     */		
	close : function()
	{
		//document.documentElement.style.overflow = '';	// Setting the CSS overflow attribute of the <html> tag back to default.

		/* Hiding divs */
  if(this && this.divs_transparentDiv){
   this.divs_transparentDiv.style.display='none';
	 	this.divs_content.style.display='none';
		 this.divs_shadow.style.display='none';
 		if(this.MSIE)this.iframe.style.display='none';
   document.documentElement.style.cssText = this.existingHTMLOverFlowStyle;
  }


	}	
	// }}}	
	,

 setFocus : function(fieldname)
	{
		var v = document.getElementById(fieldname);
  if(v)
   v.focus({preventScroll:true});
		if(this.MSIE) {
    var i = this.iframe.contentDocument || this.iframe.contentWindow.document;
    if(i){
      var v = i.getElementById(fieldname);
      if(v)
       v.focus({preventScroll:true});
    }
  }
	}
	// }}}
	,

	// {{{ __addEvent()
    /**
     *	Add event
     * 	
     *
     * @private	
     */		
	addEvent : function(whichObject,eventType,functionName,suffix)
	{ 
	  if(!suffix)suffix = '';
	  if(!whichObject.addEventListener && whichObject.attachEvent){
	    whichObject['e'+eventType+functionName+suffix] = functionName; 
	    whichObject[eventType+functionName+suffix] = function(){whichObject['e'+eventType+functionName+suffix]( window.event );} 
	    whichObject.attachEvent( 'on'+eventType, whichObject[eventType+functionName+suffix] ); 
	  } else 
	    whichObject.addEventListener(eventType,functionName,false);
	} 
	// }}}
 ,
	// {{{ __removeEvent()
    /**
     *	Remove event
     *
     *
     * @private
     */
	removeEvent : function(whichObject,eventType,functionName,suffix)
	{
	  if(!suffix)suffix = '';
	  if(!whichObject.removeEventListener && whichObject.detachEvent){
	    whichObject['e'+eventType+functionName+suffix] = functionName;
	    whichObject[eventType+functionName+suffix] = function(){whichObject['e'+eventType+functionName+suffix]( window.event );}
	    whichObject.detachEvent( 'on'+eventType, whichObject[eventType+functionName+suffix] );
	  } else
	    whichObject.removeEventListener(eventType,functionName,false);
	}
	// }}}
	,
	// {{{ __createDivs()
    /**
     *	Create the divs for the modal dialog box
     * 	
     *
     * @private	
     */		
	__createDivs : function()
	{
		// Creating transparent div
		this.divs_transparentDiv = document.createElement('DIV');
		this.divs_transparentDiv.className='modalDialog_transparentDivs';
		this.divs_transparentDiv.style.left = '0px';
		this.divs_transparentDiv.style.top = '0px';
		
		document.body.appendChild(this.divs_transparentDiv);
		// Creating content div
		this.divs_content = document.createElement('DIV');
		this.divs_content.className = 'modalDialog_contentDiv';
		this.divs_content.id = 'DHTMLSuite_modalBox_contentDiv';
		this.divs_content.style.zIndex = 100000;
		
		if(this.MSIE){
			this.iframe = document.createElement('<IFRAME src="about:blank" frameborder=0>');
			this.iframe.style.zIndex = 90000;
			this.iframe.style.position = 'absolute';
			document.body.appendChild(this.iframe);	
		}
			
		document.body.appendChild(this.divs_content);
		// Creating shadow div
		this.divs_shadow = document.createElement('DIV');
		this.divs_shadow.className = 'modalDialog_contentDiv_shadow';
		this.divs_shadow.style.zIndex = 95000;
		document.body.appendChild(this.divs_shadow);
		window.refToModMessage = this;
		this.addEvent(window,'scroll',function(e){ window.refToModMessage.__repositionTransparentDiv() });
		this.addEvent(window,'resize',function(e){ window.refToModMessage.__repositionTransparentDiv() });
		
  this.existingHTMLOverFlowStyle = document.documentElement.style.cssText;

	}
	// }}}
	,
	// {{{ __getBrowserSize()
    /**
     *	Get browser size
     * 	
     *
     * @private	
     */		
	__getBrowserSize : function()
	{
    	var bodyWidth = document.documentElement.clientWidth;
    	var bodyHeight = document.documentElement.clientHeight;
    	
		var bodyWidth, bodyHeight; 
		if (self.innerHeight){ // all except Explorer 
		 
		   bodyWidth = self.innerWidth; 
		   bodyHeight = self.innerHeight; 
		}  else if (document.documentElement && document.documentElement.clientHeight) {
		   // Explorer 6 Strict Mode 		 
		   bodyWidth = document.documentElement.clientWidth; 
		   bodyHeight = document.documentElement.clientHeight; 
		} else if (document.body) {// other Explorers 		 
		   bodyWidth = document.body.clientWidth; 
		   bodyHeight = document.body.clientHeight; 
		} 
		return [bodyWidth,bodyHeight];		
		
	}
	// }}}	
	,
	// {{{ __resizeDivs()
    /**
     *	Resize the message divs
     * 	
     *
     * @private	
     */	
    __resizeDivs : function(firstShow)
    {
    	
    	var topOffset = Math.max(document.body.scrollTop,document.documentElement.scrollTop);

   		if(this.cssClassOfMessageBox)
   			this.divs_content.className=this.cssClassOfMessageBox;
   		else
   			this.divs_content.className='modalDialog_contentDiv';
			    	
    	if(!this.divs_transparentDiv)return;
    	
    	// Preserve scroll position
    	var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
    	var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
    	
     //if(!window.frameElement){
       window.scrollTo(sl,st);
      	setTimeout('window.scrollTo(' + sl + ',' + st + ');',10);
     //}

    	this.__repositionTransparentDiv();
    	

		var brSize = this.__getBrowserSize();
		var bodyWidth = brSize[0];
		var bodyHeight = brSize[1];
    	
    	// Setting width and height of content div
     this.divs_content.style.width = this.width + 'px';
    	this.divs_content.style.height = this.height + 'px';
    	
     // set calculated Width an Height
     var child = null;
     if(!firstShow){
       var childs = this.divs_content.childNodes;
       for(var i = 0; i<Math.min(3, childs.length); i++){
         if(childs[i].tagName == "FORM" || childs[i].tagName == "TABLE"){  // no DIV or P check here!!
           child = childs[i];
           // block element after FORM holds the correct visual size
           if(child.tagName == "FORM" && child.firstChild && (child.firstChild.tagName == "TABLE" || child.firstChild.tagName == "DIV" || child.firstChild.tagName == "P"))
             child = child.firstChild;
           break;
         }
       }
     }

     if(!child){
       this.divs_content.style.width = (this.divs_content.offsetWidth) + 'px';
      	this.divs_content.style.height = (this.divs_content.offsetHeight) + 'px';
     }else{
       this.divs_content.style.width = (child.offsetWidth) + 'px';
      	this.divs_content.style.height = (child.offsetHeight) + 'px';
     }
    	// Creating temporary width variables since the actual width of the content div could be larger than this.width and this.height(i.e. padding and border)
    	var tmpWidth = this.divs_content.offsetWidth;	
    	var tmpHeight = this.divs_content.offsetHeight;


     if(!firstShow){
      	// Setting width and height of left transparent div
      	this.divs_content.style.left = Math.ceil((bodyWidth - tmpWidth) / 2) + 'px';;
      	this.divs_content.style.top = (Math.ceil((bodyHeight - tmpHeight) / 2) +  topOffset) + 'px';
     }else{
       /// move it outside of visible area for correct calculation of elements width/height
       this.divs_content.style.left = '-1000px';
      	this.divs_content.style.top = '-1000px';
     }
 		
   		if(this.MSIE){
   			this.iframe.style.left = this.divs_content.style.left;
   			this.iframe.style.top = this.divs_content.style.top;
   			this.iframe.style.width = this.divs_content.style.width;
   			this.iframe.style.height = this.divs_content.style.height;
   		}

    	this.divs_shadow.style.left = (this.divs_content.style.left.replace('px','')/1 + this.shadowOffset) + 'px';
    	this.divs_shadow.style.top = (this.divs_content.style.top.replace('px','')/1 + this.shadowOffset) + 'px';
    	this.divs_shadow.style.height = tmpHeight + 'px';
    	this.divs_shadow.style.width = tmpWidth + 'px';
    	
    	
    	
    	if(!this.shadowDivVisible)this.divs_shadow.style.display='none';	// Hiding shadow if it has been disabled
    	
    	
    }
    // }}}	
    ,
	// {{{ __insertContent()
    /**
     *	Insert content into the content div
     * 	
     *
     * @private	
     */	    
    __repositionTransparentDiv : function()
    {
     this.divs_transparentDiv.style.top = Math.max(document.body.scrollTop,document.documentElement.scrollTop) + 'px';
    	this.divs_transparentDiv.style.left = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft) + 'px';
		var brSize = this.__getBrowserSize();
		var bodyWidth = brSize[0];
		var bodyHeight = brSize[1];
    	this.divs_transparentDiv.style.width = bodyWidth + 'px';
    	this.divs_transparentDiv.style.height = bodyHeight + 'px';		
		   	
    }
	// }}}	
	,
	// {{{ __insertContent()
    /**
     *	Insert content into the content div
     * 	
     *
     * @private	
     */	
    __insertContent : function()
    {
		if(this.url){	// url specified - load content dynamically
			ajax_loadContent('DHTMLSuite_modalBox_contentDiv',this.url);
		}else{	// no url set, put static content inside the message box
			this.divs_content.innerHTML = this.htmlOfModalMessage;	
		}
    }		
}
