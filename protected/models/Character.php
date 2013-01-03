<?php

/**
 * This is the model class for table "character".
 *
 * The followings are the available columns in table 'character':
 * @property string $characterid
 * @property string $casting_callid
 * @property string $name
 * @property string $desc
 * @property string $gender
 * @property string $age_start
 * @property string $age_end
 * @property string $ethnicityid
 * @property string $created
 * @property string $last_modified
 *
 * The followings are the available model relations:
 * @property CastingCall $castingCall
 */
class Character extends CActiveRecord {

    public $searchLanguages;
    public $artiste_portfolioid;
    public $searchProd;
    public $searchCCTitle;
    public $searchDates;
    public $limit;
    public $offset;

    public function getSearchResult() {
        return array(
            'userid' => $this->castingCall->castingManagerPortfolio->user->userid,
            'characterid' => $this->characterid,
            'title' => $this->castingCall->title,
            'casting_callid' => $this->castingCall->casting_callid,
            'url' => $this->castingCall->url,
            'character' => $this->name,
            'desc' => $this->desc,
            'photoUrl' => $this->castingCall->photo->url,
        );
    }

    public function toArray() {
        $arr = array(
            'characterid' => $this->characterid,
            'casting_callid' => $this->casting_callid,
            'ethnicity' => $this->getEthnicity(),
            'status' => $this->status->toArray(),
        );

        $arr['characterLanguages'] = $this->getCharacterLanguages();
        $arr['otherRequirements'] = $this->getOtherRequirements();
        $arr['skills'] = $this->getSkills();
        $arr['photoAttachments'] = $this->getPhotoAttachments();
        $arr['videoAttachments'] = $this->getVideoAttachments();
        if ($this->name)
            $arr['name'] = $this->name;
        if ($this->desc)
            $arr['desc'] = $this->desc;
        if ($this->nationality)
            $arr['nationality'] = $this->nationality;
        if ($this->gender)
            $arr['gender'] = $this->gender;
        if ($this->age_start)
            $arr['age_start'] = $this->age_start;
        if ($this->age_end)
            $arr['age_end'] = $this->age_end;
        return $arr;
    }

    public function getPhotoAttachments() {
        $arr = array();
        foreach ($this->photoAttachments as $photoAttachment) {
            $arr[] = $photoAttachment->toArray();
        }

        return $arr;
    }

    public function getVideoAttachments() {
        $arr = array();
        foreach ($this->videoAttachments as $videoAttachment) {
            $arr[] = $videoAttachment->toArray();
        }
        return $arr;
    }

    public function getSkills() {
        $arr = array();
        foreach ($this->skills as $skill) {
            $arr[] = $skill->toArray();
        }
        return $arr;
    }

    public function getEthnicity() {
        $arr = array();
        if ($this->ethnicity) {
            $arr = $this->ethnicity->toArray();
        }
        return $arr;
    }

    public function getOtherRequirements() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $otherRequirements = $this->otherRequirements;
        $arr = array();
        foreach ($otherRequirements as $otherRequirement) {
            $arr[] = $otherRequirement->toArray();
        }

