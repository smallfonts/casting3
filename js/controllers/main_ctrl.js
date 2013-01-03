/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function simpleTemplate(src,options){
    var dom = $('#'+src).clone();
    var tmpString = dom.html();
    for(var i in options){
        tmpString = tmpString.replace(new RegExp(i,"g"),options[i]);
    }
    
    return tmpString;
}

//initializes datepicker for 2 date inputs representing start and end dates.
//Logic will ensure that both start and end dates have values when either have been set, and start date <= end date
//Parameters
//dataNode: the object which start and end dates are stored (ie. eccc.castingCall.data)
//startId : the ID of the input element that represent the start dates and end dates
//endId : the ID of the input element that represent the start dates and end dates
//
//opts : Optional settings (object)
//startDataNodeAttribute : the attribute that stores the data value in datanode for start date (*note: if left empty, startDataNodeAttribute = startId)
//endDataNodeAttributes: the attribute that stores the data value in datanode for end date (*note: if left empty, endDataNodeAttribute = endId)

function initDatePeriod(dataNode,startId,endId,opts){
    var startDataNodeAttribute = startId;
    var endDataNodeAttribute = endId;
    if(typeof opts != 'undefined'){
        if(typeof opts.startDataNodeAttribute != 'undefined') startDataNodeAttribute = opts.startDataNodeAttribute;
        if(typeof opts.endDataNodeAttribute != 'undefined') endDataNodeAttribute = opts.endDataNodeAttribute;
    }
    
    //initialize values of start and end date from datanodeattributes
    if(dataNode[startDataNodeAttribute] != null){ 
        var startDate = stringToDate(dataNode[startDataNodeAttribute],'yyyy-mm-dd');
        if(startDate != 'Invalid Date') $('#'+startId).val(dateToString(startDate,'dd/mm/yyyy'));
    }
    if(dataNode[endDataNodeAttribute] != null){ 
        var endDate = stringToDate(dataNode[endDataNodeAttribute],'yyyy-mm-dd');
        if(endDate != 'Invalid Date') $('#'+endId).val(dateToString(endDate,'dd/mm/yyyy'));
    }
    
    
    
    $('#'+startId).datepicker({
        format : 'dd/mm/yyyy'
    });
    
    $('#'+endId).datepicker({
        format : 'dd/mm/yyyy'
    });
    
    //date logic for audition start and end
    $('#'+startId).change(function(){
        if($('#'+startId).val() == ''){
            $('#'+endId).val('');
            dataNode[startDataNodeAttribute] = '';
            dataNode[endDataNodeAttribute] = '';
            return;
        }
        
        var endTime = -1;
        var startDate = stringToDate($('#'+startId).val(),'dd/mm/yyyy');
        if(startDate == 'Invalid Date') {
            $('#'+startId).val('');
            return;
        }
        
        var endDate = startDate;
        
        if($('#'+endId).val() != ''){ 
            endDate = stringToDate($('#'+endId).val(),'dd/mm/yyyy');
            endTime = Date.parse(endDate); 
        }
        
        if(endTime == -1 || Date.parse(startDate) > endTime){
            $('#'+endId).val($('#'+startId).val()).datepicker('update');
        }
            
        dataNode[startDataNodeAttribute] = dateToString(startDate,'yyyy-mm-dd');
        dataNode[endDataNodeAttribute] = dateToString(endDate,'yyyy-mm-dd');
        $('.datepicker').hide();
    });
        
    $('#'+endId).change(function(){
        if($('#'+endId).val() == ''){
            $('#'+startId).val('');
            dataNode[startDataNodeAttribute] = '';
            dataNode[endDataNodeAttribute] = '';
            return;
        }
        
        var startTime = -1;
        var endDate = stringToDate($('#'+endId).val(),'dd/mm/yyyy');
        if(endDate == 'Invalid Date') endDate = new Date();
        var startDate = endDate;
        
        if($('#'+startId).val() != ''){ 
            startDate = stringToDate($('#'+startId).val(),'dd/mm/yyyy');
            startTime = Date.parse(startDate); 
        }
        if(startTime == -1 || Date.parse(endDate) < startTime){
            $('#'+startId).val($('#'+endId).val()).datepicker('update');
        }
            
        dataNode[startDataNodeAttribute] = dateToString(startDate,'yyyy-mm-dd');
        dataNode[endDataNodeAttribute] = dateToString(endDate,'yyyy-mm-dd');
        $('.datepicker').hide();
    });
}


