function view_account_ctrl($scope, $http) {
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
}