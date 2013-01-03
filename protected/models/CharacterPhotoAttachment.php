<?php

/**
 * This is the model class for table "character_photo_attachment".
 *
 * The followings are the available columns in table 'character_photo_attachment':
 * @property string $character_photo_attachmentid
 * @property string $characterid
 * @property integer $title
 * @property integer $desc
 *
 * The followings are the available model relations:
 * @property Character $character
 */
class CharacterPhotoAttachment extends CActiveRecord {

    public function toArray() {
        return array(
            'character_photo_attachmentid' => $this->character_photo_attachmentid,
            'title' => $this->title,
            'desc' => $this->desc
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CharacterPhotoAttachment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'character_photo_attachment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('characterid', 'required'),
            array('characterid', 'length', 'max' => 100),
            array('title, desc', 'required', 'on' => 'update, insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('character_photo_attachmentid, characterid, title, desc', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'character_photo_attachmentid' => 'Character Photo Attachmentid',
            'characterid' => 'Characterid',
            'title' => 'Title',
            'desc' => 'Description',
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

        $criteria->compare('character_photo_attachmentid', $this->character_photo_attachmentid, true);
        $criteria->compare('characterid', $this->characterid, true);
        $criteria->compare('title', $this->title);
        $criteria->compare('desc', $this->desc);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}