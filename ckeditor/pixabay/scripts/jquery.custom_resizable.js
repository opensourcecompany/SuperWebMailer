// https://stackoverflow.com/questions/7506104/jquery-ui-resizable-set-size-programmatically
/*
$('#myElement').mysizable('option', 'y', 100);
$('#myElement').mysizable('option', 'x', 100);
*/

(function( $ ) {
    $.widget( "custom.myresizable", $.ui.resizable, {

        options: {
            x: 0,
            y: 0
        },

        _create: function() {
            $.ui.resizable.prototype._create.call(this);
            this.options['x'] = $(this.element).width();
            this.options['y'] = $(this.element).height();
        },

        _resize: function(x, y) {
            if( x != null ){
                $(this.element).width( x );
            }
            if( y != null ){
                $(this.element).height( y );
            }
            this._proportionallyResize();
        },

        _setOption: function( key, value ) {
            $.ui.resizable.prototype.options[key] = value;
            this.options[ key ] = value;
            if(key == "x" || key== "y")
               this._update();
        },

        _update: function() {
            this._resize(
                this.options['x'],
                this.options['y']
            );
        },

        _destroy: function() {
            $.ui.resizable.prototype._destroy.call(this);
            this.options['x'] = null;
            this.options['y'] = null;
        }
    });
})( jQuery );

