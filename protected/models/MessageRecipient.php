<?php

/**
 * This is the model class for table "message_recipient".
 *
 * The followings are the available columns in table 'message_recipient':
 * @property string $message_recipientid
 * @property string $messageid
 * @property string $userid
 * @property integer $statusid
 */
class MessageRecipient extends CActiveRecord {

    public function toArray() {
        return array(
            'status' => $this->status->toArray(),
            'message_recipientid' => $this->message_recipientid,
        );
    }

    public function beforeSave() {
        $this->last_modified = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MessageRecipient the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'message_recipient';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('messageid, userid, statusid', 'required'),
            array('statusid', 'numerical', 'integerOnly' => true),
            array('messageid, userid', 'length', 'max' => 100),
            array('notified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('message_recipientid, messageid, userid, statusid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'message' => array(self::BELONGS_TO, 'Message', 'messageid'),
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'message_recipientid' => 'Message Recipientid',
            'messageid' => 'Messageid',
            'userid' => 'Userid',
            'message_typeid' => 'Message Typeid',
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

        $criteria->compare('message_recipientid', $this->message_recipientid, true);
        $criteria->compare('messageid', $this->messageid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('message_typeid', $this->message_typeid);
        $criteria->compare('statusid', $this->statusid);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}