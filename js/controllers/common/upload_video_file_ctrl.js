
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

var uvfc;
function upload_video_file_ctrl($scope, $http) {
    uvfc = $scope;
    $scope.isUploading = false;
    $scope.canUpload = "disabled";
    $scope.canSetUrl = "disabled";
    
    $('input:file').uniform();
    $('#uvf_file').change(function(){
        var file = $('#uvf_file');
        if ($scope.isValidVideoFile(file.val())) {
            $scope.canUpload = "";
            $scope.canSetUrl = "disabled";
            $scope.hasError = false;
            $scope.$apply();
        } else {
            $scope.canUpload = "disabled";
            $scope.hasError = true;
            $scope.$apply();
        }
    });
    
    
    $scope.uploadVideo = function(){
        if($scope.canUpload == 'disabled') {
            return;
        } else {
            $('#uvf_form').submit();
            $('#uvf_submit').attr('value','Uploading...');
        }
    }
    
    $scope.submitLink = function(){
        if($scope.canSetUrl == "disabled") return;
        window.location = baseUrl + '/common/confirmUpload?randToken='+randToken+'&status=200&id='+$scope.video.url;
    }
    
    $scope.processVideoData = function(data){
        $scope.video.title = data.entry.title.$t;
        $scope.$apply();
    }
    
    $scope.video = {};
                
    $scope.newVideoUrl = function(){
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
        var match = $scope.video.link.match(regExp);
        if (match&&match[2].length==11){
            $scope.video.url = match[2];
            $scope.canSetUrl = "";
            $scope.canUpload = "disabled";
            $scope.video.title = "";
            var scriptLoader = new c3ScriptLoader();
            scriptLoader.addJavascript('http://gdata.youtube.com/feeds/api/videos/'+$scope.video.url+'?alt=json-in-script&callback=uvfc.processVideoData');
            scriptLoader.load(function(){},'youtube_data');
        }else{
            $scope.canSetUrl = "disabled";
        }
    }   
    
    $scope.getExtension = function(filename) {
        var parts = filename.split('.');
        $scope.extension = parts[parts.length - 1];
        return parts[parts.length - 1];
    }
    
    $scope.isValidVideoFile = function(filename) {
        var ext = $scope.getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'm4v':
            case 'avi':
            case '3gp':
            case '3gpp':
            case 'flv':
            case 'mpegps':
            case 'wmv':
            case 'mov':
            case 'mpeg4':
            case 'mpg':
            case 'mp4':
                // etc
                return true;
        }
        return false;
    }
}
