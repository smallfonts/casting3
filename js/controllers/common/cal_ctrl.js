/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



var cc;

function fnAdjustTable(){
    
    var colCount=$('#firstTr>td').length; //get total number of column

    var m=0;
    var n=0;
    var brow='mozilla';
    jQuery.each(jQuery.browser, function(i, val) {
        if(val==true){

            brow=i.toString();
        }
    });
    $('.tableHeader').each(function(i){
        if(m<colCount){
            if(brow=='mozilla'){
                $('#firstTd').css("width",$('.tableFirstCol').innerWidth());//for adjusting first td

                $(this).css('width',$('#table_div td:eq('+m+')').innerWidth());//for assigning width to table Header div
            }
            else if(brow=='msie'){
                $('#firstTd').css("width",$('.tableFirstCol').width());

                $(this).css('width',$('#table_div td:eq('+m+')').width()-2);//In IE there is difference of 2 px
            }
            else if(brow=='safari'){
                $('#firstTd').css("width",$('.tableFirstCol').width());

                $(this).css('width',$('#table_div td:eq('+m+')').width());
            }
            else{
                $('#firstTd').css("width",$('.tableFirstCol').width());

                $(this).css('width',$('#table_div td:eq('+m+')').innerWidth());
            }
        }
        m++;
    });

    $('.tableFirstCol').each(function(i){
        if(brow=='mozilla'){
            $(this).css('height',$('#table_div td:eq('+colCount*n+')').outerHeight());//for providing height using scrollable table column height
        }else if(brow=='msie'){
            $(this).css('height',$('#table_div td:eq('+colCount*n+')').innerHeight()-2);
        }else{
            $(this).css('height',$('#table_div td:eq('+colCount*n+')').height());
        }
        n++;
    });

}

//function to support scrolling of title and first column
function fnScroll(){
    $('#divHeader').scrollLeft($('#table_div').scrollLeft());
    $('#firstcol').scrollTop($('#table_div').scrollTop());
}

