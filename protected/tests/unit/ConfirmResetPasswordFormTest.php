<?php

class ConfirmResetPasswordFormTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'prts' => 'PasswordResetToken'
    );

    public function testConfirmResetPasswordWithValidInfo() {
        $model = new ConfirmResetPasswordForm;
        $model->setAttributes(array(
            'newPassword' => '123',
            'newPassword2' => '123'
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->changePassword(1));
        $this->assertTrue($model->deleteToken(1));
    }

    public function testConfirmResetPasswordWithWrongPassword() {
        $model = new ConfirmResetPasswordForm;
        $model->setAttributes(array(
            'newPassword' => '123',
            'newPassword2' => '1234'
        ));

        $this->assertFalse($model->validate());
    }

    public function testConfirmResetPasswordWithEmptyPassword() {
        $model = new ConfirmResetPasswordForm;
        $model->setAttributes(array(
            'newPassword' => '',
            'newPassword2' => '1234'
        ));

        $this->assertFalse($model->validate());
    }

    public function testConfirmResetPasswordWithEmptyPassword2() {
        $model = new ConfirmResetPasswordForm;
        $model->setAttributes(array(
            'newPassword' => '123',
            'newPassword2' => ''
        ));

        $this->assertFalse($model->validate());
    }

}

?>
