
/*
 * 
 * 1. After document has been loaded to html body
 * 2. sccInit();
 * 3. scc.show();
 * 4. sppc.setSubmitCallback(function(data){profilePic = data});
 * 
 * 
 * 
 * 
 * 
 */

var iac;
function invite_artiste_ctrl($scope, $http) {
    iac = $scope;
    
    $scope.photoBaseUrl = c3MainCtrl.photoBaseUrl;
    $scope.baseUrl = baseUrl;
    
    //These are parameters which can be set for setVideos
    $scope.setParams = function(params){
        if(params){
            if(params.onCloseCallback) $scope.onCloseCallback = params.onCloseCallback;
            if(params.onSubmitCallback) $scope.onSubmitCallback = params.onSubmitCallback;
            if(params.artiste_portfolioid) $scope.artiste_portfolioid = params.artiste_portfolioid;
            if(params.name) $scope.name = params.name;
            if(params.photoUrl) $scope.photoUrl = params.photoUrl;
        }
    }
    
    $scope.init = function(){
    //init stuff
    };
    
    $scope.show = function(){
        //get casting calls to invite artiste to
        $.post(baseUrl+'/castingmanager/getInvitationList',{
            ArtistePortfolio:{
                artiste_portfolioid : $scope.artiste_portfolioid
            }
        },function(data){
            iac.castingCalls = angular.fromJson(data);
            iac.$apply();
            $('#c3_iac_modal').modal('show'); 
        });
    };
    
    $scope.inviteArtiste = function(casting_callid){
        
        $.post(baseUrl+'/castingmanager/inviteArtiste',{
            CastingCall : {
                casting_callid : casting_callid
            },
            ArtistePortfolio: {
                artiste_portfolioid : $scope.artiste_portfolioid
            },
            Invite:true
        },function(){
            var castingCall = iac.getCastingCall(casting_callid);
            castingCall.invited = true;
            iac.$apply();
        });
        
    }
    
    $scope.uninviteArtiste = function(casting_callid){
        $.post(baseUrl+'/castingmanager/inviteArtiste',{
            CastingCall : {
                casting_callid : casting_callid
            },
            ArtistePortfolio: {
                artiste_portfolioid : $scope.artiste_portfolioid
            },
            Invite:false
        },function(){
            var castingCall = iac.getCastingCall(casting_callid);
            castingCall.invited = false;
            iac.$apply();
        });
    }
    
    $scope.getCastingCall = function(casting_callid){
        for(var i in $scope.castingCalls){
            if($scope.castingCalls[i].casting_callid == casting_callid){
                return $scope.castingCalls[i];
            }
        }
        return null;
    }
    
    $scope.noInvitedCastingCalls = function(){
        for(var i in $scope.castingCalls){
            if($scope.castingCalls[i].invited) return false;
        }
        
        return true;
    }
    
    $scope.noAvailableCastingCalls = function(){
        
        for(var i in $scope.castingCalls){
            if(!$scope.castingCalls[i].invited) return false;
        }
        
        return true;
        
    }
    
    $scope.closeModal = function(){
        if($scope.onCloseCallback){
            $scope.onCloseCallback();
        }
        
        $('#c3_iac_modal').modal('hide'); 
    }
    
    
}
