
<style>
    .c3droppableSlotBox {
        background: #F7F7F7;
        color: grey;
        font-weight: bold;
        margin:2px;
        margin-bottom:10px;
        height:30px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border : 3px grey dashed;
        padding-top:10px;
        text-align:center;
    }

    .c3SlotSelection {
        opacity:0.8;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 2px solid;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        padding:2px;
        color:white;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #49AFCD;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5BC0DE), to(#2F96B4));
        background-image: -webkit-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: -o-linear-gradient(top, #5BC0DE, #2F96B4);
        background-image: linear-gradient(to bottom, #5BC0DE, #2F96B4);
        background-image: -moz-linear-gradient(top, #5BC0DE, #2F96B4);
        background-repeat: repeat-x;
        border-color: #2F96B4 #2F96B4 #1F6377;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        height:48px;
        margin-bottom:10px
    }

    .c3SlotSelection-success {
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

    .c3SlotSelection-warning {
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

    .c3SlotSelection-error {
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


    .c3droppableSlotBoxHover {
        background: #C4C4C4;
    }

</style>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/audition/apply_audition_ctrl.js"></script>

<script>
    var audition = <?php print_r($jsonAudition); ?>;
    var slotPriorities = <?php print_r($jsonSlotPriorities); ?>;
    var artistePortfolio = <?php print_r($jsonArtistePortfolio); ?>;
    var auditionSlots = <?php print_r($jsonAuditionSlots); ?>;
    var unselectableSlots = <?php print_r($jsonUnselectableSlots); ?>;
    var auditionIntervieweeSlots = <?php print_r($jsonAuditionIntervieweeSlots); ?>;
    var currentMillis = <?php print_r($currentMillis); ?>;
    $(function(){aac.init()});
</script>



<span ng-controller="apply_audition_ctrl">
    <div class="row-fluid line">
        <span class="span7" style="height:40px">
            <h6 style="line-height:5px">Audition Application</h6>
            <h1>{{audition.title}}</h1>
        </span>
        <span class="span5">
        </span>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <div style="margin-bottom:10px">
                <div ng-show="countdown.status=='countdown_to_open'" class="alert alert-warning" style="padding:0px;text-align:center;margin:0px">
                    <h5>Coming Soon!</h5>
                    <h2>{{countdown.days}}d {{countdown.hours}}:{{countdown.minutes}}:{{countdown.seconds}}</h2>
                </div>
                <div ng-show="countdown.status=='countdown_to_close'" class="alert alert-success" style="padding:0px;text-align:center;margin:0px">
                    <h5>Applications Open</h5>
                    <h2>{{countdown.days}}d {{countdown.hours}}:{{countdown.minutes}}:{{countdown.seconds}}</h2>
                </div>
                <div ng-show="countdown.status == 'application_closed'" class="alert alert-error" style="padding:0px;text-align:center;margin:0px">
                    <h5>Applications</h5>
                    <h2>Closed</h2>
                </div>
            </div>

            <div class="c3-group" style="margin-bottom:10px">
                <div class="header header-warning">
                    <h6><i class="icon-calendar icon-white"></i> Mini Calendar</h6>
                </div>
                <div id="aac_miniCal" class="body" style="padding:0px">
                </div>
            </div>

            
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header {{hasError(errors.auditionIntervieweeSlot)}} header-warning">
                    <h6><i class="icon-edit icon-white"></i> Selected Slot</h6>
                </div>
                <div class="body">
                    <div class="c3droppableSlotBox" ng-show="auditionIntervieweeSlots.length == 0">No Selected Slot</div>
                    <div ng-show="auditionIntervieweeSlots.length > 0" class="c3SlotSelection c3SlotSelection-success">
                        <table>
                            <tr>
                                <td style="width:100px">
                                    <h5>{{auditionIntervieweeSlots[0].day}} {{auditionIntervieweeSlots[0].month}} {{auditionIntervieweeSlots[0].year}}</h5>
                                    {{auditionIntervieweeSlots[0].start + ' - ' + auditionIntervieweeSlots[0].end}}
                                </td>
                                <td>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
               
            </div>
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header header-warning">
                    <h6><i class="icon-calendar icon-white"></i> Legend</h6>
                </div>
                <img ng-src="{{baseUrl + '/images/guides/legend.png'}}" style="width: 100px; padding:7px;"/>
            </div>
             
        </div>
        <div class="span10">
            <div id="audition_calendar">

            </div>
        </div>
    </div>
    <span id="interviewees_modal">

    </span>
</span>