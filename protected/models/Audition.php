<?php

/**
 * This is the model class for table "audition".
 *
 * The followings are the available columns in table 'audition':
 * @property string $auditionid
 * @property string $casting_callid
 * @property string $casting_manager_portfolioid
 * @property string $production_portfolioid
 * @property integer $duration
 * @property string $application_start
 * @property string $application_end
 * @property integer $statusid
 * @property string $created
 *
 * The followings are the available model relations:
 * @property CastingCall $castingCall
 * @property ProductionPortfolio $productionPortfolio
 * @property CastingManagerPortfolio $castingManagerPortfolio
 * @property Status $status
 * @property AuditionInterviewee[] $auditionInterviewees
 * @property AuditionSlot[] $auditionSlots
 */
class Audition extends CActiveRecord {

    private $slots = array();
    private $auditionInterviewees = array();
    private $matrix = array();

    public function toArray() {
        $arr = array(
            'auditionid' => $this->auditionid,
            'title' => $this->title,
            'casting_callid' => $this->casting_callid,
            'casting_call' => $this->castingCall->toArray(),
            'status' => $this->status->toArray(),
            'reselectable_slots' => $this->reselectable_slots, 
            'created' => $this->created,
        );

        if (!is_null($this->application_start)) {
            $arr['application_start'] = $this->application_start;
        }

        if (!is_null($this->application_end)) {
            $arr['application_end'] = $this->application_end;
        }

        return $arr;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Audition the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'audition';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('casting_callid, production_portfolioid, casting_manager_portfolioid, statusid', 'required'),
            array('statusid', 'numerical', 'integerOnly' => true),
            array('casting_callid, production_portfolioid', 'length', 'max' => 100),
            array('title,application_start, application_end, reselectable_slots', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('auditionid, casting_callid, production_portfolioid, casting_manager_portfolioid, application_start, application_end, statusid, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'castingCall' => array(self::BELONGS_TO, 'CastingCall', 'casting_callid'),
            'productionPortfolio' => array(self::BELONGS_TO, 'ProductionPortfolio', 'production_portfolioid'),
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
            'auditionInterviewees' => array(self::HAS_MANY, 'AuditionInterviewee', 'auditionid'),
            'auditionSlots' => array(self::HAS_MANY, 'AuditionSlot', 'auditionid'),
            'auditionNotes' => array(self::HAS_MANY,'AuditionNote','auditionid'),
        );
    }

    public function beforeSave() {
        if (is_null($this->created))
            $this->created = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'auditionid' => 'Auditionid',
            'casting_callid' => 'Casting Callid',
            'production_portfolioid' => 'Production Portfolioid',
            'application_start' => 'Application Start',
            'application_end' => 'Application End',
            'statusid' => 'Statusid',
            'created' => 'Created',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('auditionid', $this->auditionid, true);
        $criteria->compare('casting_callid', $this->casting_callid, true);
        $criteria->compare('production_portfolioid', $this->production_portfolioid, true);
        $criteria->compare('casting_manager_portfolioid', $this->casting_manager_portfolioid, true);
        $criteria->compare('application_start', $this->application_start, true);
        $criteria->compare('application_end', $this->application_end, true);
        $criteria->compare('statusid', $this->statusid);
        $criteria->compare('created', $this->created, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    
    /*
    public function processTimeslots() {
        $this->createMatrix();
        $this->displayMatrix();
        $algo = new HungarianAlgorithmUtil;
        $result = $algo->process($this->matrix);
        print_r('<br/><br/>');
        $sql = "Update `audition_interviewee_slot` SET statusid='10' WHERE auditionid=:auditionid";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(':auditionid' => $this->auditionid));

        $sql2 = "Update `audition_interviewee` SET statusid = 14 WHERE `auditionid`=:auditionid AND `artiste_portfolioid`=:artiste_portfolioid";
        $command2 = Yii::app()->db->createCommand($sql2);

        $sql = "Update `audition_interviewee_slot` SET statusid='9' WHERE `audition_slotid`=:audition_slotid and `artiste_portfolioid`=:artiste_portfolioid and `interval`=:interval";
        $command = Yii::app()->db->createCommand($sql);

        for ($r = 0; $r < count($result); $r++) {
            if ($r < count($this->auditionInterviewees)) {
                $auditionInterviewee = $this->auditionInterviewees[$r];
                $slotid = $result[$r];
                if ($slotid < count($this->slots)) {
                    $artistePortfolioid = $auditionInterviewee->artiste_portfolioid;
                    $auditionSlotid = $this->slots[$slotid]['audition_slotid'];
                    $interval = $this->slots[$slotid]['interval'];
                    $command->execute(array(
                        ':audition_slotid' => $auditionSlotid,
                        ':artiste_portfolioid' => $artistePortfolioid,
                        ':interval' => $interval
                    ));
                    
                    $command2->execute(array(
                        ':auditionid' => $auditionInterviewee->auditionid,
                        ':artiste_portfolioid' => $artistePortfolioid,
                    ));
                }
            }
        }

        $this->statusid = 14;
        $this->save();
    }

    public function displayMatrix() {
        echo "Matrix <br/>";
        echo "<table>";
        for ($r = 0; $r < count($this->matrix); $r++) {
            echo "<tr>";
            for ($c = 0; $c < count($this->matrix[$r]); $c++) {
                echo "<td>" . $this->matrix[$r][$c] . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    public function createMatrix() {
        //compute square matrix
        //interviewee vs auditionslot matrix[intervieweetindex][auditionslotindex]->priority,line
        //interviewees = array[intervieweeindex]->artiste_portfolioid
        //auditionslots = array[auditionslotindex]->audition_slotid,interval

        $auditionInterviewees = AuditionInterviewee::model()->findAllByAttributes(array(
            'auditionid' => $this->auditionid,
                ));

        $auditionSlots = AuditionSlot::model()->findAllByAttributes(array(
            'auditionid' => $this->auditionid,
                ));

        $slots = array();
        $intervieweeRowTemplate = array();
        foreach ($auditionSlots as $auditionSlot) {
            $startTime = strtotime($auditionSlot->start);
            $endTime = strtotime($auditionSlot->end);
            $diff = $endTime - $startTime;

            //intervals are used to determine audition slots and their corresponding start and end dates
            //eg. start time of 1st 1hr interval of auditionslot = auditionslot start time + (interval) * duration
            //intervals start from 0
            $intervals = floor($diff / ($this->duration * 60));
            $sql = "SELECT count(audition_interviewee_slotid) from `audition_interviewee_slot` where audition_slotid=:audition_slotid AND `interval`=:interval";
            $command = Yii::app()->db->createCommand($sql);

            for ($i = 0; $i < $intervals; $i++) {
                //select intervals where there are applicants applying for them
                $results = $command->queryRow(false, array(':audition_slotid' => $auditionSlot->audition_slotid, ':interval' => $i));
                foreach ($results as $row) {
                    $count = $row[0];
                    if ($count > 0) {
                        $slots[] = array(
                            'audition_slotid' => $auditionSlot->audition_slotid,
                            'interval' => $i,
                        );
                        $intervieweeRowTemplate[] = 10;
                    }
                }
            }
        }
        $this->slots = $slots;

        $matrix = array();
        $this->auditionInterviewees = $auditionInterviewees;
        foreach ($auditionInterviewees as $interviewee) {
            $intervieweeRow = $intervieweeRowTemplate;
            //check if interviewee has made audition slot preferences
            $auditionIntervieweeSlots = $interviewee->auditionIntervieweeSlots;
            if (count($auditionIntervieweeSlots) > 0) {
                foreach ($auditionIntervieweeSlots as $intervieweeSlot) {
                    for ($i = 0; $i < count($slots); $i++) {
                        if ($slots[$i]['audition_slotid'] == $intervieweeSlot->audition_slotid &&
                                $slots[$i]['interval'] == $intervieweeSlot->interval) {
                            $intervieweeRow[$i] = $intervieweeSlot->priority;
                        }
                    }
                }
                $matrix[] = $intervieweeRow;
            }
        }

        //pad matrix
        while (count($matrix) < count($slots)) {
            $matrix[] = array_fill(0, count($intervieweeRowTemplate), 0);
        }
        if (count($matrix) > 0) {
            while (count($slots) < count($matrix[0])) {
                for ($i = 0; $i < count($matrix); $i++) {
                    $row = $matrix[$i];
                    $row[] = 0;
                    $matrix[$i] = $row;
                }
            }
        }

        $this->matrix = $matrix;
    }
     */

}