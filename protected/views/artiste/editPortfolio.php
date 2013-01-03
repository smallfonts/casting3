<style>

    .c3_input:hover{
        cursor: text;
    }

    .language_input:focus {
        border-color: rgba(82, 168, 236, 0.8);
        outline: 0;
        outline: thin dotted \9;
        /* IE6-9 */

        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
        -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
    }

    .language_input input {
        border:0px;
        -webkit-box-shadow: 0 0 0;
        -moz-box-shadow: 0 0 0;
        box-shadow: 0 0 0;
        width:auto
    }

    .language_input input:focus{
        border:0px;
        -webkit-box-shadow: 0 0 0;
        -moz-box-shadow: 0 0 0;
        box-shadow: 0 0 0;
    }

    .language_input .label {
        margin:2px;
        padding-left:5px;
        padding-right:5px;
        width:auto;
    }

    .language_input .label span:hover {
        color:#FFA8C7;
        cursor:pointer;
    }

    .language_input .label span {
        font-size:18px; 
        position:relative;
        top:1px;
        margin-left:5px;
    }

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

    .line {
        margin-bottom:10px;
    }

</style>

<!-- Datepicker Plugin -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/datepicker.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/datepicker/bootstrap-datepicker.js"></script>

<!-- Select2 Plugin -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/select2/select2.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/controllers/artiste/edit_portfolio_ctrl.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/countries.js"></script>



<script>
    var jsonPortfolio = <?php print_r($jsonPortfolio); ?>; 
    var jsonLanguageProficiencies = <?php print_r($jsonLanguageProficiencies); ?>;
    function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
</script>

<script>
    $(function(){
        epc.init();
    });
</script>

<?php
//$jsArtisteSkills = json_decode($jsonArtisteSkills); 
$jsPortfolio = json_decode($jsonPortfolio);
?>

