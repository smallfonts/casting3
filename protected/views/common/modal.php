<!--
    modal API
    description: 
    This is a generic modal display for forms
    Use the following javascript syntax to activate
    the modal and load your form into it
    
    methods
    method 1: mInit(callback); //loads javascripts and executes callback after load
    method 2: m.show('url',callback); //loads the form from url and opens the model after load

    note: this modal has a default submit method which will display
          a c3_alert after it receives successful response.
         
    Usage Example:
    
    Eg: in local angular controller
    $scope.formModalLoaded=false; //checks whether content has been already loaded
    $scope.open = function(url){
        if(!$scope.formModalLoaded){
            $scope.loadContent(baseUrl+'/common/modal',function(){
                mInit(function(){
                    c3_m.show(url);
                });
            });
            $scope.formModalLoaded=true;
        }else{
            c3_m.show(url);
        }
    }

    Eg: in local view page 
    A button which will trigger $scope.open method and passing in the url value
    <a class="btn btn-info btn-mini" ng-click="open('<?php echo Yii::app()->request->baseUrl; ?>/site/resetPassword')">Reset Password Via Email</a>


    Eg: in Form page
    Displays the header name in the modal
    <input type="hidden" id="header_name" value="Change Email"/>
    <form>
    </form>
-->


<script>
    function mInit(callback){
        var scriptLoader;
        scriptLoader = new c3ScriptLoader();
        scriptLoader.addJavascript(baseUrl+'/js/controllers/common/modal_ctrl.js');
        scriptLoader.load(function(){
            angular.bootstrap($('#c3_m'), []);
            if(callback!=undefined){
                callback();
            }
        });
    }
</script>


<span id="c3_m" ng-controller="modal_ctrl">
    <div id="c3_m_modal" class="modal hide fade">

        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3 id="modal-header">{{header}}</h3>
        </div>

        <div id="modal-body" class="modal-body" ng-bind-html-unsafe="body"></div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" ng-click="close()">Close</a>
            <a href="#" class="btn btn-primary" ng-click="submit()">Submit</a>
        </div>
    </div>
</span>