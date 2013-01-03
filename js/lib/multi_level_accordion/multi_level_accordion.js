(function($){
    $.fn.accordion = function(){
        this.addClass('c3-accordion');
        this.find('ul').hide();
        this.find('li header').click(function(){
            var openMe = $(this).next();
            var mySiblings = $(this).parent().siblings().find('ul');
            if (openMe.is(':visible')) {
                openMe.slideUp('normal');  
            } else {
                mySiblings.slideUp('normal');  
                openMe.slideDown('normal');
            }
        });
    }
})( jQuery );