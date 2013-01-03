<!-- landing css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3_landing.css" />

<?php $this->pageTitle = Yii::app()->name; ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/index_ctrl.js"></script>
<script>
    var role = '<?php echo $model->role; ?>';
    var altRole = '<?php echo $altRole; ?>';
</script>
<span ng-controller="index_ctrl">
    <div class="well">
        <div class="container-fluid">
            <div class="row-fluid">
                <div id="c3placeholder" class="span9">
                    <div class="thumbnail">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/images/blue_centre.png">
                    </div>
                </div>
                <div class="span3">

                    <h5 style="display:inline;">I am a.. </h5><h3 style="text-transform: uppercase;color:#08C">{{role}}.</h3>
                    <h5 style=";display:inline"> Sign Me Up! </h5>

<!--                    <h3>Sign Up <small> {{role}}</small></h3>-->
                    <hr class="compressed"/>
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
                            <?php echo $form->label($model, 'email'); ?>
                            <?php echo $form->textField($model, 'email'); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'email2'); ?>
                            <?php echo $form->textField($model, 'email2'); ?>
                            <?php echo $form->error($model, 'email2'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'password'); ?>
                            <?php echo $form->passwordField($model, 'password'); ?>
                            <?php echo $form->error($model, 'password'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'password2'); ?>
                            <?php echo $form->passwordField($model, 'password2'); ?>
                            <?php echo $form->error($model, 'password2'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->hiddenField($model, 'role'); ?>
                        </div>

                        <div class="row buttons">
                            <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'icon' => 'ok', 'label' => 'Submit')); ?>
                        </div>

                        <hr class="lesscompressed"/>
                        <h6 style="display:inline;text-transform:none; "> OR Sign Up as an </h6> <strong><span style="font-size:12px; text-transform: uppercase;text-decoration:underline;color:#08C" ><?php echo CHTML::link($altRole, array('/site')) ?></span></strong>

<!--                        <h6>Sign up as an <?php echo CHTML::link($altRole, array('/site')) ?></h6>-->
                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                </div>
            </div>
        </div>
    </div>
</span>