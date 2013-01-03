<style>
    .artiste_portfolio {
        margin:10px;
        margin-top:0px;
        max-width:20%;
        min-width:150px;
    }
    
    .result_detail {
        max-width:200px;
        text-align:center;
    }
    
    .result_detail div:nth-child(2) {
        padding-left:5px;
        text-align:left;
    }

</style>


<span class="artiste_portfolio span3 pull-left">
    <div class="row-fluid">
        <a href="{{baseUrl + '/artiste/portfolio/' + result.url}}" class="thumbnail" style="margin:10px;margin-left:0px;">
                <img style="width:100%;" ng-src="{{photoBaseUrl + '/m' + result.photoUrl}}"/>
        </a>
    </div>
    <div class="row-fluid">
        <div class="result_detail">
                <h3>{{result.name}}</h3>
        </div>
    </div>
</span>