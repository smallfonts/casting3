<script>
    function ccInit(params,callback){
        var calScriptLoader;
        calScriptLoader = new c3ScriptLoader();
        calScriptLoader.addJavascript(baseUrl+'/js/controllers/common/cal_ctrl.js');
        calScriptLoader.addJavascript(baseUrl+'/js/lib/jquery.scrollTo-1.4.3.1-min.js');
        calScriptLoader.load(function(){
            angular.bootstrap($('#c3_cc'), []);
            $("#c3_cc").show();
            if(params!=undefined){
                cc.init(params);
                if(callback!=undefined){
                    callback();
                }
            }
        });
    }
</script>

<style>
    
    .c3NoteText {
        text-align: left;
    }
    
    .c3Selection h5,
    .c3Selection_confirmed h5{
        font-size: 0.7em;
        margin:2px;
        margin-left:5px;
        color: #269DFF;
    }

    .c3Selection .close,
    .c3Selection_confirmed .close{
        margin:2px;
        margin-right:5px;
    }

    .c3Selection,.c3Selection_confirmed {
        position:absolute;
        width:100px;
        background:#B8EEFF;
        border: #47D4FF solid 2px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        z-index:100;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        z-index:1;
    }

    .c3Time div {
        height:104px;
        text-align:center;
        border:1px solid #EDEDED;
        border-left:none;
        padding:1px;
    }


    .c3_day div .c3Minutes {
        height:25px;
        border:1px solid #EDEDED;
        z-index:999999999999999;
    }


    .c3Minutes:hover, .c3Droppable{
        background:#EDEDED;
    }

    .c3SlotBox{
        position:absolute;
        width:100px;
        margin-left:1px;
        margin-top:0px;
        border: 2px;
        z-index:100;
    }

    .c3Slot {
        opacity:0.9;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 2px solid;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        padding-left:2px;
        padding-right:2px;
        width:99px;
        color:white;
        background-color: #49AFCD;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5BC0DE), to(#2F96B4));
        background-image: -webkit-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: -o-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: linear-gradient(to bottom, #5BC0DE, #2F96B4);
        background-image: -moz-linear-gradient(top, #5BC0DE, #2F96B4);
        background-repeat: repeat-x;
        border-color: #2F96B4 #2F96B4 #1F6377;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        height: 26px;
        -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        margin-bottom:1px;
    }

    .c3Slot-faded {
        opacity:0.4;
    }

    .c3Slot-info{
        color: white;
        background-color: #49AFCD;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5BC0DE), to(#2F96B4));
        background-image: -webkit-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: -o-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: linear-gradient(to bottom, #5BC0DE, #2F96B4);
        background-image: -moz-linear-gradient(top, #5BC0DE, #2F96B4);
        background-repeat: repeat-x;
        border-color: #2F96B4 #2F96B4 #1F6377;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    }

    .c3Slot-default{
        color: #333;
        background-color: whiteSmoke;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(white), to(#E6E6E6));
        background-image: -webkit-linear-gradient(top, white, #E6E6E6);
        background-image: -o-linear-gradient(top, white, #E6E6E6);
        background-image: linear-gradient(to bottom, white, #E6E6E6);
        background-image: -moz-linear-gradient(top, white, #E6E6E6);
        background-repeat: repeat-x;
        border: 1px solid #BBB;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        border-color: #E6E6E6 #E6E6E6 #BFBFBF;
    }

    .c3Slot-error {
        background-color: #DA4F49;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#EE5F5B), to(#BD362F));
        background-image: -webkit-linear-gradient(top, #EE5F5B, #BD362F);
        background-image: -o-linear-gradient(top, #EE5F5B, #BD362F);
        background-image: linear-gradient(to bottom, #EE5F5B, #BD362F);
        background-image: -moz-linear-gradient(top, #EE5F5B, #BD362F);
        background-repeat: repeat-x;
        border-color: #BD362F #BD362F #802420;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    }

    .c3Slot-warning{
        background-color: #FAA732;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FBB450), to(#F89406));
        background-image: -webkit-linear-gradient(top, #FBB450, #F89406);
        background-image: -o-linear-gradient(top, #FBB450, #F89406);
        background-image: linear-gradient(to bottom, #FBB450, #F89406);
        background-image: -moz-linear-gradient(top, #FBB450, #F89406);
        background-repeat: repeat-x;
        border-color: #F89406 #F89406 #AD6704;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    }

    .c3Slot-success {
        background-color: #5BB75B;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62C462), to(#51A351));
        background-image: -webkit-linear-gradient(top, #62C462, #51A351);
        background-image: -o-linear-gradient(top, #62C462, #51A351);
        background-image: linear-gradient(to bottom, #62C462, #51A351);
        background-image: -moz-linear-gradient(top, #62C462, #51A351);
        background-repeat: repeat-x;
        border-color: #51A351 #51A351 #387038;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    }

    .c3Slot-temp {
        background-color: #FAA732;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FBB450), to(#F89406));
        background-image: -webkit-linear-gradient(top, #FBB450, #F89406);
        background-image: -o-linear-gradient(top, #FBB450, #F89406);
        background-image: linear-gradient(to bottom, #FBB450, #F89406);
        background-image: -moz-linear-gradient(top, #FBB450, #F89406);
        background-repeat: repeat-x;
        border-color: #F89406 #F89406 #AD6704;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    }


    .c3Slot:hover{
        cursor:pointer;
    }

    .c3Slot header{
        font-size:9px;
    }

    .ui-selecting { background: #FECA40; }
    .ui-selecting .c3Selection_confirmed { 
        background: #F5B0C7;
        border-color: #E01B6A;
    }

    .ui-selecting .c3Selection_confirmed h5{ 
        color: #E01B6A;
    }

    .c3Slot-unselectable:hover {
        cursor:not-allowed;
    }
    
</style>

<span id="c3_cc" ng-controller="cal_ctrl" style="display:none">

    <table style="width:{{width}}px">
        <tr>
            <td style="width:100px;padding-right:20px;vertical-align:top">
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" style="padding-left:5px;width:25px" ng-click="changeDate({year:curDate.getFullYear()-1})"><i class="icon-chevron-left icon-white"></i></button>
                    <button class="btn btn-primary disabled">{{curDate.getFullYear()}}</button>
                    <button class="btn btn-primary" style="padding-left:5px;width:25px" ng-click="changeDate({year:curDate.getFullYear()+1})"><i class="icon-chevron-right icon-white"></i></button>
                </div>
            </td>
            <td>
                <ul class="nav nav-pills">
                    <li class="{{isActive(curMonth)}}" ng-repeat="curMonth in month">
                        <a ng-click="changeDate({month:$index})">{{curMonth}}</a>
                    </li>
                </ul>
            </td></tr>
    </table>

    <table id="c3_cal" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td id="firstTd" style="text-align:center">
                <h6>Time</h6>
            </td>
            <td style="border:1px #EDEDED solid;background:white">
                <div id="divHeader" style="overflow:hidden;width:{{width-20}}px;">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td ng-repeat="day in curDays">
                                <div style="width:100px;border:1px #EDEDED solid;border-top:none;border-bottom:none;{{isToday(day.year,day.month,day.day)}}">
                                    <h6 style="text-align:center" >{{day.day}} {{month[curMonth]}}</h6>
                                    <h6 style="text-align:center" >{{weekdays[day.weekday]}}</h6>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="height:5px"><td colspan="2"></td></tr>
        <tr style="border:1px #EDEDED solid;background:white;">
            <td valign="top">
                <div id="firstcol" style="overflow:hidden;height:{{height}}px">
                    <table width="100px" cellspacing="0" cellpadding="0" border="0" >
                        <tr>
                            <td class="c3Time tableFirstCol">
                                <div >
                                    <h6>12am</h6>
                                </div>
                                <div >
                                    <h6>1am</h6>
                                </div>
                                <div >
                                    <h6>2am</h6>
                                </div>
                                <div >
                                    <h6>3am</h6>
                                </div>
                                <div >
                                    <h6>4am</h6>
                                </div>
                                <div >
                                    <h6>5am</h6>
                                </div>
                                <div >
                                    <h6>6am</h6>
                                </div>
                                <div >
                                    <h6>7am</h6>
                                </div>
                                <div >
                                    <h6>8am</h6>
                                </div>
                                <div >
                                    <h6>9am</h6>
                                </div>
                                <div >
                                    <h6>10am</h6>
                                </div>
                                <div >
                                    <h6>11am</h6>
                                </div>
                                <div >
                                    <h6>12pm</h6>
                                </div>
                                <div >
                                    <h6>1pm</h6>
                                </div>
                                <div >
                                    <h6>2pm</h6>
                                </div>
                                <div >
                                    <h6>3pm</h6>
                                </div>
                                <div >
                                    <h6>4pm</h6>
                                </div>
                                <div >
                                    <h6>5pm</h6>
                                </div>
                                <div >
                                    <h6>6pm</h6>
                                </div>
                                <div >
                                    <h6>7pm</h6>
                                </div>
                                <div >
                                    <h6>8pm</h6>
                                </div>
                                <div >
                                    <h6>9pm</h6>
                                </div>
                                <div >
                                    <h6>10pm</h6>
                                </div>
                                <div >
                                    <h6>11pm</h6>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td valign="top" style="padding-left:1px;">
                <div id="table_div" style="overflow:scroll;width:{{width}}px;height:{{height+18}}px;position:relative" onscroll="fnScroll()" >
                    <table id="c3Selectable" id="calendar_content" width="500px" cellspacing="0" cellpadding="0" border="0" >
                        <tr id="firstTr">
                            <td class="c3_day" ng-repeat="day in curDays" style="{{isToday(day.year,day.month,day.day)}}" day="{{day.day}}">
                                <div style="width:102px">
                                    <span id="{{day.day}}_0"></span>
                                    <div id="day_{{day.day}}_0" class="c3Minutes c3IsSelectable"  time="0" name="12:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_1"></span>
                                    <div id="day_{{day.day}}_1" class="c3Minutes c3IsSelectable"  time="1" name="12:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_2"></span>
                                    <div id="day_{{day.day}}_2" class="c3Minutes c3IsSelectable"  time="2" name="12:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_3"></span>
                                    <div id="day_{{day.day}}_3" class="c3Minutes c3IsSelectable"  time="3" name="12:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_4"></span>
                                    <div id="day_{{day.day}}_4" class="c3Minutes c3IsSelectable"  time="4" name="1:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_5"></span>
                                    <div id="day_{{day.day}}_5" class="c3Minutes c3IsSelectable"  time="5" name="1:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_6"></span>
                                    <div id="day_{{day.day}}_6" class="c3Minutes c3IsSelectable"  time="6" name="1:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_7"></span>
                                    <div id="day_{{day.day}}_7" class="c3Minutes c3IsSelectable"  time="7" name="1:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_8"></span>
                                    <div id="day_{{day.day}}_8" class="c3Minutes c3IsSelectable"  time="8" name="2:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_9"></span>
                                    <div id="day_{{day.day}}_9" class="c3Minutes c3IsSelectable"  time="9" name="2:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_10"></span>
                                    <div id="day_{{day.day}}_10" class="c3Minutes c3IsSelectable"  time="10" name="2:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_11"></span>
                                    <div id="day_{{day.day}}_11" class="c3Minutes c3IsSelectable"  time="11" name="2:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_12"></span>
                                    <div id="day_{{day.day}}_12" class="c3Minutes c3IsSelectable"  time="12" name="3:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_13"></span>
                                    <div id="day_{{day.day}}_13" class="c3Minutes c3IsSelectable"  time="13" name="3:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_14"></span>
                                    <div id="day_{{day.day}}_14" class="c3Minutes c3IsSelectable"  time="14" name="3:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_15"></span>
                                    <div id="day_{{day.day}}_15" class="c3Minutes c3IsSelectable"  time="15" name="3:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_16"></span>
                                    <div id="day_{{day.day}}_16" class="c3Minutes c3IsSelectable"  time="16" name="4:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_17"></span>
                                    <div id="day_{{day.day}}_17" class="c3Minutes c3IsSelectable"  time="17" name="4:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_18"></span>
                                    <div id="day_{{day.day}}_18" class="c3Minutes c3IsSelectable"  time="18" name="4:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_19"></span>
                                    <div id="day_{{day.day}}_19" class="c3Minutes c3IsSelectable"  time="19" name="4:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_20"></span>
                                    <div id="day_{{day.day}}_20" class="c3Minutes c3IsSelectable"  time="20" name="5:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_21"></span>
                                    <div id="day_{{day.day}}_21" class="c3Minutes c3IsSelectable"  time="21" name="5:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_22"></span>
                                    <div id="day_{{day.day}}_22" class="c3Minutes c3IsSelectable"  time="22" name="5:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_23"></span>
                                    <div id="day_{{day.day}}_23" class="c3Minutes c3IsSelectable"  time="23" name="5:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_24"></span>
                                    <div id="day_{{day.day}}_24" class="c3Minutes c3IsSelectable"  time="24" name="6:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_25"></span>
                                    <div id="day_{{day.day}}_25" class="c3Minutes c3IsSelectable"  time="25" name="6:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_26"></span>
                                    <div id="day_{{day.day}}_26" class="c3Minutes c3IsSelectable"  time="26" name="6:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_27"></span>
                                    <div id="day_{{day.day}}_27" class="c3Minutes c3IsSelectable"  time="27" name="6:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_28"></span>
                                    <div id="day_{{day.day}}_28" class="c3Minutes c3IsSelectable"  time="28" name="7:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_29"></span>
                                    <div id="day_{{day.day}}_29" class="c3Minutes c3IsSelectable"  time="29" name="7:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_30"></span>
                                    <div id="day_{{day.day}}_30" class="c3Minutes c3IsSelectable"  time="30" name="7:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_31"></span>
                                    <div id="day_{{day.day}}_31" class="c3Minutes c3IsSelectable"  time="31" name="7:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_32"></span>
                                    <div id="day_{{day.day}}_32" class="c3Minutes c3IsSelectable"  time="32" name="8:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_33"></span>
                                    <div id="day_{{day.day}}_33" class="c3Minutes c3IsSelectable"  time="33" name="8:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_34"></span>
                                    <div id="day_{{day.day}}_34" class="c3Minutes c3IsSelectable"  time="34" name="8:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_35"></span>
                                    <div id="day_{{day.day}}_35" class="c3Minutes c3IsSelectable"  time="35" name="8:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_36"></span>
                                    <div id="day_{{day.day}}_36" class="c3Minutes c3IsSelectable"  time="36" name="9:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_37"></span>
                                    <div id="day_{{day.day}}_37" class="c3Minutes c3IsSelectable"  time="37" name="9:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_38"></span>
                                    <div id="day_{{day.day}}_38" class="c3Minutes c3IsSelectable"  time="38" name="9:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_39"></span>
                                    <div id="day_{{day.day}}_39" class="c3Minutes c3IsSelectable"  time="39" name="9:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_40"></span>
                                    <div id="day_{{day.day}}_40" class="c3Minutes c3IsSelectable"  time="40" name="10:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_41"></span>
                                    <div id="day_{{day.day}}_41" class="c3Minutes c3IsSelectable"  time="41" name="10:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_42"></span>
                                    <div id="day_{{day.day}}_42" class="c3Minutes c3IsSelectable"  time="42" name="10:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_43"></span>
                                    <div id="day_{{day.day}}_43" class="c3Minutes c3IsSelectable"  time="43" name="10:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_44"></span>
                                    <div id="day_{{day.day}}_44" class="c3Minutes c3IsSelectable"  time="44" name="11:00a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_45"></span>
                                    <div id="day_{{day.day}}_45" class="c3Minutes c3IsSelectable"  time="45" name="11:15a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_46"></span>
                                    <div id="day_{{day.day}}_46" class="c3Minutes c3IsSelectable"  time="46" name="11:30a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_47"></span>
                                    <div id="day_{{day.day}}_47" class="c3Minutes c3IsSelectable"  time="47" name="11:45a" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_48"></span>
                                    <div id="day_{{day.day}}_48" class="c3Minutes c3IsSelectable"  time="48" name="12:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_49"></span>
                                    <div id="day_{{day.day}}_49" class="c3Minutes c3IsSelectable"  time="49" name="12:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_50"></span>
                                    <div id="day_{{day.day}}_50" class="c3Minutes c3IsSelectable"  time="50" name="12:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_51"></span>
                                    <div id="day_{{day.day}}_51" class="c3Minutes c3IsSelectable"  time="51" name="12:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_52"></span>
                                    <div id="day_{{day.day}}_52" class="c3Minutes c3IsSelectable"  time="52" name="1:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_53"></span>
                                    <div id="day_{{day.day}}_53" class="c3Minutes c3IsSelectable"  time="53" name="1:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_54"></span>
                                    <div id="day_{{day.day}}_54" class="c3Minutes c3IsSelectable"  time="54" name="1:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_55"></span>
                                    <div id="day_{{day.day}}_55" class="c3Minutes c3IsSelectable"  time="55" name="1:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_56"></span>
                                    <div id="day_{{day.day}}_56" class="c3Minutes c3IsSelectable"  time="56" name="2:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_57"></span>
                                    <div id="day_{{day.day}}_57" class="c3Minutes c3IsSelectable"  time="57" name="2:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_58"></span>
                                    <div id="day_{{day.day}}_58" class="c3Minutes c3IsSelectable"  time="58" name="2:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_59"></span>
                                    <div id="day_{{day.day}}_59" class="c3Minutes c3IsSelectable"  time="59" name="2:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_60"></span>
                                    <div id="day_{{day.day}}_60" class="c3Minutes c3IsSelectable"  time="60" name="3:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_61"></span>
                                    <div id="day_{{day.day}}_61" class="c3Minutes c3IsSelectable"  time="61" name="3:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_62"></span>
                                    <div id="day_{{day.day}}_62" class="c3Minutes c3IsSelectable"  time="62" name="3:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_63"></span>
                                    <div id="day_{{day.day}}_63" class="c3Minutes c3IsSelectable"  time="63" name="3:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_64"></span>
                                    <div id="day_{{day.day}}_64" class="c3Minutes c3IsSelectable"  time="64" name="4:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_65"></span>
                                    <div id="day_{{day.day}}_65" class="c3Minutes c3IsSelectable"  time="65" name="4:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_66"></span>
                                    <div id="day_{{day.day}}_66" class="c3Minutes c3IsSelectable"  time="66" name="4:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_67"></span>
                                    <div id="day_{{day.day}}_67" class="c3Minutes c3IsSelectable"  time="67" name="4:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_68"></span>
                                    <div id="day_{{day.day}}_68" class="c3Minutes c3IsSelectable"  time="68" name="5:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_69"></span>
                                    <div id="day_{{day.day}}_69" class="c3Minutes c3IsSelectable"  time="69" name="5:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_70"></span>
                                    <div id="day_{{day.day}}_70" class="c3Minutes c3IsSelectable"  time="70" name="5:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_71"></span>
                                    <div id="day_{{day.day}}_71" class="c3Minutes c3IsSelectable"  time="71" name="5:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_72"></span>
                                    <div id="day_{{day.day}}_72" class="c3Minutes c3IsSelectable"  time="72" name="6:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_73"></span>
                                    <div id="day_{{day.day}}_73" class="c3Minutes c3IsSelectable"  time="73" name="6:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_74"></span>
                                    <div id="day_{{day.day}}_74" class="c3Minutes c3IsSelectable"  time="74" name="6:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_75"></span>
                                    <div id="day_{{day.day}}_75" class="c3Minutes c3IsSelectable"  time="75" name="6:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_76"></span>
                                    <div id="day_{{day.day}}_76" class="c3Minutes c3IsSelectable"  time="76" name="7:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_77"></span>
                                    <div id="day_{{day.day}}_77" class="c3Minutes c3IsSelectable"  time="77" name="7:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_78"></span>
                                    <div id="day_{{day.day}}_78" class="c3Minutes c3IsSelectable"  time="78" name="7:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_79"></span>
                                    <div id="day_{{day.day}}_79" class="c3Minutes c3IsSelectable"  time="79" name="7:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_80"></span>
                                    <div id="day_{{day.day}}_80" class="c3Minutes c3IsSelectable"  time="80" name="8:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_81"></span>
                                    <div id="day_{{day.day}}_81" class="c3Minutes c3IsSelectable"  time="81" name="8:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_82"></span>
                                    <div id="day_{{day.day}}_82" class="c3Minutes c3IsSelectable"  time="82" name="8:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_83"></span>
                                    <div id="day_{{day.day}}_83" class="c3Minutes c3IsSelectable"  time="83" name="8:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_84"></span>
                                    <div id="day_{{day.day}}_84" class="c3Minutes c3IsSelectable"  time="84" name="9:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_85"></span>
                                    <div id="day_{{day.day}}_85" class="c3Minutes c3IsSelectable"  time="85" name="9:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_86"></span>
                                    <div id="day_{{day.day}}_86" class="c3Minutes c3IsSelectable"  time="86" name="9:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_87"></span>
                                    <div id="day_{{day.day}}_87" class="c3Minutes c3IsSelectable"  time="87" name="9:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_88"></span>
                                    <div id="day_{{day.day}}_88" class="c3Minutes c3IsSelectable"  time="88" name="10:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_89"></span>
                                    <div id="day_{{day.day}}_89" class="c3Minutes c3IsSelectable"  time="89" name="10:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_90"></span>
                                    <div id="day_{{day.day}}_90" class="c3Minutes c3IsSelectable"  time="90" name="10:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_91"></span>
                                    <div id="day_{{day.day}}_91" class="c3Minutes c3IsSelectable"  time="91" name="10:45p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_92"></span>
                                    <div id="day_{{day.day}}_92" class="c3Minutes c3IsSelectable"  time="92" name="11:00p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_93"></span>
                                    <div id="day_{{day.day}}_93" class="c3Minutes c3IsSelectable"  time="93" name="11:15p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_94"></span>
                                    <div id="day_{{day.day}}_94" class="c3Minutes c3IsSelectable"  time="94" name="11:30p" >
                                    </div>
                                </div><div style="width:102px">
                                    <span id="{{day.day}}_95"></span>
                                    <div id="day_{{day.day}}_95" class="c3Minutes c3IsSelectable"  time="95" name="11:45p" >
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
        </tr>
    </table>

    <div id="c3CalEditNote" class="dropdown clearfix" style="position:absolute;display:none;z-index:9999999999999999;">
        <ul class="dropdown-menu" style="padding-top:0px;display: block; position: static; margin-bottom: 5px; width: 220px;">
            <li style="padding-left:10px;margin-bottom:5px;" class="c3Slot-default">
                <h6><i class="icon-comment"></i> Edit Note</h6>
            </li>
            <li style="padding-left:10px;">
                <div class="input-prepend">
                    <div class="add-on">Title</div>
                    <input type="text" ng-model="note.title" maxLength="20" style="margin-left:-5px;width:150px" />
                </div>

                <textarea style="height:80px;width:183px;resize:none" ng-model="note.text"></textarea>

                <button class="btn btn-primary btn-small" ng-click="cancelEditNote()">Cancel</button>
                <button class="btn btn-primary btn-small" ng-click="saveNote()">Done</button>
            </li>
        </ul>
    </div>


    <div id="c3CalMoveSlot" class="dropdown clearfix" style="position:absolute;display:none;z-index:100">
        <ul class="dropdown-menu" style="padding-top:0px;display: block; position: static; margin-bottom: 5px; width: 220px;">
            <li style="padding-left:14px;margin-bottom:5px;" class="c3Slot-default">
                <table>
                    <tr>
                        <td><h6><i class="icon-cog"></i>Slot Duration </h6></td>
                        <td style="padding-left:10px;padding-top:3px;">
                            <div class="input-append">
                                <select ng-model="audition.duration" ng-options="i as i for i in durations" style="padding:0px;height:20px;font-size:12px;width:50px;margin:0px;margin-right:-5px"></select>
                                <span class="add-on" style="margin:0px;padding:0px;padding-left:3px;padding-right:3px;font-size:12px;height:18px">min</span>
                            </div></td>
                    </tr>
                </table>
            </li>
            <li>
                <a id="c3CreateSlot" ng-click="confirmMove()" href="#"><i class="icon-share-alt"></i> Move here</a> 
            </li>
            <li class="divider"></li>
            <li><a href="" ng-click="cancelMoveSlot()">Cancel</a></li>
        </ul>
    </div>

    <div id="c3CalActions" class="dropdown clearfix" style="position:absolute;display:none;z-index:100">
        <ul class="dropdown-menu" style="padding-top:0px;display: block; position: static; margin-bottom: 5px; width: 220px;">
            <li style="padding-left:14px;margin-bottom:5px;" class="c3Slot-default">
                <table>
                    <tr>
                        <td><h6><i class="icon-cog"></i>Slot Duration </h6></td>
                        <td style="padding-left:10px;padding-top:3px;">
                            <div class="input-append">
                                <select ng-model="audition.duration" ng-options="i as i for i in durations" style="padding:0px;height:20px;font-size:12px;width:50px;margin:0px;margin-right:-5px"></select>
                                <span class="add-on" style="margin:0px;padding:0px;padding-left:3px;padding-right:3px;font-size:12px;height:18px">min</span>
                            </div></td>
                    </tr>
                </table>
            </li>
            <li><a id="c3CreateSlot" ng-click="createSlot('unconfirmed')" href="#"><i class="icon-calendar"></i> Create Slot <span style="margin-left:10px" class="badge badge-success">{{audition.duration}} min</span></a></li>
            <li><a id="c3CreateNote" ng-click="createSlot('note')" href="#"><i class="icon-pencil" ></i> Create Note</a></li>
            <li class="divider"></li>
            <li><a href="" ng-click="removeSelection()" >Cancel</a></li>
        </ul>
    </div>

    <span class="c3Template">

        <span id="c3_cal_note">
            <div class="c3SlotBox c3SlotBox-note" style="height:#slotHeight#px;overflow-y:hidden;overflow-x:hidden">
                <div id="#id#" 
                     class="c3Slot c3Slot-success"
                     style="height:#slotHeight#px; z-index:99999999999999"
                     
                     rel="popover" 
                     data-content="<div class='c3NoteText'>#text#</div><button class='btn btn-mini pull-right' style='margin-top:7px;margin-bottom:10px' onClick='cc.editNote(#id#)'><i class='icon-edit'></i>edit</button>" 
                     data-original-title="<h4><small>#date# <span class='pull-right'>#startTime# - #endTime#<span><small><h4>"
                     
                     >

                    <header>#startTime# - #endTime# <div class="close pull-right">×</div></header>
                    <div class="c3NoteTitle" style="margin-top:8px;font-size:12px">
                        #title#
                    </div>
                </div>
            </div>
        </span>

        <span id="c3_cal_block">
            <div id="slot_#startTimeStamp#" 
                 class="#c3SlotClass#" 
                 style="height:#slotHeight#px;"
                 start="#startTimeStamp#"
                 end="#endTimeStamp#"
                 >

                <h5>#startTime# - #endTime#</h5>
            </div>
        </span>

        <span id="c3_cal_slot">
            <div id="slot_#startTimeStamp#_#endTimeStamp#" 
                 class="c3Slot #c3SlotClass#" 
                 audition_slotid="#slotid#"
                 internalid ="#internalid#"
                 time = "#startTimeStamp#"
                 style="height:#slotHeight#px">

                <header>#startTime# - #endTime#</header>
            </div>
        </span>

        <span id="c3_cal_unconfirmed">
            <div id="unconfirmedslot_#startTimeStamp#" class="c3SlotBox" style="height:#slotHeight#px;">
                <div id="slot_#startTimeStamp#_#endTimeStamp#" 
                     class="c3Slot #slotClass#"
                     internalid ="#internalid#"
                     audition_slotid="#slotid#"  
                     size="#size#"
                     style="height:#slotHeight#px; z-index:99999999999999"
                     >

                    <header>#startTime# - #endTime# <div class="close pull-right">×</div></header>

                </div>
            </div>
        </span>

        <span id="c3_cal_confirmed">
            <div id="slot_#startTimeStamp#" class="c3SlotBox c3SlotBox-confirmed" style="height:#slotHeight#px;">
                <div id="confirmedSlot_#startTimeStamp#_#endTimeStamp#" 
                     class="c3Slot c3Slot-confirmed" 
                     audition_slotid="#slotid#" 
                     size="#size#"
                     audition_intervieweeid ="#audition_intervieweeid#"
                     style="height:#slotHeight#px; z-index:99999999999999"

                     rel="popover" data-content="
                     <table style='width:100%'><tr><td style='width:60px'>
                     <img class='thumbnail' src='#photoUrl#'></img>
                     </td><td style='padding-left:10px;vertical-align:top'>
                     <h3><small>#artisteName#</small></h3>
                     <div style='height:30px'> </div>
                     <a class='btn btn-mini btn-info' href='{{baseUrl}}/artiste/portfolio/#portfolioUrl#'>view portfolio</a>
                     </td></tr></table>
                     " data-original-title="<h4><small>#date# <span class='pull-right'>#startTime# - #endTime#<span><small><h4>"

                     >

                    <header>#artisteName#</header>

                </div>
            </div>
        </span>

        <span id="c3_cal_temp">
            <div class="#c3SlotClass# c3SlotBox" style="height:#slotHeight#px;">
                <div class="c3Slot c3Slot-temp" style="height:#slotHeight#px; z-index:99999999999999">

                    <header>#artisteName#</header>

                </div>
            </div>
        </span>

        <div id="c3_cal_onDrag">
            <div class="thumbnail c3-thumbnail-small">
                <i class="icon-user"></i>
            </div>
        </div>

    </span>
</span>