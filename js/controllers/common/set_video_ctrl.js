
/*
 * 
 * 1. After document has been loaded to html body
 * 2. sccInit();
 * 3. scc.show();
 * 4. sppc.setSubmitCallback(function(data){profilePic = data});
 * 
 * 
 * 
 * 
 * 
 */

var svc;
function set_video_ctrl($scope, $http) {
    svc = $scope;
   
    
    $scope.authUrl = authUrl;
    $scope.hasToken = hasToken;
    $scope.baseUrl = baseUrl;
    $scope.randToken = randToken;
    
    //These are parameters which can be set for setVideos
    $scope.setParams = function(params){
        if(params){
            if(params.onCloseCallback) $scope.onCloseCallback = params.onCloseCallback;
            if(params.onSubmitCallback) $scope.onSubmitCallback = params.onSubmitCallback;
            if(params.videoTitle) $scope.videoTitle = params.videoTitle;
            if(params.videoDescription) $scope.videoDescription = params.videoDescription;
        }
    }
    
    $scope.init = function(){
    //init
    };
    
    $scope.show = function(){
        $scope.authBtnClass='';
        $scope.authStarted = false;
        $scope.$apply();
        $('#c3_svc_modal').modal('show');
        if($scope.hasToken) {
            $scope.showUploadVideo();
        } else {
            $('#c3_svc_tab a[href="#svc_1"]').tab('show');
        }
    };
    
    $scope.startAuth = function(){
        if($scope.authStarted) return;
        $scope.authBtnClass = 'disabled';
        $scope.authStarted = true;
        window.open($scope.authUrl,'Grant Youtube Access To Casting3', "height=500,width=500");
        $scope.waitAuth();
    }
    
    $scope.showUploadVideo = function(){
        $scope.authStarted = true;
        $.post(baseUrl+'/common/setVideoMetadata',{
            videoTitle: svc.videoTitle,
            videoDescription: svc.videoDescription
        },function(data){
            $('#submitVideo').attr('src',baseUrl+'/common/uploadVideoFile?randToken='+$scope.randToken);
            $scope.$apply();
            $('#c3_svc_tab a[href="#svc_2"]').tab('show');
            setTimeout(function(){
                svc.waitSubmitVideo();
            },20000);
        });

                
    }
    
    $scope.waitAuth = function(){
        $.get(baseUrl+'/common/checkAuthenticated?randToken='+$scope.randToken,function(data){
            if(data == 'true'){
                
                    svc.showUploadVideo();
                
            } else {
                setTimeout(function(){
                    svc.waitAuth();
                },1500);
            }
        });
    }
    
    $scope.waitSubmitVideo = function(){
        $.get(baseUrl+'/common/checkConfirmUpload?randToken='+$scope.randToken,function(data){
            if(!svc.authStarted) return;
            if(data == 'false'){
                setTimeout(function(){
                    svc.waitSubmitVideo();
                },3000);
            } else {
                data = angular.fromJson(data);
                $('#c3_svc_modal').modal('hide');
                if(svc.onSubmitCallback){
                    svc.onSubmitCallback(data);
                }
            }
        });
    }
    
    $scope.closeModal = function(){
        if($scope.onCloseCallback){
            $scope.onCloseCallback();
        }
    }
    
    
}
