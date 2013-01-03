function c3CheckAlive(){
    onTimeout = function(){
        c3NavBarAlert('#c3NavBarError');
    };
    onSuccessAfterTimeout = function(){
        c3NavBarAlert('#c3NavBarSuccess');
    }
    
    new check_alive({
        onTimeout: onTimeout,
        onSuccessAfterTimeout: onSuccessAfterTimeout,
        src: baseUrl+'/html/echo.php',
        quietMillis:3500
    });
}

function c3NavBarAlert(type){
    $('.navbar').next('.nba').remove();
    $('#c3NavBarAlert '+type).clone().insertAfter('.navbar').hide().slideDown(function(){
        $(this).addClass('nba');
        $(this).find('button').click(function(){
            $('.navbar').next('.nba').slideUp(function(){
                $('.navbar').next('.nba').remove()
            })
        });
    });
}

function check_alive(options){
    
    this.hasTimedOutBefore = false;
    this.onTimeout = function(){};
    this.onSuccessAfterTimeout = function(){};
    this.timeout = 2000;
    this.quietMillis = 3000;
    this.src = '/';
    
    if(typeof options.onTimeout != 'undefined') this.onTimeout = options.onTimeout;
    if(typeof options.onSuccessAfterTimeout != 'undefined') this.onSuccessAfterTimeout = options.onSuccessAfterTimeout;
    if(typeof options.src != 'undefined') this.src = options.src;
    if(typeof options.timeout != 'undefined') this.timeout = options.timeout;
    if(typeof options.quietMillis != 'undefined') this.quietMillis = options.quietMillis;
    
    this.activate = function (){
        i = Math.floor(Math.random()*10000);
        caObj = this;
        $.ajax({
            url: this.src + '?echo=' + i,
            success: function(){
                if(caObj.hasTimedOutBefore){ 
                    caObj.hasTimedOutBefore = false;
                    caObj.onSuccessAfterTimeout();
                }
                setTimeout(function(){
                    caObj.activate();
                },caObj.timeout);
            },     
            error: function(){
                if(!caObj.hasTimedOutBefore){
                    caObj.hasTimedOutBefore = true;
                    caObj.onTimeout();
                }
                setTimeout(function(){
                    caObj.activate();
                },caObj.timeout);
            },
            timeout: this.timeout
        });
    }
    
    this.activate();
}