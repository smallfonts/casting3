<style>

    .c3_input:hover{
        cursor: text;
    }

    .crop { width: 260px; height: 180px; overflow: hidden; }
    .crop img { max-width:100%; margin: -18% 0 0 0; }

    .line {
        margin-bottom:10px;
    }

</style>
<!-- Alert Button Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/reveal/css/styles.css"/>


<!-- c3 Languages-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages.js"></script>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/artiste/apply_ctrl.js"></script>

<script>
    var jsonArtistePortfolio = <?php print_r($jsonArtistePortfolio); ?>;
    var jsonApplication = <?php print_r($jsonApplication); ?>; 
    var jsonCastingCall = <?php print_r($jsonCastingCall); ?>;
    var jsonCharacter = <?php print_r($jsonCharacter); ?>;
    var jsonPhotoReqs = <?php print_r($jsonPhotoReqs); ?>;
    var jsonVideoReqs = <?php print_r($jsonVideoReqs); ?>;
    var jsonAppPhotos = <?php print_r($jsonAppPhotos); ?>;
    var jsonAppVideos = <?php print_r($jsonAppVideos); ?>;
    var jsonLanguageProficiencies = <?php print_r($jsonLanguageProficiencies); ?>;
</script>

<script>
    $(function(){
        ac.init();
    });
</script>

