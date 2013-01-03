<?php

class SiteController extends Controller {

    public $layout = '//layouts/landing';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            /* redirects user to artiste page if user has already logged in as an artiste user
             *
             */
            array('deny',
                'roles' => array('1'),
                'redirect' => array('artiste'),
            ),
            /* redirects user to production house page if user has already logged in as an production house user
             *
             */
            array('deny',
                'roles' => array('2'),
                'redirect' => array('production'),
            ),
            /* redirects user to admin page if user has already logged in as an admin user
             *
             */
            array('deny',
                'roles' => array('3'),
                'redirect' => array('admin'),
            ),
            /* redirects user to admin page if user has already logged in as an admin user
             *
             */
            array('deny',
                'roles' => array('4'),
                'redirect' => array('castingManager'),
            ),
            
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex() {
        if (isset($_POST['LoginForm'])) {
            $this->actionLogin();
            return;
        } else {
            $this->actionMain(1, 2, 'index');
        }
    }

    public function actionProductionHouse() {
        $this->actionMain(2, 1, 'index_production_house');
    }

    /**
     * default 'index' action
     * This is the default 'index' action that is invoked when an action is not explicitly requested by users.
     */
    public function actionMain($roleId, $altRoleId, $page) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //Log In Form
        //
        $loginModel = new LoginForm;


        //Register Account Form
        //
        $model = new UserAccount;
        $model->roleid = $roleId;
        $model->statusid = 1;

        //find role name
        //
        $role = Role::model()->findByPK($model->roleid);

        //find alt role name
        //
        $altRole = Role::model()->findByPK($altRoleId)->name;
        $model->role = $role->name;
        $model->scenario = 'insert';

        // if it is ajax validation request
        if (isset($_POST['ajax'])) {
            switch ($_POST['ajax']) {
                case 'UserAccount':
                    $this->actionRegisterAccount($model);
                    break;
            }
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $this->actionLogin();
            return;
        } else if (isset($_POST['UserAccount'])) {
            $model = $this->actionRegisterAccount($model);
        }

        // display the login form
        $this->render($page, array('model' => $model, 'loginModel' => $loginModel, 'altRole' => $altRole));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'LoginForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                if (Yii::app()->user->returnUrl == "/timberwerkz/index.php" || Yii::app()->user->returnUrl == "/timberwerkz/index-test.php") {
                    $this->redirect(array('/'));
                } else {
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionRegisterAccount($model) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'UserAccount') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['UserAccount'])) {
            $model->attributes = $_POST['UserAccount'];

            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $log->logInfo("Saving User Account: " . $model->email);
                $model->save();

                //automatically logs in for user
                $loginForm = new LoginForm;
                $loginForm->setAttributes(array(
                    'email' => $model->email,
                    'password' => $model->password2,
                ));
                $loginForm->login();
              switch($model->roleid){
                    case '1':
                        $this->redirect(array('/artiste/editPortfolio'));
                        break;
                    case '2':
                        $this->redirect(array('/production/editPortfolio'));
                        break;   
                }
            } else {
                foreach ($model->getErrors() as $errArr) {
                    foreach ($errArr as $error) {
                        $log->logError("Validation Error " . $error);
                    }
                }
                return $model;
            }
        }
    }

    public function actionResetPassword() {
        $model = new ResetPasswordForm;
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('enters prt');

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ResetPasswordForm') {
            $log->logInfo('ajax');
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }



        // collect user input data
        if (isset($_POST['ResetPasswordForm'])) {
            $log->logInfo('collects user input');
            $model->attributes = $_POST['ResetPasswordForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $log->logInfo('t');
                $model->sendEmail();
                $jsonObj = array(
                    'status' => 'successful',
                    'data' => "Email has been sent successfully",
                );

                echo json_encode($jsonObj);
                return;
            }
        }

        $this->renderPartial('resetPassword', array('model' => $model));
    }
    

    public function actionConfirmResetPassword() {
        if (isset($_GET['userid'])) {
            $id = $_GET['userid'];
            $model = new ConfirmResetPasswordForm;

// if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'ConfirmResetPasswordForm') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

// collect user input data
            if (isset($_POST['ConfirmResetPasswordForm'])) {
                $model->attributes = $_POST['ConfirmResetPasswordForm'];
// validate user input and redirect to the previous page if valid
                if ($model->validate()) {

                    if ($model->changePassword($id)) {
                        if ($model->deleteToken($id)) {
                            $jsonObj = array(
                                'status' => 'successful',
                                'data' => "Password has been changed successfully",
                            );
                            $this->render('resetpwd_success');
                        } else {
                            $this->renderPartial('Error', array('message' => 'Failed! Please try again.', 'code' => 400));
                        }
                    } else {
                        $this->renderPartial('Error', array('message' => 'Failed! Please try again.', 'code' => 400));
                    }



                   // echo json_encode($jsonObj);
                    return;
                }
            }

            $criteria = new CDbCriteria;
            $criteria->compare('userid', $id, true);
            $PasswordResetToken = PasswordResetToken::model()->find($criteria);

            if (isset($_GET['prt']) and !is_null($PasswordResetToken) and $PasswordResetToken['token'] == $_GET['prt']) {
                $this->render('confirmResetPwdBase', array('model' => $model));
//$this->renderPartial('ChangePassword', array('model' => model));
            } else {
                $this->renderPartial('Error', array('message' => 'Invalid token. Please try again.', 'code' => 400));
            }
        } else {
            $this->renderPartial('Error', array('message' => 'Invalid token. Please try again.', 'code' => 400));
        }
    }

}

?>