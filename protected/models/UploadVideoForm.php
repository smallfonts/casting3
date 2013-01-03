<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
/*Yii::import("ext.Zend.EZendAutoloader", true);
Yii::registerAutoloader(array("EZendAutoloader", "loadClass"), true);
EZendAutoloader::$prefixes = array('Zend', 'Custom');*/

class UploadVideoForm extends CFormModel {

    // public $nextUrl = null;
    public $videoTitle;
    public $videoDescription;
    public $videoCategory;
    public $videoTags;

    //public $session_token;
    /**
     * Declares the validation rules.
     * The rules state that videoTitle, videoDescription, videoCategory, videoTags and session_token are required
     * and password needs to be authenticated.
     */
    public function rules() {
        /* $log = new Logger(get_class($this));
          $log->setMethod(__FUNCTION__);
          $log->loginfo($this->videoTitle); */
        return array(
            // videoTitle, videoDescription, videoCategory, videoTags are required
            //array('videoTitle, videoDescription, videoCategory, videoTags, session_token', 'required'),
            array('videoTitle, videoDescription, videoCategory, videoTags', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'videoTitle' => 'Video Title',
            'videoDescription' => 'Video Description',
            'videoCategory' => 'Video Category',
            'videoTags' => 'Video Tags (please separate tags with a space)',
        );
    }

    /*
     * After Google's authentication server redirects the user's browser back to the current application,
     * a GET request parameter is set, called token. The value of this parameter is a single-use token that
     * can be used for authenticated access. This token can be converted into a multi-use token and stored in
     * your session. Then use the token value in a call to Zend_Gdata_AuthSub::getHttpClient().
     * This function returns an instance of Zend_Http_Client, with appropriate headers set so that subsequent
     * requests your application submits using that HTTP Client are also authenticated.
     */

    public function addVideoMetaData() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
         try {
                    $httpClient = Zend_Gdata_AuthSub::getHttpClient(Yii::app()->user->account->youtube_token);
                   // $httpClient = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken']);
                } catch (Zend_Gdata_App_Exception $e) {
                    print 'ERROR - Could not obtain authenticated Http client object. '
                            . $e->getMessage();
                    return;
                }
                $httpClient->setHeaders('X-GData-Key', 'key=' . 'AI39si7_9Mk_tO8WNjaTB9u9NTBKuFPBEqpFY_IAZjfVt0MQP990MyZbuMwJXh6e7PYU7Cmw3jmCUFDW_wZNjnyZmoi9dH5e4w');
                //$httpClient = getAuthSubHttpClient();
                $youTubeService = new Zend_Gdata_YouTube($httpClient);
                $newVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
                $newVideoEntry->setVideoTitle($this->videoTitle);
                $newVideoEntry->setVideoDescription($this->videoDescription);

                //make sure first character in category is capitalized
                $this->videoCategory = strtoupper(substr($this->videoCategory, 0, 1))
                        . substr($this->videoCategory, 1);
                $newVideoEntry->setVideoCategory($this->videoCategory);

                // convert videoTags from whitespace separated into comma separated
                $newVideoEntry->setVideoTags($this->videoTags);
                $tokenHandlerUrl = "http://gdata.youtube.com/action/GetUploadToken";
                $tokenArray = $youTubeService->getFormUploadToken($newVideoEntry, $tokenHandlerUrl);
                $tokenValue = $tokenArray['token'];
                $postUrl = $tokenArray['url'];
                unset(Yii::app()->session['tokenValue']);
                unset(Yii::app()->session['postUrl']);
                Yii::app()->session['tokenValue'] = $tokenValue;
                Yii::app()->session['postUrl'] = $postUrl;
                $log->loginfo($tokenValue);
                $log->loginfo($postUrl);
    }

}

?>
