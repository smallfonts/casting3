<?php

class ResetPasswordFormTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount'
    );

    public function testResetPasswordWithValidInfo() {
        $model = new ResetPasswordForm;
        $model->setAttributes(array(
            'email' => 'timberwerkz@timberwerkz.com'
        ));

        $this->assertTrue($model->validate());
        //?
        //$this->assertTrue($model->sendEmail());
    }

    public function testResetPasswordWithEmptyEmail() {
        $model = new ResetPasswordForm;
        $model->setAttributes(array(
            'email' => ''
        ));

        $this->assertFalse($model->validate());
    }
/*
    public function testResetPasswordWithInvalidEmail() {
        $model = new ChangePasswordForm;
        $model->setAttributes(array(
            'email' => 'timberwerkz'
        ));

        $this->assertFalse($model->validate());
    }*/

}

?>
