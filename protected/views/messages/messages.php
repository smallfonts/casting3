<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/messages/messages_ctrl.js"></script>

<style>

    .unread {
        width: 10px;
        height: 10px;
        border-radius: 50%;

        background-color: #FAA732;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FBB450), to(#F89406));
        background-image: -webkit-linear-gradient(top, #FBB450, #F89406);
        background-image: -o-linear-gradient(top, #FBB450, #F89406);
        background-image: linear-gradient(to bottom, #FBB450, #F89406);
        background-image: -moz-linear-gradient(top, #FBB450, #F89406);
        background-repeat: repeat-x;
        border-color: #F89406 #F89406 #AD6704;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

        -moz-box-shadow: 0 0  2px #888;
        -webkit-box-shadow: 0 0 2px #888;
        box-shadow: 0 0 2px #888;
    }

    .messageSummaryContainer{
        z-index:10000;
        position:absolute;
        width:320px;
        -moz-box-shadow: 3px 0  15px #888;
        -webkit-box-shadow: 3px 0 15px #888;
        box-shadow: 3px 0 15px #888;
        border:2px solid lightgray;
    }

    .messageSummary td:nth-child(1){
        vertical-align: middle;
        width:10px;
    }

    .messageSummary td:nth-child(2){
        width:30px;
    }

    .messageSummary td:nth-child(2) div{
        width:30px;
    }
    
    .message-header tr td{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 13px;
        padding-bottom: 4px;
    }
    
    .message{
        max-width:98%;
    }

    .message .c3-group{
        -moz-box-shadow: 0 0 20px #888;
        -webkit-box-shadow: 0 0 20px#888;
        box-shadow: 0 0 20px #888;
    }

    .c3-group{
        border:3px;
        -webkit-border-radius: 3px 3px 3px 3px;
        -moz-border-radius: 3px 3px 3px 3px;
        border-radius: 3px 3px 3px 3px;
    }

    .header-warning{
        -webkit-border-radius: 2px 0 0 0;
        -moz-border-radius: 2px 0 0 0;
        border-radius: 2px 0 0 0;
    }

    .messageContainer{
        position:absolute;
        z-index:0;
        left:-30px;
        width:100%;
        height:620px;
        overflow-y:scroll;
        padding-top:10px;
        padding-left:25px;
        -moz-box-shadow: 0 0 10px #888;
        -webkit-box-shadow: 0 0 10px#888;
        box-shadow: 0 0 10px #888;
    }
</style>

<span id="c3_mc" ng-controller="messages_ctrl" style="height:100%">
    <div class="row-fluid">
        <span class="span12">
            <button class="btn btn-info btn-medium" ng-click="refresh()"><i class="icon-refresh icon-white"></i></button>
            <button class="btn btn-primary btn-medium" ng-click="newMessage()">Create New Message</button>
        </span>
    </div>

    <div class="row-fluid" style="margin-top:10px;height:100%">
        <div class="span4 c3-group" style="position:relative;height:650px;">
            <div class="messageSummaryContainer">
                <div class="header header-warning"><h6><i class="icon-envelope icon-white"></i> My Inbox</h6></div>
                <div id="summary" class="body" style="height:620px;overflow-y:scroll;padding:0px">
                    <center  ng-show="messageSummary.length == 0">
                        <br/>
                        <h6><small>You have no messages</small></h6></center>
                    <span ng-show="messageSummary.length > 0">
                        <table class="table table-condensed">
                            <tr class="messageSummary c3-click" ng-repeat="message in messageSummary" ng-click="showMessage(message.message.messageid,$index)">
                                <td>
                                    <div ng-show="message.status.statusid == 18" class="unread"></div>
                                </td>
                                <td><div class="thumbnail c3-thumbnail-small"><img ng-src="{{photoBaseUrl + '/s' + message.message.photoUrl}}"/></div></td> 
                                <td>
                                    <span class="pull-right"><h6>{{message.message.created}}</h6></span>
                                    <strong>{{message.message.name}}</strong>
                                    <div>{{message.message.title}}</div>
                                </td>
                            </tr>
                        </table>
                    </span>
                    <div ng-show="loading">
                        <ng-include src="'<?php echo Yii::app()->baseUrl; ?>/common/loading'"></ng-include>
                    </div>
                </div>
            </div>
        </div>
        <div class="span8" style="position:relative;margin-top:10px">
            <div class="messageContainer">
                <div ng-show="messages" class="message">
                    <div class="c3-group" style="margin-bottom:10px;{{getMessageStyle($index)}}}" ng-repeat="message in messages">
                        <div class="header header-default">
                            <table>
                                <tr>
                                    <td><div style="width:40px;" class="thumbnail c3-thumbnail-small"><img ng-src="{{photoBaseUrl + '/s' + message.sender.photoUrl}}" /></div></td>
                                    <td style="width:600px;vertical-align:top">
                                        <button class="pull-right btn btn-small btn-success" ng-click="reply(message.messageid)"><i class="icon-share-alt icon-white"></i> reply</button>
                                        <table class="message-header">
                                            <tr><th style="text-align:right">From:</th><td>{{message.sender.name}} [ {{message.sender.email}} ]</td>
                                            <tr><th style="text-align:right">Date:</th><td>{{message.sent}}</td></tr>
                                            <tr><th style="text-align:right">Subject:</th><td>{{message.title}}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="body" style="padding:10px;background:white" ng-bind-html-unsafe="message.body">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</span>


