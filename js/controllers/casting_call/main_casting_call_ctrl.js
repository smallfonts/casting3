var mccc;

function getParameterByName(name,getParam)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(getParam);
    if(results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

function main_casting_call_ctrl($scope, $http) {
    mccc = $scope;
    $scope.baseUrl = baseUrl;
    $scope.castingCalls = jsonCastingCalls;
    
    $scope.init = function() {
        $('#c3_accordion').accordion();
        return;
    }
    
    $scope.getCastingCallClass = function(statusid){
        switch(statusid){
            case '5':
                return 'alert-success';
            case '6':
                return 'alert-warning';
            case '7':
                return 'alert-error';
        }
    }
    
     $scope.confirmDeleteCastingCall = function(casting_callid){
         c3Confirm({
            header : "Delete Casting Call",
            body : "Are you sure you want to delete this casting call?",
            onAccept: function(){
                mccc.deleteCastingCall(casting_callid)
            }
        });
    }
    
    $scope.deleteCastingCall = function(casting_callid){
        
        //reqires confirmation
        
        $.post(baseUrl+'/castingCall/deleteCastingCall',{
            CastingCall: {
                casting_callid : casting_callid
            },
            casting_callid : casting_callid
        },function(){
            var casting_callid = getParameterByName('casting_callid',this.data);
            for(var i in mccc.castingCalls){
                if(mccc.castingCalls[i].casting_callid == casting_callid){
                    mccc.castingCalls.splice(i,1);
                    mccc.$apply();
                    return;
                }
            }
        });
    }
    
}
