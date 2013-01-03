<?php

/**
 * This is the model class for table "message_sent".
 *
 * The followings are the available columns in table 'message_sent':
 * @property string $message_sentid
 * @property string $messageid
 * @property string $userid
 */
class MessageSent extends CActiveRecord {

    public function beforeSave() {
        $this->last_modified = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MessageSent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'message_sent';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('message_sentid, messageid, userid', 'required'),
            array('message_sentid, messageid, userid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('message_sentid, messageid, userid', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'message_sentid' => 'Message Sentid',
            'messageid' => 'Messageid',
            'userid' => 'Userid',
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

        $criteria->compare('message_sentid', $this->message_sentid, true);
        $criteria->compare('messageid', $this->messageid, true);
        $criteria->compare('userid', $this->userid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}