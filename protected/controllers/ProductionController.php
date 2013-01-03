<?php

class ProductionController extends Controller {

    public $layout = '//layouts/production';
    public $home = '/production';

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
        $productionPortfolio = ProductionPortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));
        $this->redirect(array('/production/portfolio/' . $productionPortfolio->url));
    }

    public function actionAccount() {
        $this->render('viewAccount', array('model' => Yii::app()->user->account));
    }

    public function actionPortfolio() {

        $url = Yii::app()->getRequest()->getQuery('url');
        $productionPortfolio = ProductionPortfolio::model()->findByAttributes(array(
            'url' => $url,
                ));

        $isOwner = true;
        if (!isset(Yii::app()->user->account)) {
            $this->layout = '//layouts/landing';
            $isOwner = false;
        } elseif (Yii::app()->user->account->userid != $productionPortfolio->userid) {
            $isOwner = false;
            if (Yii::app()->user->account->roleid != 2) {
                $this->layout = '//layouts/artiste';
            }
        }

        $profilePic = $productionPortfolio->photo;

        $jsonPortfolio = CJSON::encode($productionPortfolio);
        $jsonProfilePic = CJSON::encode($profilePic);
        $this->render('viewPortfolio', array('jsonPortfolio' => $jsonPortfolio, 'jsonProfilePic' => $jsonProfilePic, 'isOwner' => $isOwner));
    }

    public function actionSuspendUser() {
        $userid = $_POST['cm_userid'];
        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => $userid
                ));
        $cm->statusid = 3;
        $cm->save();
        return;
    }

    public function actionUnsuspendUser() {
        $userid = $_POST['cm_userid'];
        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => $userid
                ));
        $cm->statusid = 1;
        $cm->save();
        return;
    }

    public function actionEditPortfolio() {
        $productionPortfolio = ProductionPortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid,
                ));

        // collect user input data
        if (isset($_POST['ProductionPortfolio'])) {
            $productionPortfolio = ProductionPortfolio::model()->findByAttributes(array(
                'userid' => Yii::app()->user->account->userid,
                    ));
            $productionPortfolio->attributes = $_POST['ProductionPortfolio'];

            if ($productionPortfolio->validate()) {
                $productionPortfolio->save();
                $this->redirect(array('artiste'));
            }
        }

        $profilePic = $productionPortfolio->photo;

        $jsonPortfolio = CJSON::encode($productionPortfolio);
        $jsonProfilePic = CJSON::encode($profilePic);
        $this->render('editPortfolio', array('jsonPortfolio' => $jsonPortfolio, 'jsonProfilePic' => $jsonProfilePic, 'productionPortfolio' => $productionPortfolio));
    }

    public function actionResendInvitation() {
        $userid = $_POST['cm_userid'];
        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => $userid
                ));
        $firstname = $cm->first_name;
        $lastname = $cm->last_name;
        $cm->token = $this->createCastingManagerReistrationToken();
        $cm->save();
        $from = array(Yii::app()->params->adminEmail);

        $user = UserAccount::model()->findByAttributes(array(
            'userid' => $userid
                ));
        $to = $user->email;
        $subject = "Invitation to join as a casting manager";
        $body = "Dear " . $firstname . " " . $lastname . ",<br/><br/> You have been invited to join Casting3 as a casting manager. <br/><br/>Please follow this link to sign up: <a href='" . Yii::app()->getBaseUrl(true) . "/castingmanager/register?token=" . $cm->token . "'>Sign up</a><br/><br/>Yours Sincerely, <br/>Casting 3 Administrators";

        $success = Email::sendEmail($to, $from, $subject, $body);
        if ($success) {
            $response['status'] = "Saved";
            $response['alerts'] = array(
                array(
                    "template" => "success",
                    "text" => "Invitation resent to casting manager"
                ),
            );
        }
        echo json_encode($response);
        return;
    }

    public function actionSendInvite() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email'])) {
            $log->logInfo('here');
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $user = null;
            $cm = null;
            $from = array(Yii::app()->params->adminEmail);
            $token = null;
            $log->logInfo('here2');
            //check for VALID EMAIL

            $to = $_POST['email'];
            $currUser = UserAccount::model()->findByAttributes(array(
                'email' => $_POST['email'],
                    ));

            if (!is_null($currUser)) {
                //if user exists already - resend with replacement in first name and last name
                $log->logInfo('current user');
                $user = $currUser;

                $cm = CastingManagerPortfolio::model()->findByAttributes(array(
                    'userid' => $user->userid,
                        ));
                $cm->first_name = $firstname;
                $cm->last_name = $lastname;
                $cm->photoid = 1;
                $cm->token = $this->createCastingManagerReistrationToken();
                $log->logInfo($cm->token);
                //search for existing token
                //if 
                $cm->save();
            } else {
                $user = new UserAccount;
                $user->setAttributes(array(
                    'email' => $to,
                    'email2' => $to,
                    'password' => '12345',
                    'password2' => '12345',
                    'roleid' => 4,
                    'statusid' => 15
                ));
                $user->save();


                //create casting_manager_portfolio object next
                $cm = new CastingManagerPortfolio;
                $cm->userid = $user->userid;
                $cm->first_name = $firstname;
                $cm->last_name = $lastname;
                $cm->statusid = 15;
                $cm->photoid = 1;
                $cm->token = $this->createCastingManagerReistrationToken();
                $cm->save();
                $log->logInfo('here5');

                $prodHouseUser = new ProductionHouseUser;
                $prodHouseUser->cm_userid = $cm->userid;
                $prodHouseUser->production_userid = Yii::app()->user->account->userid;
                $prodHouseUser->save();
            }


            $subject = "Invitation to join as a casting manager";
            $body = "Dear " . $firstname . " " . $lastname . ",<br/><br/> You have been invited to join Casting3 as a casting manager. <br/><br/>Please follow this link to sign up: <a href='" . Yii::app()->getBaseUrl(true) . "/castingmanager/register?token=" . $cm->token . "'>Sign up</a><br/><br/>Yours Sincerely, <br/>Casting 3 Administrators";
            $log->logInfo('here6');
            $success = Email::sendEmail($to, $from, $subject, $body);
            if ($success) {
                $response['status'] = "Saved";
                $response['alerts'] = array(
                    array(
                        "template" => "success",
                        "text" => "Invitation sent to casting manager"
                    ),
                );
            }
            $log->logInfo($success);
            echo json_encode($response);
        }
        return;
    }

    public function createCastingManagerReistrationToken() {
        $unique = false;
        $token = null;
        while (!$unique) {
            $token = CryptoUtil::generateToken(100);
            $cmportfolio = CastingManagerPortfolio::model()->findByAttributes(array(
                'token' => $token
                    ));

            if (is_null($cmportfolio)) {
                //success!
                $unique = true;
            } 
        }
        return $token;
    }

    public function actionUsermanagement() {
        //need to get all the casting managers belongong to this production house
        $productionHouseUsers = ProductionHouseUser::model()->findAllByAttributes(array(
            'production_userid' => Yii::app()->user->account->userid,
                ));

        $jsonProductionHouseUsers = array();
        foreach ($productionHouseUsers as $phu) {
            $jsonProductionHouseUsers[] = $phu->toArray();
        }
        $jsonProductionHouseUsers = json_encode($jsonProductionHouseUsers);

        $this->render('userManagement', array('jsonProductionHouseUsers' => $jsonProductionHouseUsers));
    }

}

?>