
<script>
    var slcInitiated = false;
    function slcInit(callback){
        if (slcInitiated) return;
        slcInitiated = true;
        $('#c3_slc').hide();
        slcScriptLoader = new c3ScriptLoader();
        slcScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/search_latest_ctrl.js');
        slcScriptLoader.load(function(){
            angular.bootstrap($('#c3_slc'), []);
            
            slc.init();
            if(callback){
                callback();
            }
            
            $('#c3_slc').show();
        });
    }
    
</script>

<div id="c3_slc" ng-controller="search_latest_ctrl">
    
    <div ng-show="hasNoResults()">
        <h6>No results found. </h6>
    </div>

    <!-- Displays Casting Calls-->
    <div style="position:relative">
    <div ng-hide="{{searchCastingCallResults.length == 0}}">
        <div ng-repeat="result in searchCastingCallResults">
            <div ng-include="'CastingCallMainResultTemplate'"></div>
        </div>
    </div>


    <!-- Displays Artiste Portfolios-->
    <div ng-hide="{{searchArtistePortfolioResults.length == 0}}">
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


</div>