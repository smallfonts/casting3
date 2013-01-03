<?php

class CastingmanagerController extends Controller {

    public $layout = '//layouts/castingmanager';
    public $home = '/castingmanager';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
        );
    }

    /**
     * default 'index' action
     * This is the default 'index' action that is invoked when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        $this->redirect(array('/common/search'));
    }
    
    public function actionRegister() {
        $this->layout = '//layouts/landing';
        if (isset($_GET['token'])){
            $token = $_GET['token'];
            $jsonCMPortfolio = null;
            $cmportfolio = CastingManagerPortfolio::model()->findByAttributes(array(
               'token' => $token
            ));
            
            if (is_null($cmportfolio)){
                echo "Token is invalid.";
            } else{
                //display registration page
                
                //log in for user
                $loginForm = new LoginForm;
                $loginForm->email = $cmportfolio->user->email;
                $loginForm->autoLogin();
                
                $jsonCMPortfolio = json_encode($cmportfolio->toArray());
                $this->render('register', array('jsonCMPortfolio' => $jsonCMPortfolio));
            }
            
            
        }
    }
    
    public function actionSetCastingManagerPhoto(){
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        if (isset($_POST['Photo'])) {
            $cmid = $_POST['Photo']['cmid'];
            $log->logInfo('cmid: '.$cmid);
            $cmportfolio = CastingManagerPortfolio::model()->findByAttributes(array(
               'casting_manager_portfolioid' => $cmid
            ));
            $photoid = $_POST['Photo']['photoid'];
            $log->logInfo('photoid: '.$photoid);
            $cmportfolio->photoid = $photoid;
            $log->logInfo('cm: '.$cmportfolio->photoid);
            $cmportfolio->save();
            $log->logInfo('saved');
        }
        return;
    }

    public function actionRegisterCastingManager() {
        $cmid = $_POST['CastingManager']['cmid'];
        $firstname = $_POST['CastingManager']['firstname'];
        $lastname = $_POST['CastingManager']['lastname'];
        $mobile = $_POST['CastingManager']['mobile'];
        $password = $_POST['CastingManager']['password'];

        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'casting_manager_portfolioid' => $cmid
                ));

        $user = UserAccount::model()->findByAttributes(array(
            'userid' => $cm->userid
                ));

        $cm->first_name = $firstname;
        $cm->last_name = $lastname;
        $cm->mobile = $mobile;
        $cm->statusid = 1;
        $cm->save();
        $user->password = CryptoUtil::hashPassword($password);
        $user->save();
        return;
    }

    public function actionViewAccount() {
        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid
                ));

        $jsonCastingManagerPortfolio = json_encode($cm->toArray());

        $this->render('viewAccount', array('jsonPortfolio' => $jsonCastingManagerPortfolio, 'model' => Yii::app()->user->account));
    }

    public function actionInviteArtistePage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->renderPartial('inviteArtiste');
    }

    public function actionInviteArtiste() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['ArtistePortfolio']['artiste_portfolioid']) && isset($_POST['CastingCall']['casting_callid']) && isset($_POST['Invite'])) {
            $artiste_portfolioid = $_POST['ArtistePortfolio']['artiste_portfolioid'];
            $casting_callid = $_POST['CastingCall']['casting_callid'];
            $invite = $_POST['Invite'];

            //check if castingcall belongs to user
            $castingCall = CastingCall::model()->findByAttributes(array(
                'casting_callid' => $casting_callid,
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if ($castingCall) {
                if ($invite == "true") {

                    $castingCallInvitation = new CastingCallInvitation;
                    $castingCallInvitation->setAttributes(array(
                        'casting_callid' => $casting_callid,
                        'artiste_portfolioid' => $artiste_portfolioid,
                        'statusid' => 5,
                        'notified' => 0,
                    ));
                    $castingCallInvitation->save();
                } else {
                    $log->logInfo('here');
                    $castingCallInvitation = CastingCallInvitation::model()->findByAttributes(array(
                        'artiste_portfolioid' => $artiste_portfolioid,
                        'casting_callid' => $casting_callid,
                            ));

                    if (!is_null($castingCallInvitation)) {
                        $log->logInfo('here');
                        $castingCallInvitation->delete();
                    }
                }
            }
        }
    }

    public function actionGetInvitationList() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['ArtistePortfolio']['artiste_portfolioid'])) {
            $artiste_portfolioid = $_POST['ArtistePortfolio']['artiste_portfolioid'];
            $casting_manager_portfolioid = Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid;

            //gets casting calls that is currently available
            $castingCalls = CastingCall::model()->findAllByAttributes(array(
                'casting_manager_portfolioid' => $casting_manager_portfolioid,
                'statusid' => 5
                    ));

            $castingCallsArr = array();
            foreach ($castingCalls as $castingCall) {
                $castingCallsArr[] = array(
                    'casting_callid' => $castingCall->casting_callid,
                    'title' => $castingCall->title,
                );
            }

            //check if user has been already invited to casting call
            $sql = "SELECT casting_callid from `casting_call_invitation` where artiste_portfolioid=:artiste_portfolioid and casting_callid IN ( SELECT casting_callid from `casting_call` where casting_manager_portfolioid=:casting_manager_portfolioid and statusid=5)";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryRow(false, array(':casting_manager_portfolioid' => $casting_manager_portfolioid, ':artiste_portfolioid' => $artiste_portfolioid));
            if ($results) {
                foreach ($results as $row) {
                    for ($i = 0; $i < count($castingCallsArr); $i++) {
                        if ($castingCallsArr[$i]['casting_callid'] == $row[0]) {
                            $castingCallsArr[$i]['invited'] = true;
                        }
                    }
                }
            }

            //check if user has already applied to casting call
            $sql = "SELECT casting_callid from `character` where casting_callid IN ( select casting_callid from casting_call where casting_manager_portfolioid=:casting_manager_portfolioid ) and characterid IN (SELECT characterid from `character_application` where artiste_portfolioid=:artiste_portfolioid)";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryRow(false, array(':casting_manager_portfolioid' => $casting_manager_portfolioid, ':artiste_portfolioid' => $artiste_portfolioid));
            if ($results) {
                foreach ($results as $row) {
                    for ($i = 0; $i < count($castingCallsArr); $i++) {
                        if ($castingCallsArr[$i]['casting_callid'] == $row[0]) {
                            $castingCallsArr[$i]['applied'] = true;
                        }
                    }
                }
            }

            echo json_encode($castingCallsArr);
        }
    }

}

?>