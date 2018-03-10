	var arrayOfScrollboxRolloverClasses = new Array();
	var arrayOfScrollboxClickClasses = new Array();
	var activeScrollboxRow = false;
	var activeScrollboxRowClickArray = new Array();

	function addScrollboxRolloverEffect(ScrollboxId,whichClass,whichClassOnClick)
	{
		var scrollboxObj = document.getElementById(ScrollboxId);
		arrayOfScrollboxRolloverClasses[ScrollboxId] = whichClass;
		arrayOfScrollboxClickClasses[ScrollboxId] = whichClassOnClick;

		element = scrollboxObj.firstChild;
		while(element != null ) {
    if(element.tagName == "SPAN") { // NO INPUT, CHECKBOX STYLE PROBLEMS
      element.onmouseover = highlightScrollboxRow;
    		element.onmouseout = resetScrollboxStyle;
    		if(whichClassOnClick){
    			element.onclick = clickOnScrollboxRow;
    		}
  		}
  		element = element.nextSibling;
		}
	}


	function highlightScrollboxRow()
	{
		var scrollboxObj = this.parentNode;
		if(scrollboxObj.tagName != "DIV") scrollboxObj = scrollboxObj.parentNode;

		if(this!=activeScrollboxRow){
			this.setAttribute('origCl',this.className);
			this.origCl = this.className;
		}
		if(scrollboxObj.disabled == false) this.className = arrayOfScrollboxRolloverClasses[scrollboxObj.id];

		activeScrollboxRow = this;

	}

	function clickOnScrollboxRow()
	{
		var scrollboxObj = this.parentNode;
		if(scrollboxObj.tagName != "DIV") scrollboxObj = scrollboxObj.parentNode;

		if(activeScrollboxRowClickArray[scrollboxObj.id] && this!=activeScrollboxRowClickArray[scrollboxObj.id]){
			activeScrollboxRowClickArray[scrollboxObj.id].className='';
		}
		this.className = arrayOfScrollboxClickClasses[scrollboxObj.id];

		activeScrollboxRowClickArray[scrollboxObj.id] = this;

	}

	function resetScrollboxStyle()
	{
		var scrollboxObj = this.parentNode;
		if(scrollboxObj.tagName != "DIV") scrollboxObj = scrollboxObj.parentNode;

		if(activeScrollboxRowClickArray[scrollboxObj.id] && this==activeScrollboxRowClickArray[scrollboxObj.id]){
			this.className = arrayOfScrollboxClickClasses[scrollboxObj.id];
			return;
		}

		var origCl = this.getAttribute('origCl');
		if(!origCl)origCl = this.origCl;
		this.className=origCl;

	}
