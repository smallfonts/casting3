<?php
class LoginFormTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount'
    );
    
    public function testLoginWithValidInfo() {
        $model = new LoginForm;
        $model->email = 'timberwerkz@timberwerkz.com';
        $model->password = '123';

        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors());
        $model->login();
        $this->assertTrue(Yii::app()->user->account != null);
    }
    
    public function testLoginAccountIdSameAsUserAccountId(){
        $user = UserAccount::model()->findByAttributes(array(
            'email'=>'timberwerkz@timberwerkz.com'
        ));
        $model = new LoginForm;
        $model->email = 'timberwerkz@timberwerkz.com';
        $model->password = '123';
        $model->login();
        
        $this->assertEquals($user->userid,Yii::app()->user->account->userid);
    }

    public function testLoginWithEmptyPassword() {
        $model = new LoginForm;
        $model->email = 'timberwerkz@timberwerkz.com';
        $this->assertFalse($model->validate());
    }

    public function testLoginWithEmptyUsername() {
        $model = new LoginForm;
        $model->password = '123';
        $this->assertFalse($model->validate());
    }

    public function testLoginWithWrongPassword() {
        $model = new LoginForm;
        $model->email = 'timberwerkz@timberwerkz.com';
        $model->password = '1234';
        $this->assertFalse($model->validate());
        $this->assertFalse($model->login());
    }

}
?>
