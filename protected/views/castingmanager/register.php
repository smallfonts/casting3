<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/register_ctrl.js"></script>

<script>
    var jsonCMPortfolio = <?php print_r($jsonCMPortfolio); ?>;
    
    $(function(){
        rc.init();
    })
</script>

<div ng-controller="register_ctrl">
    <br/><br/><br/>
    <div class="row-fluid">
        <div class="span2"> </div>
        <div class="span7 well c3_body_well">
            <h3 class="line">Casting Manager Self-Registration</h3>
            <div class="row-fluid">
                <div class="span4">
                    <div id="cmPhoto" class="c3-click thumbnail" style="width:150px" ng-click="changeFeaturedPhoto()">

                        <img ng-src="{{photoBaseUrl + '/l' + cmportfolio.photourl}}" alt="" />

                    </div>

                </div>
                <div class="span8">
                    <table class="c3-register pull-left">
                        <tr>
                            <th style="width: 100px">First Name</th>
                            <td><input style='width:200px' type="text" id="firstname" value="{{cmportfolio.first_name}}"/></td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td><input style='width:200px' type="text" id="lastname" value="{{cmportfolio.last_name}}"/></td>
                        </tr>
                        <tr>
                            <th>Company</th>
                            <td>{{cmportfolio.prodhouse}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{cmportfolio.email}}</td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td><input style='width:200px' type="password" id="pwd"/></td>
                        </tr>
                        <tr>
                            <th>Repeat Password</th>
                            <td><input style='width:200px' type="password" id="pwd2"/></td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td><input style='width:200px' type="text" id="mobile"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="pull-right"><button class="btn btn-primary pull-right" ng-click="validate()">&nbsp;Register&nbsp;</button></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
        <div class="span2"> </div>
    </div>
</div>
