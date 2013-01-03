
<!-- Select2 Plugin -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.css" />


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/nicEdit/nicEdit.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/messages/new_message_ctrl.js"></script>

<style>
    body {
        background:white;
        overflow-x:hidden;
        overflow-y:hidden;
        height:500px;
    }

    .message-header-top {
        padding:10px;
        background-color: #FAFAFA;
        background-image: -moz-linear-gradient(top, white, #F2F2F2);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(white), to(#F2F2F2));
        background-image: -webkit-linear-gradient(top, white, #F2F2F2);
        background-image: -o-linear-gradient(top, white, #F2F2F2);
        background-image: linear-gradient(to bottom, white, #F2F2F2);
        background-repeat: repeat-x;
        border: 1px solid #D4D4D4;
        filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffffff', endColorstr='#fff2f2f2', GradientType=0);
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
    }

    .message-header {
        padding:5px;
        padding-top:10px;
        background: #2C2C2C;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1);
        -moz-box-shadow: 0 1px 3px rgba(0,0,0,.25), inset 0 -1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1);
    }

    .select2-container{
        top:11px;
        margin-left:-5px;
    }

    .select2-container-multi .select2-choices{
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
    }
</style>

<script>
    var message = <?php print_r($message); ?>;
    var to = <?php print_r($to); ?>;
</script>

<span id="c3_mc" ng-controller="new_message_ctrl">
    <div class="message-header-top" style="height:25px">
        <button class="pull-right btn btn-small btn-success {{sendBtnClass()}}" ng-click="sendMessage()"><i class="{{sendBtnIcon}} icon-white"></i> {{sendBtn}}</button>
    </div>
    <div class="message-header">

        <div class="input-prepend">
            <span class="add-on" style="width:50px;height:19px;">To</span>
            <input id="c3Recipients" type="text" ng-model="message.to" style="margin-left:-5px;width:87%"/>
        </div>
        <div class="input-prepend">
            <span class="add-on" style="width:50px">Subject</span>
            <input id="application_start" type="text" maxLength="58" ng-model="message.title" style="margin-left:-5px;width:85%"/>
        </div>
    </div>
    <div>
        <textarea id="messageBody" ng-mode="message.body" style="width:100%;height:311px;"></textarea>
    </div>
</span>

<!-- templates -->
<span class="c3Template">
    <span id="c3UserSearchResult">
        <div class="row-fluid"><span class="span1 thumbnail c3-thumbnail-small"><img #attributes# /></span><span class="span11"><strong>#name#</strong><br/><small>#email#</small></span></div>
    </span>
</span>


