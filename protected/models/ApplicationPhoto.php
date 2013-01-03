<?php

/**
 * This is the model class for table "application_photo".
 *
 * The followings are the available columns in table 'application_photo':
 * @property string $application_photoid
 * @property string $artiste_portfolioid
 * @property string $photoid
 * @property string $characterid
 * @property string $character_photo_attachmentid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio $artistePortfolio
 * @property Photo $photo
 * @property Character $character
 */
class ApplicationPhoto extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApplicationPhoto the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'application_photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('artiste_portfolioid, photoid, characterid', 'required'),
            array('artiste_portfolioid, photoid, characterid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('application_photoid, artiste_portfolioid, photoid, characterid', 'safe', 'on' => 'search'),
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
            'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
            'characterPhotoAttachment' => array(self::BELONGS_TO, 'CharacterPhotoAttachment', 'character_photo_attachmentid'),
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'application_photoid' => 'Application Photoid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
            'photoid' => 'Photoid',
            'characterid' => 'Characterid',
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

        $criteria->compare('application_photoid', $this->application_photoid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);
        $criteria->compare('photoid', $this->photoid, true);
        $criteria->compare('characterid', $this->characterid, true);
        $criteria->compare('character_photo_attachmentid', $this->character_photo_attachmentid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        return array(
            'characterid' => $this->characterid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'application_photoid' => $this->application_photoid,
            'character_photo_attachmentid' => $this->character_photo_attachmentid,
            'photoid' => $this->photoid,
            'photourl' => $this->photo->url,
        );
    }

}