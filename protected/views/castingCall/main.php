

<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- Multilevel Accordion -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.css" />


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/main_casting_call_ctrl.js"></script>

<script>
    jsonCastingCalls = <?php print_r($jsonCastingCalls); ?>;
</script>

<script>
    $(function(){
        mccc.init();
    });
</script>

<style>
    .casting-call-list  tr td{
        vertical-align: middle;
    }
</style>

<div ng-controller="main_casting_call_ctrl" class="well c3_body_well" style="width:1020px">
    <table style="width:100%;margin-bottom:10px;">
        <tr>
            <td style="width:70%;vertical-align:bottom">
                <ul class="c3-breadcrumb">
                    <li>Casting Call </li>
                </ul>
            </td>
            <td style="width:30%"><a class="btn btn-primary btn-medium pull-right" href="<?php echo Yii::app()->baseUrl; ?>/castingCall/new"><i class="icon-plus icon-white"></i> New Casting Call</a></td>
        </tr>
    </table>
    <table class="table table-hover casting-call-list" style="margin-top:5px">
        <tr><th style="text-align:center"><h5>Photo</h5></th><th><h5>Title</h5></th><th><h5 style="text-align:center">Status</h5></th><th style="text-align:center;width:200px"><h5>Actions</h5></th></tr>
        <tr ng-repeat="castingCall in castingCalls">
            <td style="width:40px;">
                <div class="thumbnail c3-thumbnail-small">
                    <img ng-src="{{photoBaseUrl + '/s' + castingCall.photoUrl}}"/>
                </div>
            </td>
            <td>
                <a href="{{baseUrl + '/castingCall/edit/' + castingCall.url}}" >
                <h2>{{castingCall.title}}</h2>
                </a>
            </td>
            <td style="width:200px">
                <center>
                    <div ng-show="castingCall.statusid == 7" class="alert alert-error c3-well-tiny" style="text-align:center"><h3>Incomplete</h3></div>
                    <div ng-show="castingCall.statusid == 6" class="alert alert-warning c3-well-tiny"  style="text-align:center"><h3>Not Published</h3></div>
                    <div ng-show="castingCall.statusid == 5" class="alert alert-success c3-well-tiny"  style="text-align:center"><h3>Published</h3></div>
                </center>
            </td>
            <td style="width:300px">
                    <a href="{{baseUrl + '/castingCall/applicants/' + castingCall.url}}" class="btn btn-medium" ><i class="icon-user"></i> Applicants <span class="label label-info" style="position:relative;top:-1px">{{castingCall.applicants}}</span> </a>
                    <a href="{{baseUrl + '/castingCall/auditions/' + castingCall.url}}" class="btn btn-medium" ><i class="icon-calendar"></i> Auditions <span class="label label-info" style="position:relative;top:-1px">{{castingCall.auditions}}</span></a>
                    <a ng-click="confirmDeleteCastingCall(castingCall.casting_callid)" class="btn btn-medium btn-danger" href="#"><i class="icon-trash icon-white"></i></a>
            </td>
                         
        </tr>
        
    </table>
    
<div ng-show="castingCalls.length == 0">
    <br/>
    <br/>
    <p class="center">You do not have any casting calls</p>
</div>


</div>