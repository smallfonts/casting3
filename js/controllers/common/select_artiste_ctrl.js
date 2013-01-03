
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

var sac;
function select_artiste_ctrl($scope) {
    sac = $scope;
    $scope.photoBaseUrl = c3MainCtrl.photoBaseUrl;
    $scope.characters = characters;
    $scope.characters.unshift({
        characterid:0,
        name:'ALL'
    });
    $scope.selectedCharacter = 0;
    $scope.selectedApplicants = new Array();
    $scope.applicants = applicants;
    $scope.addedApplicants = new Array();
    $scope.init = function(){
    }
    
    /* Determines the difference between the original and final selected interviewees, and returns 
     * an object containing added and deleted interviewees
     */
    $scope.diff = function(interviewees){
        var tmpInterviewees = interviewees.slice(0);
        var tmpSelectedApplicants = $scope.selectedApplicants.slice(0);
        //remove common applicants in both interviewees and tmpSelectedCharacters
        for(var i = 0; i < tmpInterviewees.length; i++){
            for(var x = 0 ; x < tmpSelectedApplicants.length ; x++){
                if(tmpInterviewees[i].character_applicationid == tmpSelectedApplicants[x].character_applicationid){
                    tmpInterviewees.splice(i,1);
                    i--;
                    tmpSelectedApplicants.splice(x,1);
                    x--;
                    break;
                }
            }
        }
        
        //compile added artistes
        for(var x in tmpSelectedApplicants){
            tmpSelectedApplicants[x].tmpid = Math.random();
        }

        return {
            added : tmpSelectedApplicants,
            deleted : tmpInterviewees
        };
        
    }
    
    $scope.selectApplicant = function(index){
        $scope.selectedApplicants[$scope.selectedApplicants.length] = $scope.applicants[index];
    }
    
    $scope.unselectApplicant = function(index){
        //if artiste has already been invited, the invitation cannot be withdrawn
        if($scope.selectedApplicants[index].confirmed && !$scope.canUninviteConfirmedArtistes) return;  
        $scope.selectedApplicants.splice(index,1);
    }
    
    $scope.onClose = function(){
        if($scope.closeCallback != undefined){
            $scope.closeCallback();
        }
    }
    
    $scope.isSelected = function(artiste_portfolioid){
        for(var i in $scope.selectedApplicants){
            if($scope.selectedApplicants[i].artiste_portfolioid == artiste_portfolioid) return true;
        }
        return false;
    }
    
    $scope.canUninvite = function(artiste_portfolioid){
        for(var i in $scope.selectedApplicants){
            if($scope.selectedApplicants[i].artiste_portfolioid == artiste_portfolioid){
                if($scope.selectedApplicants[i].confirmed && !$scope.canUninviteConfirmedArtistes)return false;  
            } 
        }
        return true;
    }
    
    $scope.setParams = function(params){
        $scope.canUninviteConfirmedArtistes = typeof params.canUninviteConfirmedArtistes != 'undefined' ? params.canUninviteConfirmedArtistes : true;
        $scope.closeCallback = typeof params.onClose != 'undefined' ? params.onClose : undefined;
        var selectedArtistes = typeof params.selectedArtistes != 'undefined' ? params.selectedArtistes : undefined;
        
        if(selectedArtistes != undefined){
            var tmpArr = new Array();
            //clone selectedArtiste to new array
            for(var i = 0 ; i < selectedArtistes.length; i++){
                selectedArtistes[i].confirmed = true;
                tmpArr[tmpArr.length] = selectedArtistes[i];
            }
            $scope.selectedApplicants = tmpArr;
            $scope.$apply();
        }
        
    }
    
    $scope.show = function(){
        $('#c3_sac_modal').modal('show');
        $('#optgroup').multiSelect();
    }
}
