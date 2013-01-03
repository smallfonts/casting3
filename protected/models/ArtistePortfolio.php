<?php

/**
 * This is the model class for table "artiste_portfolio".
 *
 * The followings are the available columns in table 'artiste_portfolio':
 * @property string $artiste_portfolioid
 * @property string $userid
 * @property string $name
 * @property string $ethnicityid
 * @property string $gender
 * @property string $nationality
 * @property string $height
 * @property string $weight
 * @property string $email
 * @property string $mobile_phone
 * @property string $dob
 * @property string $status
 * @property string $photoid
 * @property string $videoid
 * @property string $url
 * @property string $chest
 * @property string $waist
 * @property string $hip
 * @property string $shoe
 * @property string $profession
 * @property string $desc
 * @property string $portfolio_guide
 * @property string $experience

 * 
 * The followings are the available model relations:
 * @property Photo $photo
 * @property UserAccount $user
 * @property Language[] $languages
 * @property Video[] $videos

 */
class ArtistePortfolio extends CActiveRecord {

    public $characterid;
    public $age_start;
    public $age_end;
    public $searchAge;
    public $searchGender;
    public $searchLanguages;
    public $searchProfessions;
    public $searchSkills;
    public $searchEthnicity;
    public $sortAttr;
    public $limit;
    public $offset;

    /*
     * Returns ArtistePortfolio data as an array
     * 
     * Note
     * attribute "dob" is converted to age
     * attribute "language" stores an array of language names
     * attribute "featuredPhotos" stores an array of featured photo urls
     */

    public function toArray() {
        $arr = array(
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'name' => $this->name,
            'age' => DateUtil::getAge($this->dob),
            'nationality' => $this->nationality,
            'height' => $this->height,
            'weight' => $this->weight,
            'chest' => $this->chest,
            'waist' => $this->waist,
            'hip' => $this->hip,
            'shoe' => $this->shoe,
            'gender' => $this->gender,
            'url' => $this->url,
            'photoUrl' => $this->photo->url,
            'video' => $this->getVideo(),
            'featuredPhotos' => $this->getFeaturedPhotos(),
            'languages' => $this->getLanguages(),
            'skills' => $this->getSkills(),
            'professions' => $this->getProfessions(),
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
            'completeness' => $this->getCompleteness(),
            'years_of_experience' => $this->years_of_experience,
            'experience' => $this->experience,
            'portfolio_guide' => $this->portfolio_guide
        );

        if ($this->ethnicity) {
            $arr['ethnicity'] = $this->ethnicity->toArray();
        }

        return $arr;
    }

    public function getVideo() {
        $video = $this->video;
        if (!is_null($video)) {
            return $video->toArray();
        } else {
            return null;
        }
    }

    public function getCompleteness() {
        //number of attributes to fill
        //url is removed from completeness
        $max = 18;
        $score = 0;
        if ($this->name != '')
            $score++;
        if ($this->ethnicityid != '')
            $score++;
        if ($this->dob != '')
            $score++;
        if ($this->nationality != '')
            $score++;
        if ($this->height != '')
            $score++;
        if ($this->weight != '')
            $score++;
        if ($this->chest != '')
            $score++;
        if ($this->waist != '')
            $score++;
        if ($this->hip != '')
            $score++;
        if ($this->shoe != '')
            $score++;
        if ($this->gender != '')
            $score++;
        if ($this->email != '')
            $score++;
        if ($this->mobile_phone != '')
            $score++;
        if ($this->photo->photoid != 1)
            $score++;
        if ($this->video->videoid != 1)
            $score++;
        if (count($this->skills) > 0)
            $score++;
        if (count($this->languages) > 0)
            $score++;

        foreach ($this->featuredPhotos as $featuredPhoto) {
            if ($featuredPhoto->photoid != 2 && $featuredPhoto->photoid != 3) {
                $score++;
                break;
            }
        }

        return intval(($score / $max) * 100);
    }

    /*
     * Returns an array of names of all skills of user
     * 
     */

    public function getSkills() {
        $skills = array();
        foreach ($this->skills as $skill) {
            $skills[] = array(
                'skillid' => $skill->skillid,
                'name' => $skill->name,
            );
        }
        return $skills;
    }

