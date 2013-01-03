
<?php
$this->pageTitle = Yii::app()->name . ' - Upload Video';
/* $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
  'homeLink' => CHtml::link('Home', array($this->home)),
  'links' => array('Account'),
  )); */
?>

<h1>Edit Profile - Videos</h1>
<br/>
<br/>
<h3>Upload Video</h3>
<hr style="margin-top:3px"/>


    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'UserAccount',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
        ?>

        <?php $this->widget('bootstrap.widgets.BootAlert'); ?>

        <div class="row">
            <?php echo $form->label($model, 'videoTitle'); ?>
            <?php echo $form->textField($model, 'videoTitle'); ?>
            <?php echo $form->error($model, 'videoTitle'); ?>
        </div>

        <div class="row">
            <?php echo $form->label($model, 'videoDescription'); ?>
            <?php echo $form->textField($model, 'videoDescription'); ?>
            <?php echo $form->error($model, 'videoDescription'); ?>
        </div>

        <div class="row">
            <?php echo $form->label($model, 'videoCategory'); ?>
            <?php echo $form->listBox($model, 'videoCategory', array('autoVehicles' => 'Autos & Vehicles', 'music' => 'Music', 'petsAnimals' => 'Pets & Animals', 'sports' => 'Sports', 'comedy' => 'Comedy')); ?>
            <?php echo $form->error($model, 'videoCategory'); ?>
        </div>

        <div class="row">
            <?php echo $form->label($model, 'videoTags'); ?>
            <?php echo $form->textField($model, 'videoTags'); ?>
            <?php echo $form->error($model, 'videoTags'); ?>
        </div>

        <div class="row buttons">
            <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'icon' => 'ok', 'label' => 'Submit')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
