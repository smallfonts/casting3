/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var c3_m;
function modal_ctrl($scope,$http){
    c3_m = $scope;
    $scope.formId;
    $scope.header;
    $scope.body;
    
    $scope.show = function(url,callback){
        
        $http.get(url).success(function(data) {
            $scope.body = data;
            $scope.$apply();
            $scope.header = $('#header_name').val();
            $scope.bindReturnKey();
            $('#c3_m_modal').modal('show');
        }); 
        
        if(callback){
            callback();
        }
        
    }
    
    $scope.bindReturnKey = function(){
        $("#modal-body form").delegate('input','keypress',function(e) {
            if(e.which == 13) {
                $scope.submit();
            }
        });
    }

    $scope.submit = function(){
        $.post($("#modal-body form").attr('action'), $("#modal-body form").serialize(), function(data){
            var isJson = true;
            var obj;
            try{
                obj = angular.fromJson(data);
            } catch(e){
                isJson = false;
            }
            
            if(isJson && obj.status == 'successful'){
                processResponse(obj);
                
                $('#c3_m_modal').modal('hide');
            } else {
                $scope.body = data;
                $scope.$apply();
                $scope.bindReturnKey();
            }
        });
    }
}

