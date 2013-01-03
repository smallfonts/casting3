var acdc;

function admin_console_database_ctrl($scope, $http) {
    
    acdc = $scope;
    
    $scope.output="";
    $scope.loading=false;
    $scope.disabled = false;
    $scope.isDisabled = function() {
        if($scope.disabled){
            return 'disabled';
        } else {
            return '';
        }
    };
    
    $scope.isLoading = function(){
        return true;
    }
    
    $scope.invoke = function(url,confirm){
        if(confirm){
            c3Confirm({
                'header' : 'Bootstrap System',
                'body' : 'This will delete all information in the database. <br/>Do you want to continue?',
                'onAccept' : function(){
                    acdc.invoke(url,false);
                }
            });
            return;
        }
        
        if($scope.disabled){
            return;
        }
        $scope.disabled = true;
        $scope.output = "";
        $scope.loading = true;
        $scope.$apply();
        $http.get(baseUrl+url).success(function(data) {
            $scope.loading = false;
            $scope.output = data;
            $scope.disabled = false;
            $scope.$apply();
        }); 
    }
    
}