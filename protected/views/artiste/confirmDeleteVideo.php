<input type="hidden" id="header_name" value="Delete video?"/>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'DeleteVideoForm',
        'enableClientValidation' => true,
        'htmlOptions' => array('class' => 'well'),
       
            ));
    ?>

    <p class="note">Are you sure you want to delete this video?</p>
    <p class="note">Your video will only be deleted on this website and will not be removed from the respective Youtube account.</p>

    <?php $this->widget('bootstrap.widgets.BootAlert'); ?>

    <div class="row">
        <input type="hidden" name="videoid"><?php $model->videoid?></input>
        <?php
         $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
         $log->logInfo("view videoid: ".$model->videoid);
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div>