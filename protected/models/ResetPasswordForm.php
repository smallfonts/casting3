<?php

Yii::import('application.extensions.swift.*');
require_once 'lib/swift_required.php';
require_once 'classes/Transport/AWSTransport.php';
require_once 'classes/AWSTransport.php';
require_once 'classes/AWSInputByteStream.php';

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ResetPasswordForm extends CFormModel {

    public $email;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // email is required
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'exist', 'className' => 'UserAccount', 'attributeName' => 'email', 'message' => 'You have not registered on our portal.'),
        );
    }

    /**
     * Declares attribute label.
     */
    public function attributeLabels() {
        return array(
            'email' => 'Your email',
        );
    }

    public function sendEmail() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        try {

            $prt = new PasswordResetToken();
            $prt->email = $this->email;
            $log->logInfo($prt->email);
            $prt->beforeSave();
            $prt->save();

            $log->logInfo("Sending Email");


            $subject = "Request to reset password";
            $from = Yii::app()->params->adminEmail;
            $to = array($prt->email);
            //$to=array($prt->email);
            $body = "Hi, you have requested to reset your password. Please follow this link: <a href='".Yii::app()->getBaseUrl(true)."/site/confirmResetPassword?userid=".$prt->userid."&prt=".$prt->token."'>Reset Password</a>";
            
            $success = Email::sendEmail($to, $from, $subject, $body);
            
        } catch (Exception $e) {
            $log->logInfo('Exception: ' . $e->getMessage());
            return false;
        }
        return $success;
    }

}

?>
