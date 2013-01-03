<input type="hidden" id="header_name" value="Set Video"/>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ConfirmSetVideoForm',
        'enableClientValidation' => true,
        'htmlOptions' => array('class' => 'well'),
       
            ));
    ?>

    <p class="note">Are you sure you want to set this as your Featured video?</p>

    <?php $this->widget('bootstrap.widgets.BootAlert'); ?>

    <div class="row">
        <input type="hidden" name="videoReply" value="Yes">
    </div>

    <?php $this->endWidget(); ?>

</div>