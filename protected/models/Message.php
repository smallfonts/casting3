<?php

/**
 * This is the model class for table "message".
 *
 * The followings are the available columns in table 'message':
 * @property string $messageid
 * @property string $userid
 * @property string $title
 * @property string $body
 * @property string $created
 *
 * The followings are the available model relations:
 * @property UserAccount $user
 */
class Message extends CActiveRecord {

    public function toArray() {
        return array(
            'sender' => array(
                'userid' => $this->userid,
                'email' => $this->user->email,
                'photoUrl' => $this->user->getPortfolio()->photo->url,
                'name' => $this->user->getPortfolio()->getName(),
            ),
            'messageid'=>$this->messageid,
            'title' => $this->title,
            'body' => $this->body,
            'sent' => $this->created
        );
    }

    public function beforeSave() {
        if (is_null($this->created))
            $this->created = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Message the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, title, body', 'required'),
            array('userid', 'length', 'max' => 100),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('messageid, userid, title, body, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
            'reply_message' => array(self::BELONGS_TO, 'Message', 'reply_messageid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'messageid' => 'Messageid',
            'userid' => 'Userid',
            'title' => 'Title',
            'body' => 'Body',
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

        $criteria->compare('messageid', $this->messageid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('created', $this->created, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}