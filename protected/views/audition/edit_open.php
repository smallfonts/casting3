
<style>
    .audition_top_console tr td {
        padding-left:10px;
        vertical-align:top;
    }

    .c3Title {
        min-width:100px;
        line-height:40px;
        padding-left:7px;
        padding-right:7px;
        font-size: 30px;
        font-family: inherit;
        font-weight: bold;
    }

</style>

<!-- Datepicker Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/bootstrap-datepicker.js"></script>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/audition/edit_audition_ctrl_open.js"></script>

<script>
    var castingCall = <?php print_r($jsonCastingCall); ?>;
    var audition = <?php print_r($jsonAudition); ?>;
    var auditionSlots = <?php print_r($jsonAuditionSlots); ?>;
    var auditionInterviewees = <?php print_r($jsonAuditionInterviewees); ?>;
    $(function(){eaco.init()});
</script>



<div ng-controller="edit_audition_ctrl_open" class="well c3_body_well" style="margin-top:-30px;width:1020px">
    <div class="row-fluid">
        <ul class="c3-breadcrumb">
            <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
            <li><a href="{{baseUrl + '/castingCall/auditions/' + castingCall.url}}">{{castingCall.title}} (auditions)</a> <i class="ico-arrow"></i></li>
            <li>{{audition.title}}</li>
        </ul>
    </div>
    <div class="row-fluid line">
        <span class="span7" style="height:40px">
            <span id="c3Title" class="c3Title" >{{audition.title}}</span>
        </span>
        <span class="span5">
            <button class="btn btn-primary {{canSave}} pull-right" ng-click="confirmSubmit()"><i class="icon-folder-open icon-white"></i> Submit</button>
        </span>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header">
                    <h6><i class="icon-info-sign icon-white"></i> Key Information</h6>
                </div>
                <div class="body">
                    <table>
                        <tr><th style="text-align:left">Total Slots </th><td style="padding-left:5px">{{availableSlots}}</td></tr>
                        <tr><th style="text-align:left">Interviewees </th><td style="padding-left:5px">{{totalInterviewees}}</td></tr>
                    </table>
                </div>
            </div>

            <div class="c3-group" style="margin-bottom:10px">
                <div class="header">
                    <h6><i class="icon-calendar icon-white"></i> Mini Calendar</h6>
                </div>
                <div id="eaco_miniCal" class="body" style="padding:0px">
                </div>
            </div>
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header {{hasError(errors.audition.application_start)}}">
                    <h6><i class="icon-cog icon-white "></i> Audition Settings</h6>
                </div>
                <div class="body">
                    <h5 class="line">Audition Duration</h5>
                    <div class="input-append">
                        <input type="text" ng-model="audition.duration" style="padding-left:10px;width:30px;margin-right:-5px" disabled></input>
                        <span class="add-on">min</span>
                    </div>
                    <h5 class="line">Application Period</h5>
                    <div class="input-prepend">
                        <span class="add-on" style="width:30px">From</span>
                        <input id="application_start" type="text" maxLength="10" style="margin-left:-5px;width:80px" placeholder="dd/mm/yyyy" disabled/>
                    </div>
                    <div class="input-prepend">
                        <span class="add-on" style="width:30px">To</span>
                        <input id="application_end" type="text" maxLength="10" style="margin-left:-5px;width:80px" placeholder="dd/mm/yyyy" disabled/>
                    </div>


                </div>
            </div>
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header {{hasError(errors.auditionInterviewee)}}">
                    <h6><i class="icon-user icon-white"></i> Interview Details</h6>
                </div>
                <div class="body">
                    <button class="btn btn-small" style="width:98%;" ng-click="selectInterviewees()"><i class="icon-plus"></i> Select Interviewees</button>
                </div>
            </div>
        </div>
        <div class="span10">
            <div id="audition_calendar">

            </div>
        </div>
    </div>
    <span id="interviewees_modal">

    </span>
</div>