<?php

class AuditionController extends Controller {

    public $layout = '//layouts/castingmanager';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            /* redirects user to artiste page if user has already logged in as an artiste user
             *
             */
            array('deny',
                'roles' => array('3'),
                'redirect' => array('/'),
            ),
            array('deny',
                'users' => array('?'),
                'redirect' => array('/'),
            ),
        );
    }

    public function actionNew() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);


        $url = Yii::app()->getRequest()->getQuery('url');
        $castingCall = CastingCall::model()->findByAttributes(array(
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
            'url' => $url,
                ));

        if (!is_null($castingCall)) {
            //create new audition schedule
            $audition = new Audition;
            $phu = ProductionHouseUser::model()->findByAttributes(array('cm_userid' => Yii::app()->user->account->userid));
            $prod = ProductionPortfolio::model()->findByAttributes(array(
                'userid' => $phu->production_userid
                    ));
            $audition->setAttributes(array(
                'production_portfolioid' => $prod->production_portfolioid,
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                'title' => 'New Audition',
                'casting_callid' => $castingCall->casting_callid,
                'statusid' => 11,
            ));
            $audition->save();

            $this->redirect(array('/audition/edit/' . $audition->auditionid));
            return;
        }

        $this->redirect(array('/'));
    }

    public function actionExport() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $auditionid = Yii::app()->getRequest()->getQuery('auditionid');
        $filename = Yii::app()->getRequest()->getQuery('filename');
        $audition = Audition::model()->findByAttributes(array(
            'auditionid' => $auditionid,
            'casting_manager_portfolioid' => Yii::app()->user->account->getPortfolio()->casting_manager_portfolioid
                ));

        if (!is_null($audition)) {
            $temp = tmpFile();
            fwrite($temp, "Date,Start,End,Name,Contact\r\n");
            $sql = "SELECT date(a.start) as date ,time(a.start) as start, time(a.end) as end, c.name, c.mobile_phone FROM
                    `audition_slot` as a, 
                    `audition_interviewee_slot` as b,
                    `artiste_portfolio` as c
                    WHERE
                    a.auditionid = b.auditionid
                    and a.audition_slotid = b.audition_slotid
                    and a.auditionid = :auditionid
                    and b.artiste_portfolioid = c.artiste_portfolioid
                    order by a.start asc;";
            $command = Yii::app()->db->createCommand($sql);
            $results = $command->queryAll(false, array(':auditionid' => $audition->auditionid));
            foreach ($results as $row) {
                fwrite($temp, "$row[0],$row[1],$row[2],$row[3],$row[4]\r\n");
            }
            fseek($temp, 0);
            header('Cache-Control:public');
            header('Content-Description:File Transfer');
            //header('Content-Length:'.filesize($temp).';');
            header('Content-Disposition: attachment;filename=' . $filename);
            header('Content-Type: application/octet-stream;');
            header('Content-Transfer-Encoding: binary');
            echo fread($temp, 1024);
            fclose($temp); // this removes the file
        }
    }

    public function actionSaveAndInvite() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['Audition'])) {

            $response = $this->actionSave();

            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $_POST['Audition']['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            $err = array();
            $index = 1;
            $errString = '';
            //check that application start and end is not empty
            if (is_null($audition->application_start) || is_null($audition->application_end)) {
                $err['audition'] = array();
                $err['audition']['application_start'] = 'Application Dates cannot be empty';

                $errString .= $index . '. ' . $err['audition']['application_start'] . '<br/>';
                $index++;
            }

            //count timeslots
            $auditionSlot = AuditionSlot::model()->findByAttributes(array(
                'auditionid' => $audition->auditionid,
                    ));

            if (is_null($auditionSlot)) {
                $err['auditionSlot'] = 'At least 1 audition slot must be made available';
                $errString .= $index . '. ' . $err['auditionSlot'] . '<br/>';
                $index++;
            }

            //count interviewees
            $auditionInterviewees = AuditionInterviewee::model()->findAllByAttributes(array(
                'auditionid' => $audition->auditionid,
                    ));

            if (is_null($auditionInterviewees) || count($auditionInterviewees) == 0) {
                $err['auditionInterviewee'] = 'At least 1 artiste must be invited to this audition';
                $errString .= $index . '. ' . $err['auditionInterviewee'] . '<br/>';
                $index++;
            }

            if (count($err) == 0) {

                $startDate = new DateTime($audition->application_start);
                $endDate = new DateTime($audition->application_end);

                $emailMessageData = array(
                    'castingCallTitle' => $audition->castingCall->title,
                    'auditionTitle' => $audition->title,
                    'applicationStart' => $startDate->format("D M j G:i Y"),
                    'applicationEnd' => $endDate->format("D M j G:i Y"),
                );

                foreach ($auditionInterviewees as $auditionInterviewee) {

                    if ($auditionInterviewee->email_sent == 0) {

                        $emailMessageData['recipientEmail'] = $auditionInterviewee->artistePortfolio->user->email;
                        $emailMessageData['recipientName'] = $auditionInterviewee->artistePortfolio->getName();

                        //send email invites to auditionees
                        //this template is generated from a .moustache template in /protected/templates
                        $renderToVariable = true;
                        $emailNotificationSubject = '[Casting3] Audition for ' . $emailMessageData['castingCallTitle'];
                        $adminEmail = Yii::app()->params->adminEmail;
                        $emailNotification = Yii::app()->mustache->render('email_audition_published_notification', $emailMessageData, null, null, $renderToVariable);
                        Email::sendEmail(array($emailMessageData['recipientEmail']), array($adminEmail), $emailNotificationSubject, $emailNotification);
                        $auditionInterviewee->email_sent = 1;
                        $auditionInterviewee->save();
                    }
                }
                $applicationEnd = strtotime($audition->application_end);
                if ($applicationEnd <= time()) {
                    $audition->statusid = 17;
                } else {
                    $audition->statusid = 16;
                }



                $audition->save();
                $response['location'] = '/castingCall/auditions/' . $audition->castingCall->url;
            } else {
                $response['errors'] = $err;

                $response['alerts'] = array(
                    array(
                        'template' => 'error',
                        'text' => $errString,
                    )
                );
            }

            echo json_encode($response);
        }
    }

    public function actionDeleteAuditionSlot() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['AuditionSlot'])) {
            $audition = Audition::model()->findByAttributes(array(
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                'auditionid' => $_POST['AuditionSlot']['auditionid']
                    ));

            if (!is_null($audition)) {
                //find audition slot
                $sql = "DELETE FROM`audition_slot` WHERE auditionid = :auditionid AND audition_slotid = :audition_slotid";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute(array(
                    ':auditionid' => $audition->auditionid,
                    ':audition_slotid' => $_POST['AuditionSlot']['audition_slotid'],
                ));
            }
        }
    }

    public function actionCreateAuditionSlot() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['NewAuditionSlots']) && is_array($_POST['NewAuditionSlots'])) {


            $audition = Audition::model()->findByAttributes(array(
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                'auditionid' => $_POST['NewAuditionSlots'][0]['auditionid'],
                    ));


            if (!is_null($audition)) {

                $response = array(
                    'auditionSlots' => array()
                );

                foreach ($_POST['NewAuditionSlots'] as $newSlot) {

                    $auditionSlot = new AuditionSlot;
                    $auditionSlot->auditionid = $audition->auditionid;
                    $auditionSlot->start = $newSlot['start'];
                    $auditionSlot->end = $newSlot['end'];
                    $auditionSlot->statusid = 1;
                    $auditionSlot->fixed = 0;
                    $auditionSlot->save();

                    $response['auditionSlots'][] = array(
                        'tmpid' => $newSlot['tmpid'],
                        'audition_slotid' => $auditionSlot->audition_slotid,
                    );
                }

                echo json_encode($response);
            }
        }
    }

    public function actionSaveApplication() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);


        if (isset($_POST['AuditionIntervieweeSlots'])) {

            $newIntervieweeSlots = $_POST['AuditionIntervieweeSlots'];
            $response = array();
            $response['errors'] = array();



            //check if user is invited to audition
            $auditionInterviewee = AuditionInterviewee::model()->findByAttributes(array(
                'auditionid' => $newIntervieweeSlots[0]['auditionid'],
                'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid
                    ));

            if (!is_null($auditionInterviewee)) {

                //prevent choosing of slots when past dateline
                $audition = $auditionInterviewee->audition;
                if (strtotime($audition->application_end) < time()) {
                    return;
                }

                //check if audition settings allow user to reselect slots
                if ($audition->reselectable_slots == 0 && count($auditionInterviewee->auditionIntervieweeSlots) > 0) {
                    return;
                }

                //check whether slot is taken
                //get interviewees with confirmed slots
                $sql = "SELECT count(*) from `audition_interviewee_slot` where audition_slotid=:audition_slotid";
                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryAll(false, array(':audition_slotid' => $newIntervieweeSlots[0]['audition_slotid']));
                foreach ($results as $row) {
                    if ($row[0] > 0)
                        return;
                }


                //delete old slots
                $sql = "DELETE FROM`audition_interviewee_slot` WHERE artiste_portfolioid = :artiste_portfolioid AND auditionid = :auditionid";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute(array(
                    ':artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                    ':auditionid' => $newIntervieweeSlots[0]['auditionid']
                ));

                //create new slots
                $newSlot = $newIntervieweeSlots[0];
                $auditionIntervieweeSlot = new AuditionIntervieweeSlot;
                $auditionIntervieweeSlot->attributes = $newSlot;
                $auditionIntervieweeSlot->audition_intervieweeid = $auditionInterviewee->audition_intervieweeid;
                $auditionIntervieweeSlot->artiste_portfolioid = Yii::app()->user->account->artistePortfolio->artiste_portfolioid;
                $auditionIntervieweeSlot->statusid = 9;
                $auditionIntervieweeSlot->save();


                //set audition interviewee's status to 8, submitted
                $auditionInterviewee->statusid = 8;
                $auditionInterviewee->save();

                $response['alerts'] = array(
                    array(
                        'template' => 'success',
                        'text' => 'slot has been confirmed',
                    ),
                );
                echo json_encode($response);
                return;
            }
        }
    }

    public function actionViewConfirmed() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_GET['auditionid'])) {

            $audition = Audition::model()->findByAttributes(array(
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                'auditionid' => $_GET['auditionid']
                    ));

            if (is_null($audition)) {
                $this->redirect(array('/'));
            }

            //get audition notes
            $auditionNotes = $audition->auditionNotes;
            $jsonAuditionNotes = array();
            foreach ($auditionNotes as $auditionNote) {
                $jsonAuditionNotes[] = $auditionNote->toArray();
            }
            $jsonAuditionNotes = json_encode($jsonAuditionNotes);


            $jsonAudition = json_encode($audition->toArray());
            $jsonCastingCall = json_encode($audition->castingCall->toArray());

            //get all interviewees
            $interviewees = $audition->auditionInterviewees;
            $jsonInterviewees = array();
            foreach ($interviewees as $interviewee) {
                $tmpArr = $interviewee->toArray();
                $artistePortfolio = $interviewee->artistePortfolio;
                $artistePortfolioArr = $artistePortfolio->toArray();

                //get application
                $characterApplication = CharacterApplication::model()->findByAttributes(array(
                    'character_applicationid' => $interviewee->character_applicationid,
                        ));

                $characterApplicationArr = $characterApplication->toArray();

                $intervieweeProfile = array_merge($tmpArr, $artistePortfolioArr);
                $intervieweeProfile['characterApplication'] = $characterApplicationArr;
                $jsonInterviewees[] = $intervieweeProfile;
            }
            $jsonInterviewees = json_encode($jsonInterviewees);

            //get interviewees with confirmed slots
            $sql = "Select a.artiste_portfolioid as artiste_portfolioid,d.name as name,d.url as url,e.url as photoUrl, b.start as start, b.end as end, c.title as title, a.audition_intervieweeid as audition_intervieweeid, a.audition_slotid as audition_slotid 
                    from `audition_interviewee_slot` as a, `audition_slot` as b, `audition` as c ,`artiste_portfolio` as d,`photo` as e
                    WHERE c.auditionid = :auditionid and b.auditionid = c.auditionid and a.audition_slotid = b.audition_slotid and d.artiste_portfolioid = a.artiste_portfolioid and e.photoid = d.photoid and a.statusid = 9 ORDER BY start ASC";
            $command = Yii::app()->db->createCommand($sql);

            $results = $command->queryAll(false, array(':auditionid' => $_GET['auditionid']));

            //0 -> artiste_portfolioid
            //1-> name
            //2-> url
            //3-> photoUrl
            //4-> start
            //5-> end
            //6-> title
            $slotsEachDay = array();
            $currentStart = null;
            foreach ($results as $result) {
                //if it belongs to the current date, add into slotsEachDay[currentIndex]
                //if its already the next date, +1 to currentIndex, assign new date to currentStart, add to slotsEachDay[currentIndex]
                $log->logInfo('time ' . substr($result[4], 0, 10));
                $thisdate = substr($result[4], 0, 10);
                if (strcmp($thisdate, $currentStart) != 0) {
                    $log->logInfo('slot ' . substr($result[4], 0, 10) . "new date");
                    $slotsEachDay[] = array();

                    $currentStart = $thisdate;
                }

                $slotsEachDay[(count($slotsEachDay) - 1)][] = array(
                    'artiste_portfolioid' => $result[0],
                    'name' => $result[1],
                    'url' => $result[2],
                    'photoUrl' => $result[3],
                    'start' => $result[4],
                    'end' => $result[5],
                    'title' => $result[6],
                    'audition_intervieweeid' => $result[7],
                    'audition_slotid' => $result[8]
                );
            }


            $jsonSlotsEachDay = json_encode($slotsEachDay);
            $languageProficiencies = LanguageProficiency::model()->findAll();
            $jsonLanguageProficiencies = CJSON::encode($languageProficiencies);

            //get slots have have been created by casting manager
            $auditionSlots = AuditionSlot::model()->findAllByAttributes(array(
                'auditionid' => $audition->auditionid,
                'statusid' => 1
                    ));

            $jsonAuditionSlots = array();
            foreach ($auditionSlots as $auditionSlot) {
                $jsonAuditionSlots[] = $auditionSlot->toArray();
            }

            $jsonAuditionSlots = json_encode($jsonAuditionSlots);

            $this->render('viewConfirmed', array(
                'jsonAuditionInterviewees' => $jsonInterviewees,
                'jsonAuditionSlots' => $jsonAuditionSlots,
                'jsonAuditionNotes' => $jsonAuditionNotes,
                'jsonCastingCall' => $jsonCastingCall,
                'jsonAudition' => $jsonAudition,
                'jsonSlotsEachDay' => $jsonSlotsEachDay,
                'jsonLanguageProficiencies' => $jsonLanguageProficiencies,
                'currentMillis' => (time() * 1000),
            ));
            return;
        }
        $this->redirect(array('/'));
    }

    public function actionSaveDraft() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        echo json_encode($this->actionSave());
    }

    public function actionSave() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['Audition'])) {
            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $_POST['Audition']['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            $audition->attributes = $_POST['Audition'];

            $response = array();
            $response['changes'] = array();
            $audition->save();

            if (isset($_POST['Changes'])) {
                $changes = $_POST['Changes'];






                if (isset($changes['AuditionSlots'])) {
                    $response['changes']['auditionSlots'] = array();
                    if (isset($changes['AuditionSlots']['added'])) {
                        $response['changes']['auditionSlots']['added'] = array();
                        $newSlots = $changes['AuditionSlots']['added'];
                        foreach ($newSlots as $newSlot) {
                            $auditionSlot = new AuditionSlot;
                            $auditionSlot->setAttributes(array(
                                'auditionid' => $audition->auditionid,
                                'start' => $newSlot['start'],
                                'end' => $newSlot['end'],
                                'statusid' => 1,
                            ));
                            $auditionSlot->save();
                            $response['changes']['auditionSlots']['added'][] = array(
                                'tmpid' => $newSlot['tmpid'],
                                'audition_slotid' => $auditionSlot->audition_slotid
                            );
                        }
                    }

                    if (isset($changes['AuditionSlots']['deleted'])) {
                        $response['changes']['auditionSlots']['deleted'] = array();
                        $deleteSlots = $changes['AuditionSlots']['deleted'];
                        foreach ($deleteSlots as $deleteSlot) {
                            $auditionSlot = AuditionSlot::model()->findByAttributes(array(
                                'audition_slotid' => $deleteSlot['audition_slotid'],
                                'auditionid' => $audition->auditionid,
                                    ));
                            if (!is_null($auditionSlot)) {
                                $auditionSlot->delete();
                                $response['changes']['auditionSlots']['deleted'][] = array(
                                    'audition_slotid' => $auditionSlot->audition_slotid
                                );
                            }
                        }
                    }
                }


                if (isset($changes['AuditionInterviewees'])) {
                    $response['changes']['auditionInterviewees'] = array();

                    if (isset($changes['AuditionInterviewees']['added'])) {
                        $response['changes']['auditionInterviewees']['added'] = array();
                        $addedInterviewees = $changes['AuditionInterviewees']['added'];
                        foreach ($addedInterviewees as $addedInterviewee) {
                            $auditionInterviewee = new AuditionInterviewee;
                            $auditionInterviewee->setAttributes(array(
                                'auditionid' => $audition->auditionid,
                                'artiste_portfolioid' => $addedInterviewee['artiste_portfolioid'],
                                'character_applicationid' => $addedInterviewee['character_applicationid'],
                                'notified' => 0,
                            ));

                            $auditionInterviewee->save();
                            $response['changes']['auditionInterviewees']['added'][] = array(
                                'tmpid' => $addedInterviewee['tmpid'],
                                'audition_intervieweeid' => $auditionInterviewee->audition_intervieweeid,
                            );
                        }
                    }

                    if (isset($changes['AuditionInterviewees']['deleted'])) {
                        $response['changes']['auditionInterviewees']['deleted'] = array();
                        $deletedInterviewees = $changes['AuditionInterviewees']['deleted'];
                        foreach ($deletedInterviewees as $deletedInterviewee) {
                            $auditionInterviewee = AuditionInterviewee::model()->findByAttributes(array(
                                'audition_intervieweeid' => $deletedInterviewee['audition_intervieweeid'],
                                'auditionid' => $audition->auditionid
                                    ));

                            if (!is_null($auditionInterviewee)) {
                                $auditionInterviewee->delete();
                            }
                        }
                    }
                }
            }

            $response['alerts'] = array(
                array(
                    "template" => "success",
                    "text" => "Audition saved as draft",
                ),
            );
            return $response;
        }
    }

    public function actionEdit() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = Yii::app()->getRequest()->getQuery('url');

        $audition = Audition::model()->findByAttributes(array(
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
            'auditionid' => $url
                ));

        $castingManagerPortfolio = CastingManagerPortfolio::model()->findByAttributes(array(
            'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                ));

        if (!is_null($audition)) {

            $castingCall = CastingCall::model()->findByAttributes(array(
                'casting_callid' => $audition->casting_callid,
                    ));

            $castingManagerPortfolio->audition_guide = $castingManagerPortfolio->audition_guide + 1;
            $castingManagerPortfolio->save();

            $jsonCastingManagerPortfolio = json_encode(array(
                'audition_guide' => $castingManagerPortfolio->audition_guide
                    ));

            $jsonCastingCall = json_encode(array(
                'casting_callid' => $castingCall->casting_callid,
                'title' => $castingCall->title,
                'url' => $castingCall->url
                    ));

            $auditionSlots = $audition->auditionSlots;
            $jsonAuditionSlots = array();
            foreach ($auditionSlots as $auditionSlot) {
                $jsonAuditionSlots[] = $auditionSlot->toArray();
            }
            $jsonAuditionSlots = json_encode($jsonAuditionSlots);

            $auditionNotes = $audition->auditionNotes;
            $jsonAuditionNotes = array();
            foreach ($auditionNotes as $auditionNote) {
                $jsonAuditionNotes[] = $auditionNote->toArray();
            }
            $jsonAuditionNotes = json_encode($jsonAuditionNotes);

            $interviewees = $audition->auditionInterviewees;
            $jsonInterviewees = array();
            foreach ($interviewees as $interviewee) {
                $jsonInterviewees[] = $interviewee->toArray();
            }
            $jsonInterviewees = json_encode($jsonInterviewees);

            $jsonAudition = json_encode($audition->toArray());


            $this->render('edit', array(
                'jsonCastingCall' => $jsonCastingCall,
                'jsonAuditionNotes' => $jsonAuditionNotes,
                'jsonAuditionInterviewees' => $jsonInterviewees,
                'jsonAudition' => $jsonAudition,
                'jsonCastingManagerPortfolio' => $jsonCastingManagerPortfolio,
                'jsonAuditionSlots' => $jsonAuditionSlots));
            return;
        }

        $this->redirect(array('/'));
    }

    public function actionCreateAuditionNote() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['AuditionNotes']) && is_array($_POST['AuditionNotes'])) {
            $auditionNotes = $_POST['AuditionNotes'];
            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $auditionNotes[0]['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if (!is_null($audition)) {

                $response = array(
                    'auditionNotes' => array(),
                );

                foreach ($auditionNotes as $auditionNoteVals) {
                    $auditionNote = new AuditionNote;
                    $auditionNote->attributes = $auditionNoteVals;
                    $auditionNote->save();

                    $response['auditionNotes'][] = array(
                        'tmpid' => $auditionNoteVals['tmpid'],
                        'audition_noteid' => $auditionNote->audition_noteid,
                    );
                }

                echo json_encode($response);
            }
        }
    }

    public function actionSaveAuditionNote() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['AuditionNote'])) {
            $auditionNoteVal = $_POST['AuditionNote'];
            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $auditionNoteVal['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));
            if (!is_null($audition)) {

                $auditionNote = AuditionNote::model()->findByAttributes(array(
                    'auditionid' => $audition->auditionid,
                    'audition_noteid' => $auditionNoteVal['audition_noteid'],
                        ));

                if (!is_null($auditionNote)) {
                    $log->logInfo("audition note is not null");
                    $auditionNote->attributes = $auditionNoteVal;
                    $auditionNote->save();
                }
            }
        }
    }

    public function actionDeleteAuditionNote() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_POST['AuditionNote'])) {
            $auditionNoteVal = $_POST['AuditionNote'];
            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $auditionNoteVal['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));
            if (!is_null($audition)) {

                $sql = "DELETE FROM `audition_note` WHERE auditionid = :auditionid AND audition_noteid = :audition_noteid";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute(array(
                    ':auditionid' => $audition->auditionid,
                    ':audition_noteid' => $auditionNoteVal['audition_noteid'],
                ));
            }
        }
    }

    public function actionApply() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $this->layout = '//layouts/artiste';
        $url = Yii::app()->getRequest()->getQuery('url');
        $audition = Audition::model()->findByAttributes(array(
            'auditionid' => $url,
                ));

        //check if audition is expired
        $endDate = strtotime($audition->application_end);
        if (!is_null($audition) && $endDate > time()) {
            $auditionInterviewee = AuditionInterviewee::model()->findByAttributes(array(
                'auditionid' => $audition->auditionid,
                'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid
                    ));


            if (!is_null($auditionInterviewee)) {

                //check if artiste has been allocated to a fixed slot
                $auditionIntervieweeSlot = AuditionIntervieweeSlot::model()->findByAttributes(array(
                    'audition_intervieweeid' => $auditionInterviewee->audition_intervieweeid,
                    'auditionid' => $audition->auditionid,
                        ));

                if (!is_null($auditionIntervieweeSlot)) {
                    $auditionSlot = $auditionIntervieweeSlot->auditionSlot;
                    if ($auditionSlot->fixed == 1) {
                        return;
                    }
                }

                //find audition slots
                $auditionSlots = AuditionSlot::model()->findAllByAttributes(array(
                    'auditionid' => $audition->auditionid,
                        ));

                $jsonAuditionSlots = array();
                $jsonUnselectableSlots = array();
                $audition_slotids = "";
                foreach ($auditionSlots as $auditionSlot) {
                    if ($auditionSlot->statusid == 1) {
                        $jsonAuditionSlots[] = $auditionSlot->toArray();
                    } else {
                        $jsonUnselectableSlots[] = $auditionSlot->toArray();
                    }
                    $audition_slotids .= "," . $auditionSlot->audition_slotid;
                }

                $jsonAuditionSlots = json_encode($jsonAuditionSlots);
                $jsonUnselectableSlots = json_encode($jsonUnselectableSlots);

                //gets slots that have already been chosen by auditionees
                $audition_slotids = substr($audition_slotids, 1);
                $sql = "SELECT a.audition_slotid, count(a.audition_slotid) FROM `audition_slot` as a, `audition_interviewee_slot` as b  where 
                        a.audition_slotid IN (" . $audition_slotids . ") 
                        and priority = 0 
                        and a.audition_slotid = b.audition_slotid
                        group by a.audition_slotid";


                $command = Yii::app()->db->createCommand($sql);
                $results = $command->queryAll(false);

                $slotPriorities = array();
                foreach ($results as $row) {
                    $slotPriorities[] = array(
                        'audition_slotid' => $row[0],
                        'count' => $row[1]
                    );
                }
                $jsonSlotPriorities = json_encode($slotPriorities);

                $artistePortfolio = ArtistePortfolio::model()->findByAttributes(Array(
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid
                        ));

                $artistePortfolio->audition_guide = $artistePortfolio->audition_guide + 1;
                $artistePortfolio->save();

                $jsonArtistePortfolio = json_encode(array(
                    'audition_guide' => $artistePortfolio->audition_guide
                        ));

                //get interviewee slots
                $auditionIntervieweeSlots = AuditionIntervieweeSlot::model()->findAllByAttributes(array(
                    'auditionid' => $audition->auditionid,
                    'artiste_portfolioid' => Yii::app()->user->account->artistePortfolio->artiste_portfolioid,
                        ), array(
                    'order' => 'priority ASC',
                        ));



                $jsonAuditionIntervieweeSlots = array();
                foreach ($auditionIntervieweeSlots as $auditionIntervieweeSlot) {
                    $jsonAuditionIntervieweeSlots[] = $auditionIntervieweeSlot->toArray();
                }
                $jsonAuditionIntervieweeSlots = json_encode($jsonAuditionIntervieweeSlots);

                //get json audition
                $jsonAudition = json_encode($audition->toArray());

                $this->render('apply', array(
                    'jsonAuditionSlots' => $jsonAuditionSlots,
                    'jsonArtistePortfolio' => $jsonArtistePortfolio,
                    'jsonUnselectableSlots' => $jsonUnselectableSlots,
                    'jsonSlotPriorities' => $jsonSlotPriorities,
                    'jsonAuditionIntervieweeSlots' => $jsonAuditionIntervieweeSlots,
                    'jsonAudition' => $jsonAudition,
                    'currentMillis' => (time() * 1000),
                ));
            }
        }
    }

    public function actionAllocateIntervieweeSlot() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['AuditionIntervieweeSlot'])) {
            $auditionIntervieweeSlot = $_POST['AuditionIntervieweeSlot'];

            //check if user is owner of audition
            $audition = Audition::model()->findByAttributes(array(
                'auditionid' => $auditionIntervieweeSlot['auditionid'],
                'casting_manager_portfolioid' => Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid,
                    ));

            if (!is_null($audition)) {

                //check if interviewee has signed up for audition
                $auditionInterviewee = AuditionInterviewee::model()->findByAttributes(array(
                    'audition_intervieweeid' => $auditionIntervieweeSlot['audition_intervieweeid'],
                    'auditionid' => $auditionIntervieweeSlot['auditionid'],
                        ));

                if (is_null($auditionInterviewee)) {
                    return;
                }

                $oldAuditionIntervieweeSlot = AuditionIntervieweeSlot::model()->findByAttributes(array(
                    'auditionid' => $audition->auditionid,
                    'audition_intervieweeid' => $auditionIntervieweeSlot['audition_intervieweeid']
                        ));

                if (!is_null($oldAuditionIntervieweeSlot)) {
                    //set auditionSlot fixed status to 0
                    $auditionSlot = $oldAuditionIntervieweeSlot->auditionSlot;
                    $auditionSlot->fixed = 0;
                    $auditionSlot->save();
                    $oldAuditionIntervieweeSlot->delete();
                }

                //try to find existing slot
                $auditionSlot = AuditionSlot::model()->findByAttributes(array(
                    'auditionid' => $audition->auditionid,
                    'start' => $auditionIntervieweeSlot['start'],
                    'end' => $auditionIntervieweeSlot['end'],
                    'statusid' => 1,
                        ));


                //create a new slot
                if (is_null($auditionSlot)) {
                    $auditionSlot = new AuditionSlot;
                    $auditionSlot->setAttributes(array(
                        'auditionid' => $audition->auditionid,
                        'start' => $auditionIntervieweeSlot['start'],
                        'end' => $auditionIntervieweeSlot['end'],
                        'statusid' => 2,
                        'fixed' => 1,
                    ));
                    $auditionSlot->save();
                } else {
                    $auditionSlot->fixed = 1;
                    $auditionSlot->save();
                }



                //create new interviewee slot
                $newAuditionIntervieweeSlot = new AuditionIntervieweeSlot;
                $newAuditionIntervieweeSlot->setAttributes(array(
                    'audition_slotid' => $auditionSlot->audition_slotid,
                    'audition_intervieweeid' => $auditionIntervieweeSlot['audition_intervieweeid'],
                    'artiste_portfolioid' => $auditionIntervieweeSlot['artiste_portfolioid'],
                    'auditionid' => $auditionIntervieweeSlot['auditionid'],
                    'priority' => 0,
                    'statusid' => 9
                ));
                $newAuditionIntervieweeSlot->save();
            }
        }
    }

    public function actionSaveFeedback() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        if (isset($_POST['CharacterApplication'])) {
            //get character application id
            $characterApplication = CharacterApplication::model()->findByPK($_POST['CharacterApplication']['character_applicationid']);
            if (!is_null($characterApplication)) {
                //check if casting call for application belongs to user
                if ($characterApplication->character->castingCall->casting_manager_portfolioid == Yii::app()->user->account->castingmanagerPortfolio->casting_manager_portfolioid) {

                    $characterApplication->comments = $_POST['CharacterApplication']['comments'];
                    $characterApplication->rating = $_POST['CharacterApplication']['rating'];
                    $characterApplication->save();

                    $response = array();
                    $response['alerts'] = array(
                        array(
                            "template" => "success",
                            "text" => "Feedback has been saved",
                        ),
                    );

                    echo json_encode($response);
                }
            }
        }
    }

}

?>