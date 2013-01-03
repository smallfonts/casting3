
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/resetpwd_ctrl.js"></script>
<?php $this->beginContent('//layouts/main'); ?>
<div id="mainmenu">
  
    

</div><!-- mainmenu -->
<?php
$this->pageTitle=Yii::app()->name . ' - Reset Password';
$this->widget('bootstrap.widgets.BootBreadcrumbs', array(
                'links'=>array('Reset Password'),
                ));
?>
<h1>Reset Password</h1>

<span ng-controller="resetpwd_ctrl">
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ConfirmResetPasswordForm',
        'enableClientValidation' => true,
        'htmlOptions' => array('class' => 'well'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php $this->widget('bootstrap.widgets.BootAlert'); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'newPassword'); ?>
        <?php echo $form->passwordField($model, 'newPassword'); ?>
        <?php echo $form->error($model, 'newPassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'newPassword2'); ?>
        <?php echo $form->passwordField($model, 'newPassword2'); ?>
        <?php echo $form->error($model, 'newPassword2'); ?>
    </div>

    <div class="row buttons">
		<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'icon' => 'pencil', 'label' => 'Reset')); ?>
   </div>
   

</div>
     
<?php $this->endWidget(); ?>
    <?php $this->endContent(); ?>
</span>
