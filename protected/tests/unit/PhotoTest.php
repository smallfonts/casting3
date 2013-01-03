<?php

class PhotoTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'photos' => 'Photo',
    );
    
    public $testFile = array(
       'name'=>'test.png',
       'tmp_name'=>'C:\wamp\www\timberwerkz\images\photos\test.png',
       'type'=>'image/png',
       'size'=>3582976,
       'error'=>0
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

    public function testUploadPhotoNullUserid() {
        $model = new Photo;
        $model->scenario = 'manualInsert';
        $model->url = '/photourl.jpg';
        $this->assertTrue($model->validate());
        $this->assertTrue($model->save());
    }

    public function UploadPhotoForChromeAndMozWithValidInfo() {
        //log user in
        $this->testLoginWithValidInfo();
        
        $model = new Photo;
        $imageFile = new CUploadedFile($this->testFile['name'],$this->testFile['tmp_name'],$this->testFile['type'],$this->testFile['size'],$this->testFile['error']);
        $model->setAttributes(array(
            'image' => $imageFile,
            'x1' => 10.999992370605469,
            'x2' => 16,
            'y1' => 0,
            'y2' => 5.000007629394531,
            'width' => 5.000007629394531,
            'height' => 5.000007629394531,
            'ext' => 'png',
        ));
        
        $this->assertTrue($model->validate());
        $this->assertTrue($model->save());
    }

}

?>
