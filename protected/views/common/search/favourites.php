<script>
    
    var favouriteCharacters = <?php
if (isset($jsonFavouriteCharacters)) {
    echo $jsonFavouriteCharacters;
} else {
    echo "''";
}
?>;
    var favouriteArtistePortfolios = <?php
if (isset($jsonFavouriteArtistePortfolios)) {
    echo $jsonFavouriteArtistePortfolios;
} else {
    echo "''";
}
?>;
    var fscInitiated = false;
    function fscInit(callback){
        if (fscInitiated) return;
        fscInitiated = true;
        $('#c3_fsc').hide();
        sscScriptLoader = new c3ScriptLoader();
        sscScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/favourite_search_ctrl.js');
        sscScriptLoader.load(function(){
            angular.bootstrap($('#c3_fsc'), []);
            fsc.init();
            if(callback) {
                callback();
            }
            $('#c3_fsc').show();
        });
    };
    
</script>


<span id="c3_fsc" ng-controller="favourite_search_ctrl">
    <div ng-show="hasNoResults()">
        <h6>You don't have any favourites yet.</h6>
    </div>
    
    <!-- Displays Casting Calls-->
    <div ng-hide="{{favouriteCharacters == ''}}">
        <span ng-repeat="result in favouriteCharacters">
            <div ng-include="'CastingCallResultTemplate'"></div>
        </span>
    </div>


    <!-- Displays Artiste Portfolios-->
    <div ng-hide="{{favouriteArtistePortfolios == ''}}">
        <span ng-repeat="result in favouriteArtistePortfolios">
            <span ng-include="'ArtistePortfolioResultTemplate'"></span>
        </span>
    </div>
    
    <div style="clear:both"></div>
</span>