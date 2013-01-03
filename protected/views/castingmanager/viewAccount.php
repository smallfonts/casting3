
<style>

    .profile th{
        text-align:left;
        vertical-align: top;
        width:75px;
    }

    .profile td{
        text-align:left;
    }

    .crop { width: 260px; height: 180px; overflow: hidden; }
    .crop img { max-width:100%; margin: -18% 0 0 0; }
    .first_column {
        width:100px;
        text-align: left;
    }
    .second_column {
        width: 10%;
        text-align:left;
        padding-left: 20px;
    }
    .third_column {
        padding-left:30px;
        text-align: left;
    }
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/castingmanager/view_cm_portfolio_ctrl.js"></script>
<script>
    var jsonPortfolio = <?php print_r($jsonPortfolio); ?>; 
    $(function(){vcpc.init()});
</script>

<div ng-controller="view_cm_portfolio_ctrl" class="well c3_body_well" style="width:1020px">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div id="c3_profilepic" class="thumbnail c3-click" ng-click="changeFeaturedPhoto()">
                    <img style="width:100%" ng-src="{{photoBaseUrl + '/' + portfolio.photourl}}">
                </div>
                <br/>
            </div>
            <div class="span10">
                <div class="row-fluid line">
                    <table><tr><td>
                                <h1>{{portfolio.first_name}} {{portfolio.last_name}}</h1></td><td style="padding-left:10px">
                                <button class="btn btn-mini btn-info" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changeName')">change</button> 
                            </td></tr></table>
                </div>
                <div class="row-fluid">

                    <table>
                        <tr>
                            <th class="first_column">Email</th>
                            <td class="second_column"><?php echo $model->email; ?></td>
                            <td class="third_column">
                                <a id="changeEmail" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changeEmail')">Change</a>
                            </td>
                        </tr>
                        <tr>
                            <th class="first_column">Password</th>
                            <td class="second_column">********</td>
                            <td class="third_column">
                                <a id="changePassword" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changePassword')">Change</a>

                            </td>
                        </tr>
                        <tr>
                            <th class="first_column">Mobile Number</th>
                            <td class="second_column">{{portfolio.mobile}}</td>
                            <td class="third_column">
                                <a id="changePassword" data-toggle="modal" class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/common/changeMobile')">Change</a>

                            </td>
                        </tr>
                    </table>

                </div>


            </div>


        </div>

    </div>