
<style>
    .profile th {
        max-width: 60px;
    }
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/artiste/view_portfolio_ctrl.js"></script>

<!-- Fancybox Plugin -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/source/jquery.fancybox.pack.js?v=2.0.6"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/fancybox/source/jquery.fancybox.css?v=2.0.6" type="text/css" media="screen" />



<script>
    var jsonPortfolio = <?php echo $jsonPortfolio; ?>; 
    var jsonLanguageProficiencies = <?php echo $jsonLanguageProficiencies; ?>;
    
    $(function(){
        $(".fancybox").fancybox();
    });
    
</script>
<div ng-controller="view_portfolio_ctrl" class="well c3_body_well" style="width:1020px">
    <div class="row-fluid">
        <div class="span4">
            <div id="c3_profilepic" class="thumbnail">
                <img style="width:100%" ng-src="{{photoBaseUrl + '/' + portfolio.photoUrl}}">
            </div>
            <br/>

            <?php if (!$isOwner) { ?>
            <div class="c3-group">
                <div class="header header-warning" style="padding-left:10px"><h4>Actions</h4></div>
                <div class="body" style="padding:0;">
                    <ul class="nav nav-list">
                        <li>
                            <a href="" ng-click="invite(portfolio.artiste_portfolioid,portfolio.name,portfolio.photoUrl)">
                                <i class="icon-plus"></i> Invite user to casting call
                            </a>
                        </li>
                        <li>
                            <a href="" ng-click="sendMessage()">
                                <i class="icon-envelope"></i> Send message
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="span8">
            <div class="row-fluid line">
                <div class="span7">
                    <h1>{{portfolio.name}}</h1>
                </div>
                <div class="span5">
                    <div class="row">
                        <div class="span8">
                            <!-- Profile Completeness -->
                            <div class="row-fluid"><h6>Profile Completeness <small style="margin-left:10px;">{{portfolio.completeness}}%</small></h6></div>
                            <div class="row-fluid" style="max-height:25px;">
                                <div class="progress progress-striped {{getProgressBarClass()}}">
                                    <div id="c3-progress-bar" class="bar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="span4" style="padding-top:10px">
                            <?php if ($isOwner) { ?><a class="btn btn-primary btn-small pull-right" href="{{baseUrl + '/artiste/editPortfolio'}}"><i class="icon-cog icon-white"></i> &nbsp;Edit</a><?php } ?>
                        </div>
                    </div>   
                </div>
            </div>
            <div class="row-fluid" style="margin-top:10px;height:229px">
                <div class="span7">
                    <ul class="thumbnails c3-thumbnails-condensed">
                        <li class="thumbnail" ng-repeat="photo in portfolio.featuredPhotos">                              
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
                <div class="thumbnail span5" style="width:295px;margin-left:-15px">
                    <object >
                        <param name="movie"
                               value="https://www.youtube.com/v/<?php echo $artistePortfolio->video->url; ?>?version=3&autohide=1"></param>
                        <param name="allowScriptAccess" value="always"></param>
                        <param name="wmode" value="opaque" />
                        <embed src="https://www.youtube.com/v/<?php echo $artistePortfolio->video->url; ?>?version=3&autohide=1"
                               type="application/x-shockwave-flash"
                               allowfullscreen="true"
                               allowscriptaccess="always"
                               wmode="opaque"
                               width="285" height="200"></embed>
                    </object>
                </div>
            </div>
            <div class="row">
                <ul class="nav nav-pills pull-right" style="margin-left:20px;">
                    <li class="active"><a href="#Info" data-toggle="tab">Info</a></li>
                    <li><a href="#Experiences" data-toggle="tab">Experiences</a></li>
                </ul>
            </div>
            <div class="tab-content" style="height:auto;padding-left:20px">
                <div class="tab-pane fade active in" id="Info">
                    <div class="row" style="padding-left:20px">
                        <div class="span6">
                            <h6><i class="icon-user"></i> General Information</h6>
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
                        <div class="span6">
                            <h6><i class="icon-resize-small"></i> Measurements</h6>

                            <table class="table profile">
                                <tr>
                                    <th>Height</th><td>{{portfolio.height}}m</td>
                                    <th>Weight</th><td>{{portfolio.weight}}Kg</td>
                                </tr>
                                <tr>
                                    <th>Chest</th><td>{{portfolio.chest}}"</td>
                                    <th>Waist</th><td>{{portfolio.waist}}"</td>
                                </tr>
                                <tr>
                                    <th>Hip</th><td>{{portfolio.hip}}"</td>
                                    <th>Shoe</th><td>{{portfolio.shoe}}</td>
                                </tr>
                            </table>

                            <h6><i class="icon-film"></i> Achievements</h6>
                            <table class="table profile">
                                <tr><th>Years of Experience</th><td>{{portfolio.years_of_experience}} years</td></tr>
                                <tr><th>Skills</th><td>
                                        <span ng-repeat="skill in portfolio.skills"> <span ng-show="showComma($index)">,</span> {{skill.name}}</span>
                                    </td></tr>
                                <tr><th>Profession</th><td>
                                        <span ng-repeat="profession in portfolio.professions"> <span ng-show="showComma($index)">,</span> {{profession.name}}</span>
                                    </td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="Experiences">
                    <div class="well">
                        <div class="row-fluid">
                            {{portfolio.experience}}



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>