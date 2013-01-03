
var eacc;

function edit_audition_ctrl_confirmed($scope){
    eacc = $scope;
    $scope.castingCall = castingCall;
    $scope.audition = audition;
    $scope.auditionSlots = auditionSlots;
    $scope.auditionInterviewees = auditionInterviewees;
    $scope.confirmedSlots = slotsEachDay;
    $scope.auditionNotes = auditionNotes;
    $scope.durations = ["15","30","45","60","75","90","105","120"];
    $scope.audition.duration = "30";
    
    //derive unconfiemd Interviewees
    var tmpInterviewees = $scope.auditionInterviewees.slice(0);
    for(var i =0 ; i < tmpInterviewees.length; i++){
        for(var x = 0 ; x < $scope.confirmedSlots.length ; x++){
            for (var y = 0; y < $scope.confirmedSlots[x].length; y++){
                var curSlot = $scope.confirmedSlots[x][y];
                if(curSlot.audition_intervieweeid == tmpInterviewees[i].audition_intervieweeid){
                    tmpInterviewees.splice(i,1);
                    x = $scope.confirmedSlots.length;
                    i--;
                    break;
                }
            }
        }
    }
    
    $scope.unconfirmedInterviewees = tmpInterviewees;
    
    
    
    $scope.totalInterviewees = $scope.auditionInterviewees.length;
    $scope.changes = {
        AuditionInterviewees: new Array(),
        AuditionSlots: new Array()
    };
    
    $scope.availableSlots= 0;
    $scope.durations = ["30","60","90","120","150","180"];

    $scope.change = function(){
        var slotDuration = parseInt($scope.audition.duration) * 60000;
        $scope.availableSlots = 0;
        for(var i in cc.curSelections){
            // i = year
            var curYear = cc.curSelections[i];
            for(var x in curYear){
                //x = month
                var curMonth = curYear[x];
                for(var y in curMonth){
                    //y = day
                    var curDay = curMonth[y];
                    for(var z in curDay){
                        //z = slot
                        var curSlot = curDay[z];
                        $scope.availableSlots += Math.floor((curSlot.end.date - curSlot.start.date) / slotDuration);
                    }
                }
            }
        }
        
        $scope.$apply();
        
    }
    
    $scope.saveAndInvite = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
        $scope.changes.AuditionInterviewees = sac.diff($scope.auditionInterviewees);
        
        $.post($scope.baseUrl+'/audition/saveAndInvite',{
            'Audition':$scope.audition,
            'Changes':$scope.changes
        },function(data){
            $scope.processData(data);
            data = angular.fromJson(data);
            if(data.errors){
                $scope.errors = data.errors;
            } else {
                $scope.errors = undefined;
            }
            $scope.$apply();
        });
    }
    
    $scope.selectIntervieweesOnClose = function(){
        $scope.totalInterviewees = sac.selectedApplicants.length;
        $scope.$apply();
    }
    
    $scope.selectInterviewees = function(){
        sac.show();
    }
    
    $scope.changeTitle = function(){
        $('#c3_title_modal').modal('show');
    }
    
    $scope.hasError = function(obj){
        if(obj == undefined) return '';
        return 'header-error';
    }
    
    $scope.toggleCheckbox = function(type){
        switch(type){
            case 'reselectable_slots':
                if($scope.reselectable_slots == true){
                    $scope.audition.reselectable_slots = 1;
                } else {
                    $scope.audition.reselectable_slots = 0;
                }
                break;
        }
    }
    
    $scope.init = function(){
        
        /*
         *  Initialize application status + countdown timer
         *
         */
         
        //date logic for application_start and end
        initDatePeriod($scope.audition,'application_start','application_end'); 
        /*
        //compute application timestamps 
        $scope.applicationStartMillis = Date.parse(stringToDate($scope.audition.application_start,'yyyy-mm-dd'));
        var tmpEndDate = stringToDate($scope.audition.application_end,'yyyy-mm-dd');
        tmpEndDate.setDate(tmpEndDate.getDate() + 1);
        $scope.applicationEndMillis = Date.parse(tmpEndDate);
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
        */
       
       //process checkbox
        $scope.reselectable_slots = $scope.audition.reselectable_slots == 0 ? false : true;
        
        $('#c3Title').tooltip({
            placement: 'bottom'
        });
        
        //rebuild slotsEachDay for cal ctrl
        var confirmedSlots = new Array();
        for(var x in slotsEachDay){
            for (var y = 0; y < slotsEachDay[x].length ; y++){
                confirmedSlots[confirmedSlots.length] = slotsEachDay[x][y];
            }
        }
        
        c3MainCtrl.loadContent(baseUrl+'/common/selectArtiste/'+$scope.castingCall.url,function(){
            sacInit({
                onClose: function(){
                    eaco.selectIntervieweesOnClose()
                },
                selectedArtistes: $scope.auditionInterviewees,
                canUninviteConfirmedArtistes: false
            });
        });
        
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
        },'eacc_miniCal');
        setTimeout(function(){
            c3MainCtrl.loadContent(baseUrl + '/common/calendar',function(){
                ccInit({
                    width:720,
                    height:450,
                    auditionNotes : $scope.auditionNotes,
                    audition : $scope.audition,
                    unconfirmedSlots: $scope.auditionSlots, //used for unconfirmed slots
                    confirmedSlots:confirmedSlots, //slots that have been confirmed
                    slotDuration: $scope.audition.duration,
                    isDroppable: true,
                    onDrop: function(slot){
                        
                        if(cc.dragged){
                            for(var i in $scope.unconfirmedInterviewees){
                                if($scope.unconfirmedInterviewees[i].audition_intervieweeid == cc.dragged.audition_intervieweeid){
                                    $scope.unconfirmedInterviewees.splice(i,1);
                                    break;
                                }
                            }
                        }
                        
                        var startDate = slot.startDate;
                        var endDate = slot.endDate;

                                
                        $.post(baseUrl+'/audition/allocateIntervieweeSlot',{
                            AuditionIntervieweeSlot : {
                                artiste_portfolioid : slot.artiste_portfolioid,
                                audition_intervieweeid : slot.audition_intervieweeid,
                                auditionid : $scope.audition.auditionid,
                                start : slot.start,
                                end : slot.end
                            }
                        });
                        
                        var daySlots;
                                
                        //delete any existing slots
                        for(var x in vcsc.slots){
                            daySlots = vcsc.slots[x];
                            for(var y in daySlots){
                                if(daySlots[y].audition_intervieweeid == slot.audition_intervieweeid){
                                    daySlots.splice(y,1);
                                    if(daySlots.length == 0){
                                        vcsc.slots.splice(x,1);
                                    }
                                    break;
                                }
                                        
                            }
                        }
                                
                        //add to slotsEachDay
                        var slotStart = stringToDate(slot.start,'yyyy-mm-dd hh:mm:ss');
                        var selectedIndex = -1;
                        for(var x in vcsc.slots){
                            daySlots = vcsc.slots[x];
                                    
                            if(daySlots.length == 0){
                                selectedIndex = x;
                                break;
                            }
                                    
                            //check if slot belongs to current day
                            var start = stringToDate(daySlots[0].start,'yyyy-mm-dd hh:mm:ss');
                            if(start.getFullYear() == slotStart.getFullYear() && start.getMonth() == slotStart.getMonth() && start.getDate() == slotStart.getDate()){
                                selectedIndex = x;
                                break;
                            } else if (start.getFullYear() > slotStart.getFullYear() || start.getMonth() > slotStart.getMonth() || start.getDate() > slotStart.getDate()) {
                                vcsc.slots.splice(x,0,new Array());
                                selectedIndex = x;
                                break;
                            }
                        }
                                
                        if(selectedIndex == -1){
                            vcsc.slots[vcsc.slots.length] = new Array(slot);
                        } else {
                            daySlots = vcsc.slots[selectedIndex];
                            if(daySlots.length == 0){
                                vcsc.slots[selectedIndex].push(slot);
                            } else {
                                var allocated = false;
                                for(var y in daySlots){
                                    var start = stringToDate(daySlots[0].start,'yyyy-mm-dd hh:mm:ss');
                                    if(start.getTime() > slotStart.getTime()){
                                        vcsc.slots[selectedIndex].splice(y,0,slot);
                                        allocated =true;
                                        break;
                                    }
                                }
                                if(!allocated){
                                    vcsc.slots[selectedIndex].push(slot);
                                }
                            }
                                    
                        }
                                
                        for(var y = 0; y < vcsc.slots.length; y++){
                            if(vcsc.slots[y].length == 0){
                                vcsc.slots.splice(y,1);
                                y--;
                            }
                        }
                        
                        $scope.$apply();
                        vcsc.$apply();
                    },
                    dateChange: function(date){
                        mcc.changeDate(date);  
                    },
                    scroll: function(data){
                        mcc.setWeek(data);
                    }
                });
            },'audition_calendar');
        },500);
        
        $('.interviewee').draggable({
            opacity:0.7,
            start: function(event,ui){
                $scope.dragged = {
                    audition_intervieweeid : event.currentTarget.attributes['audition_intervieweeid'].value,
                };
                
                cc.dragged = {
                    artiste_portfolioid : event.currentTarget.attributes['artiste_portfolioid'].value,
                    audition_intervieweeid : event.currentTarget.attributes['audition_intervieweeid'].value,
                    photoUrl : event.currentTarget.attributes['photoUrl'].value,
                    url : event.currentTarget.attributes['url'].value,
                    artisteName : event.currentTarget.attributes['artisteName'].value    
                }
            },
            stop: function(event,ui){
                //#this is a hack to trigger a drop event to occupy predefined slots
                //tail end of 'audition'unconfirmed' slots do not trigger a "drop" event when element is dropped on it
                                
                //check if c3SlotTempExists
                var tmpSlot = $('.c3temp');
                if(tmpSlot.length > 0){
                    cc.onSlotDrop(tmpSlot.first().parent());
                    return;
                }
                                
                //#end of hack
            },
            
            helper:function(){
                var html = simpleTemplate('interviewee_onDrag');
                return $(html);
            },
            appendTo : 'body',
            zIndex: 1000,
            cursor:'pointer',
            cursorAt:{
                right:-10,
                bottom:10
            }
        });
        
        $('.interviewee').popover({
            offset: 10,
            trigger: 'manual',
            html: true,
            placement: 'right',
            template: '<div class="popover" onmouseover="clearTimeout(timeoutObj);$(this).mouseleave(function() {$(this).hide();});"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
        }).mouseenter(function(e) {
            $(this).popover('show');
        }).mouseleave(function(e) {
            var ref = $(this);
            timeoutObj = setTimeout(function(){
                ref.popover('hide');
            }, 50);
        });
        
       
    }
    
    $scope.saveDraft = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
    }
    
    $scope.processData = function(data){
        data = angular.fromJson(data);
        processResponse(data);
        
        $scope.canSave = '';
        $scope.$apply();
    }
    
        
}
