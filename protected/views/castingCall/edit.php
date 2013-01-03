
<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- Select2 Plugin -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.css" />

<!-- country list -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/countries.js"></script>

<!-- language list -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages.js"></script>

<!-- Datepicker Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/edit_casting_call_ctrl.js"></script>

<script>
    jsonCastingCall = <?php print_r($jsonCastingCall) ?>;
    jsonLanguageProficiencies = <?php print_r(CJSON::encode($proficiencies)); ?>;
    castingManagerPortfolio = <?php print_r($jsonCastingManagerPortfolio); ?>;
</script>

<script>
    $(function(){
        eccc.init();
    });
</script>

<span ng-controller="edit_casting_call_ctrl">

    <div class="boxed row-fluid">
        <div style="width:950px;" class="container row-fluid">
            <table>
                <tr><td style="width:600px">
                        <ul class="c3-breadcrumb">
                            <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
                            <li>{{castingCall.data.title}}</li>
                        </ul>
                    </td><td><h6>Status</h6></td><td><h6>Actions</h6></td></tr>
                <tr>
                    <td><h3>{{castingCall.data.title}}</h3></td>
                    <td style="padding-right:60px">
                        <div class="btn-group">
                            <a class="btn dropdown-toggle " style="min-width: 100px; text-transform:capitalize;" ng-class="statusBtnClass()" data-toggle="dropdown" href="#">
                                {{castingCall.data.status.name}} &nbsp;<span class="caret" style=" "></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a ng-click="setCastingCallStatus(6)" >Un-Publish </a></li>   
                                <li><a ng-click="setCastingCallStatus(5)" >Publish </a></li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <div class="pull-right " style="margin: 0 auto; width: 200px; height: 28px;">
                            <a class="btn btn-medium btn-info {{submitBtnDisabled}}" ng-click="submitForm()"><i class="icon-folder-open icon-white"></i> Save</a>
                            <a class="btn btn-medium btn-info {{submitBtnDisabled}}" ng-click="submitAndView()"><i class="icon-folder-open icon-white"></i> Save & View</a>
                        </div>
                    </td></tr>
            </table>
        </div>
    </div>
    <div style="z-index:9;position:fixed;padding-left:25px;top:80px;width:1000px;background:none;padding-top:30px;height:40px">
        <ul class="nav nav-tabs cc">
            <li class="active"><a href="#epCastingCall" data-toggle="tab"><i></i><span class="c3-step">1</span> Casting Call Information</a></li>
            <li id="charInfo" rel="tooltip" title="Click Here to Add and Edit Characters for your Production." >
                <a href="#epCharacters" data-toggle="tab"><i></i><span class="c3-step">2</span> Character Information</a></li>
        </ul>
    </div>


    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'edit-portfolio-form',
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>

    <div class="container well c3_body_well" style="padding-top:80px;width:1020px">

        <div class="row-fluid">

            <div class="tab-content">

                <div class="tab-pane fade active in" id="epCastingCall">
                    <div class="row-fluid">
                        <div class="span4">
                            <a id="c3_profilepic" class="thumbnail" style="height:450px;width:293px;text-decoration:none" ng-click="changeProfilePic()">
                                <img ng-hide="!castingCall.data.photoUrl" class="c3-profilepic" style='width:100%' ng-src="{{photoBaseUrl + '/' + castingCall.data.photoUrl}}">
                            </a>
                        </div>
                        <div class="span8">
                            <table class="table-condensed">    
                                <tr ng-class="isError(castingCall.errors.title)">
                                    <th>Title</th>
                                    <td>
                                        <input type="text" maxlength="58" ng-model="castingCall.data.title" name="CastingCall[title]" />
                                        <label ng-repeat="error in castingCall.errors.title">{{error}}</label>
                                    </td>
                                </tr>
                                <tr ng-class="isError(castingCall.errors.desc)">
                                    <th>Description</th>
                                    <td>
                                        <textArea type="text" ng-model="castingCall.data.desc" name="CastingCall[desc]" style="width:550px;height:100px"></textArea>
                                        <label ng-repeat="error in castingCall.errors.desc">{{error}}</label>
                                    </td>
                                </tr>
                                <tr><th>Location</th><td><input id="location" type="text" name="CastingCall[location]" style="width:200px;" /></td></tr>                               
                            </table>
                            <h6 class="line" style="margin-top:10px"><i class="icon-calendar"></i>Application Period</h6>
                            <table class="table-condensed">
                                <tr ng-class="isError(castingCall.errors.application_start)"><th>From</th><td>
                                        <input id="application_start" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td>
                                    <th>To</th><td>  
                                        <input id="application_end" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td></tr>
                                <tr ng-class="isError(castingCall.errors.application_start)"><th></th>
                                    <td> 
                                        <label ng-repeat="error in castingCall.errors.application_start">{{error}}</label>
                                        <label ng-repeat="error in castingCall.errors.application_end">{{error}}</label>
                                    </td>
                                </tr>
                            </table>
                            <h6 class="line"><i class="icon-calendar"></i>Audition Period</h6>
                            <table class="table-condensed">

                                <tr ng-class="isError(castingCall.errors.audition_start)"><th>From</th><td>
                                        <input id="audition_start" name="CastingCall[audition_start]" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td>
                                    <th>To</th><td>  
                                        <input id="audition_end" name="CastingCall[audition_start]" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td></tr>
                                <tr ng-class="isError(castingCall.errors.audition_start)"><th></th>
                                    <td> 
                                        <label ng-repeat="error in castingCall.errors.audition_start">{{error}}</label>
                                        <label ng-repeat="error in castingCall.errors.audition_end">{{error}}</label>
                                    </td>
                                </tr>
                            </table>
                            <h6 class="line"><i class="icon-calendar"></i>Project Duration</h6>
                            <table class="table-condensed" style="margin-bottom:200px">    

                                <tr ng-class="isError(castingCall.errors.project_start)"><th>From</th><td>
                                        <input id="project_start" name="CastingCall[project_start]" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td>
                                    <th>To</th><td>  
                                        <input id="project_end" name="CastingCall[project_start]" class="c3datepicker" size="16" type="text" placeholder="dd/mm/yyyy" type="text">
                                    </td></tr>
                                <tr ng-class="isError(castingCall.errors.project_start)"><th></th>
                                    <td> 
                                        <label ng-repeat="error in castingCall.errors.project_start">{{error}}</label>
                                        <label ng-repeat="error in castingCall.errors.project_end">{{error}}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="epCharacters">
                    <div ng-hide="hasCharacters()">
                        <div class="well">
                            <table><tr><td>
                                        <img ng-src="{{baseUrl + '/images/icons/character.png'}}" class="center"/>
                                    </td><td style="padding-left:10px">
                                        <h1>Hey! There are no characters in your show yet</h1>
                                        <br/>
                                        <a class="btn btn-primary btn-large" ng-click="addCharacter()">+ Add Character</a>

                                    </td></tr></table>


                        </div>
                    </div>
                    <div ng-show="hasCharacters()" class="row-fluid">
                        <div class="tabbable tabs-left">
                            <div class="span3">
                                <div style="z-index:9;position:fixed;top:155px;width:230px;">
                                    <div class="row-fluid"><span class="span12"><a class="btn btn-info btn-medium" style="margin-bottom:5px"ng-click="addCharacter()"><i class="icon-plus icon-white"></i> Add Character</a></span></div>
                                </div>
                                <div style="z-index:9;position:fixed;top:190px;width:230px;height:580px;padding-right:10px;overflow-y:scroll">
                                    <ul id="c3_eccc_character_tabs" class="nav nav-pills nav-stacked  " style="color: ">
                                        <li ng-repeat="character in characters" ng-hide="character.data.statusid == 4 || character.data.remove">
                                            <a ng-class="getCharacterTabClass(character.id)" href="#eccc_{{character.id}}" data-toggle="tab">                             
                                                <button class="close" href="#" ng-click="confirmDeleteCharacter(character.id)">×</button> <h4 style="min-height:18px;">{{character.data.name}}</h4>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="span9">

                                <div class="tab-content">
                                    <div class="tab-pane fade" id="eccc_{{character.id}}" ng-repeat="character in characters" ng-hide="character.data.statusid == 4 || character.data.remove">
                                        <!-- Display Of Statuses-->
                                        <div ng-show="character.data.status.statusid == 7" class="alert alert-block alert-error fade in c3-well-small">
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <h2>Incomplete</h2>
                                                </div>
                                                <div class="span8">
                                                    Oh Rats! you have to fix those nasties before you can let someone else can see it !
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="line"><i class="icon-list"></i> Character Information</h4>
                                        <div style="height:580px;overflow-y:scroll;padding-right:10px;">
                                            <table class="table-condensed">
                                                <tr ng-class="isError(character.errors.name)">
                                                    <th>Name</th>
                                                    <td>
                                                        <input type="text" ng-model="character.data.name" style="width:300px"/>
                                                        <label ng-repeat="error in character.errors.name">{{error}}</label>
                                                    </td>
                                                </tr>
                                                <tr ng-class="isError(character.errors.desc)">
                                                    <th>Description</th>
                                                    <td>
                                                        <textarea style="min-width:600px; min-height:100px"  ng-model="character.data.desc"></textarea>
                                                        <label ng-repeat="error in character.errors.desc">{{error}}</label>
                                                    </td>
                                                </tr>
                                            </table>

                                            <br/>
                                            <div class="row-fluid line">
                                                <span class="span7"><h4><i class="icon-search"></i> Requirements (Optional)</h4></span>
                                                <span class="span5">
                                                    <div class="btn-group pull-right" style="position:relative;top:-5px;">
                                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                                                            + Add Requirement
                                                            <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li ng-hide="character.ageRequirement"><a ng-click="setRequirement(character.id,'age',true)">Age</a></li>
                                                            <li ng-hide="character.genderRequirement"><a ng-click="setRequirement(character.id,'gender',true)">Gender</a></li>
                                                            <li ng-hide="character.languageRequirement"><a ng-click="setRequirement(character.id,'language',true)">Spoken Languages</a></li>
                                                            <li ng-hide="character.skillRequirement"><a ng-click="setRequirement(character.id,'skill',true)">Skills</a></li>
                                                            <li ng-hide="character.nationalityRequirement"><a ng-click="setRequirement(character.id,'nationality',true)">Nationality</a></li>
                                                            <li ng-hide="character.ethnicityRequirement"><a ng-click="setRequirement(character.id,'ethnicity',true)">Ethnicity</a></li>
                                                            <li><a ng-click="newOtherRequirement(character.id)">Additional Custom Requirements</a></li>
                                                        </ul>
                                                    </div>
                                                </span>

                                            </div>

                                            <!-- 
                                                Age Requirement
                                            -->
                                            <div class="well c3-well-small" ng-show="character.ageRequirement">
                                                <a class="close" ng-click="deleteAgeRequirement(character.id)">×</a>
                                                <div class="row-fluid">
                                                    <table class="table-condensed">
                                                        <tr ng-class="isError(character.errors.age_start)" ><th style="vertical-align:middle;width:80px">Age</th>
                                                            <td>From 
                                                                <input style="width:70px" type="text" ng-model="character.data.age_start" placeholder="start" />&nbsp; 
                                                                To  
                                                                <input style="width:70px" type="text" ng-model="character.data.age_end" placeholder="end" />
                                                                <label ng-repeat="error in character.errors.age_start">{{error}}</label>
                                                                <label ng-repeat="error in character.errors.age_end">{{error}}</label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- 
                                                Gender Requirement 
                                            -->
                                            <div class="well c3-well-small" ng-show="character.genderRequirement">
                                                <a class="close" ng-click="deleteGenderRequirement(character.id)">×</a>
                                                <table class="table-condensed">
                                                    <tr><th style="width:80px">Gender</th><td>Male <input type="radio" ng-model="character.data.gender" value="male"/>&nbsp; Female <input type="radio" ng-model="character.data.gender" value="female"/></td></tr>
                                                </table>
                                            </div>

                                            <!-- 
                                                Languages Requirement
                                            -->
                                            <div class="well c3-well-small" style="height:auto" ng-show="character.languageRequirement">
                                                <a class="close" ng-click="deleteLanguageRequirement(character.id)">×</a>
                                                <table class="table-condensed">
                                                    <tr><th style="width:80px">Languages</th><td><input type="hidden" id="accLanguages_{{character.id}}" characterid="{{character.id}}" style='width:550px' val=""/></td></tr>
                                                </table>
                                            </div>

                                            <!-- 
                                                Nationality Requirement
                                            -->
                                            <div class="well c3-well-small" style="height:50px" ng-show="character.nationalityRequirement">
                                                <a class="close" ng-click="deleteNationalityRequirement(character.id)">×</a>
                                                <table class="table-condensed">
                                                    <tr><th style="width:80px">Nationality</th><td><input type="hidden" id="accNationality_{{character.id}}" style='width:300px;'/></td></tr>
                                                </table>
                                            </div>

                                            <!-- 
                                                Ethnicity Requirement
                                            -->
                                            <div class="well c3-well-small" style="height:50px" ng-show="character.ethnicityRequirement">
                                                <a class="close" ng-click="deleteEthnicityRequirement(character.id)">×</a>
                                                <table class="table-condensed">
                                                    <tr><th style="width:80px">Ethnicity</th>
                                                        <td>
                                                            <input type="hidden" id="accEthnicity_{{character.id}}" style='width:300px;' val=""/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <!-- 
                                                Skills Requirement
                                            -->

                                            <div class="well c3-well-small" style="height:auto" ng-show="character.skillRequirement">
                                                <a class="close" ng-click="deleteSkillRequirement(character.id)">×</a>
                                                <table class="table-condensed">
                                                    <tr><th style="width:80px">Skills</th>
                                                        <td>
                                                            <input type="hidden" id="accSkill_{{character.id}}" style='width:550px;' val="" characterid="{{character.id}}"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <!--
                                                Other Requirement
                                            -->
                                            <div ng-repeat="otherRequirement in character.data.otherRequirements" ng-hide="otherRequirement.remove" class="well c3-well-small">
                                                <a class="close" ng-click="deleteOtherRequirement(character.id,otherRequirement.other_requirementid)">×</a>
                                                <table class="table-condensed c3-table-condensed">
                                                    <tr ng-class="isError(otherRequirement.errors.requirement)"><th style="width:80px">Requirement</th><td>
                                                            <input type="text" ng-model="otherRequirement.requirement" style="width:250px"placeholder="Requirement"/>
                                                            <label ng-repeat="error in otherRequirement.errors.requirement">{{error}}</label>
                                                        </td></tr><tr ng-class="isError(otherRequirement.errors.desc)">
                                                        <th>Description</th><td>
                                                            <textarea style="width:300px" ng-model="otherRequirement.desc" placeholder="Description"></textarea>
                                                            <label ng-repeat="error in otherRequirement.errors.desc">{{error}}</label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <br/>
                                            <div class="row-fluid line">
                                                <span class="span7"><a style="text-decoration:none; color: #333" id="reqAttch" rel="tooltip" title="You can use this optional section if you want an artiste applying for this character to upload a video and or photo - e.g. an audition video.  Note that the it will be mandatory for artiste to upload the attachment you specify. "><h4 href="#"><i class="icon-file"></i> Required Attachments For Application (Optional)</h4></a></span>
                                                <span class="span5">
                                                    <div class="btn-group pull-right" style="position:relative;top:-5px;">
                                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" id="addReqAttch" rel="tooltip" title="OPTIONAL. Click here to add requirements for an artiste applying for this character to upload Video/Photo attachments." >
                                                            + Add Attachment
                                                            <span class="caret" ></span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li ng-hide="character.videoAttachment"><a ng-click="addVideoAttachment(character.id)">Video</a></li>
                                                            <li ng-hide="character.photoAttachment"><a ng-click="addPhotoAttachment(character.id)">Photo</a></li>
                                                        </ul>
                                                    </div>
                                                </span>

                                            </div>

                                            <!-- 
                                                Video attachment
                                            -->
                                            <div ng-repeat="videoAttachment in character.data.videoAttachments" ng-hide="videoAttachment.remove" class="well c3-well-small">
                                                <a class="close" ng-click="deleteVideoAttachment(character.id,videoAttachment.character_video_attachmentid)">×</a>
                                                <div class="row-fluid">
                                                    <table class="table-condensed">
                                                        <tr ng-class="isError(videoAttachment.errors.title)" ><th style="vertical-align:middle">Video Title</th>
                                                            <td>
                                                                <input type="text" ng-model="videoAttachment.title"/>
                                                                <label ng-repeat="error in videoAttachment.errors.title">{{error}}</label>
                                                            </td>
                                                        </tr>
                                                        <tr ng-class="isError(videoAttachment.errors.desc)">
                                                            <th>Description</th>
                                                            <td>
                                                                <textarea ng-model="videoAttachment.desc" style="width:300px"></textarea>
                                                                <label ng-repeat="error in videoAttachment.errors.desc">{{error}}</label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- 
                                                Photo attachment
                                            -->
                                            <div ng-repeat="photoAttachment in character.data.photoAttachments" ng-hide="photoAttachment.remove" class="well c3-well-small">
                                                <a class="close" ng-click="deletePhotoAttachment(character.id,photoAttachment.character_photo_attachmentid)">×</a>
                                                <div class="row-fluid">
                                                    <table class="table-condensed">
                                                        <tr ng-class="isError(photoAttachments.errors.title)" ><th style="vertical-align:middle">Photo Title</th>
                                                            <td>
                                                                <input type="text" ng-model="photoAttachment.title"/>
                                                                <label ng-repeat="error in photoAttachment.errors.title">{{error}}</label>
                                                            </td>
                                                        </tr>
                                                        <tr ng-class="isError(photoAttachment.errors.desc)">
                                                            <th>Description</th>
                                                            <td>
                                                                <textarea ng-model="photoAttachment.desc" style="width:300px"></textarea>
                                                                <label ng-repeat="error in photoAttachment.errors.desc">{{error}}</label>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</span>