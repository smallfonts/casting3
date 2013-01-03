<input type="hidden" id="header_name" value="Change Mobile Number"/>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ChangeMobileForm',
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
        <?php echo $form->labelEx($model, 'mobile'); ?>
        <?php echo $form->textField($model, 'mobile'); ?>
        <?php echo $form->error($model, 'mobile'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>