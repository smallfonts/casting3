<?php

/**
 * This is the model class for table "character_application".
 *
 * The followings are the available columns in table 'character_application':
 * @property string $characterid
 * @property string $artiste_portfolioid
 * @property string $application_date
 * @property integer $statusid
 * @property integer $character_applicationid
 * 
 * The followings are the available model relations:
 * @property Photo $photo
 * @property Video $video
 */
class CharacterApplication extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CharacterApplication the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'character_application';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('characterid, artiste_portfolioid, statusid', 'required'),
            array('statusid', 'numerical', 'integerOnly' => true),
            array('characterid, artiste_portfolioid', 'length', 'max' => 100),
            array('comments, rating','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('characterid, artiste_portfolioid, application_date, statusid', 'safe', 'on' => 'search'),
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
            'statusid' => array(self::BELONGS_TO, 'Status', 'statusid'),
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'characterid' => 'Characterid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
            'application_date' => 'Application Date',
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

        $criteria->compare('characterid', $this->characterid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);
        $criteria->compare('application_date', $this->application_date, true);
        $criteria->compare('statusid', $this->statusid);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        $character = Character::model()->findByAttributes(array(
            'characterid' => $this->characterid,
                ));
        
        $castingcall = CastingCall::model()->findByAttributes(array(
            'casting_callid' => $character->casting_callid,
                ));
        
        return array(
            'characterid' => $this->characterid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'application_date' => $this->application_date,
            'statusid' => $this->statusid,
            'photos' => $this->getPhotos(),
            'videos' => $this->getVideos(),
            'casting_call_title' => $castingcall->title,
            'character_name' => $character->name,
            'comments' => $this->comments,
            'rating' => $this->rating,
            'character_applicationid' => $this->character_applicationid
        );
    }

    public function getPhotos() {
        $photos = ApplicationPhoto::model()->findAllByAttributes(array(
            'characterid' => $this->characterid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
                ));

        $arr = array();
        foreach ($photos as $photo) {
            $tmpArr = $photo->toArray();
            $characterPhotoAttachment = $photo->characterPhotoAttachment;
            $tmpArr['title'] = $characterPhotoAttachment->title;
            $tmpArr['desc'] = $characterPhotoAttachment->desc;
            $arr[] = $tmpArr;
        }

        return $arr;
    }

    public function getVideos() {
        $videos = ApplicationVideo::model()->findAllByAttributes(array(
            'characterid' => $this->characterid,
            'artiste_portfolioid' => $this->artiste_portfolioid,
                ));

        $arr = array();
        foreach ($videos as $video) {
            $tmpArr = $video->toArray();
            $cva = $video->characterVideoAttachment;
            $tmpArr['title'] = $cva->title;
            $tmpArr['desc'] = $cva->desc;
            $arr[] = $tmpArr;
        }

        return $arr;
    }

}