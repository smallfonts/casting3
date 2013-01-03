<?php

class CronController extends Controller {

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

    public function actionGenerateTime() {
        $endStr = "'11:45p'";
        $startHr = 1;
        $startMin = 0;
        $minArr = array('00', '15', '30', '45');
        $amPm = 'a';
        $time = 'abc';
        $str = '';
        while ($time != $endStr) {
            if ($startHr == 12) {
                $amPm = 'p';
            }

            if ($startHr == 13) {
                $startHr = 1;
            }

            $time = "'" . $startHr . ':' . $minArr[$startMin] . $amPm . "'";
            $str .= ',' . $time;

            if ($startMin == count($minArr) - 1) {
                $startHr++;
                $startMin = 0;
            } else {
                $startMin++;
            }
        }

        echo $str;
    }

    public function actionCloseAuditions() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $sql = "Update `audition` SET statusid=17 WHERE statusid=16 AND application_end <= NOW()";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

    public function actionGenerateStuff() {
        $totalSlots = (24 * 4);
        $curSlot = 0;
        $timeArr = array('12:00a', '12:15a', '12:30a', '12:45a', '1:00a', '1:15a', '1:30a', '1:45a', '2:00a', '2:15a', '2:30a', '2:45a', '3:00a', '3:15a', '3:30a', '3:45a', '4:00a', '4:15a', '4:30a', '4:45a', '5:00a', '5:15a', '5:30a', '5:45a', '6:00a', '6:15a', '6:30a', '6:45a', '7:00a', '7:15a', '7:30a', '7:45a', '8:00a', '8:15a', '8:30a', '8:45a', '9:00a', '9:15a', '9:30a', '9:45a', '10:00a', '10:15a', '10:30a', '10:45a', '11:00a', '11:15a', '11:30a', '11:45a', '12:00p', '12:15p', '12:30p', '12:45p', '1:00p', '1:15p', '1:30p', '1:45p', '2:00p', '2:15p', '2:30p', '2:45p', '3:00p', '3:15p', '3:30p', '3:45p', '4:00p', '4:15p', '4:30p', '4:45p', '5:00p', '5:15p', '5:30p', '5:45p', '6:00p', '6:15p', '6:30p', '6:45p', '7:00p', '7:15p', '7:30p', '7:45p', '8:00p', '8:15p', '8:30p', '8:45p', '9:00p', '9:15p', '9:30p', '9:45p', '10:00p', '10:15p', '10:30p', '10:45p', '11:00p', '11:15p', '11:30p', '11:45p');
        $str = '';
        for ($i = 0; $i < count($timeArr); $i++) {
            $str .= '<div style="width:102px">
                                    <span id="{{day.day}}_' . $i . '"></span>
                                    <div id="day_{{day.day}}_' . $i . '" class="c3Minutes"  time="' . $i . '" name="' . $timeArr[$i] . '" style="height:25px;border:1px solid #EDEDED">
                                    </div>
                                </div>';
        }
        echo $str;
    }

    public function actionComputeAuditionSlot() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        if (isset($_GET['key']) && $_GET['key'] == Yii::app()->params->password) {

            //get all auditions that are open with expired audition times
            $criteria = new CDbCriteria;
            $criteria->condition = 'application_end + INTERVAL 1 DAY  < NOW() AND statusid=16';
            $auditions = Audition::model()->findAll($criteria);
            foreach ($auditions as $audition) {
                //set audition status to locked = 2
                $audition->statusid = 2;
                $audition->save();
            }

            //get auditions that have closed
            $auditions = Audition::model()->findAllByAttributes(array(
                'statusid' => 2,
                    ));

            foreach ($auditions as $audition) {
                $audition->processTimeslots();
            }
        } else {
            throw new CHttpException(403, 'Access denied.');
        }
    }

}

?>