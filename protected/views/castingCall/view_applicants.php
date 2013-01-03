

<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- rating plugin-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/rating/jquery.rating.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/rating/rating.css" />


<!-- Multilevel Accordion -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/multi_level_accordion/multi_level_accordion.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/view_applicants_ctrl.js"></script>

<script>
    var jsonCharacters = <?php print_r($jsonCharacters); ?>;
    var jsonCastingCall = <?php print_r($jsonCastingCall); ?>;
    
    $(function(){
        vac.init();
    });
</script>

<style>
    .casting-call-list  tr td{
        vertical-align: middle;
    }

    .table tbody tr:hover td{
        background-color: #FAFAFA;
    }

    .profile tr th{
        width:80px;
    }
</style>

<div ng-controller="view_applicants_ctrl">
    <table style="width:100%;margin-bottom:20px;">
        <tr>
            <td style="width:70%;vertical-align:bottom">
                <ul class="c3-breadcrumb">
                    <li><a href="{{baseUrl + '/castingCall/main'}}">Casting Call</a> <i class="ico-arrow"></i></li>
                    <li>{{castingCall.title}} (applicants)</li>
                </ul>
            </td>
        </tr>
    </table>
    <div class="row-fluid">
        <div class="span3">
            <div class="c3-group">
                <div class="header"><h6><i class="icon-user icon-white"></i> Applicants</h6></div>
                <div class="header header-default" style="padding-top:5px;padding-bottom:0px">
                    <select ng-model="selectedCharacter" ng-change="showApplicants()" ng-options="character.characterid as character.name for character in characters"></select>
                </div>
                <div class="body" style="height:550px;overflow-y:scroll">
                    <table class="table table-condensed" style="margin:0px">
                        <tr ng-repeat="artiste in artistePortfolios"  class="c3-hover" ng-click="showPortfolio(artiste.characterApplication.characterid,artiste.artiste_portfolioid)">
                            <td style="width:50px">
                                <div class="thumbnail c3-thumbnail-small">
                                    <img ng-src="{{photoBaseUrl + '/s' + artiste.photoUrl}}"/>
                                </div>
                            </td>
                            <td style="width:140px">
                                <h5>{{artiste.name}}</h5>
                                <h6>HP: <small>{{artiste.mobile_phone}}</small></h6>
                                <h6><small style="text-transform:none">{{artiste.characterName}}</small></h6>
                            </td>
                            <td>
                                <div class="well c3-well-tiny" style="width:15px">
                                    <div style="text-align:center;width:15px">
                                        <span rel="tooltip" data-placement="right" title="favourite">
                                            <span ng-show="artiste.isFavourite" style="width:10px;"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png" ng-click="toggleFavourite(artiste.artiste_portfolioid)"/></span>
                                            <span ng-hide="artiste.isFavourite" style="width:10px;"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_empty.png" ng-click="toggleFavourite(artiste.artiste_portfolioid)"/></span>
                                        </span>
                                    </div>
                                    <div class="c3-click" style="margin-top:10px">
                                        <span rel="tooltip" data-placement="right" title="send message">
                                            <i class="icon-envelope" ng-click="sendMessage(artiste.userid)"></i>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="span9">
            <div class="span8" style="padding-left:10px;height:600px;width:760px;overflow-x:none;overflow-y:scroll">
                <div class="well c3-well-small" ng-show="portfolio == null">
                    <br/>
                    <h6>&nbsp;&nbsp;&nbsp;&nbsp;Click on a participant on the left to view their application</h6><br/>
                </div>
                <div ng-hide="portfolio == null">
                    <div class="c3-group" style="margin-bottom:10px;width:720px">
                        <div class="header header-success"><h6><i class="icon-star icon-white"></i> Feedback</h6></div>
                        <div class="body">
                            <table>
                                <tr><td style="vertical-align:top;padding-right:10px"><h6>Rating </h6></td><td><div id="c3rating" class="rating"></div></td></tr>
                                <tr><td style="vertical-align:top;padding-right:10px"><h6>Comments </h6></td><td><textarea style="resize:none;width:620px;height:50px" ng-model="portfolio.characterApplication.comments"></textarea></td></tr>
                                <tr><td></td><td style="text-align:right;"><button class="btn btn-primary btn-small" style="margin:0px" ng-click="saveFeedback()"><i class="icon-folder-open icon-white"></i> save feedback</button></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="c3-group" style="margin-bottom:10px;width:720px">
                        <div class="header header-warning"><h5><i class="icon-user icon-white"></i> Artiste Portfolio Summary</h5></div>
                        <div class="body">
                            <div class="row-fluid">
                                <div class="span12" style=" height:auto; ">
                                    <div class="line">
                                        <div class="row-fluid">
                                            <div class="span8">
                                                <h1>{{portfolio.name}}</h1>
                                            </div>
                                            <div class="span4">
                                                <button class="btn btn-primary btn-small pull-right" ng-click="viewPortfolio()"><i class="icon-chevron-right icon-white"></i> View Entire Portfolio</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row-fluid">
                                        <div class="span3 thumbnail">
                                            <img ng-src="{{photoBaseUrl + '/l' + portfolio.photoUrl}}" style="width:100%;" />
                                        </div>
                                        <div class="span6">
                                            <table class="table profile">
                                                <tr><th>Name</th><td>{{portfolio.name}}</td></tr>
                                                <tr><th>Age</th><td>{{portfolio.age}}</td></tr>
                                                <tr><th>Ethnicity</th><td>{{portfolio.ethnicity.name}}</td></tr>
                                                <tr><th>Gender</th><td>{{portfolio.gender}}</td></tr>
                                                <tr><th>Nationality</th><td>{{portfolio.nationality}}</td></tr>                  
                                                <tr><th>Languages</th><td>
                                                        <span ng-repeat="language in portfolio.languages"> <span ng-show="showComma($index)">,</span> {{language.name}} ({{getLanguageProficiency(language.language_proficiencyid)}})</span>
                                                    </td></tr>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="c3-group" style="margin-bottom:10px;width:720px" ng-show="portfolio.characterApplication.photos.length > 0 || portfolio.characterApplication.videos.length > 0">
                        <div class="header header-warning"><h5><i class="icon-plus icon-white"></i> Application Attachments</h5></div>
                        <div ng-show="portfolio.characterApplication.photos.length > 0" class="header header-default"><h6><i class="icon-picture"></i> Attached Photographs ({{portfolio.characterApplication.photos.length}})</h6></div>
                        <div ng-show="portfolio.characterApplication.photos.length > 0" class="body" style="padding:0px;margin-bottom:-15px">
                            <table class="table table-hover casting-call-list">
                                <tr><th><h5>Photo</h5></th><th><h5>Description</h5></th></tr>
                                <tr ng-repeat="photo in portfolio.characterApplication.photos">
                                    <td style="width:100px">
                                        <span class="thumbnail c3-well-tiny">
                                            <img ng-src="{{photoBaseUrl + '/m' + photo.photourl}}" alt=""/>
                                        </span>
                                    </td>
                                    <td style="vertical-align:top;padding-top:10px">
                                        <h4>{{photo.title}}</h4>
                                        <h4><small>{{photo.desc}}</small></h4>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div ng-show="portfolio.characterApplication.videos.length > 0" class="header header-default"><h6><i class="icon-film"></i> Required Videos ({{portfolio.characterApplication.videos.length}})</h6></div>
                        <div ng-show="portfolio.characterApplication.videos.length > 0" class="body" style="padding:0px;margin-bottom:-15px;">
                            <table class="table table-hover casting-call-list">
                                <tr><th><h5>Video</h5></th><th><h5>Description</h5></th></tr>
                                <tr ng-repeat="retVideo in portfolio.characterApplication.videos">
                                    <td style="width:200px;padding-top:0px">
                                        <div class="thumbnail" style="margin-top:10px">
                                        <iframe width="200" height="150" ng-src="http://www.youtube.com/embed/{{retVideo.videourl}}?rel=0" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                        </td>
                                    <td style="text-align:left;vertical-align:top">
                                        <h4>{{retVideo.title}}</h4>
                                        <h4><small>{{retVideo.desc}}</small></h4>
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