<?php

class UserAccountTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'roles' => 'Role',
        'statuses' => 'Status',
        'artisteporfolios' => 'ArtistePortfolio',
        'artisteportfoliophotos' => 'ArtistePortfolioPhoto',
        'photos' => 'Photo',
        'videos' => 'Video',
    );

    public function testCreateUserAccountValid() {

        $model = new UserAccount;
        $model->setAttributes(array(
            'password' => 'timberwerkz',
            'password2' => 'timberwerkz',
            'email' => 'timberwerkz.new@timberwerkz.com',
            'email2' => 'timberwerkz.new@timberwerkz.com',
            'roleid' => 1,
            'statusid' => 1,
        ));

        $this->assertTrue($model->save());
    }

    public function testCreateUserAccountInvalidEmail() {

        $model = new UserAccount;
        $model->setAttributes(array(
            'password' => 'timberwerkz',
            'password2' => 'timberwerkz',
            'roleid' => 2,
            'statusid' => 1,
        ));

        $model->email = 'timberwerkz.new.1@timberwerkz';
        $model->email2 = 'timberwerkz.new.1@timberwerkz';
        $this->assertFalse($model->save());

        $model->email = 'timberwerkz.new.1@.com';
        $model->email2 = 'timberwerkz.new.1@.com';
        $this->assertFalse($model->save());

        $model->email = '@timberwerkz.com';
        $model->email2 = '@timberwerkz.com';
        $this->assertFalse($model->save());

        $model->email = '@@timberwerkz.com';
        $model->email2 = '@@timberwerkz.com';
        $this->assertFalse($model->save());
    }

    public function testUpdateUserAccountWithInvalidEmail() {
        $model = UserAccount::model()->findByAttributes(array('email' => 'timberwerkz@timberwerkz.com'));
        $model->email = 'timberwerkz.new.1@timberwerkz';
        $model->email2 = 'timberwerkz.new.1@timberwerkz';
        $this->assertFalse($model->save());

        $model->email = 'timberwerkz.new.1@.com';
        $model->email2 = 'timberwerkz.new.1@.com';
        $this->assertFalse($model->save());

        $model->email = '@timberwerkz.com';
        $model->email2 = '@timberwerkz.com';
        $this->assertFalse($model->save());

        $model->email = '@@timberwerkz.com';
        $model->email2 = '@@timberwerkz.com';
        $this->assertFalse($model->save());
    }

    public function testUpdateUserAccountWithValidEmail() {
        $model = UserAccount::model()->findByAttributes(array('email' => 'timberwerkz@timberwerkz.com'));
        $model->email = 'timberwerkz.new.1@timberwerkz.com';
        $this->assertTrue($model->save());

        $model->email = 'timberwerkz.new.1@192.168.2.11';
        $this->assertTrue($model->save());

        $model->email = 'timberwerkz_new_1@timberwerkz.com';
        $this->assertTrue($model->save());
    }

    public function testUpdateUserAccountWithExistingEmail() {
        $model = UserAccount::model()->findByAttributes(array('email' => 'timberwerkz@timberwerkz.com'));
        $model->email = 'timberwerkz.1@timberwerkz.com';
        $this->assertFalse($model->save());
    }

    public function testPasswordHashedAfterInsert() {
        $model = new UserAccount;
        $model->setAttributes(array(
            'password' => 'timberwerkz',
            'password2' => 'timberwerkz',
            'email' => 'timberwerkz.new@timberwerkz.com',
            'email2' => 'timberwerkz.new@timberwerkz.com',
            'roleid' => 2,
            'statusid' => 1,
        ));

        $passwordHash = hash('sha256', 'timberwerkz');
        $this->assertTrue($model->save());
        $this->assertEquals($model->password, $passwordHash);
    }
    
    public function testProfileNotCreatedAfterInsertIfProductionHouseUser() {
        $model = new UserAccount;
        $model->setAttributes(array(
            'password' => 'timberwerkz',
            'password2' => 'timberwerkz',
            'email' => 'timberwerkz.new@timberwerkz.com',
            'email2' => 'timberwerkz.new@timberwerkz.com',
            'roleid' => 2,
            'statusid' => 1,));
        
        $this->assertTrue($model->save());
        $userid = $model->userid;
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array('userid'=>$userid));
        $this->assertTrue($artistePortfolio == null);
    }

    public function testProfileCreatedAfterInsert() {
        $model = new UserAccount;
        $model->setAttributes(array(
            'password' => 'timberwerkz',
            'password2' => 'timberwerkz',
            'email' => 'timberwerkz.new@timberwerkz.com',
            'email2' => 'timberwerkz.new@timberwerkz.com',
            'roleid' => 1,
            'statusid' => 1,));
        
        $this->assertTrue($model->save());
        $userid = $model->userid;
        $artistePortfolio = ArtistePortfolio::model()->findByAttributes(array('userid'=>$userid));
        $this->assertTrue($artistePortfolio != null);
    }

}

?>
