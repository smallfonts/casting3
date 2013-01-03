<style>

    .hidden{
        display:none;
    }
</style>

<script>
    
    /*  Copy the following to your local controller to extend setVideo feature
     *  //loads modal to change profile pic
        //view: '../commo/setVideo'
        //
        //

            $scope.addVideo = function(){
                $('#c3_profilepic').loading(true,function(){
                    if(typeof svc === 'undefined'){
                        $scope.loadContent('../common/setVideo',function(){
                            svcInit(params);
                        });
                    } else {
                        epc.changeProfilePicInitCallback();
                    }
                });
            }
     */
    function iacInit(params,callback){
        spScriptLoader = new c3ScriptLoader();
        spScriptLoader.addJavascript(baseUrl+'/js/controllers/castingmanager/invite_artiste_ctrl.js');
        spScriptLoader.load(function(){
            angular.bootstrap($('#c3_iac'), []);
            if(params){
                iac.setParams(params);
            }
            
            if(callback != undefined){
                callback();
            }
        });
    }
</script>

<span id="c3_iac" ng-controller="invite_artiste_ctrl">
    <div class="modal hide fade" data-backdrop="static" id="c3_iac_modal" style="width:700px;height:auto;">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" ng-click="closeModal()">×</button>
            <table><tr><td style="width:25px">
                        <img class="thumbnail c3-thumbnail-small" ng-src="{{photoBaseUrl + '/s' + photoUrl}}"/>
                    </td><td style="padding-left:10px">
                        <h6 style="margin-bottom:-8px;">Invite</h6>
                        <h2 style="margin-top:0px">{{name}}</h2>
                    </td></tr></table>


        </div>
        <div class="modal-body">
            <span>
                <h6>Artiste has been invited to</h6>
                <table class="table table-condensed">
                    <tr ng-show="noInvitedCastingCalls()"><td></td><td></td></tr>
                    <tr ng-repeat="castingCall in castingCalls" ng-show="castingCall.invited">
                        <td style="width:70px"><button class="btn btn-warning btn-mini" ng-click="uninviteArtiste(castingCall.casting_callid)">un-invite</button></td><td><h5>{{castingCall.title}}</h5></td>
                    </tr>
                </table>
            </span>
            <h6>Invite Artiste To Apply For The Follwing Casting Calls</h6>
            <table class="table table-condensed">
                <tr ng-show="noAvailableCastingCalls()"><td></td><td></td></tr>
                <tr ng-repeat="castingCall in castingCalls" ng-show="!castingCall.invited">
                    <td style="width:70px"><button class="btn btn-success btn-mini" ng-click="inviteArtiste(castingCall.casting_callid)">Invite</button></td><td><h5>{{castingCall.title}}</h5></td>
                </tr>
            </table>

        </div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" ng-click="closeModal()">Close</a>
        </div>
    </div>
</span>


