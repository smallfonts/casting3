<?php

class CastingCallController extends Controller {

    public $layout = '//layouts/castingmanager';
    public $home = '/castingmanager';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('view'),
                'users' => array('?'),
            ),
            array('deny',
                'users' => array('?'),
                'redirect' => array('/'),
            ),
        );
    }

    public function actionView() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');
        if (!is_null($url)) {


            $castingCall = CastingCall::model()->findByAttributes(array(
                'url' => $url,
                    ));

            if (!isset(Yii::app()->user->account)) {
                $this->layout = '//layouts/landing';
            } else if (Yii::app()->user->account->roleid == 1) {
                $this->layout = '//layouts/artiste';
            }


            if (!is_null($castingCall)) {
                $characters = Character::model()->findAllByAttributes(array(
                    'casting_callid' => $castingCall->casting_callid,
                    'statusid' => 5
                        ));
                $jsonCharacters = array();
                foreach ($characters as $character) {
                    $jsonCharacters[] = $character->toArray();
                }
                $roleid = Yii::app()->user->account->roleid;
                $jsonCharacters = json_encode($jsonCharacters);
                $languageProficiencies = LanguageProficiency::model()->findAll();
                $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);
                $jsonCastingCall = json_encode($castingCall->toArray());
                $jsonRoleid = json_encode($roleid);
                $this->render('view', array(
                    'jsonCastingCall' => $jsonCastingCall,
                    'jsonCharacters' => $jsonCharacters,
                    'jsonLanguageProficiencies' => $jsonLanguageProficiencies,
                    'jsonRoleid' => $jsonRoleid,
                    'currentMillis' => (time() * 1000),
                ));
            }
        }
    }

    public function actionMain() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //gets user's casting calls
        $castingCalls = CastingCall::model()->findAll(array(
            'condition' => 'casting_manager_portfolioid=' . Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid . ' AND statusid != 4'
                ));

        $castingCallsArr = array();
        foreach ($castingCalls as $castingCall) {

            $castingCallArr = array(
                'casting_callid' => $castingCall->casting_callid,
                'title' => $castingCall->title,
                'statusid' => $castingCall->statusid,
                'photoUrl' => $castingCall->photo->url,
                'url' => $castingCall->url,
            );

            //count number of applicants
            //count number of artistes invited to audition
            $sql = "select count(character_applicationid) from `character_application` where characterid IN (select characterid from `character` where casting_callid=:casting_callid)";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryRow(false, array(':casting_callid' => $castingCall->casting_callid));
            foreach ($results as $row) {
                $castingCallArr['applicants'] = $row[0];
            }

            //count number of auditions
            $sql = "select count(auditionid) from `audition` where casting_callid=:casting_callid";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryRow(false, array(':casting_callid' => $castingCall->casting_callid));
            foreach ($results as $row) {
                $castingCallArr['auditions'] = $row[0];
            }

            $characters = $castingCall->characters;
            $charactersArr = array();
            foreach ($characters as $character) {
                $characterArr = array(
                    'name' => $character->name,
                );
                $applicantsArr = array();
                $applications = $character->applications;
                foreach ($applications as $application) {
                    $artistePortfolio = $application->artistePortfolio;
                    $applicantsArr[] = array(
                        'name' => $artistePortfolio->name,
                        'photoUrl' => $artistePortfolio->photo->url,
                        'url' => $artistePortfolio->url,
                    );
                }
                $characterArr['applicants'] = $applicantsArr;

                $charactersArr[] = $characterArr;
            }

            $castingCallArr['characters'] = $charactersArr;
            $castingCallsArr[] = $castingCallArr;
        }

        $jsonCastingCalls = json_encode($castingCallsArr);

        $this->render('main', array('jsonCastingCalls' => $jsonCastingCalls));
    }

    public function actionAuditions() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');

        $sql = "Update `audition` SET statusid=17 WHERE statusid=16 AND application_end <= NOW()";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();

        $castingCall = CastingCall::model()->findByAttributes(array(
            'url' => $url,
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                ));
        if (!is_null($castingCall)) {
            $auditions = Audition::model()->findAllByAttributes(array(
                'casting_callid' => $castingCall->casting_callid,
                    ));
            $jsonAuditions = array();
            foreach ($auditions as $audition) {
                $arr = $audition->toArray();

                //count number of artistes invited to audition
                $sql = "select count(distinct artiste_portfolioid) from `audition_interviewee` where auditionid=:auditionid";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryRow(false, array(':auditionid' => $audition->auditionid));
                foreach ($results as $row) {
                    $arr['invited_interviewees'] = $row[0];
                }

                //count number of artistes who applied for audition
                $sql = "select count(distinct artiste_portfolioid) from `audition_interviewee` where auditionid=:auditionid and statusid=8";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryRow(false, array(':auditionid' => $audition->auditionid));
                foreach ($results as $row) {
                    $arr['applied_interviewees'] = $row[0];
                }

                $jsonAuditions[] = $arr;
            }
            $jsonCastingCall = json_encode(array(
                'title' => $castingCall->title,
                'url' => $castingCall->url,
                    ));
            $jsonAuditions = json_encode($jsonAuditions);

            $this->render('view_auditions', array('jsonAuditions' => $jsonAuditions, 'jsonCastingCall' => $jsonCastingCall));
        }
    }

    public function actionApplicants() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');
        $log->logInfo($url);
        $castingCall = CastingCall::model()->findByAttributes(array(
            'url' => $url,
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                ));
        if (!is_null($castingCall)) {
            //populate characters in casting call
            $jsonCharacters = array();
            $characters = $castingCall->characters;
            foreach ($characters as $character) {
                //check if character has been deleted
                if ($character->statusid != 4) {
                    $sql = "SELECT count(character_applicationid) from `character_application` where characterid = :characterid";
                    $command = Yii::app()->db->createCommand($sql);
                    $results = $command->queryRow(false, array(':characterid' => $character->characterid));
                    $num_applicants = 0;
                    foreach ($results as $row) {
                        $num_applicants = $row[0];
                    }

                    $characterApplications = $character->applications;
                    $artistePortfolios = array();
                    foreach ($characterApplications as $characterApplication) {
                        $artistePortfolio = $characterApplication->artistePortfolio;
                        $tmpArr = $artistePortfolio->toArray();
                        $tmpArr['userid'] = $artistePortfolio->user->userid;
                        $tmpArr['characterApplication'] = $characterApplication->toArray();

                        //check if artiste has been favourited
                        $favouriteArtistePortfolio = FavouriteArtistePortfolio::model()->findByAttributes(array(
                            'userid' => Yii::app()->user->account->userid,
                            'artiste_portfolioid' => $artistePortfolio->artiste_portfolioid,
                                ));

                        if (!is_null($favouriteArtistePortfolio)) {
                            $tmpArr['isFavourite'] = true;
                        } else {
                            $tmpArr['isFavourite'] = false;
                        }

                        $artistePortfolios[] = $tmpArr;
                    }

                    $characterArr = array(
                        'characterid' => $character->characterid,
                        'name' => $character->name,
                        'statusid' => $character->statusid,
                        'last_modified' => $character->last_modified,
                        'num_applicants' => $num_applicants,
                    );

                    $characterArr['artistePortfolios'] = $artistePortfolios;

                    $jsonCharacters[] = $characterArr;
                }
            }

            $languageProficiencies = LanguageProficiency::model()->findAll();
            $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);

            $this->render('view_applicants', array(
                'jsonCharacters' => json_encode($jsonCharacters),
                'jsonCastingCall' => json_encode(array('title' => $castingCall->title, 'url' => $url)),
                'jsonLanguageProficiencies' => $jsonLanguageProficiencies,
            ));
        }
    }

    public function actionCharacterApplicants() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');
        $characterid = Yii::app()->getRequest()->getQuery('characterid');
        if ($this->characterExistsAndBelongsToUser($characterid)) {
            $character = Character::model()->findByPK($characterid);
            $applicants = $character->applications;
            $jsonApplicants = array();
            foreach ($applicants as $applicant) {
                $arr = $applicant->artistePortfolio->getSearchResult();
                //add mobile phone to result
                $arr['mobile_phone'] = $applicant->artistePortfolio->mobile_phone;

                $jsonApplicants[] = $arr;
            }
            $jsonApplicants = json_encode($jsonApplicants);
            $this->render('view_character_applicants', array(
                'jsonApplicants' => $jsonApplicants,
                'jsonCastingCall' => json_encode(array('title' => $character->castingCall->title, 'url' => $character->castingCall->url)),
                'jsonCharacter' => json_encode(array('name' => $character->name)),
            ));
        }
    }

    public function actionArtistePortfolioResultTemplate() {
        $this->renderPartial('artistePortfolioResultTemplate');
    }

    public function actionNew() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //creates new casting call with temp status
        $castingCall = new CastingCall;
        $castingCall->scenario = 'temp';
        $castingCall->statusid = 7;
        $castingCall->title = 'New Casting Call';
        $castingCall->url = time() . "" . CryptoUtil::generateToken(5);
        $phu = ProductionHouseUser::model()->findByAttributes(array(
            'cm_userid' => Yii::app()->user->account->userid
                ));
        $prod = ProductionPortfolio::model()->findByAttributes(array(
            'userid' => $phu->production_userid
                ));
        $castingCall->production_portfolioid = $prod->production_portfolioid;
        $castingCall->casting_manager_portfolioid = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid;
        $castingCall->photoid = 2;
        $castingCall->save();

        $this->redirect(array('/castingCall/edit/' . $castingCall->url . '?validate=false'));
    }

    public function actionEdit() {
        $url = Yii::app()->getRequest()->getQuery('url');

        $castingCall = CastingCall::model()->findByAttributes(array(
            'url' => $url,
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                ));

        $castingManagerPortfolio = CastingManagerPortfolio::model()->findByAttributes(array(
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                ));

        if (is_null($castingCall)) {
            $this->redirect(array('castingmanager/castingCall/main'));
        }

        $castingCall->validate();
        $castingCallArr = $castingCall->toArray();
        $castingCallArr['characters'] = array();
        foreach ($castingCall->characters as $character) {
            //skip character if deleted
            if ($character->statusid != 4) {
                $character->validate();
                $castingCallArr['characters'][] = array(
                    'data' => $character->toArray(),
                    'errors' => $character->getErrors(),
                );
            }
        }

        $castingCallArr = array(
            'data' => $castingCallArr,
        );

        if (isset($_GET['validate']) && $_GET['validate'] != 'false') {
            $castingCallArr['errors'] = $castingCall->getErrors();
        }

        $jsonCastingCall = json_encode($castingCallArr);

        $characters = $castingCall->characters;
        $characterArr = array();
        foreach ($characters as $character) {
            $character->validate();
            $characterArr[] = array(
                'data' => $character->toArray(),
                'errors' => $character->getErrors(),
            );
        }
        $jsonCharacters = json_encode($characterArr);

        $proficiencies = LanguageProficiency::model()->findAll();

        $castingManagerPortfolio->castingcall_guide = $castingManagerPortfolio->castingcall_guide + 1;
        $castingManagerPortfolio->save();

        $jsonCastingManagerPortfolio = json_encode(array(
            'castingcall_guide' => $castingManagerPortfolio->castingcall_guide
                ));

        $this->render('edit', array(
            'jsonCastingCall' => $jsonCastingCall,
            'jsonCharacters' => $jsonCharacters,
            'jsonCastingManagerPortfolio' => $jsonCastingManagerPortfolio,
            'proficiencies' => $proficiencies));
    }

    public function actionSaveCastingCall() {

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $castingCall = CastingCall::model()->findByPK($_POST['CastingCall']['casting_callid']);
        $response = array();
        if (isset($_POST['CastingCall'])) {
            $log->logInfo('Casting Call Post Submitted');
            $castingCall->attributes = $_POST['CastingCall'];

            $log->logInfo('Saving Characters in casting call');
            $response['characters'] = array();
            if (isset($_POST['Characters'])) {
                foreach ($_POST['Characters'] as $character) {
                    $characterResponse = $this->saveCharacter($character);
                    if (!is_null($characterResponse)) {
                        $response['characters'][] = $characterResponse;
                    }
                }
            }

            $log->logInfo('Validating Casting Call');
            if ($castingCall->validate()) {

                $log->logInfo('Casting Call Validated');
                if ($castingCall->statusid == 7) {
                    $castingCall->statusid = 6;
                }
                $castingCall->save();
                $response['status'] = $castingCall->status->toArray();
                $response['alerts'] = array(
                    array(
                        "template" => "success",
                        "text" => "Casting Call saved"
                    ),
                );
            } else {
                $errors = $castingCall->getErrors();
                $castingCall->scenario = 'temp';
                $castingCall->statusid = 7;
                $castingCall->save();
                //display errors
                $response['status'] = $castingCall->status->toArray();
                $response['alerts'] = array(
                    array(
                        "template" => "error",
                        "text" => "Casting Call saved, but has validation errors",
                    ),
                );
                $response['errors'] = $errors;
            }

            echo json_encode($response);

            return;
        }
    }

    public function actionDeleteCastingCall() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['CastingCall']['casting_callid'])) {
            $castingCall = CastingCall::model()->findByAttributes(array(
                'casting_callid' => $_POST['CastingCall']['casting_callid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if (!is_null($castingCall)) {
                $castingCall->scenario = "temp";
                $castingCall->statusid = 4;
                $castingCall->save();
            }
        }
    }

    private function characterExistsAndBelongsToUser($characterid) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $casting_manager_portfolioid = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid;
        ;
        $sql = "SELECT count(*) from `character` where characterid = :characterid and casting_callid in ( SELECT DISTINCT(casting_callid) from `casting_call` where casting_manager_portfolioid = :casting_manager_portfolioid)";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryRow(false, array(':characterid' => $characterid, ':casting_manager_portfolioid' => $casting_manager_portfolioid));
        foreach ($results as $row) {
            if ($row[0] > 0)
                return true;
            $log->logError('Character id:' . $characterid . " does not belong to casting_manager_portfolioid:" . $casting_manager_portfolioid);
            return false;
        }
    }

    /*
     * 
     * Functions for CRUD Characters
     *
     */

    public function actionNewCharacter() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['CastingCall']['casting_callid'])) {
            $castingCall = CastingCall::model()->findByAttributes(array(
                'casting_callid' => $_POST['CastingCall']['casting_callid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if (!is_null($castingCall)) {
                $character = new Character('temp');
                $character->casting_callid = $castingCall->casting_callid;
                $character->name = 'New Character';
                $character->statusid = 7;
                $character->save();
                $character->scenario = 'insert';
                $character->validate();
                $arr = array(
                    'data' => $character->toArray(),
                    'errors' => $character->getErrors(),
                );

                echo json_encode($arr);
            }
        }
    }

    private function saveCharacter($characterData) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $response = array(
            'data' => array(),
        );
        $hasOtherErrors = false;

        //Check if character is new, then create new character active record
        if (isset($characterData['tmpid']) && $characterData['tmpid'] == $characterData['characterid']) {
            $character = new Character('temp');
            $character->casting_callid = $characterData['casting_callid'];
            $character->statusid = 7;
            $character->save();
            $character->scenario = 'update';
            $response['data']['tmpid'] = $characterData['tmpid'];
        } else {
            //gets existing character from database
            $character = Character::model()->findByPK($characterData['characterid']);
        }

        if (!is_null($character)) {

            //checks if user is owner of casting call
            $castingCall = $character->castingCall;
            $isOwner = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid == $castingCall->casting_manager_portfolioid;

            if ($isOwner) {

                /*
                 * Check if character is set to delete
                 * 
                 */

                if (isset($characterData['statusid'])
                        && $characterData['statusid'] == 4) {
                    //sets statusid to 4 ('deleted')
                    $character->scenario = 'temp';
                    $character->statusid = 4;
                    $character->save();
                    return null;
                }


                /*
                 * Edit Other Requirement For Character
                 */
                $response['data']['otherRequirements'] = array();
                if (isset($characterData['otherRequirements'])) {
                    $removeExisting = array();
                    foreach ($characterData['otherRequirements'] as $otherRequirementData) {
                        if (isset($otherRequirementData['exists']) && isset($otherRequirementData['remove']) && $otherRequirementData['remove'] == 'true') {
                            $removeExisting[] = $otherRequirementData['other_requirementid'];
                        } else {
                            $otherRequirement = OtherRequirement::model()->findByAttributes(
                                    array(
                                        'other_requirementid' => $otherRequirementData['other_requirementid'],
                                        'characterid' => $character->characterid
                                    ));

                            if (!is_null($otherRequirement)) {
                                $otherRequirement->attributes = $otherRequirementData;
                            } else {
                                $otherRequirement = new OtherRequirement;
                                $otherRequirement->attributes = $otherRequirementData;
                                $otherRequirement->characterid = $character->characterid;
                            }

                            $errors = array();
                            if (!$otherRequirement->validate()) {
                                $hasOtherErrors = true;
                                $errors = $otherRequirement->getErrors();
                                $otherRequirement->scenario = 'temp';
                            }
                            $otherRequirement->save();

                            $arr = array(
                                'other_requirementid' => $otherRequirement->other_requirementid,
                                'errors' => $errors,
                            );

                            if (isset($otherRequirementData['tmpid'])) {
                                $arr['tmpid'] = $otherRequirementData['tmpid'];
                            }

                            $response['data']['otherRequirements'][] = $arr;
                        }
                    }

                    if (count($removeExisting) > 0) {
                        $sql = "DELETE FROM `other_requirement` WHERE characterid = :characterid AND other_requirementid = :other_requirementid";
                        $command = Yii::app()->db->createCommand($sql);
                        foreach ($removeExisting as $other_requirementid) {
                            $command->execute(array(':characterid' => $character->characterid, ':other_requirementid' => $other_requirementid));
                        }
                    }
                }

                /*
                 * Edit Video Attachments For Character
                 */
                $response['data']['videoAttachments'] = array();
                if (isset($characterData['videoAttachments'])) {
                    $removeExisting = array();
                    foreach ($characterData['videoAttachments'] as $videoAttachmentData) {
                        $arr = array();
                        if (isset($videoAttachmentData['remove']) && $videoAttachmentData['remove'] == 'true') {
                            //check if videoAttachment needs to be removed
                            $removeExisting[] = $videoAttachmentData['character_video_attachmentid'];
                        } else {

                            if (isset($videoAttachmentData['tmpid'])) {
                                //new video attachment
                                $videoAttachment = new CharacterVideoAttachment;
                                $videoAttachment->characterid = $character->characterid;
                                $arr['tmpid'] = $videoAttachmentData['tmpid'];
                            } else {
                                //find video attachment
                                $videoAttachment = CharacterVideoAttachment::model()->findByAttributes(
                                        array(
                                            'character_video_attachmentid' => $videoAttachmentData['character_video_attachmentid'],
                                            'characterid' => $character->characterid
                                        ));
                            }

                            $videoAttachment->attributes = $videoAttachmentData;

                            $errors = array();
                            if (!$videoAttachment->validate()) {
                                $hasOtherErrors = true;
                                $errors = $videoAttachment->getErrors();
                                $videoAttachment->scenario = 'temp';
                            }

                            $videoAttachment->save();

                            $arr['character_video_attachmentid'] = $videoAttachment->character_video_attachmentid;
                            $arr['errors'] = $errors;
                            $response['data']['videoAttachments'][] = $arr;
                        }
                    }

                    if (count($removeExisting) > 0) {
                        $sql = "DELETE FROM `character_video_attachment` WHERE characterid = :characterid AND character_video_attachmentid = :character_video_attachmentid";
                        $command = Yii::app()->db->createCommand($sql);
                        foreach ($removeExisting as $character_video_attachmentid) {
                            $command->execute(array(':characterid' => $character->characterid, ':character_video_attachmentid' => $character_video_attachmentid));
                        }
                    }
                }

                /*
                 * Edit photo Attachments For Character
                 */
                $response['data']['photoAttachments'] = array();
                if (isset($characterData['photoAttachments'])) {
                    $removeExisting = array();
                    foreach ($characterData['photoAttachments'] as $photoAttachmentData) {
                        $arr = array();
                        if (isset($photoAttachmentData['remove']) && $photoAttachmentData['remove'] == 'true') {
                            //check if photoAttachment needs to be removed
                            $removeExisting[] = $photoAttachmentData['character_photo_attachmentid'];
                        } else {

                            if (isset($photoAttachmentData['tmpid'])) {
                                //new photo attachment
                                $photoAttachment = new CharacterPhotoAttachment;
                                $photoAttachment->characterid = $character->characterid;
                                $arr['tmpid'] = $photoAttachmentData['tmpid'];
                            } else {
                                //find photo attachment
                                $photoAttachment = CharacterPhotoAttachment::model()->findByAttributes(
                                        array(
                                            'character_photo_attachmentid' => $photoAttachmentData['character_photo_attachmentid'],
                                            'characterid' => $character->characterid
                                        ));
                            }

                            $photoAttachment->attributes = $photoAttachmentData;

                            $errors = array();
                            if (!$photoAttachment->validate()) {
                                $hasOtherErrors = true;
                                $errors = $photoAttachment->getErrors();
                                $photoAttachment->scenario = 'temp';
                            }

                            $photoAttachment->save();

                            $arr['character_photo_attachmentid'] = $photoAttachment->character_photo_attachmentid;
                            $arr['errors'] = $errors;
                            $response['data']['photoAttachments'][] = $arr;
                        }
                    }

                    if (count($removeExisting) > 0) {
                        $sql = "DELETE FROM `character_photo_attachment` WHERE characterid = :characterid AND character_photo_attachmentid = :character_photo_attachmentid";
                        $command = Yii::app()->db->createCommand($sql);
                        foreach ($removeExisting as $character_photo_attachmentid) {
                            $command->execute(array(':characterid' => $character->characterid, ':character_photo_attachmentid' => $character_photo_attachmentid));
                        }
                    }
                }


                /*
                 * Edit Language Requirement For Character
                 */

                if (isset($characterData['characterLanguages'])) {
                    $removeExisting = array();
                    foreach ($characterData['characterLanguages'] as $languageData) {
                        if (isset($languageData['exists']) && isset($languageData['remove']) && $languageData['remove'] == 'true') {
                            //REMOVE LANGUAGE REQUIREMENT
                            $removeExisting[] = $languageData['languageid'];
                        } elseif (!isset($languageData['exists'])) {
                            //Add New Language Requirement
                            $characterLanguage = new CharacterLanguage;
                            $characterLanguage->setAttributes(array(
                                'characterid' => $character->characterid,
                                'languageid' => $languageData['languageid'],
                                'language_proficiencyid' => $languageData['language_proficiencyid']
                            ));
                            $characterLanguage->save();
                        }
                    }

                    if (count($removeExisting) > 0) {
                        $sql = "DELETE FROM `character_language` WHERE characterid = :characterid AND languageid = :languageid";
                        $command = Yii::app()->db->createCommand($sql);
                        foreach ($removeExisting as $languageid) {
                            $command->execute(array(':characterid' => $character->characterid, ':languageid' => $languageid));
                        }
                    }
                }


                /*
                 * Edit Skill Requirement For Character
                 * 
                 * 
                 */
                if (isset($characterData['skills'])) {
                    $removeExisting = array();
                    $response['data']['skills'] = array();
                    foreach ($characterData['skills'] as $skillData) {


                        if (isset($skillData['exists']) && isset($skillData['remove']) && $skillData['remove'] == 'true') {
                            //REMOVE SKILL REQUIREMENT
                            //check skill exists in the requirements and needs to be removed, skillids that need to be removed are added to $removeExisting array
                            $log->logInfo('Removing Existing Skill : ' . $skillData['name']);
                            $removeExisting[] = $skillData['skillid'];
                        } elseif (!isset($skillData['exists'])) {

                            //ADD SKILL REQUIREMENT

                            if ($skillData['skillid'] == $skillData['name']) {
                                //new skills do not have skillid, thus, its name and skillid are the same.
                                //create an unlisted skill
                                $skill = new Skill;
                                $skill->name = $skillData['name'];
                                $skill->save();
                                $skillid = $skill->skillid;
                                $response['data']['skills'][] = array(
                                    'tmpid' => $skillData['tmpid'],
                                    'skillid' => $skill->skillid
                                );
                            } else {
                                //Add a skill requirement that exists in the database
                                $skillid = $skillData['skillid'];
                            }

                            $characterSkill = new CharacterSkill;
                            $characterSkill->setAttributes(array(
                                'characterid' => $character->characterid,
                                'skillid' => $skillid,
                            ));
                            $characterSkill->save();
                        }
                    }

                    if (count($removeExisting) > 0) {
                        $sql = "DELETE FROM `character_skill` WHERE characterid = :characterid AND skillid = :skillid";
                        $command = Yii::app()->db->createCommand($sql);
                        foreach ($removeExisting as $skillid) {
                            $command->execute(array(':characterid' => $character->characterid, ':skillid' => $skillid));
                        }
                    }
                }




                $character->attributes = $characterData;

                //checks if character requirement has new_ethnicity
                if (isset($characterData['ethnicity']['new_ethnicity'])) {
                    $ethnicity = new Ethnicity;
                    $ethnicity->name = $characterData['ethnicity']['new_ethnicity'];
                    $ethnicity->save();
                    $character->ethnicityid = $ethnicity->ethnicityid;
                } else if (isset($characterData['ethnicityid'])) {
                    $character->ethnicityid = $characterData['ethnicityid'];
                } else {
                    $character->ethnicityid = null;
                }

                if ($character->validate() && !$hasOtherErrors) {
                    $character->statusid = 5;
                } else {
                    $errors = $character->getErrors();
                    $character->statusid = 7;
                    $character->scenario = 'temp';
                    $response['errors'] = $errors;
                }
                $character->save();
                $response['data']['characterid'] = $character->characterid;
                $response['data']['status'] = $character->status->toArray();
            }

            return $response;
        }
    }

    /*
     * 
     * Functions for CRUD Other Requirements
     *
     */

    public function actionNewOtherRequirement() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['Character']['characterid'])) {
            $isOwner = $this->characterExistsAndBelongsToUser($_POST['Character']['characterid']);
            if ($isOwner) {
                $otherRequirement = new OtherRequirement('temp');
                $otherRequirement->characterid = $_POST['Character']['characterid'];
                $otherRequirement->save();
                echo json_encode($otherRequirement->toArray());
            }
        }
    }

    public function actionDeleteOtherRequirement() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['OtherRequirement']['characterid']) && isset($_POST['OtherRequirement']['other_requirementid'])) {
            $isOwner = $this->characterExistsAndBelongsToUser($_POST['OtherRequirement']['characterid']);
            if ($isOwner) {
                $sql = "DELETE FROM `other_requirement` where other_requirementid=:other_requirementid and characterid=:characterid";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->execute(array(':other_requirementid' => $_POST['OtherRequirement']['other_requirementid'], ':characterid' => $_POST['OtherRequirement']['characterid']));
                return;
            }
        }
        $log->logError('Post Data not found');
    }

}

?>