
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/audition/view_confirmed_slots_ctrl.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/audition/edit_audition_ctrl_confirmed.js"></script>


<!-- Datepicker Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/bootstrap-datepicker.js"></script>


<!-- rating plugin-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/rating/jquery.rating.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/rating/rating.css" />

<script>
    var slotsEachDay = <?php print_r($jsonSlotsEachDay); ?>;
    var jsonLanguageProficiencies = <?php echo $jsonLanguageProficiencies; ?>;
    
    var castingCall = <?php print_r($jsonCastingCall); ?>;
    var audition = <?php print_r($jsonAudition); ?>;
    var auditionSlots = <?php print_r($jsonAuditionSlots); ?>;
    var auditionNotes = <?php print_r($jsonAuditionNotes); ?>;
    var auditionInterviewees = <?php print_r($jsonAuditionInterviewees); ?>;
    var currentMillis = <?php print_r($currentMillis); ?>;
    $(function(){eacc.init()});
</script>

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

    .interviewee:hover {
        background: #F7F7F7;
        cursor:pointer;
    }

</style>

<div ng-controller="view_confirmed_slots_ctrl" class="well c3_body_well" style="margin-top:-30px;width:1020px;padding-top:5px;padding-bottom:5px">
    <h2>{{audition.title}}</h2>
    <button class="btn btn-primary {{canSave}} pull-right" ng-click="confirmSubmit()"><i class="icon-folder-open icon-white"></i> Save</button>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#calendar_mode" data-toggle="tab"><span class="c3-step">1</span> Calendar Mode</a>
        </li>
        <li><a href="#audition_mode" data-toggle="tab"><span class="c3-step">2</span> Audition Mode</a></li>
    </ul>
    <div class="tab-content" style="height:auto;min-height:600px;">
        <div class="tab-pane fade active in" id="calendar_mode">

            <div ng-controller="edit_audition_ctrl_confirmed">
                <div class="row-fluid">
                    <div class="span2">

                        <!--<div style="margin-bottom:10px">
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
                        </div>-->

                        <div class="c3-group" style="margin-bottom:10px">
                            <div class="header">
                                <h6><i class="icon-calendar icon-white"></i> Mini Calendar</h6>
                            </div>
                            <div id="eacc_miniCal" class="body" style="padding:0px">
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
                                    <input id="application_start" type="text" maxLength="10" style="margin-left:-5px;width:80px" placeholder="dd/mm/yyyy" disabled/>
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
                            <div class="header header-warning">
                                <h6><i class="icon-warning-sign icon-white"></i> No Slots</h6>
                            </div>
                            <div class="body" style="padding:0px;height:100px;overflow-y:scroll;overflow-x:hidden">
                                <div class="alert alert-success c3-well-tiny" style="margin-left:6px;margin-top:25px;width:110px" ng-show="unconfirmedInterviewees.length == 0" >
                                    <div class="row-fluid">
                                        <div class="span2"><i class=" icon-thumbs-up"></i></div>
                                        <div class="span10"> All interviewees have a slot</div>
                                    </div>   
                                </div>
                                <div class="interviewee row-fluid" photoUrl="{{interviewee.photoUrl}}" url="{{interviewee.url}}" artisteName="{{interviewee.name}}" audition_intervieweeid="{{interviewee.audition_intervieweeid}}" artiste_portfolioid="{{interviewee.artiste_portfolioid}}" style="width:130px" ng-repeat="interviewee in unconfirmedInterviewees"
                                     data-content="
                                     <table style='width:100%'><tr><td style='width:60px'>
                                     <img class='thumbnail' src='{{photoBaseUrl + '/s' + interviewee.photoUrl}}'></img>
                                     </td><td style='padding-left:10px;vertical-align:top'>
                                     <h3><small>{{interviewee.name}}</small></h3>
                                     <div style='height:30px'> </div>
                                     <a class='btn btn-mini btn-info' href='{{baseUrl}}/artiste/portfolio/{{interviewee.url}}'>view portfolio</a>
                                     </td></tr>
                                     " data-original-title="<h4><small>No slots assigned<small><h4>"

                                     >
                                    <span class="span3" style="padding:5px;"><img class="thumbnail c3-thumbnail-small" ng-src="{{photoBaseUrl + '/s' + interviewee.photoUrl}}"></img></span>
                                    <span class="span9" style="padding:5px"><h4><small>{{interviewee.name}}</small></h4></span>
                                </div>
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
                <span id="interviewees_modal">

                </span>
            </div>







        </div>
        <div class="tab-pane fade" id="audition_mode">
            <div class="row">
                <div class="span3" style="padding-right:5px;min-height:600px;overflow-y:scroll">
                    <div class="c3-group" style="margin-bottom:10px">
                        <div ng-repeat="dayslot in slots">
                            <div class="header">
                                <h6>&nbsp;&nbsp;&nbsp;{{getDay(dayslot[0].start)}}, {{getDate(dayslot[0].start)}} {{getMonth(dayslot[0].start)}} {{getYear(dayslot[0].start)}}</h6>
                            </div>
                            <div class="body">
                                <table class="table table-condensed" style="margin:0px">
                                    <tr ng-repeat="slot in dayslot"  class="c3-hover" ng-click="showPortfolio($parent.$index, $index)">
                                        <td style="width:40px;font-size:12px;"><div style="margin-top:2px">{{getHours(slot.start)}}:{{getMinutes(slot.start)}}</div><div>{{getHours(slot.end)}}:{{getMinutes(slot.end)}}</div></td>
                                        <td style="width:50px"><img ng-src="{{photoBaseUrl + '/s' + slot.photoUrl}}"/></td>
                                        <td style="width:140px"><h6>{{slot.name}}</h6></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="span8" style="margin-left:10px;height:600px;width:760px;overflow-x:none;overflow-y:scroll">
                    <div class="well c3-well-small" ng-show="portfolio == null">
                        <br/>
                        <h6>&nbsp;&nbsp;&nbsp;&nbsp;Click on a participant on the left to show his portfolio.</h6><br/>
                    </div>
                    <div ng-hide="portfolio == null">
                        <div class="c3-group" style="margin-bottom:10px;width:730px">
                            <div class="header header-success"><h6><i class="icon-star icon-white"></i> Feedback</h6></div>
                            <div class="body">
                                <table>
                                    <tr><td style="vertical-align:top;padding-right:10px"><h6>Rating </h6></td><td><div id="c3rating" class="rating"></div></td></tr>
                                    <tr><td style="vertical-align:top;padding-right:10px"><h6>Comments </h6></td><td><textarea style="resize:none;width:600px;height:50px" ng-model="portfolio.characterApplication.comments"></textarea></td></tr>
                                    <tr><td></td><td style="text-align:right;"><button class="btn btn-primary btn-small" style="margin:0px" ng-click="saveFeedback()"><i class="icon-folder-open icon-white"></i> save feedback</button></td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="c3-group" style="margin-bottom:10px;width:730px">
                            <div class="header header-default"><h5><i class="icon-user"></i> Artiste Portfolio</h5></div>
                            <div class="body row-fluid">
                                <div class="span12" style="padding-left: 20px; padding-right: 20px; height:auto; ">
                                    <br/>
                                    <div class="row-fluid">
                                        <div class="span8 line">
                                            <h1>{{portfolio.name}}</h1>
                                        </div>
                                        <div class="span4">
                                            <ul class="nav nav-pills pull-right" style="margin-left:20px;">
                                                <li class="active"><a href="#Info" data-toggle="tab">Info</a></li>
                                                <li><a href="#photos" data-toggle="tab">Photos & Videos</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-content" style="height:auto;padding-left:20px">
                                        <div class="tab-pane fade active in" id="Info">
                                            <div class="row-fluid">
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
                                            <div class="row-fluid">
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
                                                <div class="thumbnail span5" style="width:245px;">
                                                    <iframe width="230" height="200" ng-src="http://www.youtube.com/embed/{{portfolio.video.url}}" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="c3-group" style="margin-bottom:10px;width:730px" ng-show="portfolio.characterApplication.photos.lenght > 0 || portfolio.characterApplication.videos.lenght > 0">
                            <div class="header header-default"><h5><i class="icon-plus"></i> Application Attachments</h5></div>
                            <div class="body">
                                <h6 class="line">photos</h6>
                                <ul>
                                    <li><img></img></li>
                                </ul>
                                <h6 class="line">videos</h6>
                                <iframe width="230" height="200" ng-src="http://www.youtube.com/embed/{{portfolio.video.url}}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <span class="c3Template">
        <div id="interviewee_onDrag">
            <div class="thumbnail c3-thumbnail-small">
                <i class="icon-user"></i>
            </div>
        </div>
    </span>
</div>