
<!-- c3 css-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/production/user_management_ctrl.js"></script>

<script>
    var jsonProductionHouseUsers = <?php print_r($jsonProductionHouseUsers); ?>; 
</script>

<span ng-controller="user_management_ctrl">
    <div class="row-fluid line">
        <h2>User Management Dashboard</h2>
    </div>

    <h6>Invite Casting Manager</h6>
    <table class="well">
        <tr>
            <th class="c3usermanagement">First Name</th>
            <td class="c3usermanagement"><input style='width:200px' type="text" id="firstname"/></t>
            <th class="c3usermanagement">Last Name</th>
            <td class="c3usermanagement"><input style='width:200px' type="text" id="lastname"/></td>
        </tr>
        <tr>
            <th class="c3usermanagement">Email</th>
            <td class="c3usermanagement"><input style='width:200px' type="text" id="email"/></td>
            <td></td>
            <td class="c3usermanagement pull-right"><button class="btn btn-inverse" ng-click="sendInvite()">Invite</button></td>   
        </tr>
    </table>

    <br/>
    <h6>Manage Casting Managers</h6>
    <div class="pull-right">
        <button class="btn btn-primary" ng-click="resendInvitation()">Resend Invitation</button>&nbsp;&nbsp;
        <button class="btn btn-primary" ng-click="suspend()">Suspend</button>&nbsp;&nbsp;
        <button class="btn btn-primary" ng-click="unsuspend()">Unsuspend</button>
    </div>

    <br/><br/>

    <table class="table table-hover">
        <tr><th><input type="checkbox" id  ="masterChkbx" ng-click="selectAll()"/></th><th></th><th>Casting Manager's Name</th><th><center>Status</center></th></tr>
        <tr ng-repeat="phu in phus" ng-click="select(phu.cm_userid)" id="phu"{{phu.cm_userid}}><td style="vertical-align: middle;"><input type="checkbox" class="chkbx" id="{{phu.cm_userid}}"  ng-click="select(phu.cm_userid)"/></td>
            <td></td>
            <td style="vertical-align: middle;">{{phu.casting_manager_portfolio.first_name}}&nbsp;{{phu.casting_manager_portfolio.last_name}}</td>
            <td><center>
            <div ng-show="phu.casting_manager_portfolio.statusid == 1" class="alert alert-success c3-well-tiny" style="width:200px;text-align:center"><h4>Active</h4></div>
            <div ng-show="phu.casting_manager_portfolio.statusid == 3" class="alert alert-error c3-well-tiny"  style="width:200px;text-align:center"><h4>Suspended</h4></div>
            <div ng-show="phu.casting_manager_portfolio.statusid == 10" class="alert alert-warning c3-well-tiny" style="width:200px;text-align:center"><h4>Rejected</h4></div>
            <div ng-show="phu.casting_manager_portfolio.statusid == 15" class="alert alert-warning c3-well-tiny" style="width:200px;text-align:center"><h4>Invited</h4></div>
        </center>
        </td></tr>
    </table>



</span>