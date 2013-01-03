<input type="hidden" id="header_name" value="Change Password"/>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ChangePasswordForm',
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
        <?php echo $form->labelEx($model, 'oldPassword'); ?>
        <?php echo $form->passwordField($model, 'oldPassword'); ?>
        <?php echo $form->error($model, 'oldPassword'); ?>
    </div>

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

    <?php $this->endWidget(); ?>

</div>