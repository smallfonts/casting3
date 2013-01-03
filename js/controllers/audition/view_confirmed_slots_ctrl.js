
var vcsc;

function view_confirmed_slots_ctrl($scope){
    vcsc = $scope;
    $scope.audition = audition;
    $scope.slots = slotsEachDay;
    $scope.artistes = auditionInterviewees;
    $scope.weekdays = ['sun','mon','tue','wed','thu','fri','sat'];
    $scope.month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.portfolio = undefined;
    
    $scope.confirmSubmit = function(){
        c3Confirm({
            header: 'Submit Audition',
            body: 'Do you want to continue?',
            onAccept: function(){
                eacc.saveAndInvite();
            }
        });
    }
    
    $scope.init = function(){
        
    }
    
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
    
    $scope.showPortfolio = function(i,j){
        var audition_intervieweeid = $scope.slots[i][j].audition_intervieweeid;
        
        for(var i in $scope.artistes){
            if($scope.artistes[i].audition_intervieweeid == audition_intervieweeid) $scope.portfolio = $scope.artistes[i];
        }
        
        $('#c3rating').rating({
            onClick : function(data){
                $scope.portfolio.characterApplication.rating = data;
            },
            maxvalue : 5,
            curvalue : $scope.portfolio.characterApplication.rating,
        });

    }
    
    $scope.saveFeedback = function(){
        $.post(baseUrl+'/audition/saveFeedback',{
            'CharacterApplication':{
                'character_applicationid': $scope.portfolio.characterApplication.character_applicationid,
                'comments':$scope.portfolio.characterApplication.comments,
                'rating' : $scope.portfolio.characterApplication.rating
            }
        },function(data){
            data = angular.fromJson(data);
            processResponse(data);
        });
    }
    
    $scope.getLanguageProficiency = function(id){
        for (i in $scope.languageProficiencies){
            if ($scope.languageProficiencies[i].language_proficiencyid == id) return $scope.languageProficiencies[i].name;
        }
        
        return 'Unregistered';
    }
    
    $scope.showComma = function(ind){
        if(ind == 0){
            return false;
        }
        return true;
    }
    
    
    
    
        
}
