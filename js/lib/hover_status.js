(function($){
    
    $.fn.hoverStatus = function(data,opts){
        var options = typeof opts != 'undefined' ? opts : {};
        this.prepend("<div id='hoverStatus' style='position:absolute; border: 1px solid #B8B8B8 ; border-left:none; border-top:none; padding:4px; -moz-border-radius-bottomright:5px; border-bottom-right-radius:5px; background:#E8E8E8'>"+data+"</div>");
        if(!options.persistent){
            this.find('#hoverStatus').hide();
            this.hover(function(){(this).find('#hoverStatus').show()},function(){$(this).find('#hoverStatus').hide()});
        } else {
            this.find('#hoverStatus').show()
        }
        
    }
    
})( jQuery );


