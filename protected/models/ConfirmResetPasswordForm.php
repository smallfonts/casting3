<?php

/**
 * ConfirmResetPasswordForm class.
 */
class ConfirmResetPasswordForm extends CFormModel {

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
            array('newPassword, newPassword2', 'required'),
            array('newPassword2', 'compare', 'compareAttribute' => 'newPassword'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'newPassword' => 'New Password',
            'newPassword2' => 'Repeat Password',
        );
    }

    public function changePassword($id) {
        $criteria = new CDbCriteria;
        $criteria->compare('userid', $id, true);
        $user = UserAccount::model()->find($criteria);
        $user->password = CryptoUtil::hashPassword($this->newPassword);
        return $user->save();
    }

    public function deleteToken($id) {
        $criteria = new CDbCriteria;
        $criteria->compare('userid', $id, true);
        $prt = PasswordResetToken::model()->find($criteria);
        return $prt->delete();
    }

}

?>
