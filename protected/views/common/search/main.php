<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/common/search/search_main_ctrl.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap-collapse.js"></script>
<style>
    .tab-pane>.well,
    .tab-pane>div>.well{
        background:white;
        min-height:400px;
    }

    .smc_loading {
        padding:20%;
    }
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages.js"></script>
<script> 
    var jsonLanguageProficiencies = <?php print_r($jsonLanguageProficiencies); ?>;
</script>
<script> 
    var jsonEthnicities = <?php print_r($jsonEthnicities); ?>;
</script>

<script>
    var favouriteArtistePortfolios = <?php echo $jsonFavouriteArtistePortfolios; ?>;
    var favouriteCharacters = <?php echo $jsonFavouriteCharacters; ?>;
    
    var roleid = <?php echo $model->roleid; ?>;

    $(function(){
        smc.init();
    });

</script>
<!--<span ng-controller="search_main_ctrl" id="c3_search_main">-->
<span id="c3_search_main" ng-controller="search_main_ctrl">
    <div class="container-fluid" style="height:500px">
        <div class="c3-group" style="position:fixed;width:250px;z-index:9;height:auto">
            <div class="header header-default" style="padding:10px">
                <h2><i class="ico-search"></i> Find <small ng-show="roleid == 4">Artistes</small><small ng-show="roleid == 1">Casting Calls</small></h2>
            </div>
            <div class="header" style="border-radius:0px;padding-left:10px">
                <h6>Search Filters</h6>
            </div>
            <div class="body" style="padding:10px">
                <div class="row-fluid" id="smcFilters">

                </div>
            </div>
        </div>
        <div class="row-fluid" style="height:100%">
            <div class="span3"></div>
            <div class="span9" style="padding-top:10px;padding-left:30px">
                <ul id="c3_smc_tab" class="nav nav-pills">
                    <li class="active">
                        <a data-toggle="tab" href="#smcFeaturedTab" ng-click="loadSuggested()">Suggested</a>
                    </li>
                    <li ng-show="roleid == 1">
                        <a data-toggle="tab" href="#smcLatestTab" ng-click="loadLatest()">Lastest Casting Calls</a>
                    </li>
                    <li><a data-toggle="tab" href="#smcFavouritesTab" ng-click="loadFavourites()">My Favourites</a></li>
                    <li><a data-toggle="tab" href="#smcResultsTab" ng-click="loadSearchResults()">Search Results</a></li>

                    <div ng-show="roleid == 4" class="pull-right">
                        <h6>Sort by:</h6> 
                        <select id="sort_options_artistes" style="width:175px">
                            <option value="default" selected="selected">Choose an Option</option>
                            <option id="sort_expDesc" value="sortExpDesc">Experience (Very-Least)</option>
                            <option id="sort_expAsc" value="sortExpAsc"></i> Experience (Least-Very)</option>
                            <option id="sort_nameAsc" value="sortNameAsc">Name (A-Z)</option>
                            <option id="sort_nameDesc" value="sortNameDesc"></i> Name (Z-A)</option>
                        </select> 
                    </div>
                </ul>
                <div class="tab-content" style="height:auto">
                    <div class="tab-pane fade active in" id="smcFeaturedTab">
                        <div ng-show="smcFeaturedLoading" style="min-height:550px" class="well">
                            <div ng-include="'<?php echo Yii::app()->baseUrl; ?>/common/loading'" class="smc_loading"></div>
                        </div>
                        <div ng-hide="smcFeaturedLoading" style="min-height:550px;" id="smcFeatured" class="well">

                        </div>

                    </div>
                    <div class="tab-pane fade" id="smcLatestTab">
                        <div ng-show="smcLatestLoading" style="min-height:550px" class="well">
                            <div ng-include="'<?php echo Yii::app()->baseUrl; ?>/common/loading'" class="smc_loading"></div>
                        </div>
                        <div ng-hide="smcLatestLoading" style="min-height:550px" id="smcLatest" class="well">

                        </div>

                    </div>
                    <div class="tab-pane fade" id="smcFavouritesTab">

                        <div ng-show="smcFavouritesLoading" style="min-height:550px" class="well">

                            <div ng-include="'<?php echo Yii::app()->baseUrl; ?>/common/loading'" class="smc_loading"></div>
                        </div>
                        <div ng-hide="smcFavouritesLoading" style="min-height:550px" id="smcFavourites" class="well">

                        </div>


                    </div>
                    <div class="tab-pane fade" id="smcResultsTab">
                        <div ng-hide="smcQuerying" class="hero-unit">
                            <h1>Search With Filters</h1>
                            <p>Use Search Filters on the left to retrieve search results</p>
                        </div>
                        <div ng-show="smcQuerying">
                            <div id="smcResults" style="min-height:550px" class="well">

                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>
    </div>



</span>


