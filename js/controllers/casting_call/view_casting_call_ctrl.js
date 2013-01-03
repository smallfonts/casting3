var vccc;

function fromMysqlDate(stringDate){
    if(!stringDate) return;
    var stringDate = stringDate.split('-');
    return stringDate[2] + '/' + stringDate[1] + '/' + stringDate[0];
}

function view_casting_call_ctrl($scope, $http) {
    vccc = $scope;
    $scope.baseUrl = baseUrl;
    $scope.castingCall = {
        data: jsonCastingCall
    };
    
   $scope.roleId = jsonRoleid;
    
    $scope.characters = jsonCharacters;
    
    //Language input
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.dataLanguages = new Array();
    $scope.init = function() {
        
        /*
         *  Initialize application status + countdown timer
         *
         */
         
        //compute application timestamps 
        $scope.applicationStartMillis = Date.parse(stringToDate($scope.castingCall.data.application_start));
        $scope.applicationEndMillis = Date.parse(stringToDate($scope.castingCall.data.application_end));
        $scope.currentMillis = currentMillis;
         
        $scope.countdown = {
            days : 0,
            hours : 0,
            minutes : 0,
            seconds : 0
        }
        
        $scope.countdown.totalSeconds = undefined;
        if($scope.applicationStartMillis > $scope.currentMillis){
            //countdown to application start
            $scope.countdown.totalSeconds = Math.floor(($scope.applicationStartMillis - $scope.currentMillis) / 1000);
            $scope.countdown.status = 'countdown_to_open';
        } else if ($scope.applicationEndMillis > $scope.currentMillis){
            //countdown to application end
            $scope.countdown.totalSeconds = Math.floor(($scope.applicationEndMillis - $scope.currentMillis) / 1000);
            $scope.countdown.status = 'countdown_to_close';
        } else {
            $scope.countdown.status ='application_closed';
        }
        
        if(typeof $scope.countdown.totalSeconds != 'undefined'){
            $scope.countdownInterval = setInterval(function(){
                $scope.countdown.totalSeconds -= 1;
                $scope.countdown.days = Math.floor($scope.countdown.totalSeconds / 86400);
                var daysRemainder = $scope.countdown.totalSeconds % 86400;
                var hoursRemainder = daysRemainder % 3600;
                var minutesRemainder = hoursRemainder % 60;
        
                $scope.countdown.hours = Math.floor(daysRemainder / 3600) + "";
                if($scope.countdown.hours.length == 1) $scope.countdown.hours = "0"+$scope.countdown.hours;
                $scope.countdown.minutes = Math.floor(hoursRemainder / 60) + "";
                if($scope.countdown.minutes.length == 1) $scope.countdown.minutes = "0"+$scope.countdown.minutes;
                $scope.countdown.seconds = minutesRemainder + "";
                if($scope.countdown.seconds.length == 1) $scope.countdown.seconds = "0"+$scope.countdown.seconds;
                $scope.$apply();
                
                if($scope.countdown.totalSeconds == 0){
                    if($scope.countdown.status == 'countdown_to_open'){
                        $scope.countdown.status = 'countdown_to_close';
                        $scope.countdown.totalSeconds = Math.floor(($scope.applicationEndMillis - $scope.currentMillis) / 1000);
                    } else {
                        $scope.countdown.status = 'application_closed';
                        clearInterval($scope.countdownInterval);
                        $scope.$apply();
                    }
                }
                
            },1000);
        
        }
    
    
        //format dates
        $scope.castingCall.data.application_start = fromMysqlDate($scope.castingCall.data.application_start);
        $scope.castingCall.data.application_end = fromMysqlDate($scope.castingCall.data.application_end);
        $scope.castingCall.data.project_start = fromMysqlDate($scope.castingCall.data.project_start);
        $scope.castingCall.data.project_end = fromMysqlDate($scope.castingCall.data.project_end);
        $scope.castingCall.data.audition_start = fromMysqlDate($scope.castingCall.data.audition_start);
        $scope.castingCall.data.audition_end = fromMysqlDate($scope.castingCall.data.audition_end);
        
        //populate requirements of characters
        for(var i in $scope.characters){
            character = $scope.characters[i];
            character.requirements = new Array();
            
            /*
             *  Populate Gender Requirement
             */
            
            if(typeof character.gender != 'undefined') character.requirements.push({
                name:'Gender',
                data:character.gender
            });
            
            /*
             * Populate Age Requirement
             * 
             */
            
            if(typeof character.age_start != 'undefined' || typeof character.age_end != 'undefined'){
                if(typeof character.age_end != 'undefined'){
                    character.requirements.push({
                        name:'Age',
                        data:character.age_start + ' to ' + character.age_end
                    });
                } else if (typeof character.age_end == 'undefined') {
                    character.requirements.push({
                        name:'Age',
                        data: '> ' + character.age_start
                    });
                } else {
                    character.requirements.push({
                        name:'Age',
                        data: '< ' + character.age_end
                    });
                }
            }
            
            /*
             * Populate language requirement
             * 
             */
            
            if(character.characterLanguages.length > 0){
                var languageData = "";
                for(var x in character.characterLanguages){
                    characterLanguage = character.characterLanguages[x];
                    var language = $scope.getLanguage(characterLanguage.languageid);
                    var languageProficiency = $scope.getLanguageProficiency(characterLanguage.language_proficiencyid);
                    languageData += ', '+language.name+' (' + languageProficiency.name+ ')';
                }
                languageData = languageData.substring(1);
                character.requirements.push({
                    name:'Languagues',
                    data:languageData
                })
            }
            
            /*
             * Populate Nationality Requirement
             * 
             */
            
            if(typeof character.nationality != 'undefined'){
                character.requirements.push({
                    name: 'Nationality',
                    data: character.nationality
                });
            }
            
            /*
             *  Populate Ethnicity Requirement
             */
            
            if(typeof character.ethnicity.name != 'undefined'){
                character.requirements.push({
                    name: 'Ethnicity',
                    data: character.ethnicity.name
                });
            }
            
            /*
             *  Populate Skills Requirement
             */
            
            if(character.skills.length > 0){
                var skillString = '';
                for(var i in character.skills){
                    skillString += ', ' + character.skills[i].name;
                }
                skillString = skillString.substring(1);
                character.requirements.push({
                    name: 'Skills',
                    data: skillString
                });
            }
            
            /*
             * Populate Other Requirement
             * 
             */
            
            if(character.otherRequirements.length > 0){
                for (var i in character.otherRequirements){
                    character.requirements.push({
                        name:character.otherRequirements[i].requirement,
                        data:character.otherRequirements[i].desc
                    });
                }
            }
        }
        
        $("div.apply").click(
            function()
            {
                window.location = $(this).attr("url");
                event.preventDefault();
            });
        $scope.$apply();
        
    }
    
    $scope.sendMessage = function(){
        window.open(baseUrl+'/messages/new?to[]='+$scope.castingCall.data.castingManagerPortfolio.userid,'','width=500,height=500');
    }
    
    $scope.getLanguageProficiency = function(language_proficiencyid){
        for (var i in $scope.languageProficiencies){
            if ($scope.languageProficiencies[i].language_proficiencyid == language_proficiencyid) return $scope.languageProficiencies[i];
        }
        
        return null;
    }
    
    $scope.getLanguage = function(languageid){
        for(var i in jsonAllLanguages){
            if(jsonAllLanguages[i].languageid == languageid) return jsonAllLanguages[i];
        }
        return null;
    }
    
    $scope.isArtiste = function(){
        if ($scope.roleId == 1){
            return true;
        }
        return false;
    }
    
}
