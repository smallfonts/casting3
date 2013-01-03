
<script>
    
    var featuredCharacters = <?php
if (isset($jsonFeaturedCharacters)) {
    echo $jsonFeaturedCharacters;
} else {
    echo "''";
}
?>;
    var featuredArtistePortfolios = <?php
if (isset($jsonFeaturedArtistePortfolios)) {
    echo $jsonFeaturedArtistePortfolios;
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
    function sscInit(callback){
        if(typeof sscScriptLoader != 'undefined') return;
        $('#c3_ssc').hide();
        sscScriptLoader = new c3ScriptLoader();
        sscScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/search_suggested_ctrl.js');
        sscScriptLoader.load(function(){
            angular.bootstrap($('#c3_ssc'), []);
            ssc.init();
            if(callback){
                callback();
            }
            $('#c3_ssc').show();
            sscScriptLoader = undefined;
        });
    };
    
</script>


<div id="c3_ssc" ng-controller="search_suggested_ctrl">
    <!--    <div >
            <h5>This segment displays some suggestions for you</h5>
        </div>-->

    <!-- Displays Casting Calls-->
    <div ng-hide="{{featuredCharacters == ''}}">
        <span ng-repeat="result in featuredCharacters">
            <div ng-include="'CastingCallResultTemplate'"></div>
        </span>
    </div>


    <!-- Displays Artiste Portfolios-->
    <div class="accordion" ng-hide="{{featuredArtistePortfolios == ''}}">

        <span ng-repeat="castingCall in featuredArtistePortfolios">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" href="#collapse{{$index}}">
                        <table style="width:100%"><tr>
                                <!--<td style="width:20px"><i class="icon-plus"></i></td>-->
                                <td style="width:20px">
                                    <div class="thumbnail c3-thumbnail-small">
                                        <img ng-src="{{photoBaseUrl + '/s' + castingCall.photoUrl}}" />
                                    </div>
                                </td>
                                <td style="padding-left:10px">
                                    <h3>{{castingCall.title}}</h3>
                                </td>
                            </tr></table>
                    </a>
                </div>

                <div id="collapse{{$index}}" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <div ng-repeat="character in castingCall.characters" class="row-fluid">
                            <div class="span12">
                                <h6 class="line">{{character.name}} ({{character.artistePortfolios.length}})</h6>
                                <span ng-repeat="result in character.artistePortfolios">
                                    <div ng-include="'ArtistePortfolioResultTemplate'"></div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>
    <center>
        <div ng-show="!hasResults">
            <h6>End of results.</h6>
        </div>
        <div ng-show="suggested_loading">
            <ng-include src="'<?php echo Yii::app()->baseUrl; ?>/common/loading'"></ng-include>
        </div>
    </center>
</div>