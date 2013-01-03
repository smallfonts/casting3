<?php $this->beginContent('//layouts/main'); ?>

<script>
    
<?php
$portfolio = ArtistePortfolio::model()->findByAttributes(array(
    'userid' => Yii::app()->user->account->userid
        ));
$tinyPortfolio = array(
    'name' => $portfolio->getName(),
    'photoUrl' => $portfolio->photo->url
);
?>
        
        var tinyPortfolio = <?php echo json_encode($tinyPortfolio); ?>;
    
        $(function(){
            $.get(baseUrl+'/messages/countUnread',function(data){
                if(data != '' && data != 0){
                    var html = simpleTemplate('c3Count',{
                        '#count#' : data
                    });
                    $('#messagesBadge').html(html);
                }
            });
        
            c3MainCtrl.tinyPortfolio = tinyPortfolio;
            c3MainCtrl.$apply();
        });
</script>

<span id="c3Template">
    <span id="c3Count">
        <span class="badge badge-important">#count#</span>
    </span>
</span>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand">Casting3</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="<?php echo Yii::app()->request->baseUrl . "/artiste/portfolio/" . Yii::app()->user->account->artistePortfolio->url; ?>">Portfolio</a></li>
                    <li><a href="<?php echo Yii::app()->request->baseUrl . "/artiste/viewApplications"; ?>">Applications</a></li>
                    <li><a style="min-width:90px" href="<?php echo Yii::app()->request->baseUrl . "/messages"; ?>">Messages <span id="messagesBadge"></span></a></li>
                    <li><a href="<?php echo Yii::app()->request->baseUrl . "/common/search"; ?>">Find Casting Calls</a></li>
                </ul>
                <span class="pull-right">
                    <ul class="nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" style="padding:0px" data-toggle="dropdown" href="#">
                                <table class="c3-tiny-profile">
                                    <tr>
                                        <td><img ng-src="{{photoBaseUrl + '/s' + tinyPortfolio.photoUrl}}" style="width:20px"></img></td>
                                        <td style="min-width:100px">{{tinyPortfolio.name}}</td>
                                        <td><i class="caret"></i></td>
                                    </tr>
                                </table>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo Yii::app()->request->baseUrl . "/artiste/portfolio/" . Yii::app()->user->account->artistePortfolio->url; ?>"><i class="icon-user"></i> Portfolio</a></li>
                                <li><a href="<?php echo Yii::app()->request->baseUrl . "/artiste/account"; ?>"><i class="icon-cog"></i> Account Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo Yii::app()->request->baseUrl . "/common/logout"; ?>">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
</div>

<div id="sb_content" class="sb_sidebox">
    <span class="close pull-right" onclick="$('#sb_content').fadeOut()">×</span>
    <h5>Welcome to Casting3!</h5>
    <p>Please tell us how we make your experience here even better!</p>
    <p><small>P.S This is really important for us and we really appreciate your awesomeness! =)<small></p>
    <button class="btn btn-small btn-primary" onclick="window.open('http://edu.surveygizmo.com/s3/1097950/Artiste-Feedback-Field-Test','','width=950,height=800')">Link to Evaluation Form</button>
</div>

<div class="container c3-fixed-padding" id="page">
    <div id="c3_pageAlert"></div>
    <?php echo $content; ?>
</div>


<?php $this->endContent(); ?>
