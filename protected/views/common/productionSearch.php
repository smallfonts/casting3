<style>

    #searchButton button{
        height:40px;
        padding:5px;
    }

    .casting_call {
        margin-top:10px;
    }

</style>
<?php Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('artiste-portfolio-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="container-fluid" style="height:500px">
    <div class="row-fluid" style="height:100%">
        <div class="span3 well" style="height:100%">
            <div id="searchButton" class="btn-group">
                <button class="btn btn-info" ><i class="icon-search icon-white"></i>&nbsp;<strong>Find</strong></button>
                <button class="btn btn-info" >Artiste</button>
                <button class="btn dropdown-toggle btn-info" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">&nbsp;&nbsp;Find:&nbsp;&nbsp;&nbsp;Casting Calls </a></li>
                </ul>
            </div>
            <br/>
            <hr/>
            <div style="border-bottom: 1px">
                <div class="row-fluid">
                    <div class="span9">
                        <h6>Age</h6>
                    </div>
                    <div class="span3">
                        <button class="btn btn-mini">reset</button>
                    </div>
                </div>


                <div class="input-append">
                    <table >
                        <tr>
                            <td>
                                From 
                            </td>
                            <td>
                                <input style="float:left; width:60%" type="text"> 
                            </td>
                            <td>
                                To 
                            </td>
                            <td>
                                <input style="float:left; width:60%" type="text"> 
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <div class="row-fluid">
                    <div class="span9">
                        <h6>Gender</h6>
                    </div>
                    <div class="span3">
                        <button class="btn btn-mini">reset</button>
                    </div>
                </div>


                <div class="input-append">
                    <table >
                        <tr>
                            <td>
                                <input style="float:left;" type="radio" value="Male" name="gender"/> Male
                            </td>
                            <td>
                                <input style="float:left;" type="radio" value="Female" name="gender"/> Female
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <div class="row-fluid">
                    <div class="span9">
                        <h6>Spoken Language</h6>
                    </div>
                    <div class="span3">
                        <button class="btn btn-mini">reset</button>
                    </div>
                </div>

                <div class="input-append">
                    <input style="float:left; width:68%" placeholder="language" type="text"><button class="btn" type="button">Add</button>
                </div>
                <hr/>

                <div class="row-fluid">
                    <div class="span9">
                        <h6>Artiste Type</h6>
                    </div>
                    <div class="span3">
                        <button class="btn btn-mini">reset</button>
                    </div>
                </div>

                <div class="input-append">
                    <table>
                        <tr>
                            <td><input style="float:left; width:20%" type="checkbox" value="Voice" name="actorType"/> &nbsp; Voice Over Actor</td> 
                        </tr>
                        <tr>
                            <td><input style="float:left; width:20%" type="checkbox" value="Screen" name="actorType"/> &nbsp; Screen Actor</td>  
                        </tr>
                    </table>

                </div>
            </div>



        </div>
        <div class="span9" style="padding-top:10px;padding-left:10px">
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#">Head Hunter</a>
                </li>
                <li><a href="#">Your Favourites</a></li>
                <li><a href="#">Search Results</a></li>
            </ul>
            <br/>

            <div class="casting_call row-fluid">

                <div class="span8">
                    <h2>Snow White and the Seven Dwarves</h2>
                    <h3>Grumpy</h3>
                    <?php
                    $artistePortfolio = ArtistePortfolio::model()->findAllByAttributes(array(
                        'gender' => "Female"));

                    foreach ($artistePortfolio as $item) {
                        ?>
                        <div style="text-align:justify;">
                            <div class="thumbnail span4">
                                <img src="http://placehold.it/200x200"/>
                                <div class="span3">
                                    <div style="margin-top:10px;text-align:center">
                                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_empty.png"/>
                                    </div>
                                </div>
                                <div class="span8" style="margin-top:15px;text-align:center">
                                    <?php //echo count($artistePortfolio);
                                    //echo $item->name;
                                    echo $item->name; 
                                    ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
            </div>
            <hr/>

            <div class="casting_call row-fluid">

                <div class="span8">
                    <h2>Beauty & The Beast</h2>
                    <h3>Horrible Beast</h3>
                    <div style="text-align:justify;">
                        <div class="thumbnail span4">
                            <img src="http://placehold.it/200x200"/>
                            <div class="span3">
                                <div style="margin-top:10px;text-align:center">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png"/>
                                </div>
                            </div>
                            <div class="span8" style="margin-top:15px;text-align:center">
                                Artiste Name 
                            </div>
                        </div>
                        <div class="thumbnail span4">
                            <img src="http://placehold.it/200x200"/>
                            <div class="span3">
                                <div style="margin-top:10px;text-align:center">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png"/>
                                </div>
                            </div>
                            <div class="span8" style="margin-top:15px;text-align:center">
                                Artiste Name 
                            </div>
                        </div>
                        <div class="thumbnail span4">
                            <img src="http://placehold.it/200x200"/>
                            <div class="span3">
                                <div style="margin-top:10px;text-align:center">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_full.png"/>
                                </div>
                            </div>
                            <div class="span8" style="margin-top:15px;text-align:center">
                                Artiste Name 
                            </div>
                        </div>
                        <div class="thumbnail span4">
                            <img src="http://placehold.it/200x200"/>
                            <div class="span3">
                                <div style="margin-top:10px;text-align:center">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icons/star_empty.png"/>
                                </div>
                            </div>
                            <div class="span8" style="margin-top:15px;text-align:center">
                                Artiste Name 
                            </div>
                        </div>

                    </div>
                </div>

            </div>



        </div>


    </div>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'artiste-portfolio-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'artiste_portfolioid',
        'userid',
        'name',
        'race',
        'gender',
        'nationality',
    /*
      'height',
      'weight',
      'email',
      'home_phone',
      'mobile_phone',
      'dob',
      'status',
      'address',
      'income',
      'photoid',
      'videoid',
      'url',
     */
    ),
));
?>