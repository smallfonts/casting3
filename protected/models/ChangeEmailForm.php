<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangeEmailForm extends CFormModel {

    public $password;
    public $newEmail;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('password, newEmail', 'required'),
            array('newEmail','email'),
            array('password','authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'password' => 'Password',
            'newEmail' => 'New Email',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate() {
        $accountPassword = Yii::app()->user->account->password;
        if($accountPassword != CryptoUtil::hashPassword($this->password)){
            $this->addError('password', 'Authentication unsuccessful');
        }
    }
    
    public function changeEmail(){
        $user = Yii::app()->user->account;
        $user->email = $this->newEmail;
        return $user->save();
    }

}
?>