<div ng-controller="edit_portfolio_ctrl" class="well c3_body_well" style="width:1020px">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'edit-portfolio-form',
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <div class="row-fluid">
        <div class="span4">
            <a id="c3_profilepic" class="thumbnail" ng-click="changeProfilePic()" rel="tooltip" title="Click photo to Edit">
                <img style="width:100%" class="c3-profilepic" ng-src="{{photoBaseUrl + '/' + portfolio.photoUrl}}" >
            </a>
        </div>
        <div class="span8">
            <div class="row-fluid line">
                <div class="span9">
                    <h1>Edit Portfolio</h1>
                </div>
                <div class="span3">
                    <button type="submit" class="btn btn-primary btn-medium pull-right"><i class="icon-check icon-white"></i> Save Changes</button>
                </div>   
            </div>
            <div class="row">
                <ul class="nav nav-tabs" style="margin-left:20px;">
                    <li class="active"><a href="#epGeneralInformation" data-toggle="tab"><i class="icon-user"></i> General Information</a></li>
                    <li><a href="#epMeasurements" data-toggle="tab"><i class="icon-resize-small"></i> Measurements</a></li>
                    <li><a href="#epSkillsAndProfession" data-toggle="tab"><i class="icon-star"></i> Skills and Profession</a></li>
                    <li><a href="#epFeatured" data-toggle="tab"><i class="icon-star"></i> Featured Photos & Videos</a></li>
                </ul>
                <div class="tab-content" style="height:auto;padding-left:20px">

                    <div class="tab-pane fade active in" id="epGeneralInformation">
                        <table class="profile table-condensed" style="margin:0px;padding:0px">
                            <tr>
                                <th style="vertical-align:middle">Profile url</th>
                                <td style="vertical-align:middle">
                                    artiste/portfolio/ <input type="text" style="width:198px" id="urlTip" name="ArtistePortfolio[url]" ng-model="portfolio.url" rel="tooltip" title="Customize your personal URL. e.g. timberwerkz/artiste/portfolio/<span style='color:yellow'>mindysmith</span>"/>
                                </td>
                            </tr>
                            <tr><td></td><td><?php echo $form->error($artistePortfolio, 'url', array('style' => 'color:red')); ?></td></tr>
                        </table>

                        <table class="table profile table-condensed">
                            <tr><th>Name</th>
                                <td>
                                    <input type="text" name="ArtistePortfolio[name]" ng-model="portfolio.name" style="width:290px"/>
                                </td>
                            </tr>
                            <tr><th>Birthday</th>
                                <td>
                                    <input id="portfolioDOB" type="hidden" name="ArtistePortfolio[dob]" />
                                    <select ng-model="dob.selectedDay" ng-options="n for n in dob.days" ng-change="setDOB()" style="width:60px"></select> 
                                    <select ng-model="dob.selectedMonth" ng-options="n.val as n.name for n in dob.months" ng-change="setDOB()" style="width:80px"></select> 
                                    <select ng-model="dob.selectedYear" ng-options="n for n in dob.years" ng-change="setDOB()" style="width:100px"></select>
                                </td></tr>
                            <tr><th>Ethnicity / Race</th>
                                <td>
                                    <input type="hidden" id="epEthnicity" style="width:300px"/>
                                    <input id="epcEthnicityid" type="hidden" name="ArtistePortfolio[ethnicityid]" />
                                    <input id="epcNewEthnicity"type="hidden" name="ArtistePortfolio[new_ethnicity]" />
                                </td>
                            </tr>
                            <tr><th>Gender</th><td>
                                    Male <input type="radio" name="ArtistePortfolio[gender]" ng-model="portfolio.gender" value="male"/>&nbsp; Female <input type="radio" name="ArtistePortfolio[gender]" ng-model="portfolio.gender" value="female"/>
                                </td></tr>
                            <tr><th>Country of Citizenship</th>
                                <td>
                                    <input id="nationality" type="hidden" name="ArtistePortfolio[nationality]" style="width:300px"/>
                                </td></tr>                    
                            <tr><th>Languages</th>
                                <td style="width:400px">
                                    <input type="hidden" id="epLanguages" style='width:300px' val=""/>
                                </td></tr>
                        </table>
                        <h6 class="line"><i class="icon-envelope"></i> Contact</h6>
                        <table class="profile table-condensed">
                            <tr><th>Email</th>
                                <td>
                                    <input style='width:290px' type="text" name="ArtistePortfolio[email]" ng-model="portfolio.email"/>
                                </td>
                            </tr>
                            <tr><th>Phone</th>
                                <td>
                                    <input style='width:290px' type="text" id="fapcMinAge" name="ArtistePortfolio[mobile_phone]" ng-model="portfolio.mobile_phone"/>
                                    <?php echo $form->error($artistePortfolio, 'mobile_phone', array('style' => 'color:red')); ?>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                    <div class="tab-pane fade" id="epMeasurements">
                        <table class="table profile">
                            <tr><th>Height (m)</th>
                                <td>
                                    <input id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="ArtistePortfolio[height]" ng-model="portfolio.height" />
                                </td>
                            </tr>
                            <tr><th>Weight (Kg)</th>
                                <td>
                                    <input id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="ArtistePortfolio[weight]" ng-model="portfolio.weight" />
                                </td>
                            </tr>
                            <tr><th>Chest (")</th>
                                <td>
                                    <input id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="ArtistePortfolio[chest]" ng-model="portfolio.chest" />
                                </td>
                            </tr>
                            <tr><th>Waist (")</th>
                                <td>
                                    <input id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="ArtistePortfolio[waist]" ng-model="portfolio.waist" />
                                </td>
                            </tr>
                            <tr><th>Hip (")</th>
                                <td>
                                    <input id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="ArtistePortfolio[hip]" ng-model="portfolio.hip" />
                                </td>
                            </tr>
                            <tr><th>Shoe Size (US) </th>
                                <td>
                                    <input type="text" name="ArtistePortfolio[shoe]" ng-model="portfolio.shoe" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="epSkillsAndProfession">
                        <h6><i class="icon-film"></i>Skills & Profession</h6>
                        <table class="table profile"     >
                            <tr><th style="width:170px" >Years of Acting Experience</th>
                                <td>
                                    <input type="text" style="max-width:50px"  name="ArtistePortfolio[years_of_experience]" ng-model="portfolio.years_of_experience" />
                                </td>
                            </tr>
                            <tr><th>Profession</th><td> 
                                    <input type="hidden" id="epProfessions" style="max-width:300px" val=""/>
                                </td></tr>
                            <tr   ><th>Skills</th><td>
                                    <?php
                                    echo CHtml::checkBoxList('ArtistePortfolioSkills[skillid]', $artisteSkillsCheckbox, array('1' => 'Martial Arts', '2' => 'Driving'), array('separator' => " ", 'class' => 'toggleSkillsCheckbox'));
                                    ?>
                                </td></tr>
                            <tr><th>More Skills</th><td>
                                    <input type="hidden" id="epSkills" style="max-width:300px" val=""/>
                                </td></tr>
                            
                            <tr>
                                <th>Experience</th>
                                <td>
                                    <textArea type="text" value="{{portfolio.experience}}" name="ArtistePortfolio[experience]" style="width:510px;height:100px"></textArea>    
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="epFeatured">
                        <div class="row-fluid" style="overflow:hidden">
                            <div class="span7">
                                <ul class="thumbnails c3-thumbnails-condensed">

                                    <li class="thumbnail" ng-repeat="photo in portfolio.featuredPhotos">
                                        <a id="featuredPhoto{{$index}}" class="featuredPhotos" style="text-decoration: none;" ng-click="changeFeaturedPhoto($index)">
                                            <img ng-src="{{photoBaseUrl + '/m' + photo.url}}" alt="" ng-hide="!photo"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a id="epc_profileVideo" ng-click="changeProfileVideo()" class="thumbnail span5" style="margin-left:-30px;width:275px;height:210px">
                                <img ng-src="http://img.youtube.com/vi/{{portfolio.video.url}}/0.jpg"/>
                            </a>
                            <input type="hidden" name="ArtistePortfolio[videoid]" value="{{portfolio.video.videoid}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>
 