<input type="hidden" id="header_name" value="Change Email"/>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ChangeEmailForm',
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
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'newEmail'); ?>
        <?php echo $form->textField($model, 'newEmail'); ?>
        <?php echo $form->error($model, 'newEmail'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>