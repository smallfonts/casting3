<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/login_ctrl.js"></script>

<h1>Log In</h1>

<p>Please fill out the following form with your login credentials:</p>
<span ng-controller="login_ctrl">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'htmlOptions' => array('class' => 'well c3_body_well'),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>

        <div class="row-fluid">
            <div class="span4">
                <div class="row">
                    <?php echo $form->label($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email'); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>

                <div class="row">
                    <?php echo $form->label($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password'); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>

                <div class="row buttons">
                    <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'icon' => 'ok', 'label' => 'Login')); ?>
                </div>
            </div>
            <div class="span8">
                <div class="row">
                    <h1>New User? <small>Create your own account for free!</small></h1>
                </div>
                <div class="row-fluid">
                    <div class="span6 well c3_body_well">
                        <div class="row-fluid" style="margin-bottom:10px;">
                            <div class="span3">
                                <div class="thumbnail c3-thumbnail-small">
                                    <img style="width:60px" ng-src="{{baseUrl + '/images/artiste.png'}}" />
                                </div>
                            </div>
                            <div class="span9">
                                <h1 style="padding-top:5px;line-height:22px">Artiste</h1>
                            </div>
                        </div>
                        <a class="btn btn-large btn-primary" href="{{baseUrl + '/site/index'}}">Sign Up As an Artiste</a>
                    </div>
                    <div class="span6 well c3_body_well">
                        <div class="row-fluid" style="margin-bottom:10px;">
                            <div class="span3">
                                <img style="width:50px" ng-src="{{baseUrl + '/images/Oak3Films.png'}}" />
                            </div>
                            <div class="span9">
                                <h1 style="padding-top:5px;line-height:22px">Production House</h1>
                            </div>
                        </div>
                        <a class="btn btn-large btn-primary" href="{{baseUrl + '/site/productionHouse'}}">Sign Up As a Production House</a>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        <br/>

        <div class="row">
            Forgot your password? 
            <a id="resetPassword" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/site/resetPassword')">Reset Password Via Email</a>
        </div>

    </div><!-- form -->
</span>
