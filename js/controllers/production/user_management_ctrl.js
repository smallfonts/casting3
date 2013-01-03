
var umc;
function user_management_ctrl($scope, $http) {
    umc = $scope;
    
    $scope.phus = jsonProductionHouseUsers;
    
    
    $scope.init = function(){
    //init stuff
    };
    
    $scope.select = function(id){
        if ($("#" + id).is(':checked')){
            $("#" + id).each(function(){
                $("#" + id).removeAttr('checked');
            });
            
        }else{
            $("#" + id).each(function(){
                $("#" + id).attr('checked','checked');
            });
        }
    }
    
    $scope.selectAll = function(){
        var checkAll = $("#masterChkbx").is(':checked');
        if (!checkAll){
            $(".chkbx").each( function() {
                this.checked = false;
            })
        }else{
            $(".chkbx").each( function() {
                this.checked = true;
            })
        }
    }
    
    $scope.suspend = function(){
        $(".chkbx").each( function() {
            if (this.checked == true){
                //suspend this user
                $.post(baseUrl + '/production/suspendUser',{
                    cm_userid : this.id
                },function(data){
                    data = angular.fromJson(data);
                    $scope.$apply();
                    processResponse(data);
                });
            }
        })
        window.location = baseUrl + '/production/usermanagement';
    }
    
    $scope.unsuspend = function(){
        $(".chkbx").each( function() {
            if (this.checked == true){
                //unsuspend this user
                $.post(baseUrl + '/production/unsuspendUser',{
                    cm_userid : this.id
                },function(data){
                    data = angular.fromJson(data);
                    $scope.$apply();
                    processResponse(data);
                });
            }
        })
        window.location = baseUrl + '/production/usermanagement';
    }
    
    $scope.sendInvite = function(){
        var fname = document.getElementById('firstname').value;
        var lname = document.getElementById('lastname').value;
        var em = document.getElementById('email').value;
        
        $.post(baseUrl + '/production/sendInvite',{
            firstname : fname,
            lastname : lname,
            email : em
        },function(data){
            data = angular.fromJson(data);
            if (data.status == "Saved"){
                window.location = baseUrl + '/production/usermanagement';
            }else{
                processResponse(data);
            }
            $scope.$apply();
            processResponse(data);
        });
    }
    
    $scope.resendInvitation = function(){
        
        $(".chkbx").each( function() {
            if (this.checked == true){
                //suspend this user
                $.post(baseUrl + '/production/resendInvitation',{
                    cm_userid : this.id
                },function(data){
                    data = angular.fromJson(data);
                    $scope.$apply();
                    processResponse(data);
                });
            }
        })
    }

    
}
