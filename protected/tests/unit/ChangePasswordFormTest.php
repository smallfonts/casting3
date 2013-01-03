<?php

class ChangePasswordFormTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount'
    );

    public function testLogin() {
        $model = new LoginForm;
        $model->email = 'timberwerkz@timberwerkz.com';
        $model->password = '123';

        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors());
        $model->login();
        $this->assertTrue(Yii::app()->user->account != null);
    }

    /*
     * @depands testLogin
     */

    public function testChangePasswordWithValidInfo() {
        $this->testLogin();
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'oldPassword' => '123',
            'newPassword' => '1234',
            'newPassword2' => '1234'
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->changePassword());
    }

    /*
     * @depands testLogin
     */

    public function testChangePasswordWithEmptyOldPassword() {
        $this->testLogin();
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'oldPassword' => '',
            'newPassword' => '1234',
            'newPassword2' => '1234'
        ));

        $this->assertFalse($model->validate());
    }

    /*
     * @depands testLogin
     */

    public function testChangePasswordWithEmptyNewPassword() {
        $this->testLogin();
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'oldPassword' => '123',
            'newPassword' => '',
            'newPassword2' => ''
        ));

        $this->assertFalse($model->validate());

        $model->setAttributes(array(
            'oldPassword' => '123',
            'newPassword' => '1234',
            'newPassword2' => ''
        ));

        $this->assertFalse($model->validate());

        $model->setAttributes(array(
            'oldPassword' => '123',
            'newPassword' => '',
            'newPassword2' => '1234'
        ));

        $this->assertFalse($model->validate());
    }

    /*
     * @depands testLogin
     */

    public function testChangePasswordWithIncorrectPassword() {
        $this->testLogin();
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'oldPassword' => '1234',
            'newPassword' => '123',
            'newPassword2' => '123'
        ));

        $this->assertFalse($model->validate());
    }

    /*
     * @depands testLogin
     */

    public function testChangePasswordWithNonRepeatedPassword() {
        $this->testLogin();
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'oldPassword' => '123',
            'newPassword' => '1234',
            'newPassword2' => '123'
        ));

        $this->assertFalse($model->validate());
    }

}

?>
