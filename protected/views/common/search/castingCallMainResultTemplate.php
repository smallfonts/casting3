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
                    <div class="row-fluid">
                        <div class="span8" style="min-height:0px">
                            <span style="color:#999999; font-style:italic;"><strong>Production House:</strong> {{result.productionPortfolio.name}}</span>
                        </div>
                        <div class="span4" style="min-height:0px">
                            <span style="color:#999999; font-style:italic;" class="pull-right">{{result.created}}</span>
                        </div>
                    </div>
                    <div style="text-align:justify;">
                        {{result.desc}} 
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="span1">
        <div class="well c3-well-tiny" style="width:15px">
            <div class="c3-click" rel="tooltip" data-placement="right" title="<div style='width:100px'>send message to casting manager</div>">
                <i class="icon-envelope" ng-click="sendMessage(result.castingManagerPortfolio.userid)"></i>
            </div>
        </div>
    </div>
</div>

<script>
    $("[rel=tooltip]").tooltip();
</script>