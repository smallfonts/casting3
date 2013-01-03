<?php

/**
 * This is the model class for table "audition_interviewee".
 *
 * The followings are the available columns in table 'audition_interviewee':
 * @property string $audition_intervieweeid
 * @property string $auditionid
 * @property string $artiste_portfolioid
 * @property string $statusid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio $artistePortfolio
 * @property Audition $audition
 */
class AuditionInterviewee extends CActiveRecord {

    public function toArray() {
        return array(
            'audition_intervieweeid' => $this->audition_intervieweeid,
            'character_applicationid' => $this->character_applicationid,
            'auditionid' => $this->auditionid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'status' => $this->statusid
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AuditionInterviewee the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'audition_interviewee';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('auditionid, artiste_portfolioid, character_applicationid', 'required'),
            array('auditionid, artiste_portfolioid', 'length', 'max' => 100),
            array('notified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('audition_intervieweeid, auditionid, artiste_portfolioid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'artistePortfolio' => array(self::BELONGS_TO, 'ArtistePortfolio', 'artiste_portfolioid'),
            'audition' => array(self::BELONGS_TO, 'Audition', 'auditionid'),
            'auditionIntervieweeSlots' => array(self::HAS_MANY, 'AuditionIntervieweeSlot', 'audition_intervieweeid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'audition_intervieweeid' => 'Audition Intervieweeid',
            'auditionid' => 'Auditionid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
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

        $criteria->compare('audition_intervieweeid', $this->audition_intervieweeid, true);
        $criteria->compare('auditionid', $this->auditionid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}