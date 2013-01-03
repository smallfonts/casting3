
var aac;

function apply_audition_ctrl($scope){
    aac = $scope;
    $scope.audition = audition;
    $scope.artistePortfolio = artistePortfolio;
    $scope.auditionSlots = auditionSlots;
    $scope.unselectableSlots = unselectableSlots;
    $scope.slotPriorities = slotPriorities;
    $scope.auditionIntervieweeSlots = auditionIntervieweeSlots;
    
    $scope.setAuditionIntervieweeSlot = function(audition_slotid,day,month,year,startTimeName,endTimeName){
        
        
        var newSlot = {
            audition_slotid : audition_slotid,
            day: day,
            month: month,
            year: year,
            priority : 0,
            slotClass : 'c3SlotSelection-success',
            auditionid : $scope.audition.auditionid,
            start: startTimeName,
            end: endTimeName
        }
        
        //check if existing audition selection exists
        for(var i = 0; i < $scope.auditionIntervieweeSlots.length; i++){
            var curSelection = $scope.auditionIntervieweeSlots[i];
            if(curSelection != undefined){
                if(curSelection.audition_slotid == newSlot.audition_slotid &&
                    curSelection.interval == newSlot.interval){
                    $scope.auditionIntervieweeSlots[i] = undefined;
                    break;
                }
            }
        }
        
        $scope.auditionIntervieweeSlots[0]= newSlot;
        $scope.$apply();
    }
    
    $scope.save = function(){
        $.post(baseUrl+'/audition/saveApplication',{
            AuditionIntervieweeSlots : $scope.auditionIntervieweeSlots
        },function(data){
            data = angular.fromJson(data);
            processResponse(data);
            if(data.errors){
                $scope.errors = data.errors;
            } else {
                $scope.errors = undefined;
            }
        });
    }
    
    $scope.hasError = function(obj){
        if(obj == undefined) return '';
        return 'header-error';
    }
    
    $scope.getAuditionSlot = function(audition_slotid){
        for(var i in $scope.auditionSlots){
            var auditionSlot = $scope.auditionSlots[i];
            if(auditionSlot.audition_slotid == audition_slotid) return auditionSlot;
        }
        return null;
    }
    
    $scope.init = function(){
        
        /*
         *  Initialize application status + countdown timer
         *
         */
         
        //compute application timestamps 
        $scope.applicationStartMillis = Date.parse(stringToDate($scope.audition.application_start));
        $scope.applicationEndMillis = Date.parse(stringToDate($scope.audition.application_end));
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
    
        
        
        c3MainCtrl.loadContent(baseUrl + '/common/calendarMini',function(){
            mccInit({
                click: function(y,m,d){
                    if(cc.curYear != y || cc.curMonth !=m){
                        var tmpDate = new Date(y,m,d);
                        cc.setDate(tmpDate);
                        cc.refreshSlots(tmpDate);
                    }
                    cc.scrollTo(d);
                }
            });
        },'aac_miniCal');
        setTimeout(function(){
            c3MainCtrl.loadContent(baseUrl + '/common/calendar',function(){
                ccInit({
                    audition:$scope.audition,
                    width:720,
                    height:450,
                    slotType: 'slot',
                    slotPriorities : $scope.slotPriorities,
                    selectionSlots: $scope.auditionSlots,
                    canCreateSlots : false,
                    onSelectSlot : function(data){
                        aac.setAuditionIntervieweeSlot(
                            data.slot.audition_slotid,
                            data.start.day,
                            cc.month[data.start.month],
                            data.start.year,
                            data.start.name,
                            data.end.name
                            );
                        aac.$apply();
                        aac.save();
                    },
                    artisteSlots : $scope.auditionIntervieweeSlots,
                    slotDuration : $scope.audition.duration,
                    dateChange: function(date){
                        mcc.changeDate(date);  
                    },
                    slotChange: function(){
                        var allSlots = cc.curSelections;
                        for(var y in allSlots){
                            //y=year
                            var curYear = allSlots[y];
                            for(var m in curYear){
                                //m=month
                                var curMonth = curYear[m];
                                for(var d in curMonth){
                                    //d=day
                                    var curDay = curMonth[d];
                                    var hasEvent = curDay.length > 0 ? true : false;
                                    mcc.setEvent(y,m,d,hasEvent);
                                }
                            }

                        }
                    },
                    scroll: function(data){
                        mcc.setWeek(data);
                    }
                },function(){
                    for(var i in aac.auditionIntervieweeSlots){
                        var slot = aac.auditionIntervieweeSlots[i];
                        var auditionSlot = "";
                        for(var x in aac.auditionSlots){
                            if(aac.auditionSlots[x].slot.audition_slotid == slot.audition_slotid){
                                auditionSlot = aac.auditionSlots[x];
                                break;
                            }
                        }
                        
                        if(auditionSlot == ""){
                            for(var x in aac.unselectableSlots){
                                if(aac.unselectableSlots[x].audition_slotid == slot.audition_slotid){
                                    var startDate = stringToDate(aac.unselectableSlots[x].start,'yyyy-mm-dd hh:mm:ss');
                                    var endDate = stringToDate(aac.unselectableSlots[x].end,'yyyy-mm-dd hh:mm:ss');
                                    
                                    auditionSlot = cc.convertToSlot(startDate,endDate,aac.unselectableSlots[x]);
                                    
                                }
                            }
                        }
                        
                        aac.setAuditionIntervieweeSlot(
                            auditionSlot.slot.audition_slotid,
                            auditionSlot.start.day,
                            cc.month[auditionSlot.start.month],
                            auditionSlot.start.year,
                            auditionSlot.start.name,
                            auditionSlot.end.name
                            );
                        
                    }
                });
                
            },'audition_calendar');
        },500);
        if ($scope.artistePortfolio.audition_guide == 1){
            var imagesArray = ["select_slot1.png", "select_slot2.png","select_slot3.png", "select_slot4.png", "select_slot5.png", "select_slot6.png"];
            displayGuide(imagesArray);
        }
    }
    
    $scope.saveAndInvite = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
        $scope.changes.AuditionSlots = cc.diff($scope.auditionSlots);
        $scope.changes.AuditionInterviewees = sac.diff($scope.auditionInterviewees);
        $scope.canSave = '';
    }
    
    
        
}
