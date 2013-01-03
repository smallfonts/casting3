<?php $this->beginContent('//layouts/main'); ?>

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
                    <li><a href="<?php echo Yii::app()->request->baseUrl."/admin"; ?>">Management Console</a></li>
                    <li><a href="<?php echo Yii::app()->request->baseUrl."/admin/account"; ?>">Account</a></li>
                    
                </ul>
                <ul class="nav pull-right">
                    <li><a href="<?php echo Yii::app()->request->baseUrl."/common/logout"; ?>">Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
    
<div class="container c3-fixed-padding" id="page">
    <div id="c3_pageAlert"></div>
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
