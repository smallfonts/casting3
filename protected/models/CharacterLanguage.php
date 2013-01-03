<?php

/**
 * This is the model class for table "character_language".
 *
 * The followings are the available columns in table 'character_language':
 * @property string $characterid
 * @property string $languageid
 *
 * The followings are the available model relations:
 * @property Language $language
 * @property Character $character
 */
class CharacterLanguage extends CActiveRecord {

    public function toArray() {
        return array(
            'characterid' => $this->characterid,
            'languageid' => $this->languageid,
            'language_proficiencyid' => $this->language_proficiencyid,
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CharacterLanguage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'character_language';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('characterid, languageid, language_proficiencyid', 'required'),
            array('characterid, languageid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('characterid, languageid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'languageid'),
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'characterid' => 'Characterid',
            'languageid' => 'Languageid',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('characterid', $this->characterid, true);
        $criteria->compare('languageid', $this->languageid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}