<span ng-controller="apply_ctrl">

    <div class="row-fluid" ng-model="application">

        <div class="row-fluid line">
            <div class="span5">
                <h1 style="margin-top:10px">Application Summary</h1>
            </div>
            <div class="span7">
                <div class="alert alert-success pull-right" style="width:260px;margin-bottom:10px;padding-top:5px;padding-bottom:5px;padding-right:10px;"><h2>Application Submitted</h2></div>
                <!--<div class="pull-right">                    <a class="btn btn-medium {{submitBtnDisabled}}" ng-click="confirmWithdrawApplication()"><i class="icon-remove"></i> Withdraw Application</a>
                </div>-->
            </div>
        </div>
        <br/>
        <div class="c3-group" style="margin-bottom:10px">
            <div class="header header-default"><h6><i class="icon-book"></i> Character Summary</h6></div>
            <div class="body" style="padding:10px">
                <div class="row-fluid">
                    <div class="span6">
                        <h6 class="line">Production Title</h6>
                        <p><a href="{{baseUrl + '/castingCall/view/' + castingCall.url}}">{{castingCall.title}}</a></p>
                    </div>
                    <div class="span6">
                        <h6 class="line">Character Name</h6>
                        <p>{{character.name}}</p>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <h6 class="line">Character Description</h6>
                        <p>{{character.desc}}</p>
                    </div>
                    <div class="span6">
                        <h6 class="line" ng-show="character.requirements.length > 0">Requirements</h6>
                        <div class="row-fluid">
                            <div style="margin:5px;"ng-repeat="requirement in character.requirements">
                                <strong>{{requirement.name}}: </strong>
                                {{requirement.data}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="c3-group" style="margin-bottom:10px;">
            <div class="header header-warning" style="padding-left:10px"><h3>Personal Information</h3></div>
            <div class="body" style="padding:10px;">
                <div class="row-fluid">
                    <div class="span4">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#Info" data-toggle="tab">Info</a></li>
                            <li><a href="#photos" data-toggle="tab">Photos & Videos</a></li>
                        </ul>
                    </div>
                    <div class="span8">
                        </div>
                </div>
                <div class="tab-content" style="height:auto;">
                    <div class="tab-pane fade active in" id="Info">
                        <div class="row-fluid">
                            <div class="span2 thumbnail" >
                                <img ng-src="{{photoBaseUrl + '/' + artistePortfolio.photoUrl}}"/>
                            </div>
                            <div class="span4">
                                <h6><i class="icon-user"></i> General Information</h6>
                                <table class="table profile">
                                    <tr><th>Name</th><td>{{artistePortfolio.name}}</td></tr>
                                    <tr><th>Age</th><td>{{artistePortfolio.age}}</td></tr>
                                    <tr><th>Ethnicity</th><td>{{artistePortfolio.ethnicity.name}}</td></tr>
                                    <tr><th>Gender</th><td>{{artistePortfolio.gender}}</td></tr>
                                    <tr><th>Nationality</th><td>{{artistePortfolio.nationality}}</td></tr>                  
                                    <tr><th>Languages</th><td>
                                            <span ng-repeat="language in artistePortfolio.languages"> <span ng-show="showComma($index)">,</span> {{language.name}} ({{getLanguageProficiency(language.language_proficiencyid)}})</span>
                                        </td></tr>
                                </table>
                            </div>
                            <div class="span6">
                                <h6><i class="icon-resize-small"></i> Measurements</h6>

                                <table class="table profile">
                                    <tr>
                                        <th>Height</th><td>{{artistePortfolio.height}}m</td>
                                        <th>Weight</th><td>{{artistePortfolio.weight}}Kg</td>
                                    </tr>
                                    <tr>
                                        <th>Chest</th><td>{{artistePortfolio.chest}}"</td>
                                        <th>Waist</th><td>{{artistePortfolio.waist}}"</td>
                                    </tr>
                                    <tr>
                                        <th>Hip</th><td>{{artistePortfolio.hip}}"</td>
                                        <th>Shoe</th><td>{{artistePortfolio.shoe}}</td>
                                    </tr>
                                </table>

                                <h6><i class="icon-film"></i> Achievements</h6>
                                <table class="table profile">
                                    <tr><th>Years of Experience</th><td>{{artistePortfolio.years_of_experience}} years</td></tr>
                                    <tr><th>Skills</th><td>
                                            <span ng-repeat="skill in portfolio.skills"> <span ng-show="showComma($index)">,</span> {{skill.name}}</span>
                                        </td></tr>
                                    <tr><th>Profession</th><td>
                                            <span ng-repeat="profession in portfolio.professions"> <span ng-show="showComma($index)">,</span> {{profession.name}}</span>
                                        </td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span11">
                                <h6><i class="icon-user"></i> Experience</h6>
                                <table class="table profile">
                                    <tr>
                                        <td>Experience</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="photos">
                        <div class="row-fluid" style="width:700px">
                            <div class="span6">
                                <ul class="thumbnails c3-thumbnails-condensed">
                                    <li class="thumbnail" ng-repeat="photo in artistePortfolio.featuredPhotos">                              
                                        <a class="fancybox" rel="gallery1" style="text-decoration: none;" href="{{photoBaseUrl + '/' + photo.url}}" >
                                            <img ng-src="{{photoBaseUrl + '/m' + photo.url}}" alt="" ng-hide="photo==null"/>
                                            <div class="addImage" ng-show="photo==null">
                                                <h6>+</h6>
                                                <h6>Add Image</h6>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="thumbnail span5" style="width:240px;">
                                <iframe width="230" height="200" ng-src="http://www.youtube.com/embed/{{artistePortfolio.video.url}}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div ng-show="hasPhotos() || hasVideos()" class="c3-group" style="margin-bottom:50px">
            <div class="header header-warning" style="padding-left:10px"><h3>Uploaded Attachments</h3></div>
            <div ng-show="hasPhotos()" class="header header-default"><h6><i class="icon-picture"></i> Required Photographs ({{photoReqs.length}})</h6></div>
            <div ng-show="hasPhotos()" class="body" style="padding:0px;margin-bottom:-15px">
                <table class="table table-hover casting-call-list" style="margin-top:5px">
                    <tr><th><h5>Title</h5></th><th><h5>Description</h5></th><th style="width:20px;"></th><th><h5>Photograph</h5></th></tr>
                    <tr ng-repeat="retPhoto in retPhotos">
                        <td style="width:200px">
                            {{retPhoto.title}}
                        </td>
                        <td>
                            {{retPhoto.desc}}
                        </td>
                        <td style="width:20px;"></td>
                        <td style="width:100px;height:100px;">
                            <div class="addImage" ng-show="!retPhoto.attachment" style="width:100px; height:100px; margin-top:0px">
                                <h6>No Image</h6>
                            </div>
                            <span ng-show="retPhoto.attachment" class="thumbnail c3-well-tiny">
                                <img ng-src="{{photoBaseUrl + '/l' + retPhoto.attachment}}" alt=""/>
                            </span>
                        </td>
                    </tr>

                </table>
            </div>
            <div ng-show="hasVideos()" class="header header-default"><h6><i class="icon-film"></i> Required Videos ({{videoReqs.length}})</h6></div>
            <div ng-show="hasVideos()" class="body" style="padding:0px;margin-bottom:-15px">
                <table class="table table-hover casting-call-list" style="margin-top:5px">
                    <tr><th><h5>Title</h5></th><th><h5>Description</h5></th><th style="width:20px;"></th><th><h5>Video</h5></th></tr>
                    <tr ng-repeat="retVideo in retVideos">
                        <td style="width:200px">
                            {{retVideo.title}}
                        </td>
                        <td>
                            {{retVideo.desc}}
                        </td>
                        <td style="width:20px;"></td>
                        <td style="width:100px;padding-top:0px">
                            <div class="thumbnail" ng-hide="!retVideo.attachment">
                                <iframe width="250" height="200" src="{{'https://www.youtube.com/v/' + retVideo.attachment + ''}}" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="addImage" ng-show="!retVideo.attachment" style="width:100px; height:100px">
                                <h6>No Video</h6>
                            </div>
                        </td>
                        
                    </tr>

                </table>
            </div>
        </div>
    </div>




</div>
</span>