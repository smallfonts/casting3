
<style>

    .profile th{
        text-align:left;
        vertical-align: top;
        width:75px;
    }

    .profile td{
        text-align:left;
    }

    .crop { width: 260px; height: 180px; overflow: hidden; }
    .crop img { max-width:100%; margin: -18% 0 0 0; }

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/view_prod_portfolio_ctrl.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap-carousel.js"></script>

<script>
    var jsonPortfolio = <?php print_r($jsonPortfolio); ?>; 
    var jsonProfilePic = <?php print_r($jsonProfilePic); ?>;
    
    initArr.push(function(){
        $('.carousel').carousel();
    });
</script>

<span ng-controller="view_prod_portfolio_ctrl">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="thumbnail">
                    <img style="width:100%" src="{{photoBaseUrl + '/' + profilePic.url}}">
                </div>
                <br/>
            </div>
            <div class="span10">
                <div class="row-fluid line">
                    <div class="span9">
                        <h1>{{portfolio.name}}</h1>
                    </div>
                    <div class="span3">
                       <?php if ($isOwner) { ?><a class="btn btn-primary btn-small pull-right" href="<?php echo Yii::app()->request->baseUrl; ?>/production/editPortfolio"><i class="icon-cog icon-white"></i> &nbsp;Edit</a><?php } ?>
                    </div>     

                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <span style="color:#999999; font-style:italic;">{{portfolio.products}}</span>
                        <br/>
                        <div style="text-align:justify">
                            {{portfolio.description}}
                        </div>
                        <a href="{{portfolio.website}}" target="_blank">{{portfolio.website}}</a>

                    </div>
                </div>
                <br/>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="accordion-group">
                            <h3 style="padding:8px">&nbsp;<i class="ico-envelope"></i>&nbsp;&nbsp;Name Card</h3>

                            <table class="table ">
                                <tr><th>Company</th><td>{{portfolio.name}}</td></tr>
                                <tr><th>Address</th><td>{{portfolio.address}} <br/>{{portfolio.address2}} <br/> {{portfolio.country}}&nbsp;{{portfolio.postalcode}}</td></tr>
                                <tr><th>Email</th><td><a href="mailto:{{portfolio.email}}" target="_blank">{{portfolio.email}}</a></td></tr>
                                <tr><th>Phone</th><td>{{portfolio.phone}}</td></tr>
                                <tr><th>Website</th><td><a href="{{portfolio.website}}" target="_blank">{{portfolio.website}}</a></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="span6">
                        <iframe class="thumbnail" style="width:100%;height:250px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" ng-src="http://maps.google.com.sg/maps?q={{portfolio.country}}+{{portfolio.postalcode}}&amp;hl=en&amp;hnear={{portfolio.postalcode}}&amp;t=m&amp;ie=UTF8&amp;hq=&amp;z=16&amp;output=embed&iwloc=near"></iframe>
                        <a class="btn btn-mini" href="http://maps.google.com.sg/maps?q={{portfolio.country}}+{{portfolio.postalcode}}&amp;hl=en&amp;hnear={{portfolio.postalcode}}&amp;t=m&amp;ie=UTF8&amp;hq=&amp;z=17&amp;source=embed">View Larger Map</a></small>
                    </div>

                </div>


            </div>


        </div>

</span>