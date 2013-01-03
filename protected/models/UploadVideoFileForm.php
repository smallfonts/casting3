<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
/*Yii::import("ext.Zend.EZendAutoloader", true);
Yii::registerAutoloader(array("EZendAutoloader", "loadClass"), true);
EZendAutoloader::$prefixes = array('Zend', 'Custom');*/

class UploadVideoFileForm extends CFormModel {

    public $nextUrl = null;
    public $videoFile;
    public $tokenValue;
    public $postUrl;
    /**
     * Declares the validation rules.
     * The rules state that nextUrl, videoFile, tokenValue,and postUrl are required
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // nextUrl, videoFile, tokenValue, postUrl are required
            array('nextUrl, videoFile, tokenValue, postUrl', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'videoFile' => 'Please select the video file you want to upload',
        );
    }



    
}

?>
