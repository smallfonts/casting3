<?php

Yii::import('application.extensions.swift.*');
require_once 'lib/swift_required.php';
require_once 'classes/Transport/AWSTransport.php';
require_once 'classes/AWSTransport.php';
require_once 'classes/AWSInputByteStream.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Email {

    public static function sendEmail($to, $from, $subject='untitled', $body='') {
        $log = new Logger("EmailUtil");
        $log->setMethod(__FUNCTION__);
        try {

            //Create the Transport
            $transport = Swift_AWSTransport::newInstance(Yii::app()->params->awsAccessKey, Yii::app()->params->awsSecretKey);
            //Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($transport);
            $log->logInfo('from :' . print_r($from,true));
            $log->logInfo('to :' . print_r($to,true));
            $log->logInfo('subject :' . $subject);

            //Create the message
            $message = Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($from)
                    ->setTo($to)
                    ->setBody($body, 'text/html');
            $failures = "";
            
            if (!$mailer->send($message, $failures)) {
                $log->logInfo($failures);
            }
        } catch (Exception $e) {
            $log->logInfo('Exception: ' . $e->getMessage());
            return false;
        }
        return true;
    }

}

?>
