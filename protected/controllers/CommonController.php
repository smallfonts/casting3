<?php

class CommonController extends Controller {

    public $layout = '//layouts/landing';
    public $home = '/artiste';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('modal', 'setPhoto', 'setVideo', 'setFeaturedPhoto'),
                'users' => array('?'),
            ),
            array('deny',
                'users' => array('?'),
                'redirect' => array('/'),
            )
        );
    }

    public function actionGetUserAccount() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_GET['UserAccount'])) {
            $search = new UserAccount('search');
            $search->attributes = $_GET['UserAccount'];
            $userAccounts = $search->search()->getData();
            $jsonUserAccounts = array();
            foreach ($userAccounts as $userAccount) {

                $tmpArr = $userAccount->toArray();

                switch ($userAccount->roleid) {
                    case 1:
                        $tmpArr['photoUrl'] = $userAccount->artistePortfolio->photo->url;
                        $tmpArr['name'] = $userAccount->artistePortfolio->name;
                        break;
                    case 2:
                        $tmpArr['photoUrl'] = $userAccount->productionPortfolio->photo->url;
                        $tmpArr['name'] = $userAccount->productionPortfolio->name;
                        break;
                    case 3:
                        $tmpArr['photoUrl'] = 'anonymous.png';
                        $tmpArr['name'] = 'Administrator';
                        break;
                    case 4:
                        $tmpArr['photoUrl'] = $userAccount->castingmanagerPortfolio->photo->url;
                        $tmpArr['name'] = $userAccount->castingmanagerPortfolio->first_name;
                        break;
                }

                $jsonUserAccounts[] = $tmpArr;
            }

            $jsonUserAccounts = json_encode($jsonUserAccounts);
            echo $jsonUserAccounts;
        }
    }

    public function actionSelectArtiste() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');
        $castingCall = CastingCall::model()->findByAttributes(array(
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
            'url' => $url,
                ));

        if ($castingCall) {
            $characters = $castingCall->characters;
            $jsonCharacters = array();

            foreach ($characters as $character) {
                $jsonCharacters[] = array(
                    'characterid' => $character->characterid,
                    'name' => $character->name,
                );
            }

            $jsonApplicants = array();
            $sql = "SELECT a.artiste_portfolioid, a.name, b.url, c.characterid, ca.character_applicationid 
                FROM `artiste_portfolio` as a, `photo` as b , `character` as c, `casting_call` as cc, `character_application` as ca
                WHERE 
                cc.casting_callid=:casting_callid AND 
                c.casting_callid=cc.casting_callid AND 
                ca.characterid = c.characterid AND 
                ca.artiste_portfolioid = a.artiste_portfolioid AND 
                b.photoid = a.photoid
            ";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryAll(false, array(':casting_callid' => $castingCall->casting_callid));
            foreach ($results as $row) {
                $jsonApplicants[] = array(
                    'artiste_portfolioid' => $row[0],
                    'name' => $row[1],
                    'photoUrl' => $row[2],
                    'characterid' => $row[3],
                    'character_applicationid' => $row[4],
                );
            }

            $jsonApplicants = json_encode($jsonApplicants);
            $jsonCharacters = json_encode($jsonCharacters);
            $this->renderPartial('selectArtiste', array('jsonCharacters' => $jsonCharacters, 'jsonApplicants' => $jsonApplicants));
        }
    }

    public function actionCalendar() {
        $this->renderPartial('calendar');
    }

    public function actionCalendarMini() {
        $this->renderPartial('calendar-mini');
    }

    /*
     * Rest API for search ethnicity
     * 
     * 
     */

    public function actionGetEthnicities() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_GET['Ethnicity'])) {
            $ethnicity = new Ethnicity("search");
            $ethnicity->attributes = $_GET['Ethnicity'];
            $results = $ethnicity->search()->getData();
            $jsonResults = CJSON::encode($results);

            echo $jsonResults;
        }
    }

    /*
     * Rest API for search professions
     * 
     */

    public function actionGetProfessions() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_GET['Profession'])) {
            $profession = new Profession("search");
            $profession->attributes = $_GET['Profession'];
            $results = $profession->search()->getData();
            $jsonResults = CJSON::encode($results);

            echo $jsonResults;
        }
    }

    /*
     * Rest API for search skills
     * 
     */

    public function actionGetSkills() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_GET['Skill'])) {

            $skill = Skill::model()->findAll();
            $skill = new Skill("search");
            $skill->attributes = $_GET['Skill'];
            $results = $skill->search()->getData();
            $jsonResults = CJSON::encode($results);

            echo $jsonResults;
        }
    }

    public function actionSetFeaturedPhoto() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['ArtistePortfolioPhoto'])) {

            //check if featured photo already exist
            $featuredPhoto = ArtistePortfolioPhoto::model()->findByAttributes(array(
                'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                'order' => $_POST['ArtistePortfolioPhoto']['order'],
                    ));

            if (!is_null($featuredPhoto)) {
                //if existing featured photo is found, set photo to 'delete' status
                $photo = $featuredPhoto->photo;
                $photo->statusid = 4;
                $photo->save();

                //reassign photoid of featured photo to new photo
                $featuredPhoto->photoid = $_POST['ArtistePortfolioPhoto']['photoid'];
                $featuredPhoto->save();
            } else {
                $featuredPhoto = new ArtistePortfolioPhoto;
                $featuredPhoto->setAttributes(array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    'photoid' => $_POST['ArtistePortfolioPhoto']['photoid'],
                    'order' => $_POST['ArtistePortfolioPhoto']['order']
                ));
                $featuredPhoto->save();
            }
        }
    }

    public function actionSetCastingCallPic() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['CastingCall'])) {
            $castingCall = CastingCall::model()->findByAttributes(array(
                'casting_callid' => $_POST['CastingCall']['casting_callid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if (!is_null($castingCall)) {
                $castingCall->photoid = $model->photoid;
                $castingCall->scenario = 'temp';
                $castingCall->save();
            }
        }
    }

    public function actionSetProfilePic() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['Photo'])) {
            $portfolio = Yii::app()->user->account->getPortfolio();
            $portfolio->photoid = $_POST['Photo']['photoid'];
            $portfolio->save();
        }
    }

    public function actionYoutubeAuthenticated() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_SESSION['randToken'])) {
            Yii::app()->session[$_SESSION['randToken']] = true;
        }

        $sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
        Yii::app()->user->account->youtube_token = $sessionToken;
        Yii::app()->user->account->save();

        $this->render('closeWindow');
    }

    public function actionCheckAuthenticated() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if ($_GET['randToken'] && isset($_SESSION[$_GET['randToken']]) && $_SESSION[$_GET['randToken']]) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function actionSetVideo() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $randToken = CryptoUtil::generateToken(10);
        $_SESSION['randToken'] = $randToken;
        Yii::app()->session[$randToken] = false;

        $hasToken = 'false';

        //retrieve youtube session token from db



        $token = Yii::app()->user->account->youtube_token;

        $tokenValid = false;
        if (!is_null($token) || count($token) != 0) {
            $tokenValid = YoutubeUtil::isTokenValid($token);
        }

        $authUrl = "";
        if (!$tokenValid) {
            //if token is not valid, then generate auth url
            $authUrl = YoutubeUtil::getAuthSubRequestUrl();
        } else {
            $hasToken = 'true';
        }

        //encrypted user information for uploadify
        $arr = array(
            'userid' => Yii::app()->user->account->userid,
        );

        if (isset(Yii::app()->user->account->artistePortfolio)) {
            $arr['artiste_portfolioid'] = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
        }

        if (isset(Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid)) {
            $arr['casting_manager_portfolioid'] = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid;
        }

        $enc = CryptoUtil::encryptData($arr);

        $this->renderPartial('setVideo', array(
            'enc' => $enc,
            'authUrl' => $authUrl,
            'hasToken' => $hasToken,
            'randToken' => $randToken));
    }

    public function actionSetPhoto() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $model = new Photo;
        $model->statusid = 1;
        if (isset($_POST['Photo']) || !empty($_FILES['c3_photo_upload'])) {

            $log->logInfo('Form has photo');
            if (isset($_POST['Photo'])) {
                $model->attributes = $_POST['Photo'];
            }


            //1. set userid as owner of photo
            if (isset(Yii::app()->user->account)) {
                $log->logInfo('User account available');
                //Request is sent via client's browser session
                $model->userid = Yii::app()->user->account->userid;
            } elseif (isset($_GET['enc'])) {
                //Request is sent via uploadify with "enc" param of userid 
                $plaintext = CryptoUtil::decryptData($_GET['enc']);
                $model->userid = $plaintext['userid'];
            } elseif (isset($_GET['userid'])) {
                $log->logInfo('has userid - ' . $_GET['userid']);
                $model->userid = $_GET['userid'];
            } else {
                $log->logInfo("Uploadify submitted upload request with no userid found. Exiting..");
                return;
            }

            if (isset($_GET['cropPhoto'])) {
                if ($_GET['cropPhoto'] == 'false')
                    $model->cropPhoto = false;
            }

            if ($model->validate()) {
                $log->logInfo('form has been validated');
                $model->savePhoto();
                $log->logInfo(CJSON::encode($model));
                print_r(CJSON::encode($model));
            } else {
                $log->logInfo('form has errors');
                print_r($model->errors);
            }
            return;
        }

        $log->logInfo('here');
        $arr = null;

        if (isset($_GET['userid'])) {
            $arr = array(
                'userid' => $_GET['userid'],
            );
        } else {
            if (isset(Yii::app()->user->account->userid)) {
                //encrypted user information
                $arr = array(
                    'userid' => Yii::app()->user->account->userid,
                );
            }

            if (isset(Yii::app()->user->account->artistePortfolio)) {
                $arr['artiste_portfolioid'] = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
            }
            if (isset(Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid)) {
                $arr['casting_manager_portfolioid'] = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid;
            }
        }

        $log->logInfo('here2');


        $enc = CryptoUtil::encryptData($arr);
        $log->logInfo('here3');
        $this->renderPartial('setPhoto', array('model' => $model, 'enc' => $enc));
    }

    /*
     * FEATURE: SEARCH
     * This is the default search controller for both artiste and production houses
     * 
     */

    public function actionSearch() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        switch (Yii::app()->user->account->roleid) {
            case 1:
                $this->layout = '//layouts/artiste';
                break;
            case 4:
                $this->layout = '//layouts/castingmanager';
                break;
        }

        //get favourites
        $favouriteArtistePortfolios = FavouriteArtistePortfolio::model()->findAllByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $favouriteCharacters = FavouriteCharacter::model()->findAllByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));
        $jsonFavouriteCharacters = CJSON::encode($favouriteCharacters);
        $jsonFavouriteArtistePortfolios = CJSON::encode($favouriteArtistePortfolios);

        $languageProficiencies = LanguageProficiency::model()->findAll();
        $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);

        $ethnicities = Ethnicity::model()->findAll();
        $jsonEthnicities = CJSON::encode($ethnicities);

        $this->render('search/main', array(
            'model' => Yii::app()->user->account,
            'jsonFavouriteArtistePortfolios' => $jsonFavouriteArtistePortfolios,
            'jsonFavouriteCharacters' => $jsonFavouriteCharacters,
            'jsonLanguageProficiencies' => $jsonLanguageProficiencies,
            'jsonEthnicities' => $jsonEthnicities,
        ));
    }

    public function actionFavourites() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $favArtistePortfolios = FavouriteArtistePortfolio::model()->findAllByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));
        $favCharacters = FavouriteCharacter::model()->findAllByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        $jsonFavArtistePortfolios = array();
        foreach ($favArtistePortfolios as $favArtistePortfolio) {
            $jsonFavArtistePortfolios[] = $favArtistePortfolio->artistePortfolio->getSearchResult();
        }
        $jsonFavArtistePortfolios = json_encode($jsonFavArtistePortfolios);

        $jsonFavCharacters = array();
        foreach ($favCharacters as $favCharacter) {
            $jsonFavCharacters[] = $favCharacter->character->getSearchResult();
        }
        $jsonFavCharacters = json_encode($jsonFavCharacters);

        $this->renderPartial('search/favourites', array(
            'jsonFavouriteArtistePortfolios' => $jsonFavArtistePortfolios,
            'jsonFavouriteCharacters' => $jsonFavCharacters,
                )
        );
    }

    public function actionSetFavouriteCharacter() {
        if (isset($_GET['FavouriteCharacter'])) {
            $fc = new FavouriteCharacter;
            $fc->attributes = $_GET['FavouriteCharacter'];
            $fc->userid = Yii::app()->user->account->userid;
            $fc->set();
        }
    }

    public function actionSetFavouriteArtistePortfolio() {
        if (isset($_GET['FavouriteArtistePortfolio'])) {
            $fap = new FavouriteArtistePortfolio;
            $fap->attributes = $_GET['FavouriteArtistePortfolio'];
            $fap->userid = Yii::app()->user->account->userid;
            $fap->set();
        } elseif (isset($_POST['FavouriteArtistePortfolio'])) {
            $fap = new FavouriteArtistePortfolio;
            $fap->attributes = $_POST['FavouriteArtistePortfolio'];
            $fap->userid = Yii::app()->user->account->userid;
            $fap->set();
        }
    }

    public function actionSearchLatestCastingCalls() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $offset = 0;
        if (isset($_GET['p']) && is_numeric($_GET['p'])) {
            $offset = intval($_GET['p']) * 20;
        }

        $castingCalls = CastingCall::model()->findAll(
                'statusid = 5 order by casting_callid desc limit ' . $offset . ', 20'
        );
        $response = array(
            'type' => 'CastingCall',
        );

        $arr = array();
        foreach ($castingCalls as $castingCall) {
            $arr[] = $castingCall->toArray();
        }

        $response['results'] = $arr;

        echo json_encode($response);
    }

    //returns the page to view suggested results
    public function actionSearchSuggested() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->renderPartial('search/suggested');
    }

    public function actionSearchLatest() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->renderPartial('search/latest');
    }

    public function actionGetSuggested() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        /*
         * If no search form filters are found, action will render the suggested
         * casting calls or artiste_portfolios
         */
        $jsonFeaturedCharacters = '{}';
        $jsonFeaturedArtistePortfolios = '{}';

        $offset = 0;
        if (isset($_GET['p']) && is_numeric($_GET['p'])) {
            $offset = intval($_GET['p']) * 20;
        }

        switch (Yii::app()->user->account->roleid) {

            case 1:
                /*
                 * If user is an artiste, then search will find casting calls that
                 * match their profile
                 * 
                 */

                $searchCharacters = new Character('search');
                $artistePortfolio = Yii::app()->user->account->artistePortfolio;
                //search for casting calls that best match the user
                //gets age of user based on dob
                if (!is_null($artistePortfolio->dob) && $artistePortfolio->dob != '') {
                    $artisteAge = DateUtil::getAge($artistePortfolio->dob);
                    $searchCharacters->age_start = $artisteAge;
                    $searchCharacters->age_end = $artisteAge;
                }


                $searchCharacters->artiste_portfolioid = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
                $searchCharacters->limit = 20;
                $searchCharacters->offset = $offset;
                $criteria = $searchCharacters->search();
                $characters = $criteria->getData();
                for ($i = 0; $i < count($characters); $i++) {
                    $character = $characters[$i];
                    $characters[$i] = $character->getSearchResult();
                }

                echo json_encode($characters);

                return;

            case 2: case 4:

                /*
                 * 
                 * If User is a production house, then search will find artiste portfolios matching their casting calls
                 * 
                 */

                //retrieve all casting calls that user has
                $castingCallResults = CastingCall::model()->findAll(
                        'casting_manager_portfolioid = :casting_manager_portfolioid AND statusid IN (5,6) limit ' . $offset . ', 20', array(
                    ':casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid
                        )
                );

                //This will store the castingcalls with characters and suggested artiste for each character
                $castingCalls = array();

                foreach ($castingCallResults as $castingCallResult) {

                    $castingCall = array(
                        'title' => $castingCallResult->title,
                        'photoUrl' => $castingCallResult->photo->url,
                        'characters' => array(),
                    );

                    //search for artistes for each character in a casting call
                    foreach ($castingCallResult->characters as $characterResult) {
                        if ($characterResult->statusid == 5 || $characterResult->statusid == 6) {
                            $character = array(
                                'name' => $characterResult->name,
                                'artistePortfolios' => array(),
                            );


                            //search for artiste portfolios that match character
                            $searchArtistePortfolios = new ArtistePortfolio('search');


                            //Populate language requirement
                            $langArr = array();
                            foreach ($characterResult->characterLanguages as $characterLanguage) {
                                $langArr[] = $characterLanguage->toArray();
                            }
                            $searchArtistePortfolios->searchLanguages = $langArr;

                            //add nationality requirement
                            if (!is_null($characterResult->nationality)) {
                                $searchArtistePortfolios->nationality = $characterResult->nationality;
                            }

                            //add gender requirement
                            if (!is_null($characterResult->gender)) {
                                $searchArtistePortfolios->gender = $characterResult->gender;
                            }


                            //populate skills requirement
                            $skillArr = array();
                            foreach ($characterResult->skills as $characterSkill) {
                                $skillArr[] = $characterSkill->toArray();
                            }
                            $searchArtistePortfolios->searchSkills = $skillArr;


                            //age_start and age_end requirement
                            if (!is_null($characterResult->age_start) || !is_null($characterResult->age_end)) {
                                $searchAge = array();
                                if (!is_null($characterResult->age_start))
                                    $searchAge['min'] = $characterResult->age_start;
                                if (!is_null($characterResult->age_end))
                                    $searchAge['max'] = $characterResult->age_end;
                                $searchArtistePortfolios->searchAge = $searchAge;
                            }


                            //ethnicity requirement
                            if (!is_null($characterResult->ethnicityid)) {
                                $log->logInfo($characterResult->ethnicityid);
                                $searchArtistePortfolios->ethnicityid = $characterResult->ethnicityid;
                            }

                            $searchArtistePortfolios->limit = 30;
                            $searchArtistePortfolios->offset = 0;
                            $artistePortfolioResults = $searchArtistePortfolios->search()->getData();


                            foreach ($artistePortfolioResults as $artistePortfolioResult) {

                                /*
                                 * gets the name and photourl of each artiste portfolio found, and store it in an array,
                                 * which will be encoded in JSON.
                                 */

                                $character['artistePortfolios'][] = $artistePortfolioResult->getSearchResult();
                            }

                            //add characters to casting call
                            $castingCall['characters'][] = $character;
                        }
                    }

                    //add casting call to result
                    $castingCalls[] = $castingCall;
                }

                echo json_encode($castingCalls);
                return;
            //retrieve all casting calls of production house
            //search for artiste portfolios that best match user's casting calls
        }
    }

    public function actionQuery() {

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['ArtistePortfolio'])) {

            $offset = 0;
            if (isset($_POST['Page']) && is_numeric($_POST['Page'])) {
                $offset = intval($_POST['Page']) * 30;
            }

            /* Search filters for Artiste Portfolios exist
             */

            $model = new ArtistePortfolio('search');
            $model->unsetAttributes();  // clear any default values
            $model->attributes = $_POST['ArtistePortfolio'];
            $model->offset = $offset;
            $model->limit = 30;

            //question why limit is defaulted to 10??

            $results = $model->search()->getData();
            //convert models to array of data.
            for ($i = 0; $i < count($results); $i++) {
                $artistePortfolio = $results[$i];
                $results[$i] = $artistePortfolio->getSearchResult();
            }

            $jsonResults = array(
                'type' => 'ArtistePortfolio',
                'results' => $results,
            );

            $jsonResults = json_encode($jsonResults);

            echo $jsonResults;
        } else if (isset($_POST['Character'])) {

            $offset = 0;
            if (isset($_POST['Page']) && is_numeric($_POST['Page'])) {
                $offset = intval($_POST['Page']) * 10;
            }

            /* Search filters for casting call exists */
            //create new model object
            $model = new Character('search');
            $model->unsetAttributes();  // clear any default values
            $model->attributes = $_POST['Character'];
            //converts models to arrays of data for casting call search
            $model->limit = 10;
            $model->offset = $offset;
            $characters = $model->search()->getData();

            $resultsArr = array();
            for ($i = 0; $i < count($characters); $i++) {
                $character = $characters[$i];
                if ($character->statusid == 5) {
                    $resultsArr[] = $character->getSearchResult();
                }
            }


            $jsonResults = array(
                'type' => 'CastingCall',
                'results' => $resultsArr,
            );

            $jsonResults = json_encode($jsonResults);
            echo $jsonResults;
            return;
        }
    }
    
    public function actionCastingCallMainResultTemplate() {
        $this->renderPartial('search/castingCallMainResultTemplate');
    }
    
    public function actionArtistePortfolioResultTemplate() {
        $this->renderPartial('search/artistePortfolioResultTemplate');
    }

    public function actionCastingCallResultTemplate() {
        $this->renderPartial('search/castingCallResultTemplate');
    }

    public function actionArtistePortfolioSearchFilters() {
        $this->renderPartial('search/artistePortfolioSearchFilters');
    }

    public function actionCastingCallSearchFilters() {
        $this->renderPartial('search/castingCallSearchFilters');
    }

    public function actionSearchResults() {
        $this->renderPartial('search/searchResults');
    }

    public function actionGetAllPhotos() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        echo CJSON::encode(Photo::model()->findAllByAttributes(
                        array(
                            'userid' => Yii::app()->user->account->userid
                        )
                )
        );
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionChangePassword() {

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('enters cp');
        $model = new ChangePasswordForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ChangePasswordForm') {
            $log->logInfo('ajax');
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ChangePasswordForm'])) {
            $log->logInfo('collects input');
            $model->attributes = $_POST['ChangePasswordForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $model->changePassword();
                $jsonObj = array(
                    'status' => 'successful',
                    'alerts' => array(
                        array(
                            'tmplate' => 'success',
                            'text' => "Password has been changed successfully",
                        )
                    ),
                );

                echo json_encode($jsonObj);
                return;
            }
        }
        $this->renderPartial('changePassword', array('model' => $model));
    }
    
    public function actionChangeName(){
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        $model = Yii::app()->user->account->getPortfolio();

        // collect user input data
        if (isset($_POST['CastingManagerPortfolio'])) {
            $model->attributes = $_POST['CastingManagerPortfolio'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $model->save();
                $jsonObj = array(
                    'status' => 'successful',
                    'alerts' => array(
                        array(
                            'tmplate' => 'success',
                            'text' => "Name has been changed successfully",
                        )
                    ),
                );

                echo json_encode($jsonObj);
                return;
            }
        }
        
        $this->renderPartial('changeName',array('model' => $model));
        
    }

    public function actionChangeEmail() {
        $model = new ChangeEmailForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ChangeEmailForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ChangeEmailForm'])) {
            $model->attributes = $_POST['ChangeEmailForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $model->changeEmail();
                $jsonObj = array(
                    'status' => 'successful',
                    'alerts' => array(
                        array(
                            'template' => 'success',
                            'text' => 'Email has been changed successfully',
                        ),
                    ),
                );

                echo json_encode($jsonObj);
                return;
            }
        }

        $this->renderPartial('changeEmail', array('model' => $model));
    }

    public function actionChangeMobile() {
        $model = new ChangeMobileForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ChangeMobileForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ChangeMobileForm'])) {
            $model->attributes = $_POST['ChangeMobileForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $model->changeMobile();
                $jsonObj = array(
                    'status' => 'successful',
                    'data' => "Mobile has been changed successfully",
                );

                echo json_encode($jsonObj);
                return;
            }
        }

        $this->renderPartial('changeMobile', array('model' => $model));
    }

    //Display the form to upload video metadata
    public function actionSetVideoMetadata() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->loginfo('creating new Upload Video Form');
        $model = new UploadVideoForm();
        $model->videoTitle = $_POST['videoTitle'];
        $model->videoDescription = $_POST['videoDescription'];
        $model->videoCategory = 'Entertainment';
        $model->videoTags = 'casting3, audition';
        $log->loginfo('Adding Video Metadata');
        $metaData = $model->addVideoMetaData();
        $arr = array(
            'postUrl' => Yii::app()->session['postUrl'],
            'tokenValue' => Yii::app()->session['tokenValue'],
        );
        echo json_encode($arr);
    }

    public function actionUploadVideoFile() {
        Yii::app()->session[$_SESSION['randToken']] = false;
        $this->renderPartial('uploadVideoFile', array('randToken' => $_GET['randToken']));
    }

    public function actionConfirmUpload() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->loginfo($_GET['status']);

        if ($_GET['status'] == 200) {
            //Store video details in db
            $video = new Video();
            $video->userid = Yii::app()->user->account->userid;
            $video->url = $_GET['id'];
            $video->save();

            Yii::app()->session['videoid'] = "" . $video->videoid;
            Yii::app()->session['url'] = $video->url;
            Yii::app()->session[$_SESSION['randToken']] = true;
        }
    }

    public function actionCheckConfirmUpload() {
        if (Yii::app()->session[$_GET['randToken']]) {
            $arr = array(
                'videoid' => Yii::app()->session['videoid'],
                'url' => Yii::app()->session['url'],
            );
            echo json_encode($arr);
        } else {
            echo "false";
        }
    }

    public function actionGetNotifications() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $userAccount = Yii::app()->user->account;
        $response = array('alerts' => array());

        //check for mail
        $messageRecipients = MessageRecipient::model()->findAllByAttributes(array(
            'userid' => $userAccount->userid,
            'notified' => 0,
                ));

        foreach ($messageRecipients as $messageRecipient) {
            $messageRecipient->notified = 1;
            $messageRecipient->save();
            $message = $messageRecipient->message;
            $response['alerts'][] = array(
                'template' => 'mail',
                'text' => '<table><tr><th>From :</th><td>' . $message->user->getPortfolio()->getName() . '</td></tr><tr><th style="vertical-align:top">Subject:</th><td>' . $message->title . '</td></tr></table>'
            );
        }
        //artiste notifications
        if ($userAccount->roleid == 1) {
            $artistePortfolio = $userAccount->artistePortfolio;
            //get invitations
            $castingCallInvitations = CastingCallInvitation::model()->findAllByAttributes(array(
                'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
                'notified' => 0,
                    ));

            foreach ($castingCallInvitations as $castingCallInvitation) {
                $castingCallInvitation->notified = 1;
                $castingCallInvitation->save();
                $castingCall = $castingCallInvitation->castingCall;
                $response['alerts'][] = array(
                    'template' => 'star',
                    'text' => 'Congratulations!! <br/><u><strong>' . $castingCall->title . '</strong></u> wants you in their show! <br/>Click here to view the invitation!'
                );
            }

            $auditionInterviewees = AuditionInterviewee::model()->findAll(
                    'artiste_portfolioid=:artiste_portfolioid AND 
                        notified=0 AND 
                        auditionid IN (select auditionid from audition where statusid != 11)', array(':artiste_portfolioid' => $artistePortfolio->artiste_portfolioid)
            );

            foreach ($auditionInterviewees as $auditionInterviewee) {
                $auditionInterviewee->notified = 1;
                $auditionInterviewee->save();
                $castingCall = $auditionInterviewee->audition->castingCall;

                $response['alerts'][] = array(
                    'template' => 'megaphone',
                    'text' => "<u><strong>" . $castingCall->title . "'s</strong></u> audition is open for application! <br><b>Book a slot now before it closes!</b>"
                );
            }
        }

        echo json_encode($response);
    }

    public function actionModal() {
        $this->renderPartial('modal');
    }

    public function actionModalSetVideo() {
        $this->renderPartial('modalSetVideo');
    }

    public function actionLoading() {
        $this->renderPartial('partials/loading');
    }

}

?>