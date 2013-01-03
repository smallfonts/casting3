<style>

    .hidden{
        display:none;
    }

    #dropzone.in {
        font-size: larger;
    }
    #dropzone.hover {
        background: #eeeeee;
    }
    #dropzone.fade {
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -ms-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
        opacity: 1;
    }

    .fileselect{
        position: absolute;
        border: solid transparent;
        opacity: 0;
        filter: alpha(opacity=0);
        cursor: pointer;
    }
</style>

<script>
    
    /*  Copy the following to your local controller to extend setPhoto feature
     *  //loads modal to change profile pic
        //view: '../commo/setPhoto'
        //
        //
        $scope.changeProfilePicLoaded=false;
        $scope.changeProfilePic = function(){
            if(!$scope.changeProfilePicLoaded){
                $scope.loadContent('../common/setPhoto',function(){
                    sppcInit(function(){
                        sppc.setSubmitCallback(function(data){
                            $scope.profilePic = angular.fromJson(data);
                            $scope.$apply();
                        });
                        sppc.show();
                    });
                });

                $scope.changeProfilePicLoaded=true;
            }else{

                sppc.show();

            }
        }
     * 
     */
    
    var uploadifyEnc = encodeURIComponent("<?php echo $enc; ?>");
    var setFeaturedPhotoAction = "<?php echo Yii::app()->baseUrl ?>/common/setFeaturedPhoto";
    
    function sppcInit(callback){
        spScriptLoader = new c3ScriptLoader();
        spScriptLoader.addJavascript(baseUrl+'/js/controllers/common/set_portfolio_pic_ctrl.js');
        spScriptLoader.addJavascript(baseUrl+'/js/lib/jcrop/jquery.Jcrop.min.js');
        spScriptLoader.addJavascript(baseUrl+'/js/lib/jcrop/jquery.color.js');
        spScriptLoader.addJavascript(baseUrl+'/js/lib/uploadify/jquery.uploadify-3.1.min.js');
        spScriptLoader.addStylesheet(baseUrl+'/css/jquery.Jcrop.min.css');
        spScriptLoader.addStylesheet(baseUrl+'/js/lib/uploadify/uploadify.css');
        spScriptLoader.load(function(){
            angular.bootstrap($('#c3_sppc'), []);
            sppc.init();
            if(callback != undefined){
                callback();
            }
        });
    }
</script>

<span id="c3_sppc" ng-controller="set_portfolio_pic_ctrl">
    <div class="modal hide fade" data-backdrop="static" id="c3_cpp_modal" style="width:700px;height:auto;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="closeModal()">×</button>
            <h3>Upload Photo</h3>
        </div>
        <div class="modal-body">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" id="c3_cpp_tab">
                    <li>
                        <a href="#cpp_1" data-toggle="tab" ng-click="initUploadify()">Step 1: Select Photo</a>
                    </li>
                    <li ng-show="cropPhoto">
                        <a href="#cpp_2">Step 2: Edit Photo</a>
                    </li>
                </ul>

                <div class="tab-content" style="height:auto">
                    <div class="tab-pane fade" id="cpp_1">
                        <div class="tabbable"><!--
                            <ul class="nav nav-pills" id="c3_cpp_pill">
                                <li>
                                    <a href="#cpp_pill_1" data-toggle="tab">New Photo</a>
                                </li>
                                <li><a href="#cpp_pill_2" data-toggle="tab" ng-click="getAllPhotos()">existing Photo</a></li>
                            </ul>
                            <div class="tab-content" style="height:auto">
                                <div class="tab-pane fade" id="cpp_pill_1">
