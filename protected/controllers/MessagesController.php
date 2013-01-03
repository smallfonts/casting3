<?php

class MessagesController extends Controller {

    public $layout = '//layouts/landing';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
        );
    }

    public function actionIndex() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $userAccount = Yii::app()->user->account;
        switch ($userAccount->roleid) {
            case 1:
                $this->layout = '//layouts/artiste';
                break;
            case 2:
                $this->layout = '//layouts/production';
                break;
            case 3:
                break;
            case 4:
                $this->layout = '//layouts/castingmanager';
                break;
        }

        $this->render('messages');
    }

    public function actionCheckUpdate() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_GET['last_update'])) {
            
        }
    }

    public function actionNew() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->layout = "";
        $message = array();
        $to = array();
        if (isset($_GET['to']) && is_array($_GET['to'])) {
            $sendTo = $_GET['to'];
            //check if userids are valid
            $criteria = new CDbCriteria;
            $criteria->addInCondition('userid', $sendTo);
            $numUsers = UserAccount::model()->count($criteria);
            if ($numUsers == count($sendTo)) {
                $to = $sendTo;
            }
        } else if (isset($_GET['reply'])) {
            $messageid = $_GET['reply'];

            $replymessage = Message::model()->findByAttributes(array(
                'messageid' => $messageid,
                    ));

            if (!is_null($replymessage)) {
                if ($replymessage->userid == Yii::app()->user->account->userid) {
                    $message = $replymessage->toArray();
                } else {
                    //check if user is recipient of message that is to be replied for
                    $messageRecipient = MessageRecipient::model()->findByAttributes(array(
                        'userid' => Yii::app()->user->account->userid,
                        'messageid' => $messageid
                            ));

                    if (!is_null($messageRecipient)) {
                        $message = $messageRecipient->message->toArray();
                    }
                }
            }
        }

        $this->render('new', array(
            'message' => json_encode($message),
            'to' => json_encode($to),
        ));
    }

    public function actionCountUnread() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $sql = "SELECT count(*) from `message_recipient` where userid=:userid and statusid=18";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll(false, array(':userid' => Yii::app()->user->account->userid));
        foreach ($results as $row) {
            echo $row[0];
        }
    }

    public function actionGetNew() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_GET['last_date'])) {

            $message_recipients = MessageRecipient::model()->findAll(
                    'userid=:userid AND messageid IN (select messageid from message where created > :last_date) order by messageid desc', array(':userid' => Yii::app()->user->account->userid,
                ':last_date' => $_GET['last_date'])
            );

            $jsonSummary = array();
            foreach ($message_recipients as $recipient) {
                $tmpArr = $recipient->toArray();
                $senderPortfolio = $recipient->message->user->getPortfolio();
                $tmpArr['message'] = array(
                    'messageid' => $recipient->message->messageid,
                    'title' => $recipient->message->title,
                    'photoUrl' => $senderPortfolio->photo->url,
                    'name' => $senderPortfolio->getName(),
                    'created' => $recipient->message->created
                );
                $jsonSummary[] = $tmpArr;
            }

            echo json_encode($jsonSummary);
        }
    }

    public function actionGetMessage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);


        if (isset($_GET['messageid'])) {
            $messageRecipient = MessageRecipient::model()->findByAttributes(array(
                'userid' => Yii::app()->user->account->userid,
                'messageid' => $_GET['messageid'],
                    ));

            if (!is_null($messageRecipient)) {

                if ($messageRecipient->statusid == 18) {
                    $messageRecipient->statusid = 19;
                    $messageRecipient->save();
                }

                $message = $messageRecipient->message;

                //find more recent messages in reply to current message
                $messages = array();
                $messages[] = $message->toArray();


                //find messages that current messge replies to
                $curMessage = $message;
                $curMessageRecipient = $messageRecipient;
                $isOwnerOfMessage = false;
                while (!is_null($curMessage->reply_messageid) && (!is_null($curMessageRecipient) || $isOwnerOfMessage)) {
                    $isOwnerOfMessage = false;
                    $curMessage = $curMessage->reply_message;
                    if ($curMessage->userid == Yii::app()->user->account->userid) {
                        $isOwnerOfMessage = true;
                        $messages[] = $curMessage->toArray();
                    } else {
                        $curMessageRecipient = MessageRecipient::model()->findByAttributes(array(
                            'messageid' => $curMessage->messageid,
                            'userid' => Yii::app()->user->account->userid,
                                ));

                        if (!is_null($curMessageRecipient)) {
                            $messages[] = $curMessage->toArray();
                        }
                    }
                }


                echo json_encode($messages);
            }
        }
    }

    public function getRootMessage() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $messages = Message::model()->findAll(
                '(reply_messageid=NULL AND userid=:userid)
                  OR (messageid IN (SELECT messageid from message_recipient where userid=:userid))', array(
            ':userid' => Yii::app()->user->account->userid
                )
        );
    }

    public function actionGetMessageSummary() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $order = 0;
        if (isset($_GET['p']) && is_numeric($_GET['p'])) {
            $order = intval($_GET['p']) * 20;
        }


        $message_recipients = MessageRecipient::model()->findAll(
                'userid=:userid order by message_recipientid desc limit ' . $order . ',20', array(
            ':userid' => Yii::app()->user->account->userid,
                ));

        $jsonSummary = array();
        foreach ($message_recipients as $recipient) {
            $tmpArr = $recipient->toArray();
            $senderPortfolio = $recipient->message->user->getPortfolio();
            $tmpArr['message'] = array(
                'messageid' => $recipient->message->messageid,
                'title' => $recipient->message->title,
                'photoUrl' => $senderPortfolio->photo->url,
                'name' => $senderPortfolio->getName(),
                'created' => $recipient->message->created,
            );
            $jsonSummary[] = $tmpArr;
        }

        echo json_encode($jsonSummary);
    }

    public function actionSend() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['Message'])) {

            $title = !isset($_POST['Message']['title']) ? 'untitled' : $_POST['Message']['title'];
            $body = !isset($_POST['Message']['body']) ? '' : $_POST['Message']['body'];

            $message = new Message;
            $message->setAttributes(array(
                'userid' => Yii::app()->user->account->userid,
                'title' => $title,
                'body' => $body
            ));

            if (isset($_POST['Message']['reply_messageid'])) {
                $message->reply_messageid = $_POST['Message']['reply_messageid'];
            }

            $message->save();

            if (!is_null($message->reply_messageid)) {
                $messageTree = new MessageTree;
                $messageTree->setAttributes(array(
                    'parent_messageid' => $message->reply_messageid,
                    'child_messageid' => $message->messageid
                ));
                $messageTree->save();
            }

            $messageSent = new MessageSent;
            $messageSent->setAttributes(array(
                'userid' => Yii::app()->user->account->userid,
                'messageid' => $message->messageid,
            ));
            $messageSent->save();
            
            $emailMessageData = array(
                'photoBaseUrl' => Yii::app()->params->photoBaseUrl,
                'senderPhotoUrl' => Yii::app()->user->account->getPortfolio()->photo->url,
                'senderEmail' => Yii::app()->user->account->email,
                'senderName' => Yii::app()->user->account->getPortfolio()->getName(),
                'messageTitle' => $title,
                'dateSent' => date("D M j G:i Y"),
                'messageBody' => urldecode($body),
            );
            foreach ($_POST['Message']['to'] as $userid) {

                $user = UserAccount::model()->findByPK($userid);
                if (!is_null($user)) {
                    $messageRecipient = new MessageRecipient;
                    $messageRecipient->setAttributes(array(
                        'userid' => $userid,
                        'messageid' => $message->messageid,
                        'statusid' => 18,
                        'notified' => 0,
                    ));

                    $messageRecipient->save();
                    //variables of template
                    $emailMessageData['recipientName'] = $user->getPortfolio()->getName();
                    $emailMessageData['recipientEmail'] = $user->email;

                    //this template is generated from a .moustache template in /protected/templates
                    $renderToVariable = true;
                    $emailNotificationSubject= '[Casting3] '.$emailMessageData['senderName'].' has sent you a message';
                    $adminEmail= Yii::app()->params->adminEmail;
                    $emailNotification = Yii::app()->mustache->render('email_message_notification', $emailMessageData, null, null, $renderToVariable);
                    Email::sendEmail($emailMessageData['recipientEmail'],array($adminEmail),$emailNotificationSubject,$emailNotification);
                }
            }
        }
    }

}

?>