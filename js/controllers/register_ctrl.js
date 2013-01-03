
var rc;
function register_ctrl($scope, $http) {
    rc = $scope;
    
    $scope.photoBaseUrl = c3MainCtrl.photoBaseUrl;
    $scope.baseUrl = baseUrl;
    $scope.cmportfolio = jsonCMPortfolio;
    $scope.photo = new Array();
    
    
    $scope.init = function(){
         $('#cmPhoto').hoverStatus('<h6><i class="icon-pencil"></i> Change</h6>',{
            persistent:true
        });
    };
    
    
    $scope.addFeaturedPhoto = function(featuredPhoto){

        console.info('<addFeaturedPhoto> - start featuredPhotoid:'+featuredPhoto.photoid);
        console.info('<addFeaturedPhoto> - start featuredPhotoid:'+featuredPhoto.url);
       
        $scope.cmportfolio.photourl = featuredPhoto.url;
        
        $('#cmPhoto').loading(false);
        $scope.$apply();
        //console.info('<addFeaturedPhoto> - end');
    }
    
    $scope.changeFeaturedPhotoInitCallback = function(){
        params = {};
        params.cropPhoto = true;
        params.submitCallback = function(data){
            var photo = angular.fromJson(data);
            $.post(baseUrl + '/castingmanager/setCastingManagerPhoto',{
                Photo: {
                    cmid : $scope.cmportfolio['casting_manager_portfolioid'],
                    photoid : photo.photoid
                }
            },function(){
                rc.addFeaturedPhoto(angular.fromJson(data)); 
                $('#cmPhoto').loading(false);
            });
        };
        params.closeCallback = function(){
            $('#cmPhoto').loading(false);
        };
        //params.order = index;
        sppc.show(params);
    }
    
    $scope.changeFeaturedPhoto = function(){
        $('#cmPhoto').loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent('../common/setPhoto?userid=' + $scope.cmportfolio.userid,function(){
                    sppcInit(function(){
                        rc.changeFeaturedPhotoInitCallback();
                    });
                });
            } else {
                rc.changeFeaturedPhotoInitCallback();
            }
        
        });
    }
    
    $scope.validate = function(){
        if ($('#pwd').val() == $('#pwd2').val()){
            $.post(baseUrl + '/castingmanager/registerCastingManager',{
                CastingManager: {
                    cmid : $scope.cmportfolio['casting_manager_portfolioid'],
                    firstname : $('#firstname').val(),
                    lastname :  $('#lastname').val(),
                    mobile :  $('#mobile').val(),
                    password :  $('#pwd').val()
                }
            },function(){
                window.location = baseUrl + '/castingmanager/viewAccount';
            });
        }else{
            //error message - need to have identical passwords
        }
    }
    
}
