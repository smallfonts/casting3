var eppc;

function edit_prod_portfolio_ctrl($scope, $http) {
    eppc = $scope;
    $scope.portfolio = jsonPortfolio;
    $scope.profilePic = jsonProfilePic;
    
    
    $scope.init = function(){
        $('#c3_profilepic').tooltip({placement: 'bottom'});
        $('#urlTip').tooltip({placement:"top"});
    }
    
    //loads modal to change profile pic
    //view: '../common/setPhoto'
    //
    //

    $scope.changeProfilePicInitCallback = function(){
        params = {};
        params.cropPhoto = true;
        params.aspectRatio = 1;
        params.submitCallback = function(data){
            console.info(data);
            var photo = angular.fromJson(data);
            $.post(baseUrl + '/common/setProfilePic',{
                Photo: {
                    photoid: photo.photoid
                }
            },function(){
                if(data) eppc.profilePic = photo;
                $('#c3_profilepic').loading(false);
                eppc.$apply(); 
            });
        };
        params.closeCallback = function(){
            $('#c3_profilepic').loading(false);
        };
        sppc.show(params);
    }
    
    $scope.changeProfilePic = function(){
        $('#c3_profilepic').loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent('../common/setPhoto',function(){
                    sppcInit(function(){
                        eppc.changeProfilePicInitCallback();
                    });
                });
            } else {
                eppc.changeProfilePicInitCallback();
            }
        });
    }
}

