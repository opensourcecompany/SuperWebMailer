  // ckeditor fix http://www.adamstacey.co.uk/2010/07/09/initiating-ckeditor-with-jquery-and-using-jquery-ui-dialog/
  $.extend($.ui.dialog.overlay, { create: function(dialog){
    if (this.instances.length === 0) {
      setTimeout(function() {
        if ($.ui.dialog.overlay.instances.length) {
          $(document).bind($.ui.dialog.overlay.events, function(event) {
            var parentDialog = $(event.target).parents('.ui-dialog');
            if (parentDialog.length > 0) {
              var parentDialogZIndex = parentDialog.css('zIndex') || 0;
              return parentDialogZIndex > $.ui.dialog.overlay.maxZ;
            }
            var aboveOverlay = false;
            $(event.target).parents().each(function() {
              var currentZ = $(this).css('zIndex') || 0;
              if (currentZ > $.ui.dialog.overlay.maxZ) {
                aboveOverlay = true;
                return;
              }
            });
            return aboveOverlay;
          });
        }
      }, 1);
      $(document).bind('keydown.dialog-overlay', function(event) {
        (dialog.options.closeOnEscape && event.keyCode && event.keyCode == $.ui.keyCode.ESCAPE && dialog.close(event));
      });
      $(window).bind('resize.dialog-overlay', $.ui.dialog.overlay.resize);
    }
    var $el = $('').appendTo(document.body).addClass('ui-widget-overlay').css({
      width: this.width(),
      height: this.height()
    });
    (dialog.options.stackfix && $.fn.stackfix && $el.stackfix());
    this.instances.push($el);
    return $el;
  }});
