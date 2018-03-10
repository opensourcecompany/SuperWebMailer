if ( (top.frames.length == 0) && (document.URL.indexOf('file:/') == -1) ) {
   top.location.href = "../frameset.htm?" + escape(document.URL) + '?&frame=' + escape(top.document.referrer);
}
