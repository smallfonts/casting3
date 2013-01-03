<input type="hidden" id="header_name" value="View Videos"/>
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

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/view_portfolio_ctrl.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap-carousel.js"></script>

<script>
    var jsonPortfolio = <?php print_r($jsonPortfolio); ?>; 
    var jsonProfilePic = <?php print_r($jsonProfilePic); ?>;
    var jsonVideos = <?php print_r($jsonVideos); ?>;
    var jsonFeaturedPhotos = <?php print_r($jsonFeaturedPhotos); ?>;
    initArr.push(function(){
    });
    
</script>

<?php
    $jsonStatusMsg = null; 
if ($jsonStatusMsg != null){
    $jsonStatusMsg = $_GET['jsonStatusMsg'];
    $log = new Logger(get_class($this));
    $log->setMethod(__FUNCTION__);
    $log->loginfo('jsonStatusMsg - ' . $jsonStatusMsg);
} 
?>

<span ng-controller="view_videos_ctrl">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <div class="thumbnail">
                    <img style="height:200 px;width:200px" src="{{profilePic.url}}">
                </div>
                <br/>
                <?php if ($isOwner) { ?>
                    <div class="well" style="padding:8px 0;">
                        <ul class="nav nav-list">
                            <li class="nav-header">
                                Actions
                            </li>
                            <li>
                                <a href="<?php echo Yii::app()->request->baseUrl; ?>/artiste/editPortfolio">
                                    <i class="icon-cog"></i> Edit Portfolio
                                </a>
                            </li>


                            <li>
                                <a href="#">
                                    <i class="icon-plus"></i> Add Experiences
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Yii::app()->request->baseUrl; ?>/artiste/UploadVideo">
                                    <i class="icon-plus"></i> Add Videos
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-plus"></i> Add Photos
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="well" style="padding:8px 0;">
                        <ul class="nav nav-list">
                            <li class="nav-header">
                                Actions
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-glass"></i> Invite User
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-pencil"></i> Send Message
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-flag"></i> Bookmark
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="span9">
                <div class="row-fluid line">
                    <div class="span9">
                        <h1>{{portfolio.name}}</h1>
                    </div> 
                </div>
                <ul class="nav nav-pills" style="margin:5px">
                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/artiste/viewPortfolio">Summary</a></li>
                    <li><a href="#">Experiences</a></li>
                    <li class="active"><a href="#">Videos</a></li>
                    <li><a href="#">Photos</a></li>
                </ul>
                <div class="row" style="padding-left:20px">
                    <div class="span12">


                        <?php
                        if($jsonStatusMsg != null){
                            if ($jsonStatusMsg == "Added") {
                                ?>
                                <span class="label label-success" > Congratulations! Video is successfully added! Please wait for Youtube to upload it.</span>
                            <?php } else { ?>
                                <?php echo $jsonStatusMsg; ?>
                                <span class="label label-important">Sorry, failed to add video. Please try again!</span>
                            <?php } 
                        } ?>

                        <div class="well">
=======
                        



                            <?php
                            $videosArr = json_decode($jsonVideos);
                            if (count($videosArr) == 0){
                                echo ("You have no videos in your portfolio. <a href='".Yii::app()->request->baseUrl."/artiste/UploadVideo'>Add some now!</a>");
                            }
                            foreach ($videosArr as $item) {
                                $url = 'https://gdata.youtube.com/feeds/api/videos/' . $item->url . '?v=2&alt=json';
                                $xml = file_get_contents($url);
                     
                                $JSON_Data = json_decode($xml);
                                $title = $JSON_Data->{'entry'}->{'media$group'}->{'media$title'}->{'$t'};
                                $desc = $JSON_Data->{'entry'}->{'media$group'}->{'media$description'}->{'$t'};
                                $views = $JSON_Data->{'entry'}->{'yt$statistics'}->{'viewCount'};
                                $favs = $JSON_Data->{'entry'}->{'yt$statistics'}->{'favoriteCount'};
                                ?>
                            <div class="well">
                            <h3 class="line"><?php echo $title ?></h3><br/>
                                <table><tr><td class="aligntop">
                                    <object width=160" height="125" >
                                        <param name="movie"
                                               value="https://www.youtube.com/v/<?php echo $item->url; ?>?version=3&autohide=1"></param>
                                        <param name="allowScriptAccess" value="always"></param>
                                        <embed src="https://www.youtube.com/v/<?php echo $item->url; ?>?version=3&autohide=1"
                                               type="application/x-shockwave-flash"
                                               allowfullscreen="true"
                                               allowscriptaccess="always"
                                               width="160" ></embed>
                                    </object>
                                </td><td>
                                <table class="table-condensed">
                                    <tr>
                                        <th width="30%"><i class="icon-film"></i>&nbsp;&nbsp;&nbsp;Description</th>
                                        <td  width="70%"><?php echo $desc ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%"><i class="icon-eye-open"></i>&nbsp;&nbsp;&nbsp;Views</th>
                                         <td  width="70%"><?php echo $views ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%"><i class="icon-star"></i>&nbsp;&nbsp;&nbsp;Favorited</th>
                                         <td  width="70%"><?php echo $favs ?></td>
                                    </tr>
                               
                                </table>
                                
                                <span class="floatRight">
                                    <a class="btn btn-danger btn-mini"  data-toggle="modal" ng-click="open('<?php echo Yii::app()->request->baseUrl?>/artiste/DeleteVideo?videoid=<?php echo $item->videoid ?>')"><i class="icon-trash icon-white"></i> Delete</a>
                                    <a class="btn btn-primary btn-mini" data-toggle="modal" ng-click="open('<?php echo Yii::app()->request->baseUrl?>/artiste/SetFeaturedVideo?videoid=<?php echo $item->videoid ?>')"><i class="icon-bookmark icon-white"></i> Set as featured video</a>
                                </span>
                                </td></tr></table>
                            </div>
                                <?php
                            }
                            ?>
                                
                       

                    </div>

                </div>

            </div>
        </div>
    </div>
</span>