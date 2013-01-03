<script>
    function mccInit(params){
        var miniCalScriptLoader;
        miniCalScriptLoader = new c3ScriptLoader();
        miniCalScriptLoader.addJavascript(baseUrl+'/js/controllers/common/mini_cal_ctrl.js');
        miniCalScriptLoader.load(function(){
            angular.bootstrap($('#c3_mcc'), []);
            if(params!=undefined){
                mcc.init(params);
            }
            $('#c3_mcc').show();
        });
    }
</script>

<style>

    .isCurrentlyViewed {
        background: #F2F2F2;
    }
    
    .hasEvent {
        color: #E01B1B;
        font-weight: 900;
    }
    
    .today {
        font-weight:900;
        border:1px dotted black;
    }

    .calendar-mini tr td {
        text-align:center;
    }

    .calendar-mini * .month {
        padding-left: 5px;
        text-align: left;
    }
</style>

<div id="c3_mcc" ng-controller="mini_cal_ctrl" style="display:none;height:161px">
    <table class="calendar-mini" style="width:100%;">
        <tr>
            <td class="month" colspan="4"><h6>{{months[selectedMonth]}} {{selectedYear}}</h6></td><td colspan="3" style="text-align:right;padding-right:10px"><i class="icon-chevron-left c3-click" ng-click="nextMonth(-1)"></i><i class="icon-chevron-right c3-click" ng-click="nextMonth(1)"></i></td>
        </tr>
        <tr>
            <td><h5>S</h5></td>
            <td><h5>M</h5></td>
            <td><h5>T</h5></td>
            <td><h5>W</h5></td>
            <td><h5>T</h5></td>
            <td><h5>F</h5></td>
            <td><h5>S</h5></td>
        </tr>
        <tr ng-repeat="week in weeks"><td ng-repeat="day in days[week]" class="minical_day c3-click {{isTodayClass(day.date)}} {{hasEvent(day.date)}} {{isCurrentlyViewed(day.date)}}" ng-click="dayClicked(day.date)">{{day.date}}</td></tr>
    </table>
</div>