function c3Timer(params){
    if(params){
        if(params.timeout){
            this.timeout = params.timeout;
        } else {
            this.timeout = 200;
        }
        
        if(params.onExpire){
            this.onExpire = params.onExpire;
        }
    }
    
    this.ready = true;
    
    this.start = function(){
        this.ready = false;
        this.startTimer(this);
    }
    
    this.startTimer = function(obj){
        clearTimeout(this.timeoutCallback);
        this.timeoutCallback = setTimeout(function(){
            obj.ready = true;
            if(obj.onExpire){
                obj.onExpire();
            //obj.onExpire = undefined;
            }
        },this.timeout);
    }
}

/*  Usage
 *  c3Confirm({
 *      header : 'title',
 *      body : 'Message',
 *      onAccept : function(){},
 *      onReject : function(){}
 *  });
 *
 */

function c3Confirm(params){
    var header = typeof params.header != 'undefined' ? params.header : 'Hold your horses!';
    var body = typeof params.body != 'undefined' ? params.body : 'Are you sure you want to continue?';
    $('#c3Confirm #heading').html(header);
    $('#c3Confirm #content #body').html(body);
    $('.c3Confirm-modal-bg').fadeIn();
    $('#c3Confirm').fadeIn();
    $('#c3Confirm #content #c3Accept').unbind();
    $('#c3Confirm #content #c3Accept').click(function(){
        if(params.onAccept){
            params.onAccept();
        }
        $('.c3Confirm-modal-bg').fadeOut();
        $('#c3Confirm').fadeOut();

    });
    $('#c3Confirm #content #c3Reject').unbind();
    $('#c3Confirm #content #c3Reject').click(function() {
        if(params.onReject){
            params.onReject();
        }
        $('.c3Confirm-modal-bg').fadeOut();
        $('#c3Confirm').fadeOut();
    });
    
}




//test if browser is safari
$.browser.safari = ( ($.browser.safari && /chrome/.test(navigator.userAgent.toLowerCase())) || $.browser.mozilla ) ? false : true;
$.browser.chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
if($.browser.chrome){
    $.browser.safari = false;
}
var c3MainCtrl;

//Converts date from dd/mm/yyyy format to Date object

function stringToDate(stringDate,stringFormat){
    if(stringDate == undefined || stringDate == "") return undefined;
    if(stringFormat == 'yyyy-mm-dd hh:mm:ss') {
        var t = stringDate.split(/[- :]/);
        return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
    }
    if(stringFormat == 'yyyy-mm-dd') {
        var t = stringDate.split('-');
        var date = new Date(t[0],t[1]-1,t[2]);
        date.setHours(0);
        return date;
    }
    if(stringFormat == 'dd/mm/yyyy') {
        stringDate = stringDate.split('/');
        return new Date(stringDate[1]+'/'+stringDate[0]+'/'+stringDate[2]);
    }
    return new Date(stringDate);
}

//formats date object to dd/mm/yyyy
function dateToString(dateObj,format){
    
    if(typeof dateObj == 'undefined' || dateObj == 'Invalid Date') return '';
    
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var day = dateObj.getDate() + "";
    if(day.length == 1) day = "0"+day;
    var month = (dateObj.getMonth() + 1) + "";
    if(month.length == 1) month = "0"+month;
    
    if(format == 'hh:mm') return ('00' + dateObj.getHours()).slice(-2)+':'+('00' + dateObj.getMinutes()).slice(-2);
    if(format == 'dd MMM hh:mm') return day+' '+months[dateObj.getMonth()]+' '+('00' + dateObj.getHours()).slice(-2)+':'+('00' + dateObj.getMinutes()).slice(-2);
    if(format == "dd MMM 'yy") {
        var tmpYear = dateObj.getFullYear()+'';
        tmpYear = tmpYear.substring(2);
        return day+' '+months[dateObj.getMonth()]+" '"+tmpYear;
    }
    if(format == "dd MMM 'yy hh:mm"){
        var tmpYear = dateObj.getFullYear()+'';
        tmpYear = tmpYear.substring(2);
        return day+' '+months[dateObj.getMonth()]+" '"+tmpYear+" "+('00' + dateObj.getHours()).slice(-2)+":"+('00' + dateObj.getMinutes()).slice(-2);
    }
    if(format == 'dd MMM yyyy') return day+' '+months[dateObj.getMonth()]+' '+dateObj.getFullYear();
    if(format == 'dd/mm/yyyy') return day+'/'+month+'/'+dateObj.getFullYear();
    if(format == 'yyyy-mm-dd') return dateObj.getFullYear()+'-'+month+'-'+day;
    if(format == 'yyyy-mm-dd hh:mm:ss') return dateObj.getFullYear() + '-' + ('00' + (dateObj.getMonth()+1)).slice(-2) + '-' + dateObj.getDate() + ' ' + ('00' + dateObj.getHours()).slice(-2) + ':' + ('00' + dateObj.getMinutes()).slice(-2) + ':' + ('00' + dateObj.getSeconds()).slice(-2);
    return day+'/'+month+'/'+dateObj.getYear();
}