        return $arr;
    }

    public function getCharacterLanguages() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $characterLanguages = $this->characterLanguages;
        $arr = array();
        foreach ($characterLanguages as $characterLanguage) {
            $arr[] = $characterLanguage->toArray();
        }
        return $arr;
    }

    public function beforeSave() {
        if (is_null($this->created))
            $this->created = date('Y-m-d');
        $this->last_modified = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Character the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'character';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('casting_callid', 'required'),
            array('name,desc', 'required', 'on' => 'insert, update'),
            array('age_start,age_end', 'numerical', 'integerOnly' => true),
            array('casting_callid', 'exist', 'className' => 'CastingCall'),
            array('ethnicityid', 'exist', 'className' => 'Ethnicity'),
            array('casting_callid, age_start, age_end, artiste_portfolioid', 'length', 'max' => 100),
            array('name, gender, searchLanguages', 'length', 'max' => 255),
            array('statusid,nationality', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('characterid, casting_callid, name, desc, gender, age_start, age_end, age, searchCCTitle,searchProd, searchDates', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'photoAttachments' => array(self::HAS_MANY, 'CharacterPhotoAttachment', 'characterid'),
            'videoAttachments' => array(self::HAS_MANY, 'CharacterVideoAttachment', 'characterid'),
            'skills' => array(self::MANY_MANY, 'Skill', 'character_skill(characterid,skillid)'),
            'ethnicity' => array(self::BELONGS_TO, 'Ethnicity', 'ethnicityid'),
            'castingCall' => array(self::BELONGS_TO, 'CastingCall', 'casting_callid'),
            'characterLanguages' => array(self::HAS_MANY, 'CharacterLanguage', 'characterid'),
            'otherRequirements' => array(self::HAS_MANY, 'OtherRequirement', 'characterid'),
            'languages' => array(self::MANY_MANY, 'Language', 'character_language(characterid, languageid)'),
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
            'applications' => array(self::HAS_MANY, 'CharacterApplication', 'characterid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'characterid' => 'Characterid',
            'casting_callid' => 'Casting Callid',
            'name' => 'Name',
            'desc' => 'Description',
            'gender' => 'Gender',
            'age_start' => 'Age Start',
            'age_end' => 'Age End',
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
        // should not be searched
        $criteria = new CDbCriteria;

        //search mode for suggesting characters to artistes
        if (isset($this->artiste_portfolioid)) {

            $criteria->addCondition('statusid = 5');

            /*
             * Match artiste user's spoken language to casting call characters
             * 
             */

            //Search for characters where languageid with proficiencyid requirement is met by artiste's spoken languages
            $criteria->addCondition('characterid IN (SELECT distinct characterid from `character` where
                characterid NOT IN (
                SELECT distinct a.characterid as characterid from (SELECT characterid, languageid, language_proficiencyid FROM character_language WHERE
                characterid NOT IN (
                        SELECT distinct characterid from character_language where
                        languageid NOT IN (
                                SELECT languageid from spoken_language where artiste_portfolioid =:artiste_portfolioid
                        )
                )) as a, spoken_language as b
                WHERE
                 b.artiste_portfolioid=:artiste_portfolioid 
                AND
                 b.languageid = a.languageid
                AND
                 b.language_proficiencyid < a.language_proficiencyid 
                UNION
                 SELECT distinct characterid from character_language where
                    languageid NOT IN (
                            SELECT languageid from spoken_language where artiste_portfolioid=:artiste_portfolioid
                    )))');

            /*
             * Match artiste user's skills to casting call characters
             * 
             */

            $criteria->addCondition('characterid NOT IN (
                    SELECT distinct characterid FROM character_skill WHERE skillid NOT IN(
                    SELECT skillid from artiste_portfolio_skills WHERE artiste_portfolioid=:artiste_portfolioid))'
            );

            /*
             * 
             * Match artiste user ethnicityid, gender and nationality
             * 
             */
            $criteria->addCondition('characterid IN (
                SELECT a.characterid from `character` as a, `artiste_portfolio` as b WHERE
                 b.artiste_portfolioid=:artiste_portfolioid
                  AND ( a.ethnicityid IS NULL OR a.ethnicityid = b.ethnicityid) 
                  AND ( a.gender IS NULL OR a.gender = b.gender) 
                  AND ( a.nationality IS NULL or a.nationality = b.nationality)
            )');
            
            $criteria->params[':artiste_portfolioid'] = $this->artiste_portfolioid;
        } else if (isset($this->searchLanguages)) {

            /*
             * General search for casting call characters that match language requirements
             */

            $criteria->addCondition('characterid NOT IN (SELECT distinct characterid FROM character_language where languageid NOT IN (' . implode(',', $this->searchLanguages) . '))');
        }


        //search filter for character given Casting call title
        if (isset($this->searchCCTitle) && $this->searchCCTitle != '') {
            $titleQry = "
        characterid IN
        (Select characterid from `character` where casting_callid IN 
        (SELECT casting_callid 
        FROM  casting_call 
        WHERE title like '%" . $this->searchCCTitle . "%'))";
            $criteria->addCondition($titleQry);
        }


        //Search for Characters with production house specified by search filters
        if (isset($this->searchProd) && $this->searchProd != '') {

            $prodQry = "
                characterid IN
                (select characterid from  
                `character` 
                where casting_callid IN 
                (SELECT casting_callid
                FROM casting_call
                WHERE production_portfolioid = ( 
                SELECT production_portfolioid
                FROM production_portfolio
                WHERE name like '%" . $this->searchProd . "%')))";

            $criteria->addCondition($prodQry);
        }

        if (isset($this->searchDates)) {

            $startDuration = $this->searchDates['start'];
            $endDuration = $this->searchDates['end'];
            $log->logInfo($startDuration);
            $log->logInfo($endDuration);

            $durationQry = "
             characterid IN
             (SELECT characterid from 
             `character` WHERE casting_callid IN
              (SELECT casting_callid FROM casting_call WHERE
              project_start >= '" . $startDuration . "' AND
              project_end <= '" . $endDuration . "'))";

            $criteria->addCondition($durationQry);
        }

        $criteria->compare('characterid', $this->characterid, true);
        $criteria->compare('casting_callid', $this->casting_callid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('gender', $this->gender, true);

        if (isset($this->age_start) && isset($this->age_end)) {
            $ageQry = '(';
            $ageQry .= '(age_start <= ' . $this->age_start . ' AND ' . 'age_end >= ' . $this->age_end . ') OR';
            $ageQry .= '(age_start <= ' . $this->age_start . ' AND ' . 'age_start >= ' . $this->age_end . ') OR';
            $ageQry .= '(age_end >= ' . $this->age_start . ' AND ' . 'age_end <= ' . $this->age_end . ') OR';
            $ageQry .= '(age_end IS NULL AND age_start IS NULL)';
            $ageQry .= ')';
            $criteria->addCondition($ageQry);
        }
        
        if(isset($this->limit)){
            $criteria->limit = $this->limit;
        }
        
        if(isset($this->offset)){
            $criteria->offset = $this->offset;
        }
        
//        $criteria->join = 'LEFT JOIN doc_access a ON t.id = a.doc_id and a.user_id = 7';
//        $criteria->order = $sortQuery;
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => false
                ));
    }

}
