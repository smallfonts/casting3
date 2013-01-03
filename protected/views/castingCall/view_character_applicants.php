

<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- Fancybox Plugin -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/source/jquery.fancybox.pack.js?v=2.0.6"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/source/jquery.fancybox.css?v=2.0.6" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/view_character_applicants_ctrl.js"></script>

<script>
    var jsonApplicants = <?php print_r($jsonApplicants); ?>;
    var jsonCastingCall = <?php print_r($jsonCastingCall); ?>;
    var jsonCharacter = <?php print_r($jsonCharacter); ?>;
    
    $(function(){
        $(".fancybox").fancybox();
    });
</script>

<div ng-controller="view_character_applicants_ctrl">
    <table style="width:100%;margin-bottom:20px;">
        <tr>
            <td style="width:70%;vertical-align:bottom">
                <ul class="c3-breadcrumb">
                    <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
                    <li><a href="{{baseUrl + '/castingCall/applicants/' + castingCall.url}}">{{castingCall.title}} (applicants)</a> <i class="ico-arrow"></i></li>
                    <li>{{character.name}}</li>
                </ul>
            </td>
        </tr>
    </table>

    <!-- Displays Artiste Portfolios-->
    <table class="table table-hover c3-table-middle" style="margin-top:5px">
        <tr><th></th><th><h6>Applicant Name</h6></th><th><h6>Status</h6></th><th style="text-align:center;width:200px"><h6>Actions</h6></th></tr>
        <tr ng-repeat="applicant in applicants">
            <td style="width:30px;">
                <a class="fancybox" rel="gallery1" href="{{photoBaseUrl + '/' + applicant.photoUrl}}">
                    <div class="thumbnail c3-thumbnail-small">
                        <img ng-src="{{photoBaseUrl + '/s' + applicant.photoUrl}}"/>
                    </div>
                </a>
            </td>
            <td>
                <a href="{{baseUrl + '/artiste/portfolio/' + applicant.url}}"><h2>{{applicant.name}}</h2></href>
            </td>
            <td>

            </td>
            <td>
                <div class="btn-group" style="width:200px">
                    <div class="btn-group">
                        <a href="{{baseUrl + '/castingCall/applicants/' + castingCall.url}}" class="btn btn-large"><i class="icon-envelope"></i> Send Message</a>
                        <button class="btn btn-large dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <li><a href="{{baseUrl + '/castingCall/edit/' + castingCall.url}}" ><i class="icon-edit"></i> Edit Casting Call</a></li>
                            <li><a href=""><i class="icon-calendar"></i> View Auditions</a></li>
                            <li class="divider"></li>
                            <li><a ng-click="confirmDeleteCastingCall(castingCall.casting_callid)" href="#"><i class="icon-trash"></i> Delete Casting Call</a></li>
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    
    <div ng-show="applicants.length == 0">
    <br></br>
    <img src="/timberwerkz/images/icons/folder.png" class="center"/>
    <p></p>
    <p class="center">There are currently no applicants.</p>
</div>

</div>