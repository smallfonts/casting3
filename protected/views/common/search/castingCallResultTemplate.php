<style>
    .casting_call{
        padding:5px;
        margin-bottom:10px;
        border-radius:5px;
        color:black;
    }

    .casting_call a{
        color:black;
    }

    .casting_call:hover {
        cursor: pointer;
        background-color: whitesmoke;
    }
</style>
<div class="row-fluid">
    <div class="span11">
        <div class="casting_call row-fluid">
            <a href="{{baseUrl + '/castingCall/view/' + result.url}}">
                <div class="thumbnail span2">
                    <img ng-src="{{photoBaseUrl + '/m' + result.photoUrl}}"/>
                </div>
                <div class="span10">
                    <h2>{{result.title}}</h2>
                    <h3>Character name: {{result.character}}</h3>
                    <div style="text-align:justify;">
                        {{result.desc}} 
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="span1">
        <div class="well c3-well-tiny" style="width:15px">
                    <div class="c3-click" style="text-align:center;width:15px" rel="tooltip" data-placement="right" title="favourite">
                        <span ng-show="result.favourite.isFavourite"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png" ng-click="toggleFavouriteCharacter(result.characterid)"/></span>
                        <span ng-hide="result.favourite.isFavourite"><img class="c3-star" src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_empty.png" ng-click="toggleFavouriteCharacter(result.characterid)"/></span>
                    </div>
                    <div class="c3-click" style="margin-top:10px" rel="tooltip" data-placement="right" title="<div style='width:100px'>send message to casting manager</div>">
                        <i class="icon-envelope" ng-click="sendMessage(result.userid)"></i>
                    </div>
                </div>
    </div>
</div>

<script>
    $("[rel=tooltip]").tooltip();
</script>