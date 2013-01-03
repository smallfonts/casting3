<style>

    .hidden{
        display:none;
    }

    .fileselect{
        position: absolute;
        border: solid transparent;
        opacity: 0;
        filter: alpha(opacity=0);
        cursor: pointer;
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
    
    var hasToken = <?php echo $hasToken; ?>;
    var authUrl = "<?php echo $authUrl; ?>";
    var randToken = "<?php echo $randToken; ?>"
    var uploadifyEnc = encodeURIComponent("<?php echo $enc; ?>");
    
    function svcInit(params,callback){
        spScriptLoader = new c3ScriptLoader();
        spScriptLoader.addJavascript(baseUrl+'/js/controllers/common/set_video_ctrl.js');
        spScriptLoader.addJavascript(baseUrl+'/js/lib/uploadify/jquery.uploadify-3.1.min.js');
        spScriptLoader.addStylesheet(baseUrl+'/js/lib/uploadify/uploadify.css');
        spScriptLoader.load(function(){
            angular.bootstrap($('#c3_svc'), []);
            if(params){
                svc.setParams(params);
            }
            svc.init();
            
            if(callback != undefined){
                callback();
            }
        });
    }
</script>

<span id="c3_svc" ng-controller="set_video_ctrl">
    <div class="modal hide fade" data-backdrop="static" id="c3_svc_modal" style="width:700px;height:auto;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="closeModal()">×</button>
            <h3>Upload Video</h3>
        </div>
        <div class="modal-body">

            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" id="c3_svc_tab">
                    <li>
                        <a href="#svc_1">Step 1: Get Access</a>
                    </li>
                    <li>
                        <a href="#svc_2">Step 2: Upload Video</a>
                    </li>
                </ul>

                <div class="tab-content" style="height:auto">
                    <div class="tab-pane fade" id="svc_1">
                        <div class="well c3-well-small">
                            <table><tr><td>
                            <i class="ico-youtube"></i></td><td> <button class="btn btn-large btn-primary {{authBtnClass}}" style="width:200px" ng-click="startAuth()">
                                            <span ng-hide="authStarted">Upload Video To Youtube</span>
                                            <span ng-show="authStarted">Authenticating.....</span>
                                        </button>
                                    </td></tr></table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="svc_2">
                        <iframe id="submitVideo" style="border:none;width:100%;height:200px">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" ng-click="closeModal()">Close</a>
        </div>
    </div>
</span>


