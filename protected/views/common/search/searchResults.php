
<script>
    var srcInitiated = false;
    function srcInit(callback){
        if (srcInitiated) return;
        srcInitiated = true;
        $('#c3_src').hide();
        srcScriptLoader = new c3ScriptLoader();
        srcScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/search_results_ctrl.js');
        srcScriptLoader.load(function(){
            angular.bootstrap($('#c3_src'), []);
            
            if(callback){
                callback();
            }
            
            $('#c3_src').show();
        });
    }
    
</script>

<div id="c3_src" ng-controller="search_results_ctrl">
    <div ng-show="hasNoResults()">
        <h6>No results found. </h6>
    </div>

    <!-- Displays Casting Calls-->
    <div style="position:relative">
    <div ng-hide="{{searchCharacterResults == ''}}">
        <div ng-repeat="result in searchCharacterResults">
            <div ng-include="'CastingCallResultTemplate'"></div>
        </div>
    </div>


    <!-- Displays Artiste Portfolios-->
    <div ng-hide="{{searchArtistePortfolioResults == ''}}">
        <div ng-repeat="result in searchArtistePortfolioResults">
            <div ng-include="'ArtistePortfolioResultTemplate'"></div>
        </div>
    </div>
    </div>
    <div ng-show="result_loading && !end_of_results" style="clear:both">
        <ng-include src="'<?php echo Yii::app()->baseUrl; ?>/common/loading'"></ng-include>
    </div>
    
    <div ng-show="end_of_results" style="clear:both">
        <center><hr/>
        <h6>End of results</h6><br/></center>
    </div>
    <div style="clear:both"></div>

</div>