<?php

Yii::import('application.extensions.s3.*');
require_once 'S3.php';

class FileUploadUtil {

    public static function uploadPhoto($filePath, $fileName) {
        $log = new Logger("FileUploadUtil");
        $log->setMethod(__FUNCTION__);
        $s3 = new S3(Yii::app()->params->awsAccessKey, Yii::app()->params->awsSecretKey);
        
        if ($s3->putObjectFile($filePath, Yii::app()->params->photoBucket, $fileName, S3::ACL_PUBLIC_READ)) {
            $log->logInfo('File Upload is successful for :'.$fileName);
        } else {
            $log->logError('File Upload to S3 has failed for :'.$fileName);
        }
    }

}

?>
