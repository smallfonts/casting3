var vaac;

function view_all_applications_ctrl($scope, $http) {
    vaac = $scope;
    
    $scope.applications = jsonApplications;
    $scope.invitations = jsonInvitations;
    $scope.auditionInterviewees = jsonAuditionInterviewees;
    $scope.currentMillis = currentMillis;
    $scope.weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    $scope.month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    
    for(var i in $scope.auditionInterviewees){
        var auditionInterviewee = $scope.auditionInterviewees[i];
        //check if audition has expired
        var tmpEnd = stringToDate(auditionInterviewee.audition.application_end,'yyyy-mm-dd');
        var tmpStart = stringToDate(auditionInterviewee.audition.application_start,'yyyy-mm-dd');
        tmpEnd.setDate(tmpEnd.getDate() + 1);
        if(tmpEnd.getTime() <= $scope.currentMillis){
            auditionInterviewee.audition.hasExpired = true;
        } else {
            auditionInterviewee.audition.hasExpired = false;
        }
        
        if(tmpStart.getTime() > $scope.currentMillis){
            auditionInterviewee.audition.hasStarted = false;
        } else {
            auditionInterviewee.audition.hasStarted = true;
        }
        
        
        //convert audition application start and end
        var audStart = stringToDate(auditionInterviewee.audition.application_start,'yyyy-mm-dd');
        var audEnd = stringToDate(auditionInterviewee.audition.application_end,'yyyy-mm-dd');
        audStart = dateToString(audStart,"dd MMM 'yy");
        audEnd = dateToString(audEnd,"dd MMM 'yy");
        auditionInterviewee.audition.start = audStart;
        auditionInterviewee.audition.end = audEnd;
        
        //convert interviewee slot to readable time format
        if(auditionInterviewee.slot != undefined){
            var start = stringToDate(auditionInterviewee.slot.start,'yyyy-mm-dd hh:mm:ss');
            var end = stringToDate(auditionInterviewee.slot.end,'yyyy-mm-dd hh:mm:ss');
            
            auditionInterviewee.slot.date = dateToString(start,'dd MMM yyyy');
            auditionInterviewee.slot.startTime = dateToString(start,'hh:mm');
            auditionInterviewee.slot.endTime = dateToString(end,'hh:mm');    
        }
            
    }
    
    
    $scope.init = function(){
        
        //checks whether server is reachable
        //notifies user when connection is broken
        //code located at /js/lib/check_alive/check_alive.js
        //template located at layouts/main.php
        c3CheckAlive();
        
        //prevent enter key from submitting form
        $('form').keypress(function(e){
            if (e.which == 13) {
                var tagName = e.target.tagName.toLowerCase(); 
                if (tagName !== "textarea") {
                    return false;
                }
            }
        });
        
        
        
        vaac.$apply();
        
       
        
    //End of Init Method
    }
    
    $scope.hasAuditions = function(){
        if ($scope.auditionInterviewees.length > 0){
            return true;
        }
        return false;
    }
    
    $scope.hasApplications = function(){
        if ($scope.applications.length > 0){
            return true;
        }
        return false;
    }
    
    $scope.hasInvitations = function(){
        if ($scope.invitations.length > 0){
            return true;
        }
        return false;
    }
    
    $scope.confirmDeleteInvitation = function(casting_call_invitationid){
        c3Confirm({
            header:"Delete invitation",
            body:"Are you sure you want to delete the invitation?",
            onAccept: function(){
                vaac.deleteInvitation(casting_call_invitationid);
            }
        });
    }
    
    $scope.deleteInvitation = function(casting_call_invitationid){
       
        $.post(baseUrl + '/artiste/deleteInvitation',{
            casting_call_invitationid : casting_call_invitationid
        },function(){
            window.location = baseUrl + '/artiste/viewApplications';
        });
    }
    
    $scope.confirmRejectAudition = function(auditionintid){
        c3Confirm({
            header:"Reject Audition",
            body:"Are you sure you want to reject this audition?",
            onAccept: function(){
                vaac.rejectAudition(auditionintid);
            }
        });
    }
    
    $scope.rejectAudition = function(auditionintid){
       
        $.post(baseUrl + '/artiste/rejectAudition',{
            auditionintid : auditionintid
        },function(){
            window.location = baseUrl + '/artiste/viewApplications';
        });
    }
    
    $scope.getNewInvitations = function(){
        var newInvitations = 0;
       
        for(var i = 0 ; i < $scope.invitations.length; i++){
            if ($scope.invitations[i].statusid == 12){
                newInvitations++;
            }
        }
        return newInvitations;
    }
    
    $scope.getNewAuditions= function(){
        var newAuditions = 0;
       
        for(var i = 0 ; i < $scope.auditionInterviewees.length; i++){
            if ($scope.auditionInterviewees[i].status == 12){
                newAuditions++;
            }
        }
        return newAuditions;
    }
    
/*
    $scope.getDay = function(start){
        var day = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getDay();
        return $scope.weekdays[day];
    }
    
    $scope.getDate = function(start){
        var date = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getDate();
        return date;
    }
    
    $scope.getYear = function(start){
        var year = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getFullYear();
        return year;
    }
    
    $scope.getMonth = function(start){
        var month = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getMonth();
        return $scope.month[month];
    }
    
    $scope.getHours = function(start){
        var hour = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getHours();
        return hour;
    }
    
    $scope.getMinutes = function(start){
        var min = stringToDate(start,'yyyy-mm-dd hh:mm:ss').getMinutes();
        if (min == 0){
            return "00";
        }
        return min;
    }
    */
    
    
    
    
}

