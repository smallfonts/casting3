/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var mcc;

function mini_cal_ctrl($scope){
    mcc = $scope;
    $scope.today = new Date();
    $scope.selectedDate = $scope.today;
    $scope.selectedYear = $scope.today.getFullYear();
    $scope.selectedMonth = $scope.today.getMonth();
    $scope.months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $scope.weeks = [0,1,2,3,4,5,6];
    $scope.days = new Array();
    $scope.events = new Array();
    
    $scope.setEvent = function(year,month,day,hasEvent){
        if(!$scope.events[year]) $scope.events[year] = new Array();
        if(!$scope.events[year][month]) $scope.events[year][month] = new Array();
        
        $scope.events[year][month][day] = hasEvent;
    }
    
    $scope.reset = function(){
        $scope.events = new Array();
    }
    
    $scope.hasEvent = function(day){
        if($scope.events[$scope.selectedYear]
           && $scope.events[$scope.selectedYear][$scope.selectedMonth]
           && $scope.events[$scope.selectedYear][$scope.selectedMonth][day]) return 'hasEvent';
        
        return '';
    }
    
    $scope.nextMonth=function(nextMonth){
        var newDate = new Date($scope.selectedDate);
        newDate.setMonth(newDate.getMonth() + nextMonth);
        $scope.setDate(newDate);
    }
    
    $scope.changeDate=function(date){
        $scope.setDate(date);
    }
    
    $scope.setWeek=function(data){
        $scope.firstDay = data.firstDay;
        $scope.lastDay = data.lastDay;
        $scope.viewedMonth = data.month;
        $scope.$apply();
    }
    
    $scope.isCurrentlyViewed = function(date){
        if($scope.selectedMonth != $scope.viewedMonth) return '';
        if(date >= $scope.firstDay && date <= $scope.lastDay) return 'isCurrentlyViewed';
        return '';
    }
    
    $scope.setDate = function(date){
        $scope.selectedDate = typeof date == 'undefined' ? $scope.today : date;
        $scope.selectedMonth = $scope.selectedDate.getMonth();
        $scope.selectedYear = $scope.selectedDate.getFullYear();
        var tmpDate = new Date($scope.selectedDate);
        var numDays = new Date(tmpDate.getFullYear(),tmpDate.getMonth()+1,0).getDate();
        var firstDay = tmpDate.setDate(1);
        firstDay = tmpDate.getDay();
        $scope.days = new Array([],[],[],[],[],[]);
        //front padding
        for(var x = 0 ; x < firstDay ; x++){
            $scope.days[0].push({});
        }
        
        //days
        var dayNum = 1;
        for(var i = firstDay ; i < numDays+firstDay; i++){
            
            var tmpWeek = Math.floor(i/7);
            
            $scope.days[tmpWeek].push({
                date:dayNum
            });
            
            dayNum++;
        }
        $scope.$apply();
        
    }
    
    $scope.dayClicked = function(day){
        $scope.clickCallback($scope.selectedYear,$scope.selectedMonth,day);
    }
    
    $scope.isTodayClass = function(day){
        if($scope.selectedMonth == $scope.today.getMonth()
            && day == $scope.today.getDate()) {
            return 'today'; 
        }
        
        return '';
    }
    
    $scope.init = function(params){
        if(typeof params.click != 'undefined'){
            $scope.clickCallback = params.click;
        }
        $scope.setDate();
        
    }
}

