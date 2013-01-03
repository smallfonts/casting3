<?php

class ChangeEmailFormTest extends CDbTestCase {

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

    public function testChangeEmailWithValidInfo() {
        $this->testLogin();
        $model = new ChangeEmailForm;
        $model->setAttributes(array(
            'password' => '123',
            'newEmail' => 'timberwerkz2@timberwerkz.com',
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->changeEmail());
    }

    /*
     * @depands testLogin
     */

    public function testChangeEmailWithInvalidPassword() {
        $this->testLogin();
        $model = new ChangeEmailForm;
        $model->setAttributes(array(
            'password' => 'wrongpassword',
            'newEmail' => 'timberwerkz2@timberwerkz.com',
        ));

        $this->assertFalse($model->validate());
    }

    /*
     * @depands testLogin
     */

    public function testChangeEmailWithExistingEmail() {
        $this->testLogin();
        $model = new ChangeEmailForm;
        $model->setAttributes(array(
            'password' => '123',
            'newEmail' => 'timberwerkz@timberwerkz.com',
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->changeEmail());
    }

}

?>
