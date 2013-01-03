
var eac;

function edit_audition_ctrl($scope){
    eac = $scope;
    $scope.castingCall = castingCall;
    $scope.audition = audition;
    $scope.auditionSlots = auditionSlots;
    $scope.auditionNotes = auditionNotes;
    $scope.castingManagerPortfolio = castingManagerPortfolio;
    $scope.auditionInterviewees = auditionInterviewees;
    $scope.totalInterviewees = $scope.auditionInterviewees.length;
    $scope.changes = {
        AuditionInterviewees: new Array(),
        AuditionSlots: new Array()
    };
    
    $scope.durations = ["15","30","45","60","75","90","105","120"];
    $scope.audition.duration = "30";
    
    $scope.confirmSubmit = function(){
        c3Confirm({
            header: 'Submit Audition',
            body: 'Once submitted, some parts of the audition can no longer be modified. <br/>Do you want to continue?',
            onAccept: function(){
                eac.saveAndInvite();
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
        
        
        $('#c3Title').tooltip({
            placement: 'bottom'
        });
        
        //process checkbox
        $scope.reselectable_slots = $scope.audition.reselectable_slots == 0 ? false : true;
        
        //date logic for application_start and end
        initDatePeriod(eac.audition,'application_start','application_end');
        
        c3MainCtrl.loadContent(baseUrl+'/common/selectArtiste/'+$scope.castingCall.url,function(){
            sacInit({
                onClose: function(){
                    eac.selectIntervieweesOnClose()
                },
                selectedArtistes: $scope.auditionInterviewees
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
        },'eac_miniCal');
        setTimeout(function(){
            c3MainCtrl.loadContent(baseUrl + '/common/calendar',function(){
                ccInit({
                    width:720,
                    height:450,
                    unconfirmedSlots: $scope.auditionSlots,
                    auditionNotes : $scope.auditionNotes,
                    audition: eac.audition,
                    slotChange: function(){
                        eac.auditionSlots = cc.unconfirmedSlots;
                        var allSlots = cc.unconfirmedSlots;
                        
                        mcc.reset();
                        
                        for(var y in allSlots){
                            var hasEvent = true;
                            var tmpDate = allSlots[y].start.date;
                            var year = tmpDate.getFullYear();
                            var month = tmpDate.getMonth();
                            var day = tmpDate.getDate();
                            mcc.setEvent(year,month,day,hasEvent);
                        }
                        
                        mcc.$apply();
                        
                        
                        
                        $scope.$apply();
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
        
        
        if ($scope.castingManagerPortfolio.audition_guide == 1){
            var imagesArray = ["create_audition_1.png", "create_audition_2.png", "create_audition_3b.png", "create_audition_3.png", "create_audition_4.png", "create_audition_5.png", "create_audition_6.png", "create_audition_7.png"];
            displayGuide(imagesArray);
        }
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
    
    $scope.saveDraft = function(){
        if($scope.canSave == 'disabled') return;
        $scope.canSave = 'disabled';
        //derive changes in audition slots
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
    
    $scope.change = function(){
        cc.$apply();
    }
    
        
}