function displayGuide(imagesArray){
    //insert all guide divisions (from first to last), but display just the first
    //first image: show image, close, next button
    //second to second last images: show image, close, back and next button
    //last image: show image, back and close button
    //next button - onclick will call nextGuide()
    //back button - onclick will call prevGuide()
    
    for (var i = 0; i < imagesArray.length; i++){
        if (i == 0 ){
            // if it's first image, fade in the black transparent background first
            $('.portfolioGuide').fadeIn(500);
            //Close Button
            $('.guide').append("<a href=#'' onclick='endGuide(" + i + ")'><span class='guideCloseButton' id='guideClose_0'></span></a>");
            //Image
            $('.guide').append("<div class='guideImage' id='guideImage_0'><center><img src=" + baseUrl + "/images/guides/" + imagesArray[i] + "></center></div>");
            //Next button
            $('.guide').append("<a href='#' onclick='nextGuide(" + i +"," + imagesArray.length + ")'><span class='guideNextButton' id='guideNextButton_0'></span></a>");
        } else if (i != imagesArray.length - 1){
            //Close Button
            $('.guide').append("<a href=#'' onclick='endGuide(" + i + ")'><span class='guideCloseButton' id='guideClose_" + i + "'></span></a>");
            //Image
            $('.guide').append("<div class='guideImage' id='guideImage_" + i + "'><center><img src=" + baseUrl + "/images/guides/" + imagesArray[i] + "></center></div>");
            //Previous button
            $('.guide').append("<a href='#' onclick='prevGuide(" + i +"," + imagesArray.length + ")'><span class='guidePrevButton' id='guidePrevButton_" + i + "'></span></a>");
            //Next button
            $('.guide').append("<a href='#' onclick='nextGuide(" + i +"," + imagesArray.length + ")'><span class='guideNextButton' id='guideNextButton_" + i + "'></span></a>");
        }else{
            //Close Button
           $('.guide').append("<a href=#'' onclick='endGuide(" + i + ")'><span class='guideCloseButton' id='guideClose_" + i + "'></span></a>");
             //Image
            $('.guide').append("<div class='guideImage' id='guideImage_" + i + "'><center><img src=" + baseUrl + "/images/guides/" + imagesArray[i] + "></center></div>");
            //Previous button
            $('.guide').append("<a href='#' onclick='prevGuide(" + i +"," + imagesArray.length + ")'><span class='guidePrevButton' id='guidePrevButton_" + i + "'></span></a>");
        }
    }
    
    //now that you have got everything hidden, just show the first one! :)
    $('#guideClose_0').show();
    $('#guideNextButton_0').show();
    $('#guideImage_0').show();
   
    return;
}
function prevGuide(currIndex, arrayLength){
    //hide this current division, show previous division
    $("#guideClose_" + currIndex).hide();
    $("#guideNextButton_" + currIndex).hide();
    $("#guidePrevButton_" + currIndex).hide();
    $("#guideImage_" + currIndex).hide();
    nextIndex = currIndex - 1;
    //if next is first image, don't show prev button
    if (nextIndex == 0){
        $('#guideClose_0').show();
        $('#guideNextButton_0').show();
        $('#guideImage_0').show();
    }else{
        $("#guideClose_" + nextIndex).show();
        $("#guideNextButton_" + nextIndex).show();
        $("#guidePrevButton_" + nextIndex).show();
        $("#guideImage_" + nextIndex).show();
    }
}

function nextGuide(currIndex, arrayLength){
    //hide this current division, show next division
    //hide this current division, show previous division
    $("#guideClose_" + currIndex).hide();
    $("#guideNextButton_" + currIndex).hide();
    $("#guidePrevButton_" + currIndex).hide();
    $("#guideImage_" + currIndex).hide();
    nextIndex = currIndex + 1;
    //if next is last image, don't show next btutton
    if (nextIndex == (arrayLength - 1)){
        $("#guideClose_" + nextIndex).show();
        $("#guidePrevButton_" + nextIndex).show();
        $("#guideImage_" + nextIndex).show();   
    }else{
        $("#guideClose_" + nextIndex).show();
        $("#guideNextButton_" + nextIndex).show();
        $("#guidePrevButton_" + nextIndex).show();
        $("#guideImage_" + nextIndex).show();
    }
}
function endGuide(currIndex){
    $('.portfolioGuide').fadeOut(500);
    $("#guideClose_" + currIndex).hide();
    $("#guideNextButton_" + currIndex).hide();
    $("#guidePrevButton_" + currIndex).hide();
    $("#guideImage_" + currIndex).hide();
}

