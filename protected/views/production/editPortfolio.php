
<style>

    .profile th{
        text-align:left;
        vertical-align: top;
        width:75px;
    }

    .profile td{
        text-align:left;
        width: 600px;
        padding:5px 10px 5px 5px;
    }

    .crop { width: 260px; height: 180px; overflow: hidden; }
    .crop img { max-width:100%; margin: -18% 0 0 0; }

    .line {
        margin-bottom:10px;
    }

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/edit_prod_portfolio_ctrl.js"></script>
<script>
    var jsonPortfolio = <?php print_r($jsonPortfolio); ?>; 
    var jsonProfilePic = <?php print_r($jsonProfilePic); ?>;
    
    $(function(){eppc.init()});
</script>

<span ng-controller="edit_prod_portfolio_ctrl">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'edit-portfolio-form',
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <a style="width:220px;"id="c3_profilepic" class="thumbnail" rel="tooltip" title="Click photo to Edit" ng-click="changeProfilePic()">
                    <img style="width:100%" ng-src="{{photoBaseUrl + '/' + profilePic.url}}"/>
                </a>
                <br/>
            </div>
            <div class="span9">
                <div class="row-fluid line">
                    <div class="span9">
                        <h1>Edit Portfolio</h1>
                    </div>
                    <div class="span3">
                       <button class="btn btn-primary btn-small pull-right btn-info" type="submit"><i class="icon-folder-open icon-white"></i> Save Changes</button>
                    </div>   
                </div>
                <br/>
                <div class="row" style="padding-left:20px">
                    <div class="span10">
                        <div class="well">
                            <h6 class="line"><i class="icon-tag"></i> Url</h6>
                            <table class="profile" style="margin:0px;padding:0px">
                                <tr><th style="vertical-align:middle">/production/portfolio/</th><td><?php echo $form->textField($productionPortfolio, 'url',array('style'=>'width:80px', 'id'=>"urlTip", 'rel'=>"tooltip", 'title'=>"Check this out- Customize your personal URL. e.g. timberwerkz/production/portfolio/Oak3")); ?></td></tr>
                            </table>
        
                                <?php echo $form->error($productionPortfolio, 'url', array('style'=>'color:red')); ?>

                        </div>
                        <div class="well">
                            <h6 class="line"><i class="icon-user"></i> General Information</h6>

                            <table class="profile">
                                <tr><th>Name</th><td><?php echo $form->textField($productionPortfolio, 'name'); ?></td></tr>
                                <tr><th>Description</th><td><?php echo $form->textArea($productionPortfolio, 'description', array('style'=> "width:100%; height:90px")); ?></td></tr>
                                <tr><th>Products & Services</th><td><?php echo $form->textArea($productionPortfolio, 'products', array('style'=> "width:100%; height:90px")); ?></td></tr>
                                <tr><th>Website</th><td><?php echo $form->textField($productionPortfolio, 'website',array('style'=> "width:100%")); ?></td></tr>
                                
                            </table>


                            <h6 class="line"><i class="icon-envelope"></i> Contact</h6>
                            <table class="profile">
                                <tr><th>Address 1</th><td><?php echo $form->textField($productionPortfolio, 'address'); ?></td></tr>
                                <tr><th>Address 2</th><td><?php echo $form->textField($productionPortfolio, 'address2'); ?></td></tr>
                                <tr><th>Postal Code</th><td><?php echo $form->textField($productionPortfolio, 'postalcode'); ?></td></tr>
                                <tr><th>Country</th><td><?php echo $form->textField($productionPortfolio, 'country'); ?></td></tr>
                                <tr><th>Email</th><td><?php echo $form->textField($productionPortfolio, 'email'); ?></td></tr>
                                <tr><th>Phone</th><td><?php echo $form->textField($productionPortfolio, 'phone'); ?></td></tr>
                            </table>
 

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</span>