-->
                                    <div id="dropzone" class="well" style="text-align:center;width:400px;border: 5px dashed #aaaaaa">
                                        <span ng-hide="msie || safari">
                                            <h3>Drag a picture Here</h3>
                                            <br/>
                                            <h5>OR</h5>
                                            <br/>
                                        </span>
                                        <center>
                                            <?php
                                            $form = $this->beginWidget(
                                                    'CActiveForm', array(
                                                'id' => 'photo_upload',
                                                'enableAjaxValidation' => false,
                                                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                    )
                                            );

                                            echo $form->hiddenField($model, 'x1', array('id' => 'c3_x1', 'val' => '{{cropped.coords.x1}}'));
                                            echo $form->hiddenField($model, 'x2', array('id' => 'c3_x2', 'val' => '{{cropped.coords.x2}}'));
                                            echo $form->hiddenField($model, 'y1', array('id' => 'c3_y1', 'val' => '{{cropped.coords.y1}}'));
                                            echo $form->hiddenField($model, 'y2', array('id' => 'c3_y2', 'val' => '{{cropped.coords.y2}}'));
                                            echo $form->hiddenField($model, 'width', array('id' => 'c3_width', 'val' => '{{cropped.cropWidth}}'));
                                            echo $form->hiddenField($model, 'height', array('id' => 'c3_height', 'val' => '{{cropped.cropHeight}}'));
                                            ?>
                                            <?php echo $form->fileField($model, 'image', array('id' => 'fileselect', 'class' => '{{fileselectClass}}')); ?>
                                            <?php $this->endWidget(); ?>
                                        </center>
                                        <a id="fileinput" class="btn btn-large btn-primary file-input" ng-hide="msie || safari">Click here to upload a photo

                                        </a>

                                    </div>

<!--
                                </div>
                                <div class="tab-pane fade" id="cpp_pill_2">

                                    <ul class="thumbnails">
                                        <li ng-repeat="photo in photos">
                                            <div class="thumbnail">
                                                <img src="{{photoBaseUrl + '/m' + photo.url}}" ng-click="activateCrop({url:photo.url, photoExists:true})"/> 
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
-->
                        </div>
                    </div>

                    <div class="tab-pane fade" style="height:auto;overflow:hidden" id="cpp_2">
                        <span style="position:absolute;">
                            <div style="position:relative;top:80px;left:-140px;text-align:center">
                                <div class="thumbnail">
                                    <div id="croppreviewholder" style="overflow:hidden;">
                                        <img id="croppreview" src="{{cropped.imagesrc}}" style="max-width:none;" />
                                    </div>
                                </div>
                            </div>
                        </span>
                        <div style="min-height:250px;">
                            <center><img id="crop" src="{{cropped.imagesrc}}" style="background:white;"/></center>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="modal-footer">
            <span id="uploadingStatus" class="{{barClass}}">
                <h6>Uploading Photo</h6>
                <div class="progress progress-striped {{progressBarClass}}
                     active">

                    <div class="bar"
                         style="width: {{percentage}}%;"></div>

                </div>
            </span>
            <a href="#" class="btn" data-dismiss="modal" ng-click="closeModal()">Close</a>
            <a href="#" class="btn {{submitClass}}" ng-click="submit()" ng-hide="!cropPhoto" >Save changes</a>
        </div>
    </div>
    <?php
    $form = $this->beginWidget(
            'CActiveForm', array(
        'id' => 'ie_photo_upload',
        'enableAjaxValidation' => false,
            )
    );

    echo $form->hiddenField($model, 'x1', array('value' => '{{cropped.coords.x1}}'));
    echo $form->hiddenField($model, 'x2', array('value' => '{{cropped.coords.x2}}'));
    echo $form->hiddenField($model, 'y1', array('value' => '{{cropped.coords.y1}}'));
    echo $form->hiddenField($model, 'y2', array('value' => '{{cropped.coords.y2}}'));
    echo $form->hiddenField($model, 'width', array('value' => '{{cropped.cropWidth}}'));
    echo $form->hiddenField($model, 'height', array('value' => '{{cropped.cropHeight}}'));
    echo $form->hiddenField($model, 'url', array('value' => '{{cropped.photourl}}'));
    ?><?php
    $this->endWidget();
    ?>
    <div id="test_output">
    </div>
</span>


