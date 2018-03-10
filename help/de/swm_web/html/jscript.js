if ( (top.frames.length == 0) && (document.URL.indexOf('file:/') == -1) ) {
   top.location.href = "../../frameset.htm?" + escape(document.URL) + '?&frame=' + escape(top.document.referrer);
} else{
  document.write( '<script language="javascript" type="text/javascript" src="../../frameresize.js"></script>' );
}


