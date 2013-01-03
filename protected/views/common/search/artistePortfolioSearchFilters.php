

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/countries.js"></script>


<script>
    console.info('loading page');
    function apsfInit(callback){
        console.info('apsfInit');
        $('#c3_apsf').hide();
        aspfScriptLoader = new c3ScriptLoader();
        aspfScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/filter_artiste_portfolio_ctrls.js');
        aspfScriptLoader.load(function(){
            angular.bootstrap($('#c3_apsf'), []);
            $('#c3_apsf').show();
        });
    };
</script>

<span id="c3_apsf" class="span12">
    <div ng-controller="filter_artiste_portfolio_name_ctrl">
        <h6>Artiste Name</h6>
        <input style="width:80%" id="fapncName" placeholder="Name" type="text" ng-model="name" ng-change="addName()">
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_age_ctrl">
        <h6>Artiste Age</h6>
        <table class="table-condense" style="margin-top:5px;">
            <tr>
                <td><input id="fapcMinAge" style="width:50px" placeholder="Min" type="number" min="0" max="125" ng-model="searchAge.min" ng-change="addAges()"></td>
                <td style="padding:5px"><h6>To</h6></td>
                <td><input id="fapcMaxAge" style="width:50px" placeholder="Max" type="number" min="0" max="125" ng-model="searchAge.max" ng-change="addAges()">
                </td>
            </tr>
        </table>
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_gender_ctrl">
        <h6>Artiste Gender</h6>
        <div class="checkbox" style="margin-top:0px">
            <label class="checkbox inline">
                <input id="c3_gender_m" style="float:left; display:inline;" type="checkbox" name="Gender" value="male" ng-click="queryGender()"/> Male
            </label>
            <label class="checkbox inline">
                <input id="c3_gender_f" style="float:left; display:inline;" type="checkbox" name="Gender" value="female" ng-click="queryGender()"/> Female
            </label>
        </div>


    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_languages_ctrl">
        <h6>Artiste Languages</h6>
        <input id="faplcLanguage" style="width:90%" ng-model="searchLanguage" placeholder="Language" type="hidden">
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_nationality_ctrl">
        <h6>Artiste Nationality</h6>
        <input id="fapncNationality" style="width:90%" ng-model="nationality" placeholder="Nationality" type="hidden">
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_profession_ctrl">
        <h6>Artiste Profession</h6>
        <input id="fappcProfessions" style="width:90%" ng-model="profName" placeholder="Profession" type="hidden">
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_skills_ctrl">
        <h6>Artiste Skills</h6>
        <input id="fapscSkills" style="width:90%" ng-model="searchSkills" placeholder="Skills" type="hidden">
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_artiste_portfolio_ethnicity_ctrl">
        <h6>Artiste Ethnicity</h6>
        <input id="fapecEthnicity" style="width:90%" ng-model="ethnicity" placeholder="Ethnicity" type="hidden">
    </div>
</span>