//This is a script loader utility that dynamically loads javascript and stylesheet to the head element of the document
//This utility can be used by external content, which has been loaded to the current page and has additional stylesheets and javascripts to include
//Hence, this function is often used by the "/protected/common/views"

function c3ScriptLoader(){
    
    this.stylesheets = new Array();
    this.javascripts = new Array();
    this.callback;
    this.scriptsLoaded = 0;
    
    this.addJavascript = function(url){
        this.javascripts.push(url);
        //console.debug('javascript added');
    }
    
    this.addStylesheet = function(url){
        this.stylesheets.push(url);
        //console.debug('stylesheet added');
    }
    
    this.load = function(callback,elementId){
        
        if(!elementId){
            elementId='head';
        }
        
        if(callback !=undefined){
            this.callback = callback;
        }
        
        for(var i in this.stylesheets){
            //console.debug('loading stylesheet: '+this.stylesheets[i]);
            this.loadStylesheet(this.stylesheets[i],elementId);
        }
        
        for(var i in this.javascripts){
            //console.debug('loading javascript: '+this.javascripts[i]);
            this.loadJavascript(this.javascripts[i],elementId);
        }
        
    }
    
    this.loadJavascript = function(url,elementId){
        var oHead = document.getElementById(elementId);
        var oScript = document.createElement('script');
        oScript.type = 'text/javascript';
        oScript.src = url;
        // most browsers
        oScript.onload = this.loadComplete();
        
        /* IE 6 & 7
        oScript.onreadystatechange = function() {
            if (this.readyState == 'complete') {
                c3ScriptLoader.loadComplete();
            }
        }*/
        
        oHead.appendChild(oScript);
        //console.debug('script appended:'+oScript);
    }
    
    this.loadStylesheet = function(url,elementId){
        var oHead = document.getElementById(elementId);
        var oScript = document.createElement('link');
        oScript.rel = 'stylesheet';
        oScript.type = 'text/css';
        oScript.href = url;
        
        // most browsers
        oScript.onload = this.loadComplete();
        
        /* IE 6 & 7
        oScript.onreadystatechange = function() {
            if (this.readyState == 'complete') {
                c3ScriptLoader.loadComplete();
            }
        }*/
        
        oHead.appendChild(oScript);
        //console.debug('script appended:'+oScript);
    }
    
    this.loadComplete = function(){
        this.scriptsLoaded++;
        if(this.scriptsLoaded == this.stylesheets.length + this.javascripts.length){
            //console.debug('all loaded');
            if(this.callback != undefined){
                setTimeout(this.callback,500);
            }
        }
    }
    
}

/*
 * Function to process json response from the server
 * Response Object Options:
 * 1) alerts: If errors are found in reponse object, c3alerts will be displayed for each alert found.
 * 2) location: If location is found, then browser will redirect to page
 * 
 */

function processResponse(data){
    
    if(data.alerts){
        for ( var i in data.alerts ){
            c3alert(data.alerts[i]);
        }
    }
    
    if(data.location){
        window.location = baseUrl + data.location;
    }
    
    return;
}

function main_ctrl($scope,$http){
    c3MainCtrl = $scope;
    $scope.photoBaseUrl = photoBaseUrl;
    $scope.baseUrl = baseUrl;
    $scope.externalContent = "";
    $scope.loadContent = function(url,callbackFunction,elementId){
        $http.get(url).success(function(data) {
            if(elementId){
                $("#"+elementId).html(data);
            } else {
                $('#c3ExternalContent').html(data);
            }
            if(callbackFunction){
                callbackFunction();
            }
        });
    }
    
    //get notifications
    $scope.getNotification = function(){
        $.get(baseUrl+'/common/getNotifications',function(data){
            try{
                data = angular.fromJson(data);
                processResponse(data);
            } catch(e){
              return;  
            }
        });
    }
    
    $scope.getNotification();
    notifications = setInterval(function(){$scope.getNotification()},10000);
    
    
}
