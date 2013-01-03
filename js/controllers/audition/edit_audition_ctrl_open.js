
var eaco;

function edit_audition_ctrl_open($scope){
    eaco = $scope;
    $scope.castingCall = castingCall;
    $scope.audition = audition;
    $scope.auditionSlots = auditionSlots;
    $scope.auditionInterviewees = auditionInterviewees;
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
    
    $scope.confirmSubmit = function(){
        c3Confirm({
            header: 'Submit Audition',
            body: 'Do you want to continue?',
            onAccept: function(){
                eaco.saveAndInvite();
            }
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
    
    $scope.init = function(){
        
        $('#c3Title').tooltip({
            placement: 'bottom'
        });
        
        //date logic for application_start and end
        initDatePeriod(eaco.audition,'application_start','application_end');
        
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
        },'eaco_miniCal');
        setTimeout(function(){
            c3MainCtrl.loadContent(baseUrl + '/common/calendar',function(){
                ccInit({
                    width:720,
                    height:450,
                    slots: $scope.auditionSlots,
                    slotType: 'block',
                    canDeleteCreatedSlots: false,
                    slotChange: function(){
                        eaco.change();
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
                    dateChange: function(date){
                        mcc.changeDate(date);  
                    },
                    scroll: function(data){
                        mcc.setWeek(data);
                    }
                });
            },'audition_calendar');
        },500);
    }
    
    $scope.saveAndInvite = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
        $scope.changes.AuditionSlots = cc.diff($scope.auditionSlots);
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
    
    $scope.saveDraft = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
        //derive changes in audition slots
        $scope.changes.AuditionSlots = cc.diff($scope.auditionSlots);
        $scope.changes.AuditionInterviewees = sac.diff($scope.auditionInterviewees);
        
        $.post($scope.baseUrl+'/audition/saveDraft',{
            'Audition':$scope.audition,
            'Changes':$scope.changes
        },function(data){
            $scope.processData(data);
        });
    }
    
    $scope.processData = function(data){
        data = angular.fromJson(data);
        processResponse(data);
        if(data.changes){
            if(data.changes.auditionSlots){
                    
                //deletes slots from $scope.auditionSlots
                if(data.changes.auditionSlots.deleted){
                    for(var s in data.changes.auditionSlots.deleted){
                        var deletedSlot = data.changes.auditionSlots.deleted[s];
                        for(var i in $scope.auditionSlots){
                            if($scope.auditionSlots[i].audition_slotid == deletedSlot.audition_slotid){
                                $scope.auditionSlots.splice(i,1);
                                break;
                            }
                        }
                    }
                }
                    
                //adds new slots
                if(data.changes.auditionSlots.added){
                    for(var s in data.changes.auditionSlots.added){
                        var addedSlot = data.changes.auditionSlots.added[s];
                        for(var i in $scope.changes.AuditionSlots.added){
                            var curTmpSlot = $scope.changes.AuditionSlots.added[i]
                            if(addedSlot.tmpid == curTmpSlot.tmpid){
                                curTmpSlot.audition_slotid = addedSlot.audition_slotid;
                                $scope.auditionSlots[$scope.auditionSlots.length] = curTmpSlot;
                            }
                        }
                    }
                }
            }
                
            if(data.changes.auditionInterviewees){
                //deletes audition interviewees from $scope.auditionInterviewees
                for(var i in data.changes.auditionInterviewees.deleted){
                    var deleted = data.changes.auditionInterviewees.deleted[i];
                    for(var x in $scope.auditionInterviewees){
                        if($scope.auditionInterviewees[x].audition_intervieweeid == deleted.audition_intervieweeid){
                            $scope.auditionInterviewees.splice(x,1);
                            break;
                        }
                    }
                }
                    
                //adds audition interviewees to $scope.auditionInterviewees
                for(var i in data.changes.auditionInterviewees.added){
                    var added = data.changes.auditionInterviewees.added[i];
                    for(var x in $scope.changes.AuditionInterviewees.added){
                        var curInterviewee = $scope.changes.AuditionInterviewees.added[x];
                        if(curInterviewee.tmpid == added.tmpid){
                            curInterviewee.audition_intervieweeid = added.audition_intervieweeid;
                            $scope.auditionInterviewees[$scope.auditionInterviewees.length] = curInterviewee;
                        }
                    }
                }
            }
        }
        
        $scope.canSave = '';
        $scope.$apply();
    }
    
        
}
