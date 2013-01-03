
<?php
$this->pageTitle = Yii::app()->name . ' - Admin Console';
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/admin/admin_console_database_ctrl.js"></script>

<h3>Database Administration</h3>
<br/>
<div class="container-fluid" ng-controller="admin_console_database_ctrl">
    <div class="row-fluid">
        <div class="span2" style="padding-top:20px;">
            
            <a class="btn btn-danger" style="width:130px" ng-class="isDisabled()" ng-click="invoke('/admin/importEmpty',true)">Import database</a> <br/><br/>
            <a class="btn btn-danger" style="width:130px" ng-class="isDisabled()" ng-click="invoke('/admin/import',true)">Import test database</a> <br/><br/>
            <a class="btn btn-primary" style="width:130px" ng-class="isDisabled()" ng-click="invoke('/admin/export')">Export database</a> <br/><br/>
            <a class="btn btn-primary" style="width:130px" href="/phpmyadmin"> phpMyAdmin </a>
        </div>
        <div class="span9">
            <h6>Output</h6>
            <div class="well c3_body_well" style="width:100%;min-height:500px">
                <span ng-bind-html-unsafe="output"></span>
                <ng-include ng-show="loading" src="'<?php echo Yii::app()->baseUrl; ?>/common/loading'"></ng-include>
            </div>
        </div>
    </div>
</div>
