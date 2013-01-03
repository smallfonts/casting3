function login_ctrl($scope, $http) {
    
    //View API and description at /common/modal.php
    //
    //
    $scope.formModalLoaded=false;
    $scope.open = function(url){
        if(!$scope.formModalLoaded){
            $scope.loadContent(baseUrl+'/common/modal',function(){
                mInit(function(){
                    c3_m.show(url);
                });
            });
            $scope.formModalLoaded=true;
        }else{
            c3_m.show(url);
        }
    }

}