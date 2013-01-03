<?php

/**
 * This is the model class for table "casting_call".
 *
 * The followings are the available columns in table 'casting_call':
 * @property string $casting_callid
 * @property string $production_portfolioid
 * @property string $casting_manager_portfolioid
 * @property string $title
 * @property string $desc
 * @property string $audition_start
 * @property string $audition_end
 * @property string $project_start
 * @property string $project_end
 * @property string $location
 * @property string $photoid
 *
 * The followings are the available model relations:
 * @property ProductionPortfolio $productionPortfolio
 * @property CastingManagerPortfolio $castingManagerPortfolio
 * @property Character[] $characters
 */
class CastingCall extends CActiveRecord {

    public function toArray() {
        return array(
            'casting_callid' => $this->casting_callid,
            'title' => $this->title,
            'desc' => $this->desc,
            'application_start' => $this->application_start,
            'application_end' => $this->application_end,
            'audition_start' => $this->audition_start,
            'audition_end' => $this->audition_end,
            'project_start' => $this->project_start,
            'project_end' => $this->project_end,
            'location' => $this->location,
            'photoUrl' => $this->photo->url,
            'created' => $this->created,
            'status' => $this->status->toArray(),
            'url' => $this->url,
            'productionPortfolio'=> $this->getProductionPortfolio(),
            'castingManagerPortfolio' => $this->getCastingManagerPortfolio(),
        );
    }
    
    public function getCastingManagerPortfolio(){
        $arr = array();
        $arr['name'] = $this->castingManagerPortfolio->first_name;
        $arr['photoUrl'] = $this->castingManagerPortfolio->photo->url;
        $arr['userid'] = $this->castingManagerPortfolio->user->userid;
        return $arr;
    }
    
    public function getProductionPortfolio(){
        $arr = array();
        $arr['name'] = $this->productionPortfolio->name;
        $arr['url'] = $this->productionPortfolio->url;
        return $arr;
    }
    
    public function getCharacters(){
        $arr = array();
        foreach($this->characters as $character){
            $arr[] = $character->toArray();
        }
        
        return $arr;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CastingCall the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'casting_call';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('production_portfolioid, casting_manager_portfolioid, statusid', 'required'),
            array('title, desc, application_start, application_end, audition_start, audition_end, project_start, project_end, location', 'required', 'on' => 'insert, update'),
            array('production_portfolioid, project_start, photoid', 'length', 'max' => 100),
            array('title, location, url', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('casting_callid, production_portfolioid, title, desc, casting_manager_portfolioid, audition_start, audition_end, project_start, project_end, location', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if($this->application_start == "") $this->application_start = null;
        if($this->application_end == "") $this->application_end = null;
        if($this->audition_start == "") $this->audition_start = null;
        if($this->audition_end == "") $this->audition_end = null;
        if($this->project_start == "") $this->project_start = null;
        if($this->project_end == "") $this->project_end = null;
        if (is_null($this->created)) $this->created = date('Y-m-d H:i:s');
        $this->last_modified = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * @return array relational rules
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'productionPortfolio' => array(self::BELONGS_TO, 'ProductionPortfolio', 'production_portfolioid'),
            'characters' => array(self::HAS_MANY, 'Character', 'casting_callid'),
            'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
            'castingManagerPortfolio' => array(self::BELONGS_TO, 'CastingManagerPortfolio', 'casting_manager_portfolioid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'casting_callid' => 'Casting Callid',
            'production_portfolioid' => 'Production Portfolioid',
            'title' => 'Title',
            'desc' => 'Desc',
            'audition_start' => 'Audition Start',
            'audition_end' => 'Audition End',
            'project_start' => 'Project Start',
            'project_end' => 'Project End',
            'location' => 'Location',
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
        $criteria->compare('casting_callid', $this->casting_callid, true);
        $criteria->compare('production_portfolioid', $this->production_portfolioid, true);
        $criteria->compare('casting_manaer_portfolioid', $this->casting_manager_portfolioid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('audition_start', $this->audition_start, true);
        $criteria->compare('audition_end', $this->audition_end, true);
        $criteria->compare('project_start', $this->project_start, true);
        $criteria->compare('project_end', $this->project_end, true);
        $criteria->compare('location', $this->location, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}