function overlay_showAll(doShow){
	$('a').each(
		function(aindex){
		  if( $(this).attr("class") && $(this).attr("class").indexOf('overlay_stat_')>-1){
       if( $(this).attr("class").indexOf('overlay_stat_link_active')>-1 ){
         if(!doShow)
           showhide_stat($(this));
       } else{
         if(doShow)
           showhide_stat($(this));
       }
		  }
	 }
 );
}

function showhide_stat(that){
	if( $(that).attr("class").indexOf('overlay_stat_link_active')>-1 ){
		$(that).removeClass("overlay_stat_link_active");
		$(that).find('.overlay_stat_frame').hide();
	}else{
		$(that).addClass("overlay_stat_link_active");
		var pos = $(that).find('*:first').offset();
		if(pos){
			$(that).find('.overlay_stat_frame').css("top", pos.top);
			$(that).find('.overlay_stat_frame').css("left", pos.left + 25);
		}
		$(that).find('.overlay_stat_frame').show();
	}
}

function load_overlay_stat(that, rel){
 var date = new Date();
 var nocache = date.getTime() / 1000;
	jQuery.get(rel + "&nocache=" + nocache, "", function(data){
		$(that).prepend('<img class="overlay_stat_button" border="0" width="24" height="24" src="images/blind.gif" />' + data);
		$(that).attr("target", "");
		$(that).attr("href", "");
		$(that).find('.overlay_stat_frame').hide();
		$(that).addClass("overlay_stat_link_hover");

		$(that).hover(fmouseenter, fmouseleave);

		$(that).click(function(){
			showhide_stat(this);
			return false;
		});
	});
}

function fmouseenter(){
	$(this).find(".overlay_stat_frame").css("z-index", "1000");
	return true;
}

function fmouseleave(){
	$(this).find(".overlay_stat_frame").css("z-index", "500");
	return true;
}

function init(){
 $.ajaxSetup({ cache: false });
 $('a').each(
		function(aindex){
				var aregex = /\?link=([a-z0-9\_\-]+)\&?/i;
    try {
      aregex.exec($(this).attr('href'));
  				var id = RegExp.$1;
    } catch(e){ var id=null;};
      
				if(id && id.indexOf('_') > -1) {
      load_overlay_stat(this, 'stat_campaign_overlay_getclicks.php?link_id='+id);
    }
     else{
       try{
         if( $(this).attr('href').indexOf('#') == -1) {
          $(this).attr("target", "");
     		   $(this).attr("href", "#");
     		  }
    		 }catch(e){};
    	 }
	 }
 );
}

$(document).ready(function(){
  $( init );
});

