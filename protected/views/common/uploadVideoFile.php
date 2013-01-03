<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" ng-app>
    <head>
        <!-- boostrap -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/css/bootstrap.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

        <!--c3css-->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/c3.css" />

        <!-- angular -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/angular-1.0.0rc9.min.js"></script>

        <!-- uniform js for styling upload file button -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/uniform/css/uniform.default.css" />
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/common/upload_video_file_ctrl.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/main_ctrl.js"></script>

        <style>
            .subtitle {
                font-family: Optima, Segoe, "Segoe UI", Candara, Calibri, Arial, sans-serif;
                font-size:10px;
                color:grey;
            }

            .table tbody tr:nth-child(2):hover td{
                background:none;
            }
        </style>
        <script>
            randToken = '<?php echo $randToken; ?>';
            baseUrl = '<?php echo Yii::app()->getBaseUrl() ?>';
        </script>
    </head>
    <body ng-controller="upload_video_file_ctrl" style="overflow-y:hidden">
        <form id="uvf_form" action="<?php echo Yii::app()->session['postUrl'] ?>?nexturl=<?php echo Yii::app()->getBaseUrl(true) ?>/common/confirmUpload?randToken=<?php echo $randToken; ?>" method="post" enctype ="multipart/form-data">
            <table class="table table-condensed">
                <tr>
                    <td style="width:6px;padding-right:0px"><span class="c3-tab"></span></td>
                    <td><h6>Upload New <br/><small>video</small></h6></td>
                    <td> <input id="uvf_file" name="file" type="file"/> <br/><div ng-show="hasError" style="font-size:10px;color:#B81111">{{extension}} is not a valid video format!</div>&nbsp;</td>
                    <td><input id="uvf_submit" type="button" ng-click="uploadVideo()" class="btn btn-primary btn-medium {{canUpload}}" value="Upload Video" /></td></tr>
                <tr>
                    <td colspan="4" style="text-align:center"><h3>OR</h3></td>
                </tr>
                <tr>
                    <td style="width:6px;padding-right:0px"><span class="c3-tab"></span></td>
                    <td><h6>Choose Existing <br/><small>Youtube video</small></h6></td>
                    <td style="max-width:200px">
                        <input style="height:30px" type="text" ng-model="video.link" ng-change="newVideoUrl()" placeholder="Insert Youtube Link"></input>
                        <div style="margin-top:-10px" class="subtitle">eg. http://www.youtube.com/watch?v=D9zq6Ggzjoc</div>
                        <div ng-show="canSetUrl==''">
                            <table>
                                <tr>
                                    <td><img style="width:60px;" ng-src="http://img.youtube.com/vi/{{video.url}}/0.jpg" /></td>
                                    <td style="max-width:140px;"><span class="subtitle">{{video.title}}</span></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td><input type="button" class="btn btn-primary btn-medium {{canSetUrl}}" ng-click="submitLink()" value="Submit Link" /></td></tr>
            </table>
            <input type="hidden" name="token" id="token_value" value="<?php echo Yii::app()->session['tokenValue']; ?>"/>
            <div id="youtube_data">
            </div>
        </form>
    </body>
</html>