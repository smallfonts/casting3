<?php

/**
 * This is the model class for table "application_video".
 *
 * The followings are the available columns in table 'application_video':
 * @property string $application_videoid
 * @property string $artiste_portfolioid
 * @property string $videoid
 * @property string $characterid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio $artistePortfolio
 * @property Video $video
 * @property Character $character
 */
class ApplicationVideo extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApplicationVideo the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'application_video';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('artiste_portfolioid, videoid, characterid', 'required'),
            array('artiste_portfolioid, videoid, characterid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('application_videoid, artiste_portfolioid, videoid, characterid', 'safe', 'on' => 'search'),
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
            'video' => array(self::BELONGS_TO, 'Video', 'videoid'),
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
            'characterVideoAttachment' => array(self::BELONGS_TO, 'CharacterVideoAttachment', 'character_video_attachmentid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'application_videoid' => 'Application Videoid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
            'videoid' => 'Videoid',
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

        $criteria->compare('application_videoid', $this->application_videoid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);
        $criteria->compare('videoid', $this->videoid, true);
        $criteria->compare('characterid', $this->characterid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        return array(
            'characterid' => $this->characterid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'application_videoid' => $this->application_videoid,
            'character_video_attachmentid' => $this->character_video_attachmentid,
            'videoid' => $this->videoid,
            'videourl' => $this->video->url,
        );
    }

}