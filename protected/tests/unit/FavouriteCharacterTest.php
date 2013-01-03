<?php

class FavouriteCharacterTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
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

}

?>
