<?php

//require_once 'Zend/Loader/Autoloader.php';
/* spl_autoload_unregister(array('YiiBase','autoload'));
  spl_autoload_register(array('Zend_Loader_Autoloader','autoload'));
  spl_autoload_register(array('YiiBase','autoload')); */

/* Yii::import("ext.Zend.EZendAutoloader", true);
  Yii::registerAutoloader(array("EZendAutoloader", "loadClass"), true);
  EZendAutoloader::$prefixes = array('Zend', 'Custom'); */

class ArtisteController extends Controller {

    public $layout = '//layouts/artiste';
    public $home = '/artiste';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('portfolio'),
                'users' => array('?', '@'),
            ),
            array('allow',
                'roles' => array('1'),
            ),
            array('deny'),
        );
    }

    /**
     * default 'index' action
     * This is the default 'index' action that is invoked when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        //get url of portfolio
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $this->redirect(array('/artiste/portfolio/' . $artistePortfolio->url));
    }

    public function actionPortfolio() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');

        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'url' => $url,
                ));
        $isOwner = true;

        if (!isset(Yii::app()->user->account)) {
            $this->layout = '//layouts/landing';
            $isOwner = false;
        } elseif (Yii::app()->user->account->userid != $artistePortfolio->userid) {
            $isOwner = false;
            if (Yii::app()->user->account->roleid != 1) {
                $this->layout = '//layouts/castingmanager';
            }
        }

        $languageProficiencies = LanguageProficiency::model()->findAll();
        $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);

        $artistePortfolioArr = $artistePortfolio->toArray();
        $artistePortfolioArr['userid'] = $artistePortfolio->user->userid;
        $jsonPortfolio = json_encode($artistePortfolioArr);

        $this->render('viewPortfolio', array('jsonPortfolio' => $jsonPortfolio, 'artistePortfolio' => $artistePortfolio, 'isOwner' => $isOwner, 'jsonLanguageProficiencies' => $jsonLanguageProficiencies));
    }

    public function actionUpdateSpokenLanguage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $json = Yii::app()->getRequest()->getQuery('json');
        $obj = CJSON::decode($json);
        $log->logInfo('json' . $json);
        $spokenLanguage = SpokenLanguage::model()->findByPK(array(
            'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
            'languageid' => $obj['languageid'],
                ));

        $log->logInfo('oh dear');

        if ($spokenLanguage) {
            $log->logInfo('Language Sucessfully Updated');
            $spokenLanguage->language_proficiencyid = $obj['language_proficiencyid'];
            $spokenLanguage->save();
        } else {
            $log->logError('Artiste Spoken Language Cannot be found for ArtistePortfolioid:' . Yii::app()->user->account->artistePortfolio->artiste_portfolioid . ' and languageid:' . $obj['languageid']);
        }
    }

    public function actionAddSkill() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //1. Check if skillid exists in $_POST

        if (isset($_POST['Skill'])) {
            $skill;
            if (!isset($_POST['Skill']['skillid'])) {
                //2. If it does not exist, create new skill
                $skill = new Skill;
                $skill->name = $_POST['Skill']['name'];
                $skill->save();
            } else {
                $skill = Skill::model()->findByAttributes(array(
                    'skillid' => $_POST['Skill']['skillid'],
                        ));
            }

            //3. create artistePortfolioSkills for user
            $artistePortfolioSkills = new ArtistePortfolioSkills;
            $artistePortfolioSkills->artiste_portfolioid = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
            $artistePortfolioSkills->skillid = $skill->skillid;
            $artistePortfolioSkills->save();
        }
    }

    public function actionAddProfession() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //1. Check if professionid exists in $obj
        $profession;
        if (isset($_POST['Profession'])) {
            if (isset($_POST['Profession']['professionid'])) {
                //If professionid exists, retrieve existing Profession from db
                $profession = Profession::model()->findByAttributes(array(
                    'professionid' => $_POST['Profession']['professionid'],
                        ));
            } else if (isset($_POST['Profession']['name'])) {
                //Create new profession
                $profession = new Profession;
                $profession->name = $_POST['Profession']['name'];
                $profession->save();
            }
        }


        //3. create artistePortfolioSkills for user
        $artistePortfolioProfessions = new ArtistePortfolioProfession;
        $artistePortfolioProfessions->artiste_portfolioid = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
        $artistePortfolioProfessions->professionid = $profession->professionid;
        $artistePortfolioProfessions->save();
    }

    public function actionDeleteProfession() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['Profession'])) {
            if (isset($_POST['Profession']['professionid'])) {
                $userProfession = ArtistePortfolioProfession::model()->findByPK(array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    'professionid' => $_POST['Profession']['professionid'],
                        ));

                if ($userProfession) {
                    $userProfession->delete();
                } else {
                    $log->logError('Artiste Profession Cannot be found for ArtistePortfolioid:' . Yii::app()->user->account->artistePortfolio->artiste_portfolioid . ' and professionid:' . $_POST['Profession']['professionid']);
                }
            }
        }
    }

    public function actionDeleteSkill() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['Skill'])) {
            if (isset($_POST['Skill']['skillid'])) {
                $userSkill = ArtistePortfolioSkills::model()->findByPK(array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    'skillid' => $_POST['Skill']['skillid'],
                        ));

                if ($userSkill) {
                    $userSkill->delete();
                } else {
                    $log->logError('Artiste Skill Cannot be found for ArtistePortfolioid:' . Yii::app()->user->account->artistePortfolio->artiste_portfolioid . ' and languageid:' . $_POST['Skill']['skillid']);
                }
            }
        }
    }

    public function actionAddSpokenLanguage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $json = Yii::app()->getRequest()->getQuery('json');
        $obj = CJSON::decode($json);

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
            'languageid' => $obj['languageid'],
            'language_proficiencyid' => $obj['language_proficiencyid'],
        ));

        if ($spokenLanguage->save()) {
            $log->logInfo('Spoken Language Successfully Saved');
        } else {
            $log->logError('Spoken Language faild to be saved for artiste_portfolioid:' . Yii::app()->user->account->artistePortfolio->artiste_portfolioid . ' languageid:' . $obj['languageid'] . ' language_proficiencyid:' . $obj['language_proficiencyid']);
        }
    }

    public function actionDeleteSpokenLanguage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $json = Yii::app()->getRequest()->getQuery('json');
        $obj = CJSON::decode($json);

        $spokenLanguage = SpokenLanguage::model()->findByPK(array(
            'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
            'languageid' => $obj['languageid'],
                ));

        if ($spokenLanguage) {
            $spokenLanguage->delete();
        } else {
            $log->logError('Artiste Spoken Language Cannot be found for ArtistePortfolioid:' . Yii::app()->user->account->artistePortfolio->artiste_portfolioid . ' and languageid:' . $obj['languageid']);
        }
    }

    public function actionEditSpokenLanguage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $json = Yii::app()->getRequest()->getQuery('json');
        $log->logInfo('json: ' . $json);
        $obj = CJSON::decode($json);

        $spokenLanguage = SpokenLanguage::model()->findByAttributes(array(
            'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
            'languageid' => $obj['languageid']
                ));

        if (!is_null($spokenLanguage)) {
            $spokenLanguage->language_proficiencyid = $obj['language_proficiencyid'];
            $spokenLanguage->save();
            $log->logInfo("Spoken Language Updated");
        } else {
            $log->logError("Spoken Language Not Found");
        }
    }

    public function actionEditPortfolio() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        //+1 to the number of times the artiste is viewing this
        $artistePortfolio->portfolio_guide = $artistePortfolio->portfolio_guide + 1;
        $artistePortfolio->save();

        /*
         * Populate artisteSkillsCheckbox for - (skillid:1 name:martial arts, skillid:2 name:driving)
         */

        $artisteSkillsCheckbox = array();
        if ($artistePortfolio->hasSkillid(1))
            $artisteSkillsCheckbox[] = '1';
        if ($artistePortfolio->hasSkillid(2))
            $artisteSkillsCheckbox[] = '2';

        $artisteSkills = new ArtistePortfolioSkills();

        /*
         *  collect user form data
         */
        if (isset($_POST['ArtistePortfolio'])) {
            $log->logInfo('Form Data Found');
            $artistePortfolio->scenario = 'edit_portfolio';
            $artistePortfolio->attributes = $_POST['ArtistePortfolio'];
            if (isset($_POST['ArtistePortfolio']['new_ethnicity']) && $_POST['ArtistePortfolio']['new_ethnicity'] != "") {
                $log->logInfo('Creating New Ethnicity Object {name : ' . $_POST['ArtistePortfolio']['new_ethnicity'] . '}');
                $ethnicity = new Ethnicity;
                $ethnicity->name = $_POST['ArtistePortfolio']['new_ethnicity'];
                $ethnicity->save();
                $artistePortfolio->ethnicityid = $ethnicity->ethnicityid;
            }
            if ($artistePortfolio->validate()) {
                $artistePortfolio->save();
                Yii::app()->user->account->artistePortfolio = $artistePortfolio;
                $this->redirect(array('artiste'));
            }
        }
        $languageProficiencies = LanguageProficiency::model()->findAll();
        $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);
        $jsonPortfolio = $artistePortfolio->toArray();
        $jsonPortfolio['dob'] = $artistePortfolio->dob;
        $jsonPortfolio = json_encode($jsonPortfolio);
        
        $this->render('editPortfolio', array(
            'artistePortfolio' => $artistePortfolio,
            'jsonPortfolio' => $jsonPortfolio,
            'jsonLanguageProficiencies' => $jsonLanguageProficiencies,
            'artisteSkillsCheckbox' => $artisteSkillsCheckbox,
        ));
    }

    public function actionSearch() {
        $this->render('search');
    }

    public function actionAccount() {
        $this->render('viewAccount', array("model" => Yii::app()->user->account));
    }

    //Authenticates user to upload video using google API
    public function actionUploadVideo() {
        //session_start();
        $_SESSION['developerKey'] = 'AI39si7_9Mk_tO8WNjaTB9u9NTBKuFPBEqpFY_IAZjfVt0MQP990MyZbuMwJXh6e7PYU7Cmw3jmCUFDW_wZNjnyZmoi9dH5e4w';

        function authenticated() {
            if (isset($_SESSION['sessionToken'])) {
                return true;
            }
        }

        function generateUrlInformation() {
            if (!isset($_SESSION['operationsUrl']) || !isset($_SESSION['homeUrl'])) {
                $_SESSION['operationsUrl'] = 'http://' . $_SERVER['HTTP_HOST']
                        . $_SERVER['PHP_SELF'];
                $path = explode('/', $_SERVER['PHP_SELF']);
                $path[count($path) - 1] = 'index.php';
                $_SESSION['homeUrl'] = 'http://' . $_SERVER['HTTP_HOST']
                        . implode('/', $path);
            }
        }

        function getAuthSubRequestUrl($nextUrl = null) {

            $scope = 'http://gdata.youtube.com';
            $secure = false;
            $session = true;

            //Redirect user to the following page after logging into google account
            $nextUrl = Yii::app()->getBaseUrl(true) . "/artiste/createUploadForm";

            //return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session) link to grant access to Casting3 application to google APIs
            $url = Zend_Gdata_AuthSub::getAuthSubTokenUri($nextUrl, $scope, $secure, $session);
            echo $url;
        }

        echo getAuthSubRequestUrl();
    }

    function actionRetrieveVideos() {
        $userid = Yii::app()->user->account->userid;
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => $userid,
                ));

        $isOwner = true;
        if (!isset(Yii::app()->user->account)) {
            $this->layout = '//layouts/landing';
            $isOwner = false;
        } elseif (Yii::app()->user->account->userid != $artistePortfolio->userid) {
            $isOwner = false;
            if (Yii::app()->user->account->roleid != 1) {
                $this->layout = '//layouts/castingmanager';
            }
        }

        //get all videos belonging to this user
        $videos = Video::model()->findAllByAttributes(array(
            'userid' => $userid,
                ), array(
            'order' => 'videoid DESC',
                ));

        $profilePic = $artistePortfolio->photo;
        $featuredPhotos = Photo::model()->with(array(
                    'ArtistePortfolioPhoto' => array(
                        'select' => false,
                        'joinType' => 'INNER JOIN',
                        'condition' => 'ArtistePortfolioPhoto.artiste_portfolioid=' . $artistePortfolio->artiste_portfolioid,
                        'order' => 'ArtistePortfolioPhoto.order ASC',
                    ),
                ))->findAll();

        $featuredVideo = $artistePortfolio->video;
        $jsonPortfolio = CJSON::encode($artistePortfolio);
        $jsonProfilePic = CJSON::encode($profilePic);
        $jsonFeaturedPhotos = CJSON::encode($featuredPhotos);
        $jsonVideos = CJSON::encode($videos);

        $this->render('viewVideos', array('jsonPortfolio' => $jsonPortfolio, 'jsonVideos' => $jsonVideos, 'jsonProfilePic' => $jsonProfilePic, 'jsonFeaturedPhotos' => $jsonFeaturedPhotos, 'featuredVideo' => $featuredVideo, 'isOwner' => $isOwner));
    }

    function actionDeleteVideo() {
        $videoid = $_GET['videoid'];
        $model = new DeleteVideoForm;
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo("enter del v");


        // collect user input data
        if (isset($_POST['DeleteVideoForm'])) {
            $log->logInfo("is set!");
            $model->attributes = $_POST['DeleteVideoForm'];
            $log->logInfo("post");
            $model->deleteVideo();
            $log->logInfo("video del");
            $jsonObj = array(
                'status' => 'successful',
                'data' => "Video has been deleted successfully",
            );

            return;
        }
        $model->videoid = $videoid;
        $log->logInfo('videoid: ' . $videoid . " modelvideoid: " . $model->videoid);
        $this->renderPartial('confirmDeleteVideo', array('model' => $model));
    }

    function actionSetFeaturedVideo() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        if(isset($_POST['Video'])){
            $portfolio = Yii::app()->user->account->getPortfolio();
            $portfolio->videoid = $_POST['Video']['videoid'];
            $portfolio->save();
        }
        
    }

    function actionApply() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));
        //Search for current application, if not create new application
        $currApplication = CharacterApplication::model()->findByAttributes(array(
            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
            'characterid' => $_GET['character'],
                ));

        if (!is_null($currApplication)) {
            $log->logInfo('old application found');
            $application = $currApplication;
            
            
            //if application has been submitted, redirect to view application action
            if($application->statusid == 8){
                $this->redirect(array('/artiste/viewApplication?appid='.$application->character_applicationid));
            }
            
        } else {
            $log->logInfo('new application');
            $log->logInfo($_GET['character']);
            $log->logInfo($artistePortfolio->artiste_portfolioid);
            //Create a new application
            $application = new CharacterApplication;
            //Set character in application
            $application->characterid = $_GET['character'];
            $application->artiste_portfolioid = $artistePortfolio->artiste_portfolioid;
            $application->rating = 0;
            //Set status to 'incomplete'
            $application->statusid = 7;

            $application->save();
            $log->logInfo('supposedly saved');
        }



        //Get Character object
        $character = Character::model()->findByAttributes(array(
            'characterid' => $application->characterid,
                ));

        //Get Casting Call object
        $castingCall = CastingCall::model()->findByAttributes(array(
            'casting_callid' => $character->casting_callid,
                ));

        //Retrieve requirements
        $photoReqs = CharacterPhotoAttachment::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
                ));

        $videoReqs = CharacterVideoAttachment::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
                ));

        $appPhotos = ApplicationPhoto::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
            'artiste_portfolioid' => $application->artiste_portfolioid,
                ));

        $jsonAppPhotos = array();
        foreach ($appPhotos as $photo) {
            $jsonAppPhotos[] = $photo->toArray();
        }
        $jsonAppPhotos = json_encode($jsonAppPhotos);

        $appVideos = ApplicationVideo::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
            'artiste_portfolioid' => $application->artiste_portfolioid,
                ));

        $jsonAppVideos = array();
        foreach ($appVideos as $video) {
            $jsonAppVideos[] = $video->toArray();
        }
        $jsonAppVideos = json_encode($jsonAppVideos);

        $languageProficiencies = LanguageProficiency::model()->findAll();

        $jsonApplication = json_encode($application->toArray());
        $jsonCharacter = json_encode($character->toArray());
        $jsonCastingCall = CJSON::encode($castingCall);
        $jsonPhotoReqs = CJSON::encode($photoReqs);
        $jsonVideoReqs = CJSON::encode($videoReqs);
        $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);
        
        //get artistePortfolio
        $jsonArtistePortfolio = json_encode(Yii::app()->user->account->artistePortfolio->toArray());
        
        $this->render('apply', array(
            'jsonArtistePortfolio' => $jsonArtistePortfolio,
            'jsonApplication' => $jsonApplication, 
            'jsonCharacter' => $jsonCharacter, 
            'jsonCastingCall' => $jsonCastingCall, 
            'jsonPhotoReqs' => $jsonPhotoReqs, 
            'jsonVideoReqs' => $jsonVideoReqs, 
            'jsonAppPhotos' => $jsonAppPhotos, 
            'jsonAppVideos' => $jsonAppVideos, 
            'jsonLanguageProficiencies' => $jsonLanguageProficiencies));
    }

    function actionSetApplicationVideo() {
        //get application belonging to this person
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['videoid'])) {

            //check if application video already exist
            $applVideo = ApplicationVideo::model()->findByAttributes(array(
                'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                'character_video_attachmentid' => $_POST['character_video_attachmentid'],
                    ));

            $log->logInfo('cvaid:' . $_POST['character_video_attachmentid']);
            $log->logInfo('videoid:' . $_POST['videoid']);

            if (!is_null($applVideo)) {
                $log->logInfo('existing appl');
                //if existing appl is found, set video to 'delete' status
                $video = $applVideo->video;
                $video->statusid = 4;
                $video->save();

                //reassign videoid of appl videoid to new video

                $applVideo->videoid = $_POST['videoid'];
                $applVideo->save();
            } else {

                $applVideo = new ApplicationVideo;
                $characterAttch = CharacterVideoAttachment::model()->findByAttributes(array(
                    'character_video_attachmentid' => $_POST['character_video_attachmentid'],
                        ));
                $log->logInfo('new appl');
                $log->logInfo('cvaid:' . $_POST['character_video_attachmentid']);
                $log->logInfo('videoid:' . $_POST['videoid']);
                $log->logInfo('char:' . $characterAttch->characterid);
                $applVideo->setAttributes(array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    'videoid' => $_POST['videoid'],
                    'characterid' => $characterAttch->characterid
                ));
                $applVideo->character_video_attachmentid = $_POST['character_video_attachmentid'];
                $applVideo->save();
            }

            $log->logInfo('end set app video');
        }
    }

    function actionSetApplicationPhoto() {
        //get application belonging to this person
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['ApplicationPhoto'])) {

            //check if application photo already exist
            $applPhoto = ApplicationPhoto::model()->findByAttributes(array(
                'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                'character_photo_attachmentid' => $_POST['ApplicationPhoto']['character_photo_attachmentid'],
                    ));

            $log->logInfo('cpaid:' . $_POST['ApplicationPhoto']['character_photo_attachmentid']);
            $log->logInfo('photoid:' . $_POST['ApplicationPhoto']['photoid']);

            if (!is_null($applPhoto)) {
                $log->logInfo('existing appl');
                //if existing appl is found, set photo to 'delete' status
                $photo = $applPhoto->photo;
                $photo->statusid = 4;
                $photo->save();

                //reassign photoid of appl photo to new photo

                $applPhoto->photoid = $_POST['ApplicationPhoto']['photoid'];
                $applPhoto->save();
            } else {

                $applPhoto = new ApplicationPhoto;
                $characterAttch = CharacterPhotoAttachment::model()->findByAttributes(array(
                    'character_photo_attachmentid' => $_POST['ApplicationPhoto']['character_photo_attachmentid'],
                        ));
                $log->logInfo('new appl');
                $log->logInfo('cpaid:' . $_POST['ApplicationPhoto']['character_photo_attachmentid']);
                $log->logInfo('photoid:' . $_POST['ApplicationPhoto']['photoid']);
                $log->logInfo('char:' . $characterAttch->characterid);
                $applPhoto->setAttributes(array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    'photoid' => $_POST['ApplicationPhoto']['photoid'],
                    'characterid' => $characterAttch->characterid
                ));
                $applPhoto->character_photo_attachmentid = $_POST['ApplicationPhoto']['character_photo_attachmentid'];
                $applPhoto->save();
            }

            $log->logInfo('end set app photo');
        }
    }

    function actionSaveApplicationDraft() {

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('start');
        //get application belonging to this person
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $log->logInfo('artistePortfolioID=' . $artistePortfolio->artiste_portfolioid);

        //find application belonging to this person
        $currApplication = CharacterApplication::model()->findByAttributes(array(
            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
            'characterid' => $_POST['characterid'],
                ));
        $log->logInfo('applicationNo=' . $currApplication->character_applicationid);


        $currApplication->statusid = 7;
        $currApplication->save();
        $log->logInfo('end');
        $response['status'] = "Saved";
        $response['alerts'] = array(
            array(
                "template" => "success",
                "text" => "Application saved"
            ),
        );

        echo json_encode($response);

        return;
    }

    function actionSubmitApplication() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //get artiste portfolio belonging to this person
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        //find application belonging to this person
        $currApplication = CharacterApplication::model()->findByAttributes(array(
            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
            'characterid' => $_POST['characterid'],
                ));


        //validate that all required fields are filled in
        $photoCheck = false;
        $videoCheck = false;
        $errorMsg = array();
        $first = false;
        $last = false;


        //Retrieve requirements
        $photoReqs = CharacterPhotoAttachment::model()->findAllByAttributes(array(
            'characterid' => $currApplication->characterid,
                ));

        $videoReqs = CharacterVideoAttachment::model()->findAllByAttributes(array(
            'characterid' => $currApplication->characterid,
                ));

        //Retrieve submitted photos and videos
        $appPhotos = ApplicationPhoto::model()->findAllByAttributes(array(
            'characterid' => $currApplication->characterid,
            'artiste_portfolioid' => $currApplication->artiste_portfolioid,
                ));

        $appVideos = ApplicationVideo::model()->findAllByAttributes(array(
            'characterid' => $currApplication->characterid,
            'artiste_portfolioid' => $currApplication->artiste_portfolioid,
                ));

        //Check photos req met
        if (count($photoReqs) == count($appPhotos)) {
            $photoCheck = true;
        } else {
            $log->logInfo('missing photos');
            $errorMsg[] = 'Required Photo(s)';
        }
        $log->logInfo('count vid reqs: ' . count($videoReqs));
        $log->logInfo('count vids appl: ' . count($appVideos));
        //Check videos req met
        if (count($videoReqs) == count($appVideos)) {
            $videoCheck = true;
        } else {
            $log->logInfo('missing videos');
            $errorMsg[] = 'Required Video(s)';
        }

        $finalErrorMsg = '';

        for ($counter = 0; $counter < sizeof($errorMsg); $counter++) {
            $finalErrorMsg .= $errorMsg[$counter];
            if ($counter != (sizeof($errorMsg) - 1)) {
                $finalErrorMsg .= ", ";
            }
        }

        if ($photoCheck && $videoCheck) {
            $currApplication->statusid = 8;
            //Set date to today's date
            $currApplication->application_date = date('Y-m-d');
            $currApplication->save();
            $response['status'] = "Saved";
            $response['alerts'] = array(
                array(
                    "template" => "success",
                    "text" => "Application submitted"
                ),
            );
            
        } else {
            $response['status'] = "Incomplete fields";
            $response['alerts'] = array(
                array(
                    "template" => "error",
                    "text" => "Application saved, but has missing fields: " . $finalErrorMsg,
                ),
            );
        }
        echo json_encode($response);

        return;
    }
    
    function actionApplicationSubmitted(){
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->render('applicationSubmitted');
    }

    function actionViewApplications() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //get application belonging to this person
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        //get all applications THAT HAS NOT BEEN WITHDRAWN
        $allApplications = CharacterApplication::model()->findAllByAttributes(array(
            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
                ));



        $jsonApplications = array();
        foreach ($allApplications as $app) {
            if ($app->statusid != 4) {
                $jsonApplications[] = $app->toArray();
            }
        }
        $jsonApplications = json_encode($jsonApplications);

        //get auditions
        $json_results = array();
        $auditionInterviewees = AuditionInterviewee::model()->findAll(
                'artiste_portfolioid=:artiste_portfolioid AND auditionid IN (SELECT auditionid FROM audition WHERE statusid != 11)',
                array(':artiste_portfolioid'=>$artistePortfolio->artiste_portfolioid)
                );

        foreach ($auditionInterviewees as $auditionInterviewee) {
            $tmpArr = $auditionInterviewee->toArray();
            $audition = $auditionInterviewee->audition;
            $castingCall = $audition->castingCall;
            $tmpArr['audition_intervieweeid'] = $auditionInterviewee->audition_intervieweeid;
            $tmpArr['status'] = $auditionInterviewee->statusid;
            $tmpArr['auditionid'] = $audition->auditionid;
            $tmpArr['reselectable_slots'] = $audition->reselectable_slots;
            $tmpArr['audition'] = $audition->toArray();
            $tmpArr['castingCall'] = $castingCall->toArray();

            $auditionIntervieweeSlot = AuditionIntervieweeSlot::model()->findByAttributes(array(
                'audition_intervieweeid' => $auditionInterviewee->audition_intervieweeid,
                'auditionid' => $audition->auditionid,
            ));
            
            if(!is_null($auditionIntervieweeSlot)){
                $tmpArr['slot'] = $auditionIntervieweeSlot->auditionSlot->toArray();
            }
            

            $json_results[] = $tmpArr;
        }

        $json_results = json_encode($json_results);

        //get all invitations 
        $allInvitations = CastingCallInvitation::model()->findAllByAttributes(array(
            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
                ));

        $jsonInvitations = array();
        foreach ($allInvitations as $in) {
            if ($in->statusid != 4) {
                $jsonInvitations[] = $in->toArray();
            }
        }
        $jsonInvitations = json_encode($jsonInvitations);

        $this->render('viewApplications', array(
            'jsonApplications' => $jsonApplications,
            'currentMillis' => time() * 1000,
            'jsonInvitations' => $jsonInvitations,
            'jsonAuditionInterviewees' => $json_results));
    }

    function actionViewApplication() {

        //Search for current application, if not create new application
        $currApplication = CharacterApplication::model()->findByAttributes(array(
            'character_applicationid' => $_GET['appid']
                ));

        if (!is_null($currApplication)) {
            $application = $currApplication;
        }

        //Get Character object
        $character = Character::model()->findByAttributes(array(
            'characterid' => $application->characterid,
                ));

        //Get Casting Call object
        $castingCall = CastingCall::model()->findByAttributes(array(
            'casting_callid' => $character->casting_callid,
                ));

        //Retrieve requirements
        $photoReqs = CharacterPhotoAttachment::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
                ));

        $videoReqs = CharacterVideoAttachment::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
                ));

        $appPhotos = ApplicationPhoto::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
            'artiste_portfolioid' => $application->artiste_portfolioid,
                ));

        $jsonAppPhotos = array();
        foreach ($appPhotos as $photo) {
            $jsonAppPhotos[] = $photo->toArray();
        }
        $jsonAppPhotos = json_encode($jsonAppPhotos);

        $appVideos = ApplicationVideo::model()->findAllByAttributes(array(
            'characterid' => $application->characterid,
            'artiste_portfolioid' => $application->artiste_portfolioid,
                ));

        $jsonAppVideos = array();
        foreach ($appVideos as $video) {
            $jsonAppVideos[] = $video->toArray();
        }
        $jsonAppVideos = json_encode($jsonAppVideos);

        $languageProficiencies = LanguageProficiency::model()->findAll();

        $jsonApplication = json_encode($application->toArray());
        $jsonCharacter = json_encode($character->toArray());
        $jsonCastingCall = CJSON::encode($castingCall);
        $jsonPhotoReqs = CJSON::encode($photoReqs);
        $jsonVideoReqs = CJSON::encode($videoReqs);
        $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);
        
        //get artiste portfolio
        $jsonArtistePortfolio = json_encode(Yii::app()->user->account->artistePortfolio->toArray());
        
        
        $this->render('viewApplication', 
                array(
                    'jsonArtistePortfolio' => $jsonArtistePortfolio,
                    'jsonApplication' => $jsonApplication, 
                    'jsonCharacter' => $jsonCharacter, 
                    'jsonCastingCall' => $jsonCastingCall, 
                    'jsonPhotoReqs' => $jsonPhotoReqs, 
                    'jsonVideoReqs' => $jsonVideoReqs, 
                    'jsonAppPhotos' => $jsonAppPhotos, 
                    'jsonAppVideos' => $jsonAppVideos, 
                    'jsonLanguageProficiencies' => $jsonLanguageProficiencies));
    }

    function actionWithdrawApplication() {
        //Search for current application
        $currApplication = CharacterApplication::model()->findByAttributes(array(
            'character_applicationid' => $_POST['appid']
                ));

        //set status to delete
        $currApplication->statusid = 4;
        $currApplication->save();
    }

    function actionDeleteInvitation() {
        //Search for current application
        $currInvitation = CastingCallInvitation::model()->findByAttributes(array(
            'casting_call_invitationid' => $_POST['casting_call_invitationid']
                ));
        //set status to delete
        $currInvitation->statusid = 4;
        $currInvitation->save();
    }

    function actionRejectAudition() {
        //Search for current audition interviewee
        $currAuditionint = AuditionInterviewee::model()->findByAttributes(array(
            'audition_intervieweeid' => $_POST['auditionintid']
                ));
        //set status to delete
        $currAuditionint->statusid = 4;
        $currAuditionint->save();
    }

}

?>