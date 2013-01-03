<?php $this->beginContent('//layouts/main'); ?>

<script>
    
    <?php
       
        $portfolio = CastingManagerPortfolio::model()->findByAttributes(array(
           'userid'=>Yii::app()->user->account->userid 
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
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span></a>
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand">Casting3</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="<?php echo Yii::app()->request->baseUrl."/castingCall/main"; ?>">Casting Calls</a></li>
                    <li><a style="min-width:90px" href="<?php echo Yii::app()->request->baseUrl."/messages"; ?>">Messages <span id="messagesBadge"></span></a></li>
                    <li><a href="<?php echo Yii::app()->request->baseUrl."/common/search"; ?>">Find Artistes</a></li>
                </ul>
                <span class="pull-right">
                <ul class="nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" style="padding:0px" data-toggle="dropdown" href="#">
                            <table class="c3-tiny-profile">
                                <tr>
                                    <td><img ng-src="{{photoBaseUrl + '/s' + tinyPortfolio.photoUrl}}" style="width:20px"></img></td>
                                    <td>{{tinyPortfolio.name}} <i class="caret"></i></td>
                                </tr>
                            </table>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo Yii::app()->request->baseUrl."/castingmanager/viewAccount"; ?>"><i class="icon-cog"></i> Account Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo Yii::app()->request->baseUrl."/common/logout"; ?>">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
                </span>
            </div>
        </div>
    </div>
</div>
    
<div class="container c3-fixed-padding" id="page">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
