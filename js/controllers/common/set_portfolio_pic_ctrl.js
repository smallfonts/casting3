
/*
 * 
 * 1. After document has been loaded to html body
 * 2. sppcInit();
 * 3. sppc.show();
 * 4. sppc.setSubmitCallback(function(data){profilePic = data});
 *
 * 
 * 
 * 
 * 
 * 
 */

var sppc;
function set_portfolio_pic_ctrl($scope, $http) {
    sppc = $scope;
    
    //modes
    $scope.aspectRatio = 0.65;
    $scope.cropPhoto = true; //if set to false, window will render view for upload featured photo
    $scope.submitFormAction = baseUrl + '/common/setPhoto';
    $scope.baseUrl = baseUrl;
    $scope.photoBaseUrl = photoBaseUrl;
    $scope.msie = $.browser.msie;
    $scope.safari = $.browser.safari;
    $scope.jcrop_api = null;
    $scope.blobFile = null;
    $scope.photos = new Array();
    $scope.fileselectClass = "fileselect";
    
    //Callback Functions
    //callback function that is called after photo has been successfully uploaded
    $scope.submitCallback;
    
    //callback function that is called after close button is clicked
    $scope.closeCallback;
    
    $scope.closeModal = function(){
        if($scope.closeCallback) $scope.closeCallback();
    }
    
    //height of resized image
    $scope.canvasHeight = "";
    $scope.canvasWidth = "";
    
    //cropped image
    $scope.cropped = {
        coords:{
            x1:"",
            x2:"",
            y1:"",
            y2:""   
        },
        
        ext:"",
        cropWidth:"",
        cropHeight:""
    }
    
    //progress bar
    $scope.percentage = 0;
    $scope.submitClass = 'disabled';
    
    //progress bar
    $scope.barClass ='hidden';
    $scope.progressBarClass = '';
    
    //image preview
    $scope.preview = {
        display:"display: none;"
    }
    
    /*
     * Initializes Set Portfolio Pic.
     * NOTE: initialization should only occur once
     */
    
    $scope.init = function(){
        //console.info('<init> - start');
        
        $(document).bind('dragover', function (e) {
            var dropZone = $('#dropzone'),
            timeout = window.dropZoneTimeout;
            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }
            if (e.target === dropZone[0]) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });
        $(document).bind('drop dragover', function (e) {
            e.preventDefault();
        });
        
        $('#fileselect').bind("click",function(e){
            $('#fileselect').val("");
        });
        
        $('#fileselect').bind("change",function(e){
            
            if($.browser.msie || $.browser.safari){
                $scope.submitIE();
            } else {
                //for Standard Compliant Browsers
                $scope.fileSelected(e)
            }
        });
        
        $(document).bind("drop",function(e){
            $scope.fileSelected(e)
        });
        
        $('#fileinput').bind('click',function(){
            $('#fileselect').trigger('click');
        });
        
    //console.info('<init> - end');
    }
    
    
    
    $scope.show = function(params){
        //console.info('<show> - start');
        
        //setting parameters
        if(params){
            if(typeof params.aspectRatio != 'undefined') $scope.aspectRatio = params.aspectRatio;
            $scope.cropPhoto = typeof params.cropPhoto != 'undefined' ? params.cropPhoto : true;
            $scope.submitCallback = typeof params.submitCallback != 'undefined' ? params.submitCallback : undefined;
            $scope.closeCallback = typeof params.closeCallback != 'undefined' ? params.closeCallback : undefined;
        }
        
        
        //reset data
        if($scope.jcrop_api != null){
            $scope.jcrop_api.destroy();
        }
        
        $scope.blobFile = null;
    
        //height of resized image
        $scope.canvasHeight = "";
        $scope.canvasWidth = "";
        
        //cropped image
        $scope.cropped = {
            coords:{
                x1:"",
                x2:"",
                y1:"",
                y2:""   
            },
            ext:"",
            cropWidth:"",
            cropHeight:"",
            imagesrc:"",
            photourl:""
        }
    
        //progress bar
        $scope.percentage = 0;
        $scope.submitClass = 'disabled';
    
        //progress bar
        $scope.barClass ='hidden';
        $scope.progressBarClass = '';
    
        //image preview
        $scope.preview = {
            display:"display: none;"
        }
        
        //IE Support Workflow
        $scope.initUploadify();

        
        $scope.$apply();
        
        //reset tabs
        $('#c3_cpp_modal').modal('show');
        $('#c3_cpp_tab a[href="#cpp_1"]').tab('show');
    ///$('#c3_cpp_pill a[href="#cpp_pill_1"]').tab('show');
    //console.info('<show> - end');
    }
    
    
    $scope.initUploadify = function(){
        //console.info('<initUploadify> - start');
        if($.browser.msie || $.browser.safari){
            
            $scope.fileselectClass = "";
            $('#fileselect').uploadify({
                'swf'      : baseUrl+'/js/lib/uploadify/uploadify.swf',
                'uploader' : $scope.submitFormAction+"?enc="+uploadifyEnc+"&cropPhoto="+$scope.cropPhoto,
                'buttonText' : 'Upload New Photo',
                'onUploadSuccess' : function(file, data, response) {
                    
                    if($scope.cropPhoto){
                        var photo = angular.fromJson(data)
                        $scope.activateCrop({
                            url:photo.url,
                            photoExists: true
                        });
                    } else {
                        //console.info('<uploadify> - received data :'+data);
                        $scope.submitSuccess(data);
                    }
                    $('#fileselect').uploadify('destroy');
                }
            });
        }
    //console.info('<initUploadify> - end');
    }
    
    //submits upload photo form
    $scope.submit = function(){
        if(!$scope.canSubmit()) return;
        //console.info('<submit> - start');
        
        if($scope.existingPhoto){
            //console.info('Photo cropped exists in db');
            $scope.existingPhoto = false;
            $scope.barClass='';
            $scope.percentage = 100;
            $.post($scope.submitFormAction, $('#ie_photo_upload').serialize(), function(data){
                $scope.submitSuccess(data);
            });
            $scope.$apply();
        } else {
            var formData = new FormData();
            
            
            
            if($scope.cropPhoto){
                formData.append('Photo[x1]',$scope.cropped.coords.x1);
                formData.append('Photo[x2]',$scope.cropped.coords.x2);
                formData.append('Photo[y1]',$scope.cropped.coords.y1);
                formData.append('Photo[y2]',$scope.cropped.coords.y2);
                formData.append('Photo[width]',$scope.cropped.cropWidth);
                formData.append('Photo[height]',$scope.cropped.cropHeight);
            }
            
            //If $scope.order is found, image uploaded should change the image of an existing photo
            //
            if(typeof $scope.order != 'undefined') formData.append('order',$scope.order);
            if(typeof $scope.casting_callid != 'undefined') {
                formData.append('CastingCall[casting_callid]',$scope.casting_callid);
            }
            
            formData.append('Photo[image]',$scope.blobFile);
            formData.append('Photo[ext]','png');
        
            xhr = new XMLHttpRequest();
            $scope.barClass='';
        
            xhr.upload.addEventListener("progress", function(e) {
                //console.info('<upload progress> updating progress');
                var pc = parseInt(e.loaded / e.total * 100);  
                //console.info('updating progress :'+pc+'%');
                $scope.percentage = pc;
                $scope.$apply();
            }, false);
            
            xhr.open("POST", $scope.submitFormAction, true);
            xhr.onreadystatechange=function(){
                try{
                    
                    if(xhr.status == 500){
                        //console.info("failure");
                        $("#test_output").html(xhr.responseText);
                    }
                    
                    if(xhr.readyState==4 && xhr.status==200){
                        //console.info("success :"+xhr.responseText);
                        $scope.percentage = 100;
                        $scope.$apply();
                        $scope.submitSuccess(xhr.responseText);
                    }
                    
                } catch(e){
                //console.info(e.message);
                }
            };
            //console.info('<submit> - sending form');
            xhr.send(formData);
        } 
    //console.info('<submit> - end');
    }
    
    $scope.submitSuccess = function(data){
        $scope.progressBarClass = 'progress-success';
        if($scope.submitCallback != undefined){
            $scope.submitCallback(data);
        }
                
        setTimeout(function(){
            $('#c3_cpp_modal').modal('hide');
            setTimeout(function(){
                //processes common actions from server json response
                processResponse(data);
            },500);
        },800);
    }
    
    $scope.fileSelected = function(e){
        //console.info('<fileSelected> - start');
        //This is an event handler that executes when file is dropped within dropzone
        //
        //e is a jQuery event which is created by JQuery when an action has been triggered
        //these lines stop the event from carrying on with its default actions which is to display a file in a new page
        e.stopPropagation();
        e.preventDefault();
        test = e;
        var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
        
        //image file that has been uploaded 
        file = files[0];
        
        if(file.type.indexOf('image') == 0){
            //does 3 things
            // 1. converts file into a canvas
            // 2. resize canvas 
            // 3. converts resized canvas into a blob and appends to form
            // 4. activates crop tab window if mode is setProfilePic
            // 5. If mode is not setProfilePic, then photo is submitted to server
            $scope.loadImage(file);
        
        } else {
        
            //displays an error if file is not an image
            c3alert({
                template: "error",
                text: 'This file is not an image!<br/>Only .jpg, .jpeg, .png, .gif are accepted'
            });
        
        }
    //console.info('<fileSelected> - end');
    }
        
    $scope.loadImage = function(file){
        //console.info('<loadImage> - start');
        var reader = new FileReader();  
        reader.readAsDataURL(file); 
        reader.onload = function(e) {
            //get data as a url


            img = new Image();
            img.src = e.target.result;
            img.onload = function()
            {
                //image size may be very big and take a long time to upload to the server
                // To solve this issue, we will perform an image resize on the client side using HTML5's canvas api
                // 
                // 1. we will convert this dataurl into a canvas
                // 2. resize the canvas within the defined max width (defined in imgToCanvas)
                // 3. convert the canvas back into a dataurl
                
                //loads image to canvas and resizes it.
                canvas = $scope.imgToCanvas(img);
                
                //4.activate cropping for image
                //5.and open crop tab window
                //6.makes form "submitable""
                
                if($scope.cropPhoto){
                    $scope.activateCrop({
                        dataUrl: canvas.toDataURL()
                    });
                }
            
                //7.converts image to blob and appends to form
                //
                //
                $scope.blobFile = $scope.dataURItoBlob(canvas.toDataURL());
                
                if(!$scope.cropPhoto){
                    //5.If mode is not "setProfilePic", then picture will be automatically submitted to server
                    $scope.canSubmit(true);
                    $scope.submit();
                }
            }
        }
    //console.info('<loadImage> - end');
    }
   
    //This function activates Jcrop API for image cropping
    //
    //Once crop is activated, it will activate the cropping tab window when plugin is initialized
    //
    $scope.activateCrop = function(options){
        
        var src;
        
        if (options.dataUrl){
            src = options.dataUrl;
            $scope.existingPhoto = false;
        }
        
        if (options.url){
            src = $scope.photoBaseUrl + '/' + options.url;
            $scope.cropped.photourl = options.url;
        }
        
        if (options.photoExists){
            $scope.existingPhoto = true;
        }
        
        $('#crop').load(function(){
            if($scope.jcrop_api != null){
                $scope.jcrop_api.destroy();
            }
        
            //get selected square
            selectionWidth = Math.min($('#crop').width(),$('#crop').height());
            xGap = ($('#crop').width() - selectionWidth)/2;
            yGap = ($('#crop').height() - selectionWidth)/2;
            $scope.cropped.coords.x1 = xGap;
            $scope.cropped.coords.x2 = xGap + selectionWidth;
            $scope.cropped.coords.y1 = yGap + selectionWidth;
            $scope.cropped.coords.y2 = yGap;
                
            $('#crop').Jcrop({
                onChange: $scope.loadCoords,                    
                onSelect: $scope.loadCoords,
                aspectRatio: $scope.aspectRatio,
                setSelect: new Array($scope.cropped.coords.x1,$scope.cropped.coords.y1,$scope.cropped.coords.x2,$scope.cropped.coords.y2)
            },function(){
                $scope.jcrop_api = this;
                $scope.jcrop_api.setOptions({
                    allowSelect: false
                });

            });

            $scope.preview.display="display:block;";
        });

        $('#crop').width("");
        $('#crop').height("");
        $scope.cropped.imagesrc = src;
        $scope.$apply();
        
        $('#c3_cpp_tab a[href="#cpp_2"]').tab('show');
        $scope.canSubmit(true);
    }
    
    $scope.showPreview = function (c){
        
        
        var previewWidth = 100;
        var previewHeight = (1/$scope.aspectRatio) * previewWidth;
        $('#croppreviewholder').css({
            width: previewWidth  + 'px',
            height: previewHeight + 'px'
        });
        
        
        var rx = previewWidth / c.w;
        var ry = previewHeight / c.h;
        
        $('#croppreview').css({
            width: Math.round(rx * $('#crop').width()) + 'px',
            height: Math.round(ry * $('#crop').height()) + 'px',
            marginLeft: '-' + Math.round(rx * c.x) + 'px',
            marginTop: '-' + Math.round(ry * c.y) + 'px'
        });

    }
    
    $scope.loadCoords = function (c){
        $scope.showPreview(c);
        $scope.cropped.coords.x1 = c.x;
        $scope.cropped.coords.x2 = c.x2;
        $scope.cropped.coords.y1 = c.y;
        $scope.cropped.coords.y2 = c.y2;
        $scope.cropped.cropWidth = c.w;
        $scope.cropped.cropHeight = c.h;
        $scope.$apply();
    }
    
    
    $scope.canSubmitFile = false;
    $scope.canSubmit = function(canSubmit){
        if(typeof canSubmit == 'undefined') {
            return $scope.canSubmitFile;
        }
        
        if(canSubmit){
            //enable submit button
            $scope.canSubmitFile = true;
            $scope.submitClass='btn-primary';
        } else {
            $scope.canSubmitFile = false;
            $scope.submitClass='disabled'
        }
       
        $scope.$apply();
        return $scope.canSubmitFile;
    }
    
    $scope.dataURItoBlob = function(dataURI){
        
        //get data portion of dataURI
        var binary = atob(dataURI.split(',')[1]);
        var arr = [];
        for(var i = 0; i < binary.length; i++) {
            arr.push(binary.charCodeAt(i));
        }
        
        
        // separate out the mime component
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        
        return new Blob([new Uint8Array(arr)], {
            type: mimeString
        });
        
    }
    
    $scope.imgToCanvas = function(img){
        //console.info('<imgToCanvas> - start');
        $scope.canvasHeight=img.height;
        $scope.canvasWidth=img.width;
        
        max_height=400;
        max_width=500;

        scale = Math.min(max_height/$scope.canvasHeight, max_width/$scope.canvasWidth);
        if (scale<1){
            $scope.canvasHeight *= scale;
            $scope.canvasWidth *= scale;
        }
    
        canvas = document.createElement("canvas");
        canvas.width = $scope.canvasWidth;
        canvas.height = $scope.canvasHeight;
        canvas.getContext("2d").drawImage(img, 0, 0, $scope.canvasWidth, $scope.canvasHeight);
        //console.info('<imgToCanvas> - end');
        return canvas;
    }
    
    $scope.getAllPhotos = function(){
        $.get(baseUrl+'/common/getAllPhotos',function(data){
            //console.info(data);
            $scope.photos = angular.fromJson(data);
            $scope.$apply();
        });
    }
}
