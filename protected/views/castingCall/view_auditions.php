

<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- Multilevel Accordion -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/view_auditions_ctrl.js"></script>

<script>
    var jsonAuditions = <?php print_r($jsonAuditions); ?>;
    var jsonCastingCall = <?php print_r($jsonCastingCall); ?>;
</script>

<style>
    .casting-call-list  tr td{
        vertical-align: middle;
    }
</style>

<div ng-controller="view_auditions_ctrl" class="well c3_body_well" style="width:1020px">
    <table style="width:100%;">
        <tr>
            <td style="width:70%;vertical-align:bottom">
                <ul class="c3-breadcrumb">
                    <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
                    <li>{{castingCall.title}} (auditions)</li>
                </ul>
            </td>
            <td>
                <a class="btn btn-primary btn-medium pull-right" href="<?php echo Yii::app()->baseUrl; ?>/audition/new/{{castingCall.url}}"><i class="icon-plus icon-white"></i> New Audition</a>
            </td>
        </tr>
    </table>
    <table class="table table-hover c3-table-centered" style="margin-top:5px">
        <tr>
            <th style="text-align:left;width:400px"><h6>Audition Title</h6></th>
            <th><h6>Status</h6></th>
            <th><h6>Application Open</h6></th>
            <th><h6>Application Close</h6></th>
            <th><h6>Actions</h6></th>
        </tr>
        <tr ng-repeat="audition in auditions">
            <td style="text-align:left" ng-show="audition.status.statusid != 'confirmed'">
                <a href="{{getUrl($index)}}">{{audition.title}}</a>
            </td>
            <td>
                <span class=" alert {{alertClass($index)}} c3-well-tiny" style="padding-left:5px;padding-right:5px">{{audition.status.name}}</span>
            </td>
            <td><h6>{{audition.application_start}}</h6></td>
            <td><h6>{{audition.application_end}}</h6></td>
            <td><a class="btn btn-info btn-small" href="{{baseUrl + '/audition/export/' + audition.auditionid + '/' + audition.title + '.csv'}}" ><i class="icon-download-alt icon-white"></i> Export</a></td>
        </tr>
    </table>
    <div ng-show="characters.length == 0">
        <br></br>
        <img src="/timberwerkz/images/icons/folder.png" class="center"/>
        <p></p>
        <p class="center">There are currently no characters.</p>
    </div>

</div>