

<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/reveal/css/styles.css"/>

<!-- Fancybox Plugin -->

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/artiste/view_all_applications_ctrl.js"></script>

<script>
    var jsonApplications = <?php print_r($jsonApplications); ?>;
    var jsonInvitations = <?php print_r($jsonInvitations); ?>;
    var jsonAuditionInterviewees = <?php print_r($jsonAuditionInterviewees) ?>;
    var currentMillis = <?php print_r($currentMillis); ?>;
</script>

<div ng-controller="view_all_applications_ctrl" class="well c3_body_well"style="width:1020px;min-height:580px">
    <div class="tabbable tabs-left">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><h5>Invitations to Apply&nbsp;&nbsp;
                        <span class="badge badge-important" ng-show="getNewInvitations() > 0">{{getNewInvitations()}}</span></h5></a></li>
            <li><a href="#tab2" data-toggle="tab"><h5>Applications</h5></a></li>
            <li><a href="#tab3" data-toggle="tab"><h5>Auditions&nbsp;&nbsp;
                        <span class="badge badge-important" ng-show="getNewAuditions() > 0">{{getNewAuditions()}}</span></h5></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div ng-hide="hasInvitations()">
                    <br/>
                    <h6>You have no invitations.</h6>
                </div>
                <!-- invitations -->
                <div ng-show="hasInvitations()">
                    <h2> Invitations ({{invitations.length}}) </h2>

                    <table class="table table-hover casting-call-list" style="margin-top:5px; width:100%;">
                        <tr><th><h6>Photo</h6></th><th></th><th style="width: 250px;"><h6>Casting Call Title</h6></th><th><h6 style="width: 250px;">Production House</h6></th><th><h6>Action</h6></th></tr>
                        <tr ng-repeat="invitation in invitations">
                            <td>
                                <a href="{{baseUrl + '/castingCall/view/' + invitation.castingcall.url}}">
                                    <img style="height:50px;" ng-src="{{photoBaseUrl + '/s' + invitation.castingcall.photoUrl}}" alt="" />
                                </a>
                            </td>
                            <td></td>
                            <td>
                                <a href="{{baseUrl + '/castingCall/view/' + invitation.castingcall.url}}">
                                    {{invitation.castingcall.title}}
                                </a>
                            </td>
                            <td>
                                <a href="{{baseUrl + '/production/portfolio/' + invitation.castingcall.productionPortfolio.url}}">
                                    {{invitation.castingcall.productionPortfolio.name}}
                                </a>
                            </td>

                            <td>
                                <div class="btn-group" style="width:200px">
                                    <div class="btn-group">
                                        <a href="{{baseUrl + '/castingCall/view/' + invitation.castingcall.url}}" class="btn btn-large"><i class="icon-user"></i> View Casting Call</a>
                                        <button class="btn btn-large dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-left">
                                            <li><a ng-click="confirmDeleteInvitation(invitation.casting_call_invitationid)" href="#"><i class="icon-trash"></i> Delete Invitation</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </table>




                </div>

            </div>

            <div class="tab-pane" id="tab2">
                <!-- applications -->
                <div ng-hide="hasApplications()">
                    <br/>
                    <h6>Your have no applications.</h6>
                </div>

                <!-- Displays Artiste's Applications-->
                <div ng-show="hasApplications()">
                    <h2>Applications ({{applications.length}})</h2>


                    <table class="table table-hover c3-table-middle" style="margin-top:5px">
                        <tr><th><h6>#</h6></th><th><h6>Casting Call Title</h6></th><th><h6>Character Name</h6></th><th><center><h6>Status</h6></center></th><th><h6>Submission Date</h6></th></tr>
                        <tr ng-repeat="application in applications">
                            <td>
                                <a href="">{{application.character_applicationid}}</a>
                            </td>

                            <td>
                                <a href="{{baseUrl + '/artiste/apply?character=' + application.characterid}}"  ng-show="application.statusid == 7"> {{application.casting_call_title}}</a>
                                <a href="{{baseUrl + '/artiste/viewApplication?appid=' + application.character_applicationid}}"  ng-show="application.statusid == 8"> {{application.casting_call_title}}</a>
                            </td>
                            <td>
                                <a href="{{baseUrl + '/artiste/apply?character=' + application.characterid}}"  ng-show="application.statusid == 7"> {{application.character_name}}</a>
                                <a href="{{baseUrl + '/artiste/viewApplication?appid=' + application.character_applicationid}}"  ng-show="application.statusid == 8">{{application.character_name}}</a>
                            </td>
                            <td>
                        <center>
                            <div ng-show="application.statusid == 7" class="alert alert-warning c3-well-small" style="width:150px;text-align:center"><h4>Not Submitted</h4></div>
                            <div ng-show="application.statusid == 8" class="alert alert-success c3-well-small"  style="width:150px;text-align:center"><h4>Submitted</h4></div>
                        </center>
                        </td>
                        <td>
                            <a href="{{baseUrl + '/artiste/apply?character=' + application.characterid}}"  ng-show="application.statusid == 7">-</a>
                            <a href="{{baseUrl + '/artiste/viewApplication?appid=' + application.character_applicationid}}"  ng-show="application.statusid == 8">{{application.application_date}}</a>
                        </td>
                        </tr>
                    </table>


                </div>

            </div>
            <div class="tab-pane" id="tab3">
                <div ng-hide="hasAuditions()">
                    <br/>
                    <h6>You have no auditions.</h6>
                </div>
                <!-- Auditions -->
                <div ng-show="hasAuditions()">
                    <h2> Auditions ({{auditionInterviewees.length}})</h2>
                    <table class="table table-hover casting-call-list" style="margin-top:5px;">
                        <tr><th><h6>Casting Call</h6></th><th><h6>Audition Title</h6></th><th style="width:120px;"><h6>Application Period</h6></th><th><center><h6>Selected Slot</h6></center></th><th><h6>Action</h6></th></tr>
                        <tr ng-repeat="auditionee in auditionInterviewees">
                            <td style="vertical-align:middle">
                                <h5>{{auditionee.audition.casting_call.title}}</h5>
                                        </td>
                                        <td style="vertical-align:middle">
                                            <h5>{{auditionee.audition.title}}</h5>
                                        </td>
                                        <td>
                                            <div class="label label-success">Start: {{auditionee.audition.start}}</div>
                                            <div class="label label-important" style="margin-top:5px;">End&nbsp; : {{auditionee.audition.end}}</div>
                                        </td>
                                        <td>
                                        <center>
                                            <div ng-show="!auditionee.slot" class="alert alert-warning c3-well-tiny" style="width:140px;text-align:center"><h4>Not Submitted</h4></div>
                                            <div ng-show="auditionee.slot" class="alert alert-success c3-well-tiny"  style="width:140px;text-align:center"><h4>{{auditionee.slot.date}} <br/><span style="font-size:11px">{{auditionee.slot.startTime}} - {{auditionee.slot.endTime}}</span></h4></div>
                                        </center>
                                        </td>
                                        <td>
                                            <!-- displays if schedule has yet to be submitted -->
                                            <div ng-show="auditionee.slot.fixed != 1 && !auditionee.audition.hasExpired && auditionee.audition.hasStarted && !auditionee.slot">
                                                <a href="{{baseUrl + '/audition/apply/' + auditionee.auditionid}}" style="width:100px" class="btn btn-medium"><i class="icon-calendar"></i> Select a slot</a>
                                            </div>
                                            <div ng-show="!auditionee.audition.hasStarted && auditionee.slot.fixed != 1">
                                                <button class="btn btn-medium disabled" style="width:120px">Not Open Yet</button>
                                            </div>
                                            <div ng-show="auditionee.audition.hasExpired && auditionee.slot.fixed != 1">
                                                <button class="btn btn-medium disabled" style="width:120px">Closed</button>
                                            </div>
                                            <div ng-show="auditionee.slot.fixed == 1 || (auditionee.slot && auditionee.reselectable_slots==0)">
                                                <button class="btn btn-medium disabled" style="width:120px">Slot Fixed</button>
                                            </div>
                                            <!-- displays if schedule has been submitted -->
                                            <div ng-show="auditionee.slot.fixed != 1 && !auditionee.audition.hasExpired && auditionee.audition.hasStarted && auditionee.slot && auditionee.reselectable_slots==1">
                                                <a href="{{baseUrl + '/audition/apply/' + auditionee.auditionid}}" style="width:100px" class="btn btn-medium"><i class="icon-edit"></i> Change Slot</a>
                                            </div>

                                        </td>
                                        </tr>

                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        </div>





                                        </div>