    public function hasSkillid($id) {
        foreach ($this->skills as $skill) {
            if ($skill->skillid == $id)
                return true;
        }
        return false;
    }

    public function getProfessions() {
        $professions = array();
        foreach ($this->professions as $profession) {
            $professions[] = array(
                'professionid' => $profession->professionid,
                'name' => $profession->name
            );
        }
        return $professions;
    }

    /*
     * Returns an array of names of all languages spoken by user 
     */

    public function getLanguages() {
        $languages = array();
        foreach ($this->spokenLanguages as $spokenLanguage) {
            $languages[] = $spokenLanguage->toArray();
        }
        return $languages;
    }

    /*
     * Returns an array of urls of all featured photos of user
     * 
     */

    public function getFeaturedPhotos() {
        $urls = array();

        //initialize null featured photos

        $anonymousPhoto = Photo::model()->findByPK(1);


        for ($i = 0; $i < 6; $i++) {
            $urls[] = $anonymousPhoto->toArray();
        }

        foreach ($this->featuredPhotos as $featuredPhoto) {
            $urls[$featuredPhoto->order] = $featuredPhoto->toArray();
        }

        return $urls;
    }

    public function getSearchResult() {
        return array(
            'artiste_portfolioid' => $this->artiste_portfolioid,
            'name' => $this->name,
            'photoUrl' => $this->photo->url,
            'userid' => $this->user->userid,
            'url' => $this->url
        );
    }
    
    public function getName(){
        return $this->name;
    }