function cal_ctrl($scope){
    cc = $scope;
    $scope.baseUrl = baseUrl;
    $scope.interval = 15; //slots are in intervals of 30 minutes
    $scope.changes={};
    $scope.selectedGroup = new Array();
    $scope.weekdays = ['sun','mon','tue','wed','thu','fri','sat'];
    $scope.month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $scope.hours = ['12am','1am','2am','3am','4am','5am','6am','7am','8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm','7pm','8pm','9pm','10pm','11pm'];
    $scope.minutes = ['12:00a','12:15a','12:30a','12:45a','1:00a','1:15a','1:30a','1:45a','2:00a','2:15a','2:30a','2:45a','3:00a','3:15a','3:30a','3:45a','4:00a','4:15a','4:30a','4:45a','5:00a','5:15a','5:30a','5:45a','6:00a','6:15a','6:30a','6:45a','7:00a','7:15a','7:30a','7:45a','8:00a','8:15a','8:30a','8:45a','9:00a','9:15a','9:30a','9:45a','10:00a','10:15a','10:30a','10:45a','11:00a','11:15a','11:30a','11:45a','12:00p','12:15p','12:30p','12:45p','1:00p','1:15p','1:30p','1:45p','2:00p','2:15p','2:30p','2:45p','3:00p','3:15p','3:30p','3:45p','4:00p','4:15p','4:30p','4:45p','5:00p','5:15p','5:30p','5:45p','6:00p','6:15p','6:30p','6:45p','7:00p','7:15p','7:30p','7:45p','8:00p','8:15p','8:30p','8:45p','9:00p','9:15p','9:30p','9:45p','10:00p','10:15p','10:30p','10:45p','11:00p','11:15p','11:30p','11:45p'];
    $scope.today = new Date();
    
    $scope.changeDate = function(date){
        var setMonth = typeof date.month != 'undefined' ? date.month : $scope.curDate.getMonth();
        var setYear = typeof date.year != 'undefined' ? date.year : $scope.curDate.getFullYear();
        var setToDate = new Date(setYear,setMonth,1);
        
        $scope.setDate(setToDate);
        $scope.refreshSlots(setToDate);
        $scope.scrollTo(1);
        if(typeof $scope.dateChange != 'undefined'){
            $scope.dateChange(setToDate);
        }
        
    }
    
    $scope.setDate = function(date){
        //if not date parameter is found , the date will be set to current month and year.
        if(!date){
            date = new Date();
        }
        
        $scope.curDate = date;
        $scope.curMonth = date.getMonth();
        $scope.curYear = date.getFullYear();
        
        //Get day of the week in the first day of the month (eg. Mon,Tue,Wed,Thu,Fri...)
        var beginningOfMonth = new Date($scope.curDate.getFullYear(),$scope.curDate.getMonth(),1);
        var curWeekday = beginningOfMonth.getDay();
        
        //Number of days in the current month
        var days = new Date($scope.curDate.getFullYear(),$scope.curDate.getMonth()+1,0).getDate();
        $scope.curDays = new Array();
        
        for(var i = 1; i <= days; i++){
            
            $scope.curDays[$scope.curDays.length] = {
                day: i,
                month: $scope.curMonth,
                year: $scope.curYear,
                weekday: curWeekday
            };
            
            if(++curWeekday == 7) curWeekday =0;
        }
        
        $scope.$apply();
        
        if($scope.isDroppable){
            
            $('.c3_day div').hover(function(event,ui){
                //returns if element hovered is a confirmed slot
                if($(this).find('.c3SlotBox-confirmed').length > 0) {
                    return;
                }
                
                //returns if element hovered is a note
                if($(this).find('.c3SlotBox-note').length > 0) {
                    return;
                }
                
                
                //keeps track of whether hovered element has a droppable instance
                var hasDroppable = $(this).attr('hasDroppable');
                if(!hasDroppable){
                    $(this).attr('hasDroppable','true');
                    $(this).droppable({
                        tolerance: 'pointer',
                        over: function(event,ui){
                            if($(this).find('.c3SlotBox-confirmed').length > 0) {
                                return;
                            }
                            $scope.onOver($(this),event,ui);
                        },
                        out: function(event,ui){
                            if($(this).find('.c3SlotBox-confirmed').length > 0) {
                                return;
                            }
                            $scope.onOut($(this),event,ui);
                        },
                        drop: function(event,ui){
                            $scope.onSlotDrop($(this),event,ui);
                        }
                    });
                }
                $scope.onOver($(this),event,ui);
                
            },function(event,ui){
                var hasDroppable = $(this).attr('hasDroppable');
                if(hasDroppable) $scope.onOut($(this),event,ui);
            });
        }
    }
    
    $scope.confirmMove = function(){
        //determine end time of slot
        $('.c3temp').remove();
        if($scope.dragged && $scope.dragged.tmpSlot != undefined){
            var tmpSlot = $scope.dragged.tmpSlot;
            var startDate = tmpSlot.startDate;
            
            //duration is defined as user confirms slot move via the action menu
            var endDate = new Date(startDate.getTime() + $scope.audition.duration * 60 * 1000);
            
            tmpSlot.endDate = endDate;
            tmpSlot.end = dateToString(endDate,'yyyy-mm-dd hh:mm:ss');
            
            
            var slot = $scope.convertToSlot(startDate,endDate,tmpSlot);
            
            var slotAllocated = false;
            for(var i in $scope.confirmedSlots){
                
                if($scope.confirmedSlots[i].slot.audition_intervieweeid == slot.slot.audition_intervieweeid){
                    slotAllocated = true;
                    $scope.confirmedSlots[i] = slot;
                    break;
                }
            }
            
            if(!slotAllocated){
                $scope.confirmedSlots[$scope.confirmedSlots.length] = slot;
            }
            
            $scope.displaySlot(slot,'confirmed');
            $('#c3CalMoveSlot').hide();
            
            //if slot is dragged from an existing location in the calendar, remove traces of its previous position.
            //Note* slots can be either dragged from within the calendar or from 'unallocated artiste' panel
            if($scope.dragged.id != undefined){
                $('#'+$scope.dragged.id).parent().removeAttr('hasDroppable');
                var slotid = $('#'+$scope.dragged.id).parent().attr('id');
                var size = $('#'+$scope.dragged.id+' .c3Slot').attr('size');
                $('#'+$scope.dragged.id).remove();
                $scope.enableSelectable(slotid,size);
            }
            
            
            if($scope.onDrop){
                $scope.onDrop(slot.slot);
            }
            
            $scope.dragged = undefined;
        }
    }
    
    $scope.onSlotDrop = function(dom,event,ui){
        $scope.dragged.dropped = true;
                            
        //if slot is dropped onto a confirmed slot, return
        if(dom.find('.c3SlotBox-confirmed').length > 0) {
            $scope.dragged.dropped = false;
            return;
        }
                           
        //check if element has been dropped onto a valid holder
        if(dom.hasClass('c3Minutes')){
            $scope.hasDropped = true;
            var name, artiste_portfolioid, photoUrl, audition_intervieweeid;
                                
            if($scope.dragged){
                artiste_portfolioid = $scope.dragged.artiste_portfolioid;
                name = $scope.dragged.artisteName;
                audition_intervieweeid = $scope.dragged.audition_intervieweeid;
                photoUrl = $scope.dragged.photoUrl;
            }
                               
            var timeInterval = dom.attr('time');
            var date = dom.parent().parent().attr('day');
            
            var month = $scope.curMonth;
            var year = $scope.curYear;
                                
            var startDate = new Date();
            startDate.setYear(year);
            startDate.setMonth(month);
            startDate.setDate(date);
            startDate.setMinutes(0);
            startDate.setHours(0);
            startDate.setSeconds(0);
            startDate.setMinutes(timeInterval * 15);
                                
            //end time has not been defined yet
            var tmpSlot = {
                audition_intervieweeid : audition_intervieweeid,
                name : name,
                photoUrl : photoUrl,
                artiste_portfolioid : artiste_portfolioid,
                start : dateToString(startDate,'yyyy-mm-dd hh:mm:ss'),
                startDate : startDate
            }
                                
            $scope.dragged.tmpSlot = tmpSlot;
                                
            if($scope.onDrop){
                $('#c3CalMoveSlot').css({
                    'top':$scope.mouseY,
                    'left':$scope.mouseX + 10
                }).show();
            }
                                
                                
        }
    }
    
    $scope.onOver = function(dom,event,ui){
        event.preventDefault();
        event.stopPropagation();
        
        var rootSlot = dom.attr('rootSlot');

        if(rootSlot){
            dom = $('#'+rootSlot);
        }
       
        
        //get dragged object. if no dragged object found, then exit
        if(!$scope.dragged) return;
        var obj =  $scope.dragged;
        if(obj.dropped) return;
        
        //check if hovered element is over its own slot
        if(obj.id != undefined && (dom.find('.c3SlotBox').attr('id') == obj.id)) return;
        var artisteName = obj.artisteName;
        
        var existingSlot = dom.find(".c3Slot-default");
        var slotHeight;
        if(existingSlot.length > 0){
            existingSlot = existingSlot.first();
            $scope.audition.duration = existingSlot.attr('size') * 15 + '';
            $scope.$apply();
            slotHeight = existingSlot.height();
        } else {
            slotHeight = 26;
        }
        
        var temp = simpleTemplate('c3_cal_temp',{
            '#slotHeight#' : slotHeight,
            '#c3SlotClass#' : 'c3temp',
            '#artisteName#' : artisteName
        });
        dom.append(temp);
    }
    
    $scope.onOut = function(dom,event,ui){
        event.preventDefault();
        event.stopPropagation();
        if(!$scope.dragged || $scope.dragged.dropped) return;
        dom.find('.c3temp').remove();
    }
    
    $scope.scrollCallback = undefined;
    $scope.scrollCache = {
        firstDay : undefined,
        lastDay : undefined
    }
    $scope.scrollTo = function(day){
        $('#table_div').scrollTo('#'+day+'_32',500);
    }

    $scope.scroll = function(){
        
        var posWidth = 102;
        
        //get first date
        var firstElementPos = $('#1_0').position().left;
        var firstDay = Math.floor(Math.abs(firstElementPos) / posWidth) + 1;
        var lastDay = firstDay + 6;
        
        if($scope.scrollCache.firstDay != firstDay){
            $scope.scrollCache.firstDay = firstDay;
            $scope.scrollCache.lastDay = lastDay;
            $scope.scrollCallback({
                month: $scope.curMonth,
                firstDay: firstDay,
                lastDay: lastDay
            });
        }
        
    }
    
    $scope.refreshSlots = function(date){
        var tmpDate = new Date(date.getTime());
        var year = tmpDate.getFullYear();
        var month = tmpDate.getMonth();
        
        //refresh unconfirmedslots
        for(var i in $scope.unconfirmedSlots){
            var slot = $scope.unconfirmedSlots[i];
            var start = slot.start.date;
            if(start.getMonth() == month && start.getFullYear() == year){
                $scope.displaySlot(slot,'unconfirmed');
            }
        }
        
        for(var i in $scope.auditionNotes){
            var slot = $scope.auditionNotes[i];
            var start = slot.start.date;
            if(start.getMonth() == month && start.getFullYear() == year){
                $scope.displaySlot(slot,'note');
            }
        }
        
        for(var i in $scope.selectionSlots){
            var slot = $scope.selectionSlots[i];
            var start = slot.start.date;
            if(start.getMonth() == month && start.getFullYear() == year){
                $scope.displaySlot(slot,'selection');
            }
        }
        
        for(var i in $scope.confirmedSlots){
            var slot = $scope.confirmedSlots[i];
            if(start.getMonth() == month && start.getFullYear() == year){
                $scope.displaySlot(slot,'confirmed');
            }
        }
        
        
        
    }
    
    
    $scope.mouseX;
    $scope.mouseY;
    
    $scope.init = function(params){ 
        
        
        $(document).mousemove( function(e) {
            $scope.mouseX = e.pageX; 
            $scope.mouseY = e.pageY;
        });  
        
        if(!params) params= {};
        $scope.height = typeof params.height != 'undefined' ? params.height : 543;
        $scope.width = typeof params.width != 'undefined' ? params.width : 930;

        //slots that have been selected by auditionees
        $scope.confirmedSlots = typeof params.confirmedSlots != 'undefined' ? params.confirmedSlots : undefined;
        $scope.unconfirmedSlots = typeof params.unconfirmedSlots != 'undefined' ? params.unconfirmedSlots : new Array();
        $scope.selectionSlots = typeof params.selectionSlots != 'undefined' ? params.selectionSlots : undefined;
        $scope.auditionNotes = typeof params.auditionNotes != 'undefined' ? params.auditionNotes : new Array();
        //notes
        $scope.reservedSlots = typeof params.reservedSlots != 'undefined' ? params.reservedSlots : undefined;
        
        $scope.slotChange = typeof params.slotChange != 'undefined' ? params.slotChange : undefined;
        $scope.dateChange = typeof params.dateChange != 'undefined' ? params.dateChange : undefined;
        $scope.onSelectSlot = typeof params.onSelectSlot != 'undefined' ? params.onSelectSlot : undefined;
        $scope.slotPriorities = typeof params.slotPriorities != 'undefined' ? params.slotPriorities : undefined;
        
        //defines if confirmed blocks can be deleted (used for auditions where application period is open)
        $scope.canDeleteCreatedSlots = typeof params.canDeleteCreatedSlots != 'undefined' ? params.canDeleteCreatedSlots : true;
        
        //defines if new blocks of time can be created by selecting the schedule
        //if set to false then calendar will display time blocks as individual slots that will be draggable
        $scope.canCreateSlots = typeof params.canCreateSlots != 'undefined' ? params.canCreateSlots : true;
        $scope.selectedSlots = typeof params.selectedSlots != 'undefined' ? params.selectedSlots : undefined;
        $scope.artisteSlots = typeof params.artisteSlots != 'undefined' ? params.artisteSlots : undefined;
        
        
        if($scope.artisteSlots){
            for(var i in $scope.artisteSlots){
                var tmpSlot = $scope.artisteSlots[i];
                if(tmpSlot.priority == 0){
                    $scope.artisteTopPrioritySlot = tmpSlot;
                }
            }
        }
        
        $scope.slotDuration = typeof params.slotDuration != 'undefined' ? params.slotDuration : undefined;
        $scope.audition = typeof params.audition != 'undefined' ? params.audition : {};
        $scope.durations = ["15","30","45","60","75","90","105","120"];
        $scope.audition.duration = "30";
        //determines type of slots (can be 'block' - blocks of time, 'slot' - individual time slots, 'confirmed' - slots are confirmed)
        $scope.slotType = typeof params.slotType != 'undefined' ? params.slotType : 'block';
        $scope.isDroppable = typeof params.isDroppable != 'undefined' ? params.isDroppable : false;
        $scope.onDrop = typeof params.onDrop != 'undefined' ? params.onDrop : undefined;
        
        if(typeof params.scroll != 'undefined'){
            $scope.scrollCallback = params.scroll;
            $('#table_div').scroll(function(){
                $scope.scroll()
            });
        }
        
        $scope.setDate();
        $scope.$apply();
        fnAdjustTable();
        $('#table_div').scrollTo('#'+$scope.curDate.getDate()+'_32',800);
        
        //process audition notes
        if($scope.auditionNotes != undefined){
            for(var i in $scope.auditionNotes){
                var curSlot = $scope.auditionNotes[i];
                var startDate = stringToDate(curSlot.start,'yyyy-mm-dd hh:mm:ss');
                var endDate = stringToDate(curSlot.end,'yyyy-mm-dd hh:mm:ss');
                var slot = $scope.convertToSlot(startDate,endDate,$scope.auditionNotes[i]);
                $scope.auditionNotes[i] = slot;
            }
        }
        
        //process unconfirmed Slots
        if($scope.unconfirmedSlots != undefined){
            for(var i in $scope.unconfirmedSlots){
                var curSlot = $scope.unconfirmedSlots[i];
                var startDate = stringToDate(curSlot.start,'yyyy-mm-dd hh:mm:ss');
                var endDate = stringToDate(curSlot.end,'yyyy-mm-dd hh:mm:ss');
                var slot = $scope.convertToSlot(startDate,endDate,$scope.unconfirmedSlots[i]);
                $scope.unconfirmedSlots[i] = slot;
            }
        }
        
        //display selected slots
        if($scope.confirmedSlots != undefined){
            for(var i in $scope.confirmedSlots){
                var curSlot = $scope.confirmedSlots[i];
                var startDate = stringToDate(curSlot.start,'yyyy-mm-dd hh:mm:ss');
                var endDate = stringToDate(curSlot.end,'yyyy-mm-dd hh:mm:ss');
                var slot = $scope.convertToSlot(startDate,endDate,$scope.confirmedSlots[i]);
                $scope.confirmedSlots[i] = slot;
            }
        }
        
        //display selected slots
        if($scope.selectionSlots != undefined){
            for(var i in $scope.selectionSlots){
                var curSlot = $scope.selectionSlots[i];
                var startDate = stringToDate(curSlot.start,'yyyy-mm-dd hh:mm:ss');
                var endDate = stringToDate(curSlot.end,'yyyy-mm-dd hh:mm:ss');
                var slot = $scope.convertToSlot(startDate,endDate,$scope.selectionSlots[i]);
                $scope.selectionSlots[i] = slot;
            }
        }
        
        $scope.refreshSlots($scope.curDate);
        $scope.refreshSelectable();
        if($scope.slotChange != undefined) $scope.slotChange();
        
    }
    
    $scope.refreshSelectable = function(){
        if($scope.canCreateSlots){
            $('#c3Selectable').selectable({
                filter: ".c3IsSelectable",
                distance:1,
                stop : function(event,ui){
                    $scope.groupSelected(event);
                }
            });
        }
    }
    
    
    
    $scope.curSelections = {};
    
    //triggered when new timeslots have been selected from the calendar
    //
    /*        
        $scope.tmpGroup[year][month][day][groupArr.length] = {
            id:cell.selected.id, //id of the cell selected
            day: day, //Day (1 = first day)
            year: year, //Full year (1965)
            month: month, //month (starting from 0 = Jan
            time: cell.selected.attributes['time'].value, //time is the multiple of the current time by 30mins (eg. 2 = 2 * 30mins = 0030Hrs)
            date: date //Date of the current cell
            name:cell.selected.attributes['name'].value   //name of time (eg. 1:30a)
        }
     */
    $scope.processData = function(){
        $('.c3Selection').remove();
        for(var i in $scope.tmpGroup){
            // var i denotes the year
            
            for(var x in $scope.tmpGroup[i]){
                //var x denotes the month of the cur year
                var curMonth = $scope.tmpGroup[i][x];
                
                
                for(var y in curMonth){
                    
                    //var y denotes the day of the month
                    var curDay = curMonth[y];
                    
                    var slots = new Array();
                    
                    
                    for(var t = 0 ; t < curDay.length ; t++){
                        var start = curDay[t];
                        var end = curDay[t];
                           
                        while(++t < curDay.length){
                            var tmpDate = curDay[t];
                            if(parseInt(tmpDate.time)-1 == end.time){
                                end = curDay[t]; 
                            } else{
                                t--;
                                break;
                            }       
                        }
                        slots[slots.length] = {
                            start : start,
                            end : end
                        }
                    }
                    
                    for(var s in slots){
                        var start = slots[s].start;
                        var end = slots[s].end;
                    
                        //if start and end are the same, then clone end to a new object
                        if(start.id == end.id){
                            end = $.extend({},end);
                        }
                    
                        //derive the date objects of start and end time
                        var startDate = new Date(i,x,y);
                        startDate.setMinutes(start.time*$scope.interval);
                        start.date = startDate;
  
                        var endDate = new Date(i,x,y);
                        //end time is one interval from its beginning time
                        end.time = parseInt(end.time) + 1;
                        endDate.setMinutes(end.time*$scope.interval);
                        end.date = endDate;

                        //displays a slot fom start to end time on calendar
                        var slot = $scope.convertToSlot(start.date,end.date);
                        //$scope.addSlot(slot);
                    
                    
                        $scope.displaySlot(slot,'block');
            
            
                        //calls a change callback if any
                        if(typeof $scope.slotChange != 'undefined') $scope.slotChange();
                    }
                    
                }
            }
        }
        
        $('#c3CalActions').css({
            'top':$scope.mouseY,
            'left':$scope.mouseX + 10
        }).show();
    }
    
    $scope.convertToSlot = function (startDate,endDate,existingSlot){
        
        var tmpDate = new Date(startDate);
        tmpDate.setMinutes(0);
        tmpDate.setHours(0);
        tmpDate.setSeconds(0);
        
        var y = startDate.getFullYear();
        var m = startDate.getMonth();
        var d = startDate.getDate();
        
        var numStartIntervals = Math.floor((startDate.getTime() - tmpDate.getTime()) / ($scope.interval * 60 * 1000));
        var numEndIntervals = Math.floor((endDate.getTime() - tmpDate.getTime()) / ($scope.interval * 60 * 1000));
        
        var start = {
            id : 'day_'+d+'_'+numStartIntervals,
            year : y,
            month: m,
            day: d,
            time: numStartIntervals,
            date: startDate,
            name: $scope.minutes[numStartIntervals]
        }
        
        var end = {
            id : 'day_'+d+'_'+(numEndIntervals-1),
            year : y,
            month: m,
            day: d,
            time: numEndIntervals,
            date: endDate
        }
        
        end.name = (end.time == $scope.minutes.length) ? $scope.minutes[0] : $scope.minutes[end.time];
        
        //var i = year, x = month, y = day
        if(!$scope.curSelections[y]) $scope.curSelections[y] = new Array();
        if(!$scope.curSelections[y][m]) $scope.curSelections[y][m] = new Array();
        if(!$scope.curSelections[y][m][d]) $scope.curSelections[y][m][d] = new Array();
                    
        var slot = {
            id: Math.floor(Math.random() * 1000000),
            start:start,
            end:end
        }
        
        if(existingSlot){
            slot.slot = existingSlot;
        } else {
            slot.slot = {};
        }
        

        
        return slot;
    }
    
    $scope.deleteSlot = function(internalId,type){
        switch(type){
            case 'unconfirmed':
                var audition_slotid = 0;
                for(var i in $scope.unconfirmedSlots){
                    if($scope.unconfirmedSlots[i].id == internalId){
                        audition_slotid = $scope.unconfirmedSlots[i].slot.audition_slotid;
                        $scope.unconfirmedSlots.splice(i,1);
                        break;
                    }
                }
                
                $.post(baseUrl + '/audition/deleteAuditionSlot',{
                    AuditionSlot: {
                        auditionid : $scope.audition.auditionid,
                        audition_slotid : audition_slotid
                    }
                });
                break;
        }
        
        if($scope.slotChange){
            $scope.slotChange();
        }
    }
    
    //creates a new slot
    $scope.createSlot = function(type){
        
        if(!$scope.canCreateSlots) return;
        var tmpSlotArr = new Array();
        $('.c3Selection').each(function(){
            
            var duration = parseInt($scope.audition.duration) * 60 * 1000;
            var tmpStart = parseInt($(this).attr('start'));
            var end = parseInt($(this).attr('end'));
        
            switch(type){
                case 'unconfirmed':
                    var tmpEnd = tmpStart + duration;
                    while(tmpEnd <= end){
                        //create a slot
                        var slot = $scope.convertToSlot(new Date(tmpStart),new Date(tmpEnd),{});
                        slot.tmpid = new Date().getTime() + Math.floor(Math.random() * 1000);
                        $scope.unconfirmedSlots[$scope.unconfirmedSlots.length] = slot;
                        tmpSlotArr[tmpSlotArr.length] = {
                            auditionid: $scope.audition.auditionid,
                            tmpid: slot.tmpid,
                            start: dateToString(slot.start.date,'yyyy-mm-dd hh:mm:ss'),
                            end: dateToString(slot.end.date,'yyyy-mm-dd hh:mm:ss')
                        }
                        $scope.displaySlot(slot,'unconfirmed');
                        tmpStart = tmpEnd;
                        tmpEnd += duration;
                
                    }
                    break;
                        
                case 'note':
                    var slot = $scope.convertToSlot(new Date(tmpStart),new Date(end),{
                        title : '',
                        text : ''
                    });
                    slot.tmpid = new Date().getTime() + Math.floor(Math.random() * 1000);
                    tmpSlotArr[tmpSlotArr.length] = {
                        auditionid: $scope.audition.auditionid,
                        tmpid: slot.tmpid,
                        title: '',
                        text: '',
                        start: dateToString(slot.start.date,'yyyy-mm-dd hh:mm:ss'),
                        end: dateToString(slot.end.date,'yyyy-mm-dd hh:mm:ss')
                    };
                    $scope.auditionNotes[$scope.auditionNotes.length] = slot;
                    $scope.displaySlot(slot,'note');
                    break;
            }
                
        });
            
        switch(type){
            case 'unconfirmed':
                $.post(baseUrl + '/audition/createAuditionSlot',{
                    NewAuditionSlots: tmpSlotArr
                },function(data){
                    data = angular.fromJson(data);
                    for(var x in data.auditionSlots){
                        var auditionSlot = data.auditionSlots[x];
                        for(var i in $scope.unconfirmedSlots){
                            if($scope.unconfirmedSlots[i].tmpid == auditionSlot.tmpid){
                                var slot = $scope.unconfirmedSlots[i];
                                slot.slot.audition_slotid = auditionSlot.audition_slotid;
                                $scope.$apply();
                                break;
                            }
                        }
                    }
                        
                            
                                                            
                });
                break;
            case 'note':
                $.post(baseUrl + '/audition/createAuditionNote',{
                    AuditionNotes: tmpSlotArr
                },function(data){
                    data = angular.fromJson(data);
                    var notesToEdit = new Array();
                    for(var x in data.auditionNotes){
                        var tmpNote = data.auditionNotes[x];
                        for(var i in $scope.auditionNotes){
                            var slot = $scope.auditionNotes[i];
                            if($scope.auditionNotes[i].tmpid == tmpNote.tmpid){
                                slot.slot.audition_noteid = tmpNote.audition_noteid;
                                notesToEdit[notesToEdit.length] = slot;
                                break;
                            }
                        }
                    }
                                            
                    $scope.editNote(notesToEdit);
                });
                                
                
                
                break;
        }
            
            
        $scope.removeSelection();
        
        if($scope.slotChange){
            $scope.slotChange()
        }
    }
    
    $scope.editNote = function(input){
        
        
        
        //check if audition_noteid exists
        $scope.note = {};
        $scope.note.tmpids = new Array();
        $scope.note.slots = new Array();
        
        
        var left = 0 ;
        var top = 0;
        if(input instanceof Array){
            for (var i in input){
                $scope.note.slots.push(input[i]);
            }
            
            left = $('#'+input[0].id).offset().left;
            top = $('#'+input[0].id).offset().top;
            
        } else {
            for(var i in $scope.auditionNotes){
                if($scope.auditionNotes[i].id == input){
                    $scope.note.slots.push($scope.auditionNotes[i]);
                    var tmpText = $scope.auditionNotes[i].slot.text.replace(/\&quot;/g,'"');
                    $scope.note.text = tmpText;
                    $scope.note.title = $scope.auditionNotes[i].slot.title;
                }
            }
            
            left = $('#'+input).offset().left;
            top = $('#'+input).offset().top;
        }
        
        $scope.$apply();
        $('#c3CalEditNote').css({
            'top':top-200,
            'left':left
        }).show();
        
    }
    
    $scope.cancelEditNote = function(){
        $scope.note = {};
        $('#c3CalEditNote').hide();
    }
    
    $scope.deleteNote = function(){
        
    }
    
    $scope.saveNote = function(){
        
        for(var i in $scope.note.slots){
            
            var slot = $scope.note.slots[i];
            slot.slot.text = $scope.note.text;
            slot.slot.title = $scope.note.title;
            
            //save to db
            $.post(baseUrl + '/audition/saveAuditionNote',{
                'AuditionNote' : {
                    'auditionid' : $scope.audition.auditionid,
                    'audition_noteid' : slot.slot.audition_noteid,
                    'title' : slot.slot.title,
                    'text' : slot.slot.text
                }
            });
            
            //update slot title and text
            var tmpText = $('#'+slot.id).attr('data-content');
            var tmpText1 = tmpText.substring(0,tmpText.indexOf("<div class='c3NoteText'>") + "<div class='c3NoteText'>".length);
            var tmpText2 = tmpText.substring(tmpText.indexOf("</div>"));
            $scope.note.text = $scope.note.text.replace(/\"/g,'&quot;');
            var newText = tmpText1 + $scope.note.text + tmpText2;
            $('#'+slot.id).attr('data-content',newText);
            $('#'+slot.id+' .c3NoteTitle').html($scope.note.title);
        }
        
        $scope.note = {};
        $('#c3CalEditNote').hide();
        
    }
    
    $scope.removeSelection = function(){
        $('.c3Selection').remove();
        $('#c3CalActions').hide();
    }
    
    
    $scope.addSlot = function(slot){
        
        var y = slot.start.year;
        var m = slot.start.month;
        var d = slot.start.day;
        
        //var i = year, x = month, y = day
        $scope.curSelections[y][m][d].push(slot);
    }
    
    /*selected slots are arranged as such
        $scope.tmpGroup[year][month][day][groupArr.length] = {
            id:cell.selected.id, //id of the cell selected
            day: day, //Day (1 = first day)
            year: year, //Full year (1965)
            month: month, //month (starting from 0 = Jan
            time: cell.selected.attributes['time'].value, //time is the multiple of the current time by 30mins (eg. 2 = 2 * 30mins = 0030Hrs)
            date: date //Date of the current cell
            name:cell.selected.attributes['name'].value   //name of time (eg. 1:30a)
        }
     */
    $scope.displaySlot = function(slot,type){
        var start = slot.start;
        var end = slot.end;
        
        var originalSlot = slot.slot;
        var slotLength = Math.floor((end.date - start.date)/($scope.interval * 60 * 1000));
        
        switch(type){
            
            case 'note':
                //display entire time block
                slot.slot.text = slot.slot.text.replace(/\"/g,'&quot;');
                var selectionHtml = simpleTemplate('c3_cal_note',{
                    '#startTimeStamp#' : start.date.getTime(),
                    '#endTimeStamp#' : end.date.getTime(),
                    '#slotHeight#' : 26*slotLength + (slotLength - 1),
                    '#startTime#' : start.name,
                    '#endTime#' : end.name,
                    '#title#' : slot.slot.title,
                    '#text#' : slot.slot.text,
                    '#date#' : dateToString(start.date,'dd MMM yyyy'),
                    '#id#' : slot.id
                });
                
                $('#'+start.id).html(selectionHtml);
                
                $('#'+slot.id).popover({
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
                
                $('#'+slot.id+' header .close').click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    
                    $scope.note = {};
                    $('#c3CalEditNote').hide();
                    $('#'+slot.id).popover('destroy');
                    $('#'+slot.id).parent().remove();
                    
                    $.post(baseUrl+'/audition/deleteAuditionNote',{
                        'AuditionNote' : {
                            'auditionid' : $scope.audition.auditionid,
                            'audition_noteid' : slot.slot.audition_noteid
                        }
                    });
                });
                
                break;
            
            case 'block':
                //display entire time block
                
                var selectionClass = 'c3Selection';
                var selectionHtml = simpleTemplate('c3_cal_block',{
                    '#startTimeStamp#' : start.date.getTime(),
                    '#endTimeStamp#' : end.date.getTime(),
                    '#c3SlotClass#' : selectionClass,
                    '#slotHeight#' : 26*slotLength + (slotLength - 1),
                    '#startTime#' : start.name,
                    '#endTime#' : end.name
                });
                
                $('#'+start.id).html(selectionHtml);
                
                
                break;
                
            case 'selection':
      
                $('#'+start.id).html('<div id="slot_'+start.date.getTime()+'" class="c3SlotBox" style="height:'+27*slotLength+'px;"></div>');
                var startMinuteIndex = start.time;
                var endMinuteIndex = start.time + slotLength;
                var slotHeight = slotLength * 26 + (slotLength-1);

                var slotClass = ' timeSlot';
                var canSelect = true;
                //check if artiste has selected a slot
                if($scope.artisteSlots != undefined && $scope.artisteSlots.length > 0){
                    canSelect = true;
                    if($scope.artisteSlots[0].audition_slotid == originalSlot.audition_slotid){
                        canSelect = false;
                    } else {
                        slotClass += ' c3Slot-faded ';
                        if($scope.audition.reselectable_slots == 0){
                            slotClass += ' c3Slot-unselectable';
                            canSelect = false;
                        }
                    }
                        
                } else {
                    canSelect = true;
                }
                    
                //check if slot has been booked by other artistes
                if($scope.slotPriorities != undefined){
                        
                    var isSelectedByOthers = false;
                        
                    for(var z in $scope.slotPriorities){
                            
                        //check if slot has been already chosen
                        if($scope.slotPriorities[z].audition_slotid == originalSlot.audition_slotid){
                            canSelect = false;
                            isSelectedByOthers = true;
                                

                            //check if chosen slot belongs to artiste
                            if( $scope.artisteTopPrioritySlot != undefined && 
                                $scope.artisteTopPrioritySlot.audition_slotid == $scope.slotPriorities[z].audition_slotid
                                ){
                                    
                                 
                                isSelectedByOthers = false;
                            }
                        }
                    }
                        
                    if(isSelectedByOthers){
                        slotClass += ' c3Slot-error ';
                    } else {
                        slotClass += ' c3Slot-success ';
                    }
                } else {
                    slotClass += ' c3Slot-success ';
                    
                }
                    
                    
                var slotHtml = simpleTemplate('c3_cal_slot',{
                    '#c3SlotClass#' : slotClass,
                    '#startTime#' : $scope.minutes[startMinuteIndex],
                    '#slotid#': originalSlot.audition_slotid,
                    '#internalid#' : slot.id,
                    '#endTime#' : $scope.minutes[endMinuteIndex],
                    '#startTimeStamp#' : start.date.getTime(),
                    '#endTimeStamp#' : startMinuteIndex,
                    '#slotHeight#' : slotHeight
                });
                    
                $('#slot_'+start.date.getTime()).append(slotHtml);
                $('#slot_'+start.date.getTime()+'_'+startMinuteIndex).unbind();
                
                if(canSelect){
                    $('#slot_'+start.date.getTime()+'_'+startMinuteIndex).click(function(){
                        var internalid = $(this).attr('internalid');
                        var slot = "";
                        for(var i in $scope.selectionSlots){
                            if($scope.selectionSlots[i].id == internalid){
                                slot = $scope.selectionSlots[i];
                                break;
                            }
                        }
                        var tmpDate = new Date(parseInt($(this).attr('time')));
                        var strDate = dateToString(tmpDate,'dd MMM yyyy');
                        var confirmText = 'Do you want to select this slot? <br/><strong>'+strDate+' <span style="color:grey;font-size:12px">'+$(this).text()+'</span></strong>';
                        if($scope.audition.reselectable_slots == 0){
                            confirmText += '<br/>Note: Your selection cannot be changed after this';
                        }
                        c3Confirm({
                            header : 'Select Slot',
                            body : confirmText,
                            onAccept : function(){
                                if(typeof cc.onSelectSlot != 'undefined'){
                                    cc.onSelectSlot(slot);
                                }
                                
                                $('.timeSlot').each(function(){
                                    if($(this).attr('audition_slotid') != slot.slot.audition_slotid){
                                        $(this).addClass('c3Slot-faded');
                                        if(cc.audition.reselectable_slots == 0){
                                            $(this).addClass('c3Slot-unselectable');
                                        }
                                    } else {
                                        $(this).removeClass('c3Slot-faded');
                                    }
                                });
                                if(cc.audition.reselectable_slots == 0){
                                    $('.timeSlot').unbind();
                                }
                                
                                
                            }
                        });
                        
                        
                        
                    });
                }
                

                break;
            
            case 'unconfirmed':
                
                //time slots allocated for artiste to choose
                
                var diffMins = Math.floor((end.date.getTime() - start.date.getTime())/ (60*1000));
                var startMinuteIndex = start.time;
                var endMinuteIndex = start.time + slotLength;
                var slotHeight = slotLength * 26 + (slotLength-1);
                
                var slotClass = $scope.confirmedSlots != undefined ? 'c3Slot-default' : 'c3Slot-info';
                var unconfirmedSlotHtml = simpleTemplate('c3_cal_unconfirmed',{
                    '#startTime#' : $scope.minutes[startMinuteIndex],
                    '#slotClass#' : slotClass,
                    '#endTime#' : $scope.minutes[endMinuteIndex],
                    '#startTimeStamp#' : start.date.getTime(),
                    '#endTimeStamp#' : startMinuteIndex,
                    '#internalid#' : slot.id,
                    '#slotid#' : originalSlot.audition_slotid,
                    '#slotHeight#' : slotHeight,
                    '#size#' : slotLength
                });
                
                $scope.disableSelectable(start.id,slotLength);
                    
                $('#'+start.id).prepend(unconfirmedSlotHtml);
                $('#slot_'+start.date.getTime()+'_'+startMinuteIndex+' header .close').click(function(){
                    //deleteSlot
                    var slot = $(this).parent().parent();
                    var size = parseInt(slot.attr('size'));
                    var divId = slot.parent().parent().attr('id');
                    var internalId = slot.attr('internalid');
                    $scope.deleteSlot(internalId,'unconfirmed');
                    $scope.enableSelectable(divId,size);
                    $(this).parent().parent().parent().remove();
                });
                
                break;
            
            case 'confirmed':

                var slotHeight = slotLength * 26 + (slotLength-1);

                //display individual slots

                var confirmedSlotHtml = simpleTemplate('c3_cal_confirmed',{
                    '#portfolioUrl#' : slot.slot.url,
                    '#audition_intervieweeid#' : slot.slot.audition_intervieweeid,
                    '#date#' : dateToString(start.date,'dd MMM yyyy'),
                    '#startTime#' : slot.start.name,
                    '#endTime#' : slot.end.name,
                    '#startTimeStamp#' : start.date.getTime(),
                    '#endTimeStamp#' : startMinuteIndex,
                    '#slotid#' : originalSlot.audition_slotid,
                    '#slotHeight#' : slotHeight,
                    '#photoUrl#' : photoBaseUrl+'/s'+slot.slot.photoUrl,
                    '#artisteName#' : slot.slot.name,
                    '#size#' : slotLength
                });
                    
                $scope.disableSelectable(start.id,slotLength);
                    
                $('#'+start.id).append(confirmedSlotHtml);
                $('#confirmedSlot_'+start.date.getTime()+'_'+startMinuteIndex).unbind();
                $('#confirmedSlot_'+start.date.getTime()+'_'+startMinuteIndex).draggable({
                    opacity:0.7,
                    start:function(){
                        $scope.hasDropped = false;
                        var audition_intervieweeid = $(this).attr('audition_intervieweeid');
                        var artiste = undefined;
                            
                        for(var z in $scope.confirmedSlots){
                            if($scope.confirmedSlots[z].slot.audition_intervieweeid == audition_intervieweeid){
                                var slot = $scope.confirmedSlots[z];
                                artiste = slot.slot;
                                break;
                            }
                        }
                         
                        $scope.dragged = {
                            id : $(this).parent().attr('id'),
                            audition_intervieweeid : audition_intervieweeid,
                            artisteName : artiste.name,
                            artiste_portfolioid : artiste.artiste_portfolioid,
                            photoUrl : artiste.photoUrl
                        };
                            
                        $(this).parent().hide();
                        $(this).parent().addClass('isDragged');
                        $(this).parent().attr('isDragged','true');
                    },
                    stop:function(){
                        setTimeout(function(){
                            if(!cc.hasDropped) {
                                //#this is a hack to trigger a drop event to occupy predefined slots
                                //tail end of 'audition'unconfirmed' slots do not trigger a "drop" event when element is dropped on it
                                
                                //check if c3SlotTempExists
                                var tmpSlot = $('.c3temp');
                                if(tmpSlot.length > 0){
                                    $scope.onSlotDrop(tmpSlot.first().parent());
                                    return;
                                }
                                
                                //#end of hack
                                $scope.onDragStop();
                            }
                        },100);
                    },
                    helper: function(){
                        var html = simpleTemplate('c3_cal_onDrag');
                        return $(html);
                    },
                    cursor:'pointer',
                    cursorAt:{
                        right:-10,
                        bottom:10
                    },
                    appendTo : 'body',
                    zIndex: 1000
                });

                
           
                $('#'+start.id+' .c3Slot').popover({
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
                
                
                break;
        }
    }
    
    $scope.onDragStop = function(){
        var id = $scope.dragged.id;
        $('#'+id).removeAttr('hasDroppable');
        $('#'+id).removeClass('isDragged');
        $('#'+id).removeAttr('isDragged');
        $scope.dragged = undefined;
        $('#'+id).show();
    }
    
    $scope.enableSelectable = function(startid,slotSize){
        var count = 0;
        do{
            var tmpId = startid;
            tmpId = tmpId.split('_');
            tmpId = tmpId[0]+'_'+tmpId[1]+'_'+(parseInt(tmpId[2]) + count);
            $('#'+tmpId).removeAttr('rootSlot');
            $('#'+tmpId).addClass('c3IsSelectable');
            
            count++;
        } while(count < slotSize);
        $scope.refreshSelectable();
    }
    
    $scope.disableSelectable = function(startid,slotSize){
        var count = 0;
        do{
            var tmpId = startid;
            tmpId = tmpId.split('_');
            tmpId = tmpId[0]+'_'+tmpId[1]+'_'+(parseInt(tmpId[2]) + count);
            $('#'+tmpId).attr('rootSlot',startid);
            $('#'+tmpId).removeClass('c3IsSelectable');    
            count++;
        } while(count < slotSize);
        $scope.refreshSelectable();
    }

    $scope.tmpGroup = {};
    $scope.groupSelected = function(e){
        
        var year = $scope.curYear;
        var month = $scope.curMonth;
            
        $scope.tmpGroup = {};
        $scope.tmpGroup[year] = {};
        $scope.tmpGroup[year][month] = new Array();
        
        if(!$('.ui-selected').first().hasClass('c3IsSelectable')){
            return;
        } 
        
        $('.ui-selected').each(function(){
            
            var day = $(this).parent().parent().attr('day');
            var time = $(this).attr('time');
            var divId = $(this).attr('id');
            var name = $(this).attr('name');
            var groupArr = $scope.tmpGroup[year][month][day];
            if(!groupArr){
                $scope.tmpGroup[year][month][day] = new Array();
                groupArr = $scope.tmpGroup[year][month][day];
            }

            $scope.tmpGroup[year][month][day][groupArr.length] = {
                id:divId, //id of the cell selected
                day: day, //Day (1 = first day)
                year: year, //Full year (1965)
                month: month, //month (starting from 0 = Jan
                time: time, //time is the multiple of the current time by 15mins (eg. 2 = 2 * 15mins = 0030Hrs)
                name: name   //name of time (eg. 1:30a)
            }
        });
        $('div .c3Minutes').removeClass('ui-selected');
        $scope.processData();
    }
    
    $scope.isActive = function(month){
        if($scope.month[$scope.curMonth] == month) return 'active';
        return '';
    }
    
    $scope.isToday = function(y,m,d){
        var thisYear = $scope.today.getFullYear();
        if(thisYear == y){
            var thisMonth = $scope.today.getMonth();
            if(thisMonth == m){
                var thisDay = $scope.today.getDate();
                if(thisDay == d) return "background: #FAFAFA";
            }
        }
        return '';
    }
    
    
    $scope.cancelMoveSlot = function(){
        $scope.onDragStop();
        $('.c3temp').remove();
        $('#c3CalMoveSlot').hide();
    }
}

