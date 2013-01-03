/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var ic;
function index_ctrl ($scope){
    ic = $scope;
    $scope.role = role;
    $scope.altRole = altRole;
    
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

function appendRole(aRole){
    switch(aRole[0].toUpperCase()){
        case 'A':case 'E':case 'I':case 'O':case 'U':
            aRole = 'an ' + aRole;
            break;
        
        default:
            aRole = 'a '+aRole;
    }
    
    return aRole;
}
