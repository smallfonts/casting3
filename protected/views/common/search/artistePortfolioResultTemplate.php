<style>
    .artiste_portfolio {
        margin:10px;
        margin-top:0px;
        max-width:20%;
        min-width:100px;
    }

</style>


<div class="artiste_portfolio span2 pull-left">
    <div class="row-fluid">
        <div class="span10">
            <a href="{{baseUrl + '/artiste/portfolio/' + result.url}}" class="thumbnail" style="margin-left:0px;">
                <img ng-src="{{photoBaseUrl + '/m' + result.photoUrl}}"/>
            </a>
        </div>
        <div class="span2">
            <div class="well c3-well-tiny" style="width:15px">
                <div style="text-align:center;width:15px">
                    <span rel="tooltip" data-placement="right" title="favourite">
                        <span ng-show="result.favourite.isFavourite" style="width:10px;"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png" ng-click="toggleFavouriteArtistePortfolio(result.artiste_portfolioid)"/></span>
                        <span ng-hide="result.favourite.isFavourite" style="width:10px;"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_empty.png" ng-click="toggleFavouriteArtistePortfolio(result.artiste_portfolioid)"/></span>
                    </span>
                </div>
                <div class="c3-click" style="margin-top:10px">
                    <span rel="tooltip" data-placement="right" title="send message">
                        <i class="icon-envelope" ng-click="sendMessage(result.userid)"></i>
                    </span>
                </div>

                <div class="c3-click" style="margin-top:10px" id="invitebutton">
                    <span rel="tooltip" data-placement="right" title="invite to casting call">
                        <i class="icon-plus" ng-click="invite(result.artiste_portfolioid,result.name,result.photoUrl)"></i>
                    </span>
                </div>

            </div>
        </div>
    </div>
    <div class="row-fluid">
        <h5>{{result.name}}</h5>
    </div>

</div>

<script>
    $("[rel=tooltip]").tooltip();
</script>