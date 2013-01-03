<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" style="height:100%" ng-app>
    <head id="head">

        <?php
        //disable auto loading of jquery
        Yii::app()->clientScript->scriptMap = array('jquery.js' => false,);
        Yii::app()->clientScript->scriptMap = array('jquery.js' => false,);
        ?>


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        
        
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
        
        <!-- jquery -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery-1.8.2.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery-ui-1.9.0.custom.min.js"></script>


        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-1.8.20.custom.css" />

        <!-- glyphicons -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/glyphicons/sprite_icons.css" />

        <!-- boostrap -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/css/bootstrap.css" />

        <!-- c3 css-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

        <!-- Alert Button Plugin -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/reveal/css/styles.css"/>

        <script>
            //a global variable used to store initialization functions that would be executed on page load
            var initArr = new Array();
            var baseUrl ='<?php echo Yii::app()->request->baseUrl; ?>';
            var photoBaseUrl = '<?php echo Yii::app()->params->photoBaseUrl; ?>';
        </script>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <!-- 
             IMPORT JAVASCRIPTS HERE
        -->

        
        <!-- Twitter Bootstrap -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap.min.js"></script>


        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/angular-1.0.0rc9.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/alerts.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap-tab.js"></script>

        <!-- JQuery notify (Growl-like notifications)-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.notify.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui.notify.css" />

        <!--Loading Plugin-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/loading.js"></script>

        <!--Hover Status Plugin-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/hover_status.js"></script>


        <!--Main Angular Controller-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/main_ctrl.js"></script>

        <!--Date Format JS-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/date.format.js"></script>
        

        <!-- Check_Alive Plugin -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/check_alive/check_alive.js"></script>

        <!-- Selectable and Draggable -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.core.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.selectable.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.draggable.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery.ui.droppable.js"></script>
        
        <!-- SideBar -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/sidebar/sidebar.css"/>

        
        <script>
            $(init_main);
            function init_main(){
                for(i in initArr){
                    initFunction = initArr[i];
                    initFunction();
                }
            }
        </script>
    </head>

    <body ng-controller="main_ctrl" id="c3main" style="height:100%;overflow-y:hidden">
        
        <div id="main_scroll" style="height:100%;overflow-y:scroll">
            
        <?php echo $content; ?>

        <div class="clear"></div>



        <span id="c3ExternalContent" style="width:100%;">
        </span>


        <!--alert templates-->
        <div id="c3alert" style="width:310px;position:absolute;z-index:9999999999999;top:40px; right:0px; display:none; margin:10px 10px 0 0; opacity:0.9">

            <div id="error" class="c3Notification c3Notification-error">
                <a class="close pull-right" href="#">×</a>
                <table>
                    <tr>
                        <td><i class="ico-error"></i></td>
                        <td style="vertical-align:top;padding-left:10px">#{text}</td>
                    </tr>
                </table>
            </div>
            
            <div id="star" class="c3Notification c3Notification-star"> 
                <a class="close pull-right" href="#">×</a>
                <a href="{{baseUrl}}/artiste/viewApplications" style="text-decoration:none;color:#C09853">
                <table>
                    <tr>
                        <td><i class="ico-star"></i></td>
                        <td style="vertical-align:top;padding-left:10px">#{text}</td>
                    </tr>
                </table>
                </a>
            </div>
            
            <div id="success" class="c3Notification c3Notification-success"> 
                <a class="close pull-right" href="#">×</a>
                <table>
                    <tr>
                        <td><i class="ico-tick"></i></td>
                        <td style="vertical-align:top;padding-left:10px">#{text}</td>
                    </tr>
                </table>

            </div>
            
            <div id="megaphone" class="c3Notification c3Notification-star"> 
                <a class="close pull-right" href="#">×</a>
                <a href="{{baseUrl}}/artiste/viewApplications" style="text-decoration:none;color:dimgrey">
                <table>
                    <tr>
                        <td><i class="ico-megaphone"></i></td>
                        <td style="vertical-align:top;padding-left:10px">#{text}</td>
                    </tr>
                </table>
                </a>
            </div>
            
            <div id="mail" class="c3Notification"> 
                <a class="close pull-right" href="#">×</a>
                <a href="{{baseUrl}}/messages" style="text-decoration:none;color:dimgrey">
                <table>
                    <tr>
                        <td><i class="ico-mail"></i></td>
                        <td style="vertical-align:top;padding-left:10px">#{text}</td>
                    </tr>
                </table>
                </a>
            </div>
        </div>

        <!--navbar alert templates-->
        <div id="c3NavBarAlert" style="display:none">
            <div id="c3NavBarError" class="c3-alertbar-error row-fluid">
                <div class="span3"></div>
                <div class="span1"><i class="ico-disconnect pull-right"></i></div>
                <div class="span5">
                    <h5>Looks like we have lost connection to Mother Earth. Some features may not work!<button class="close">×</button></h5>
                </div>
            </div>
            <div id="c3NavBarSuccess" class="c3-alertbar-success row-fluid">
                <div class="span3"></div>
                <div class="span1"><i class="ico-connect pull-right"></i></div>
                <div class="span5">
                    <h5>Connection to Mother Earth re-established!<button class="close">×</button></h5>
                </div>
            </div>
        </div>

        <div class="portfolioGuide" ></div>

        <div class="guide">

        </div>

        <!--navbar alert templates-->
        <div style="position:absolute;top:-1000px;width:0px;">
            <i class="ico-tick"></i>
            <i class="ico-star"></i>
            <i class="ico-mail"></i>
            <i class="ico-megaphone"></i>
            <i class="ico-error"></i>
            <i class="ico-disconnect pull-right"></i>
            <i class="ico-connect pull-right"></i>
        </div>

        <!--alert box-->

        <div id="c3Confirm" class="c3Confirm" style="opacity: 1; display:none; top: 50px; text-decoration:none;">
            <div id="heading">Confirmation Box</div>

            <div id="content">
                <p id="body"></p>

                <span href="#" id="c3Accept" class="c3ConfirmButton green"><img src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/reveal/images/tick.png">Okay!</span>

                <span href="#" id="c3Reject" class="c3ConfirmButton red"><img src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/reveal/images/cross.png">Cancel!</span>
            </div>
        </div>

        <div class="c3Confirm-modal-bg" style="display: none; cursor: pointer; "></div>
        </div>
    </body>
</html>
