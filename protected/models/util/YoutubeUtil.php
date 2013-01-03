<?php

class YoutubeUtil {

    //provides a redirect url for user to give youtube access for casting3
    public static function getAuthSubRequestUrl() {
        $scope = 'http://gdata.youtube.com';
        $nextUrl = Yii::app()->getBaseUrl(true) . "/common/youtubeAuthenticated";
        $secure = false;
        $session = true;
        return Zend_Gdata_AuthSub::getAuthSubTokenUri($nextUrl, $scope, $secure, $session);
    }

    //exchange single-use token for a permanent session token
    public static function upgradeToSessionToken($token) {
        return Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
    }

    //checks if a session token is still valid
    public static function isTokenValid($token) {
        $log = new Logger('YoutubeUtil');
        $log->setMethod(__FUNCTION__);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$token);

        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Get the response and close the channel.
        $response = curl_exec($ch);
        curl_close($ch);

        $log->logInfo($response);
        $response = CJSON::decode($response);
        if (isset($response['error'])) {
            return false;
        } else {
            return true;
        }
    }

}

?>
