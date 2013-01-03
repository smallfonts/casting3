var vcpc;

function view_cm_portfolio_ctrl($scope, $http) {
    vcpc = $scope;
    $scope.portfolio = jsonPortfolio;
    $scope.formModalLoaded=false;
    
    $scope.open = function(url){
        if(!$scope.formModalLoaded){
            $scope.loadContent('../common/modal',function(){
                mInit(function(){
                    c3_m.show(url);
                });
            });
            $scope.formModalLoaded=true;
        }else{
            c3_m.show(url);
        }
    }
    
    $scope.init = function(){
        $('#c3_profilepic').hoverStatus('<h6><i class="icon-pencil"></i> Change</h6>',{
            persistent:true
        });
    }
    
    
    //change profile pic of casting manager
    
    $scope.addFeaturedPhoto = function(featuredPhoto){
        $scope.portfolio.photourl = featuredPhoto.url;
        $('#c3_profilepic').loading(false);
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
                    cmid : $scope.portfolio['casting_manager_portfolioid'],
                    photoid : photo.photoid
                }
            },function(){
                vcpc.addFeaturedPhoto(angular.fromJson(data)); 
                $('#c3_profilepic').loading(false);
            });
        };
        params.closeCallback = function(){
            $('#c3_profilepic').loading(false);
        };
        //params.order = index;
        sppc.show(params);
    }
    
    $scope.changeFeaturedPhoto = function(){
        $('#c3_profilepic').loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent('../common/setPhoto?userid=' + $scope.portfolio.userid,function(){
                    sppcInit(function(){
                        vcpc.changeFeaturedPhotoInitCallback();
                    });
                });
            } else {
                vcpc.changeFeaturedPhotoInitCallback();
            }
        
        });
    }
    
}