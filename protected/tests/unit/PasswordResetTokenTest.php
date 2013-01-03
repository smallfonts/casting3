<?php

class PasswordResetTokenTest extends CDbTestCase {

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

    public function testResetPasswordWithValidInfoWhenLoggedIn() {
        $this->testLogin();
        $model = new PasswordResetToken;
        $model->setAttributes(array(
            'email' => 'timberwerkz@timberwerkz.com',
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->save());
    }

    public function testResetPasswordWithValidInfoWhenNotLoggedIn() {
        $model = new PasswordResetToken;
        $model->setAttributes(array(
            'email' => 'timberwerkz@timberwerkz.com',
        ));

        $this->assertTrue($model->validate());
        $this->assertTrue($model->save());
    }

    public function testResetPasswordWithNotExistingEmail() {
        $model = new PasswordResetToken;
        $model->setAttributes(array(
            'email' => 'nosuchemail@timberwerkz.com',
        ));

        $this->assertFalse($model->validate());
    }

}

?>
