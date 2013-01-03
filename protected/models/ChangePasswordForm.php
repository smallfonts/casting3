<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangePasswordForm extends CFormModel {

    public $oldPassword;
    public $newPassword;
    public $newPassword2;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('oldPassword, newPassword, newPassword2', 'required'),
            array('newPassword2','compare', 'compareAttribute'=>'newPassword'),
            array('oldPasswor','authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'oldPassword' => 'Current Password',
            'newPassword' => 'New Password',
            'newPassword2' => 'Repeat Password',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate() {
        $accountPassword = Yii::app()->user->account->password;
        if($accountPassword != CryptoUtil::hashPassword($this->oldPassword)){
            $this->addError('oldPassword', 'Authentication unsuccessful');
        }
    }
    
    public function changePassword(){
        $user = Yii::app()->user->account;
        $user->password = CryptoUtil::hashPassword($this->newPassword);
        return $user->save();
    }

}
?>
