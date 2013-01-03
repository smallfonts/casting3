<?php

/**
 * This is the model class for table "casting_call_invitation".
 *
 * The followings are the available columns in table 'casting_call_invitation':
 * @property string $casting_call_invitationid
 * @property string $casting_callid
 * @property string $artiste_portfolioid
 * @property string $statusid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio $artistePortfolio
 * @property CastingCall $castingCall
 */
class CastingCallInvitation extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CastingCallInvitation the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'casting_call_invitation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('casting_callid, artiste_portfolioid', 'length', 'max' => 100),
            array('notified','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('casting_call_invitationid, casting_callid, artiste_portfolioid', 'safe', 'on' => 'search'),
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
            'castingCall' => array(self::BELONGS_TO, 'CastingCall', 'casting_callid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'casting_call_invitationid' => 'Casting Call Invitationid',
            'casting_callid' => 'Casting Callid',
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

        $criteria->compare('casting_call_invitationid', $this->casting_call_invitationid, true);
        $criteria->compare('casting_callid', $this->casting_callid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        return array(
            'casting_call_invitationid' => $this->casting_call_invitationid,
            'casting_callid' => $this->casting_callid,
            'castingcall' => $this->castingCall->toArray(),
            'casting_call_invitationid' => $this->casting_call_invitationid,
            'statusid' => $this->statusid
        );
    }

}