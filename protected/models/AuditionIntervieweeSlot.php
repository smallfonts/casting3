<?php

/**
 * This is the model class for table "audition_interviewee_slot".
 *
 * The followings are the available columns in table 'audition_interviewee_slot':
 * @property string $audition_interviewee_slotid
 * @property string $artiste_portfolioid
 * @property string $audition_slotid
 * @property integer $priority
 * @property integer $statusid
 * @property integer $auditionid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolioPhoto $artistePortfolio
 * @property AuditionSlot $auditionSlot
 * @property Status $status
 */
class AuditionIntervieweeSlot extends CActiveRecord {

    public function toArray() {
        return array(
            'audition_interviewee_slotid' => $this->audition_interviewee_slotid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'audition_slotid' => $this->audition_slotid,
            'priority' => $this->priority,
            'status' => $this->status->toArray(),
            'created' => $this->created,
            'auditionid' => $this->auditionid
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AuditionIntervieweeSlot the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'audition_interviewee_slot';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('auditionid, artiste_portfolioid,audition_intervieweeid, audition_slotid, priority, statusid', 'required'),
            array('priority, statusid', 'numerical', 'integerOnly' => true),
            array('artiste_portfolioid, audition_slotid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('audition_interviewee_slotid, artiste_portfolioid, audition_slotid, priority, statusid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'artistePortfolio' => array(self::BELONGS_TO, 'ArtistePortfolioPhoto', 'artiste_portfolioid'),
            'auditionSlot' => array(self::BELONGS_TO, 'AuditionSlot', 'audition_slotid'),
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
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
            'audition_interviewee_slotid' => 'Audition Interviewee Slotid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
            'audition_slotid' => 'Audition Slotid',
            'priority' => 'Priority',
            'statusid' => 'Statusid',
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

        $criteria->compare('audition_interviewee_slotid', $this->audition_interviewee_slotid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);
        $criteria->compare('audition_slotid', $this->audition_slotid, true);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('statusid', $this->statusid);
        $criteria->compare('auditionid', $this->auditionid);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}