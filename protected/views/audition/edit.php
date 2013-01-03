
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

    .c3Title:hover {
        background: #F7F7F9;
        cursor:pointer;
    }

</style>

<!-- Datepicker Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/bootstrap-datepicker.js"></script>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/audition/edit_audition_ctrl.js"></script>

<script>
    var castingCall = <?php print_r($jsonCastingCall); ?>;
    var audition = <?php print_r($jsonAudition); ?>;
    var auditionSlots = <?php print_r($jsonAuditionSlots); ?>;
    var auditionNotes = <?php print_r($jsonAuditionNotes); ?>;
    var castingManagerPortfolio = <?php print_r($jsonCastingManagerPortfolio); ?>;
    var auditionInterviewees = <?php print_r($jsonAuditionInterviewees); ?>;
    $(function(){eac.init()});
</script>



<div ng-controller="edit_audition_ctrl" class="well c3_body_well" style="width:1020px">
    <div class="row-fluid">
        <ul class="c3-breadcrumb">
            <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
            <li><a href="{{baseUrl + '/castingCall/auditions/' + castingCall.url}}">{{castingCall.title}} (auditions)</a> <i class="ico-arrow"></i></li>
            <li>{{audition.title}}</li>
        </ul>
    </div>
    <div class="row-fluid line">
        <span class="span7" style="height:40px">
            <span id="c3Title" rel="tooltip" title="change audition title" class="c3Title" ng-click="changeTitle()">{{audition.title}}</span>
        </span>
        <span class="span5">
            <button class="btn btn-primary {{canSave}} pull-right" ng-click="confirmSubmit()"><i class="icon-folder-open icon-white"></i> Confirm Audition and Invite Auditionees</button>
            <button class="btn btn-primary {{canSave}} pull-right" ng-click="saveDraft()" style="margin-right:10px"><i class="icon-folder-open icon-white"></i> Save as draft</button>
        </span>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <!--<div class="c3-group" style="margin-bottom:10px">
                <div class="header">
                    <h6><i class="icon-info-sign icon-white"></i> Key Information</h6>
                </div>
                <div class="body">
                    <table>
                        <tr><th style="text-align:left">Total Slots </th><td style="padding-left:5px">{{auditionSlots.length}}</td></tr>
                        <tr><th style="text-align:left">Auditionees </th><td style="padding-left:5px">{{totalInterviewees}}</td></tr>
                    </table>
                </div>
            </div>-->

            <div class="c3-group" style="margin-bottom:10px">
                <div class="header">
                    <h6><i class="icon-calendar icon-white"></i> Mini Calendar</h6>
                </div>
                <div id="eac_miniCal" class="body" style="padding:0px">
                </div>
            </div>

            <div class="c3-group" style="margin-bottom:10px">
                <div class="header {{hasError(errors.audition.application_start)}}">
                    <h6><i class="icon-cog icon-white "></i> Audition Settings</h6>
                </div>
                <div class="body">
                    <h5 class="line">Application Period</h5>
                    <div class="input-prepend">
                        <span class="add-on" style="width:30px">From</span>
                        <input id="application_start" type="text" maxLength="10" style="margin-left:-5px;width:80px" placeholder="dd/mm/yyyy"/>
                    </div>
                    <div class="input-prepend">
                        <span class="add-on" style="width:30px">To</span>
                        <input id="application_end" type="text" maxLength="10" style="margin-left:-5px;width:80px" placeholder="dd/mm/yyyy"/>
                    </div>
                    
                    <h5 class="line">Application rules</h5>
                    <table>
                        <tr>
                            <td style="vertical-align:top"><input type="checkbox" ng-model="reselectable_slots" ng-change="toggleCheckbox('reselectable_slots')"></input></td><td style="padding-left:5px">Auditionees can change slots after selection</td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="c3-group" style="margin-bottom:10px">
                <div class="header {{hasError(errors.auditionInterviewee)}}">
                    <h6><i class="icon-user icon-white"></i> Auditionees</h6>
                </div>
                <div class="body">
                    <button class="btn btn-small" style="width:98%;" ng-click="selectInterviewees()"><i class="icon-plus"></i> Select Auditionees</button>
                </div>
            </div>
        </div>
        <div class="span10">
            <div id="audition_calendar">

            </div>
        </div>
    </div>
    <span>
        <div class="modal hide fade" data-backdrop="static" id="c3_title_modal" style="width:700px;height:auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ng-click="onClose()">×</button>
                <h3>Change Audition Title</h3>
            </div>
            <div class="modal-body" style="max-height:none;height:auto;">
                <div class="input-prepend">
                    <span class="add-on">Audition Title</span>
                    <input type="text" maxLength="33" style="margin-left:-5px" ng-model="audition.title"/>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal" ng-click="onClose()">Close</a>
            </div>
        </div>
    </span>
    <span id="interviewees_modal">

    </span>
</div>