
<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<!-- c3 Languages-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages.js"></script>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/casting_call/view_casting_call_ctrl.js"></script>

<script>
    var jsonCastingCall = <?php print_r($jsonCastingCall); ?>;
    var currentMillis = <?php print_r($currentMillis); ?>;
    var jsonCharacters = <?php print_r($jsonCharacters); ?>;
    var jsonLanguageProficiencies = <?php print_r($jsonLanguageProficiencies); ?>;
    var jsonRoleid = <?php print_r($jsonRoleid); ?>;
</script>

<script>
    $(function(){
        vccc.init();
    });  
</script>

<div ng-controller="view_casting_call_ctrl" class="well c3_body_well" style="min-height:620px;width:1020px">
    <div class="container">
        <div class="row-fluid">
            <div class="span4"></div>
            <div class="span4" style="position:fixed">
                <div id="c3_profilepic" class="thumbnail" style="width:280px;text-decoration:none">
                    <img ng-hide="!castingCall.data.photoUrl" class="c3-profilepic" style='width:100%' ng-src="{{photoBaseUrl + '/' + castingCall.data.photoUrl}}">
                </div>
                <div class="c3-group" style="margin-bottom:10px;margin-top:10px;margin-left:3px;width:280px">
                    <div class="header header-warning"><h4><i class="icon-envelope icon-white" style="margin-top:1px;"></i> Contact Information</h4></div>
                    <div class="body">
                        <table style="width:100%">
                            <tr><td rowspan="2" style="width:25px;padding-right:5px">
                                    <img ng-src="{{photoBaseUrl + '/s' + castingCall.data.castingManagerPortfolio.photoUrl}}" />
                                </td><th style="text-align:left">{{castingCall.data.castingManagerPortfolio.name}}</th><td rowspan="2" style="vertical-align:bottom"><button class="pull-right btn btn-primary btn-mini" ng-click="sendMessage()">Send Message</button></td></tr>
                            <tr><td><h6><small>Casting Manager<small></h6></td></tr>
                        </table>
                    </div>
                </div>
                <div class="c3-group" style="opacity:0.8;margin-bottom:10px;margin-top:10px;margin-left:3px;width:280px">
                <div class="body" style="padding-left:15px">
                    <table class="c3-share-bar">
                        <tr><td>
                                <div class="fb-like" data-href="{{'http://dev-casting3.hopto.org' + baseUrl + '/castingCall/view/' + castingCall.data.url}}" data-send="false" data-layout="box_count"d ata-show-faces="false"></div>
                            </td>
                            <td style="padding-left:10px">
                                <div class="g-plusone" data-size="tall"></div>
                            </td>
                            <td style="padding-left:10px">
                                <a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a>
                            </td>
                            <td style="padding-left:10px">
                                <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                <script type="IN/Share" data-counter="top"></script>
                            </td>
                        </tr>
                    </table>
                </div>
                </div>
                <!-- Place this tag after the last +1 button tag. -->
                <script type="text/javascript">
                    (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    })();
                </script>

                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

            </div>
            <div class="span8">
                <div class="row-fluid line">
                    <div class="span8">
                        <h3>{{castingCall.data.productionPortfolio.name}} Presents</h3>
                        <h1>{{castingCall.data.title}}&nbsp;</h1>
                    </div>
                    <div class="span4">
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

                    </div>
                </div>

                <div class="row-fluid">
                    <div class="row-fluid" style="margin-bottom:12px;">
                        {{castingCall.data.desc}}
                    </div>
                    <div class="row-fluid">
                        <table class="table">
                            <tr>
                                <th>Production House</th>
                                <td>
                                    <a href="{{baseUrl + '/production/portfolio/' + castingCall.data.productionPortfolio.url}}">
                                        {{castingCall.data.productionPortfolio.name}}
                                    </a>
                                </td>
                                <th>Application Period</th>
                                <td>{{castingCall.data.application_start}} - {{castingCall.data.application_end}}</td>
                            </tr>
                            <tr>
                                <th>Project Duration</th>
                                <td>{{castingCall.data.project_start}} - {{castingCall.data.project_end}}</td>
                                <th>Project Location</th>
                                <td>{{castingCall.data.location}}</td>
                            </tr>
                            <tr>
                                <th>Audition Period</th>
                                <td>
                                    {{castingCall.data.audition_start}} - {{castingCall.data.audition_end}}
                                </td>
                                <th></th>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row-fluid">
                        <h2 class="line"><i class="ico-user"></i> Characters</h2>
                        <div class="accordion" id="accordion2">
                            <div class="accordion-group" ng-repeat="character in characters">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle collapsed">
                                        <div class="row-fluid">
                                            <div class="span9">
                                                <h2 data-toggle="collapse" data-parent="#accordion2" href="#accordion_{{$index}}">{{character.name}}</h2>
                                            </div>
                                            <div class="span3">
                                                <div ng-show="isArtiste()" class="btn btn-success pull-right apply" url="{{baseUrl + '/artiste/apply?character=' + character.characterid}}">Apply Now</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div id="accordion_{{$index}}" class="accordion-body collapse" style="height: 0px; ">

                                    <div class="accordion-inner">
                                        <h6 class="line">Description</h6>
                                        <p>{{character.desc}}</p>
                                        <h6 class="line" ng-show="character.requirements.length > 0">Requirements</h6>
                                        <div class="row-fluid">
                                            <div style="margin:5px;"ng-repeat="requirement in character.requirements">
                                                <strong>{{requirement.name}}: </strong>
                                                {{requirement.data}}
                                            </div>
                                        </div>
                                        <br/>
                                        <h6 class="line" ng-show="character.photoAttachments.length > 0">Required photo attachments</h6>
                                        <div class="row-fluid">
                                            <div style="margin:5px;"ng-repeat="photoAttachment in character.photoAttachments">
                                                <strong>{{photoAttachment.title}}: </strong><br/>
                                                {{photoAttachment.desc}}<br/><br/>
                                            </div><br/>
                                        </div>

                                        <h6 class="line" ng-show="character.videoAttachments.length > 0">Required video attachments</h6>
                                        <div class="row-fluid">
                                            <div style="margin:5px;"ng-repeat="videoAttachment in character.videoAttachments">
                                                <strong>{{videoAttachment.title}}: </strong><br/>
                                                {{videoAttachment.desc}}<br/><br/>
                                            </div><br/>
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