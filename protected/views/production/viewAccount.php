
<?php
$this->pageTitle = Yii::app()->name . ' - View Account';
/*$this->widget('bootstrap.widgets.BootBreadcrumbs', array(
    'homeLink' => CHtml::link('Home', array($this->home)),
    'links' => array('Account'),
));*/
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/view_account_ctrl.js"></script>

<style>
    .first_column {
        width:100px;
    }
    .second_column {
        width: 10%;
        text-align:left;
    }
    .third_column {
        padding-left:30px;
        text-align: left;
    }
</style>

<script>
    /*
     * To initialize functions, push them into initArr, which will be executed on page load.
     * initArr is executed in the init() function in the layout header. 
     * 
     * ie. initArr.push(function(){...})
     * 
     */
    
</script>

<h1>Account</h1>
<br/>
<br/>
<h3>Security</h3>
<hr style="margin-top:3px"/>

<span ng-controller="view_account_ctrl">

    <table>
        <tr>
            <th class="first_column">Email</th>
            <td class="second_column"><?php echo $model->email; ?></td>
            <td class="third_column">
                <a id="changeEmail" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changeEmail')">Change</a>
            </td>
        </tr>
        <tr>
            <th class="first_column">Password</th>
            <td class="second_column">********</td>
            <td class="third_column">
                <a id="changePassword" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changePassword')">Change</a>
               
            </td>
        </tr>
    </table>

</span>
