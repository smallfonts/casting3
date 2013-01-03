<style>

</style>

<script>
    
    /*  Copy the following to your local controller to extend setVideo feature
     *  //loads modal to change profile pic
        //view: '../commo/setVideo'
        //
        //

            $scope.selectInterviewees = function(){
                if(typeof sac === 'undefined'){
                    $scope.loadContent('../common/selectArtiste',function(){
                        sacInit(params);
                    });
                } else {
                    epc.changeProfilePicInitCallback();
                }
            }
     */
    var characters = <?php print_r($jsonCharacters); ?>;
    var applicants = <?php print_r($jsonApplicants); ?>;
    function sacInit(params,callback){
        var sacScriptLoader = new c3ScriptLoader();
        sacScriptLoader.addJavascript(baseUrl+'/js/controllers/common/select_artiste_ctrl.js');
        sacScriptLoader.addJavascript(baseUrl+'/js/lib/multiselect/js/jquery.multi-select.js');
        sacScriptLoader.addStylesheet(baseUrl+'/js/lib/multiselect/css/multi-select.css');
        sacScriptLoader.load(function(){
            angular.bootstrap($('#c3_sac'), []);
            if(params != undefined){
                sac.setParams(params);
            }
            
            if(callback != undefined){
                callback();
            }
        });
    }
</script>

<span id="c3_sac" ng-controller="select_artiste_ctrl">
    <div class="modal hide fade" data-backdrop="static" id="c3_sac_modal" style="width:700px;height:auto;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="onClose()">×</button>
            <h3>Select Interviewees</h3>
        </div>
        <div class="modal-body" style="max-height:none;height:auto;">

            <div class="row-fluid">
                <div class="input-prepend">
                    <span class="add-on" style="width:70px">Character</span>
                    <select ng-model="selectedCharacter" ng-options="character.characterid as character.name for character in characters" style="margin-left:-5px">
                    </select>
                </div>
            </div>

            <div class="row-fluid">
                <span class="c3-group span6">
                    <div class="header" style="text-align:center"><h6>Applicants</h6></div>
                    <div class="body" style="height:400px">
                        <table class="table table-condensed">
                            <tr ng-repeat="applicant in applicants" ng-show="!isSelected(applicant.artiste_portfolioid) && (applicant.characterid == selectedCharacter || selectedCharacter == 0)" class="c3-click" ng-click="selectApplicant($index)"><td><div style="width:20px" class="thumbnail c3-thumbnail-small"><img ng-src="{{photoBaseUrl + '/s' + applicant.photoUrl}}" /></div></td>
                                <td style="vertical-align:middle"><h4>{{applicant.name}}</h4></td>
                                <td style="vertical-align:middle"><i class="icon-plus"></i></td>
                            </tr>
                        </table>
                    </div>
                </span>
                <span class="c3-group span6">
                    <div class="header" style="text-align:center"><h6>Invited Applicants</h6></div>
                    <div class="body" style="height:400px">
                        <table class="table table-condensed">
                            <tr ng-repeat="applicant in applicants" ng-show="isSelected(applicant.artiste_portfolioid)" class="c3-click" ng-click="unselectApplicant($index)"><td><div style="width:20px" class="thumbnail c3-thumbnail-small"><img ng-src="{{photoBaseUrl + '/s' + applicant.photoUrl}}" /></div></td>
                                <td style="vertical-align:middle"><h4>{{applicant.name}}</h4></td>
                                <td style="vertical-align:middle"><i ng-show="canUninvite(applicant.artiste_portfolioid)" class="icon-minus"></i></td>
                            </tr>
                        </table>
                    </div>
                </span>
            </div>

        </div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" ng-click="onClose()">Close</a>
        </div>
    </div>
</span>