    public function beforeSave() {
        if ($this->ethnicityid == "")
            $this->ethnicityid = null;
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ArtistePortfolio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'artiste_portfolio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, url', 'required'),
            array('mobile_phone','required','on'=>'edit_portfolio'),
            array('userid', 'exist', 'className' => 'UserAccount'),
            array('url', 'unique'),
            array('dob', 'safe'),
            array('years_of_experience', 'numerical'),
            array('url', 'match', 'pattern' => '/^[a-zA-Z0-9]*$/', 'message' => 'Url must contain only <strong>Alpha Numeric</strong> characters'),
            array('userid, photoid, characterid', 'length', 'max' => 100),
            array('name, gender, nationality, height, weight, email,mobile_phone, status, chest, waist, hip, shoe', 'length', 'max' => 255),
            array('desc', 'length', 'max' => 500),
            array('name,ethnicityid, gender, nationality, height, weight, email, mobile_phone, status, chest, waist, hip, shoe', 'length', 'max' => 255),
            array('dob,searchLanguages, searchAge, searchProfessions, searchSkills, ethnicity', 'safe'),
            array('experience,videoid', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('limit,offset,searchGender,searchEthnicity,artiste_portfolioid, userid, name, ethnicityid, gender, nationality, height, weight, email, mobile_phone, dob, status, photoid, sortAttr', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
            'spokenLanguages' => array(self::HAS_MANY, 'SpokenLanguage', 'artiste_portfolioid'),
            'featuredPhotos' => array(self::HAS_MANY, 'ArtistePortfolioPhoto', 'artiste_portfolioid'),
            'video' => array(self::BELONGS_TO, 'Video', 'videoid'),
            'skills' => array(self::MANY_MANY, 'Skill', 'artiste_portfolio_skills(artiste_portfolioid, skillid)'),
            'ethnicity' => array(self::BELONGS_TO, 'Ethnicity', 'ethnicityid'),
            'professions' => array(self::MANY_MANY, 'Profession', 'artiste_portfolio_profession(artiste_portfolioid, professionid)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'artiste_portfolioid' => 'Artiste Portfolioid',
            'userid' => 'Userid',
            'name' => 'Name',
            'gender' => 'Gender',
            'nationality' => 'Nationality',
            'height' => 'Height',
            'weight' => 'Weight',
            'email' => 'Email',
            'home_phone' => 'Home Phone',
            'mobile_phone' => 'Mobile Phone',
            'dob' => 'Dob',
            'status' => 'Status',
            'address' => 'Address',
            'income' => 'Income',
            'photoid' => 'Photoid',
            'url' => 'Url',
            'chest' => 'Chest',
            'waist' => 'Waist',
            'hip' => 'Hip',
            'shoe' => 'Shoe',
            'experience' => 'Experience',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $criteria = new CDbCriteria();

        /*
         * Search for users based on age filter value
         */
        if (isset($this->searchAge)) {
            //$ages = CJSON::decode($this->searchAge);
            $strAge = $this->searchAge['min'];
            if ($strAge) {
                $criteria->addCondition("ABS(DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y')) >= " . $strAge);
            }
            //$ages = CJSON::decode($this->searchAge);
            $endAge = $this->searchAge['max'];
            if ($endAge) {
                $criteria->addCondition("ABS(DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y')) <= " . $endAge);
            }
        }

        /*
         * Search for users based on name filter values
         */
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('name', $this->name, true);

        /*
         * Search for artistes who can speak the languages in the filter
         */
        if (isset($this->searchLanguages) && count($this->searchLanguages) > 0) {

            $langCount = count($this->searchLanguages);

            $langQry = "artiste_portfolioid IN ( 
                SELECT artiste_portfolioid FROM
                (SELECT artiste_portfolioid, count(artiste_portfolioid) AS num
                FROM spoken_language WHERE";
            foreach ($this->searchLanguages as $curLang) {
                $langQry .= "(languageid =" . $curLang['languageid'];
                $langQry .= " AND language_proficiencyid >=" . $curLang['language_proficiencyid'];
                $langQry .= ") OR";
            }
            $langQry .= " 1!=1  
                    GROUP BY artiste_portfolioid) AS ap WHERE 
                    ap.num =" . $langCount . ")";
            $criteria->addCondition($langQry);
        }

        //Search for artistes of the certain gender required from the filters

        if (isset($this->searchGender)) {
            if (count($this->searchGender) == 1) {
                $gendString = "'" . (string) $this->searchGender[0] . "'";
            }

            if (count($this->searchGender) == 2) {
                $gendString = "'" . (string) $this->searchGender[0] . "','" . (string) $this->searchGender[1] . "'";
            }

            $log->logInfo($gendString);
            $genderQry = "gender IN (" . $gendString . ")";
            $criteria->addCondition($genderQry);
        }

        //Search for artistes with a profession specified by the search filters
        if (isset($this->searchProfessions) && $this->searchProfessions != '') {
            $log->logInfo($this->searchProfessions);
            $professionQry = "artiste_portfolioid IN(
                SELECT artiste_portfolioid FROM artiste_portfolio_profession 
                WHERE professionid =" . $this->searchProfessions . ")";
            $criteria->addCondition($professionQry);
        }

        //Search for artistes with skills specified by the search filters
        if (isset($this->searchSkills) && count($this->searchSkills) > 0) {
            $i = 0;
            $skillQry = "artiste_portfolioid IN(
                SELECT artiste_portfolioid FROM artiste_portfolio_skills
                WHERE skillid IN(";
            foreach ($this->searchSkills as $skillObj) {
                $i++;
                if (count($this->searchSkills) != $i) {
                    $skillQry .= $skillObj['skillid'] . ",";
                } else {
                    $skillQry .=$skillObj['skillid'];
                }
            }
            $skillQry .=")GROUP BY artiste_portfolioid 
                HAVING COUNT(*) =" . count($this->searchSkills);
            $skillQry .= ")";
            $criteria->addCondition($skillQry);
        }

        //Search for artistes with ethnicity specified by the search filters
        if (isset($this->searchEthnicity) && $this->searchEthnicity != '') {
            $ethnicityQry = "artiste_portfolioid IN(
                SELECT distinct(b.artiste_portfolioid) FROM `ethnicity` AS a, `artiste_portfolio` AS b 
                WHERE a.name like :searchEthnicity AND a.ethnicityid = b.ethnicityid )";
            $criteria->addCondition($ethnicityQry);
            $criteria->params[':searchEthnicity'] = '%' . $this->searchEthnicity . '%';
        }

        if (isset($this->sortAttr) && $this->sortAttr != '') {
           $individualAttr = $this->sortAttr[0];
            $condition = $this->sortAttr[1];
            $sortQuery = $individualAttr." ".$condition;
            $criteria->order = $sortQuery;
        }

        $criteria->limit = $this->limit;
        $criteria->offset = $this->offset;
        $criteria->compare('ethnicityid', $this->ethnicityid, true);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('height', $this->height, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('mobile_phone', $this->mobile_phone, true);
        $criteria->compare('dob', $this->dob, true);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => false
                ));
    }

}