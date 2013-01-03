<?php

/**
 * This is the model class for table "favourite_artiste_portfolio".
 *
 * The followings are the available columns in table 'favourite_artiste_portfolio':
 * @property string $userid
 * @property string $artiste_portfolioid
 *
 * The followings are the available model relations:
 * @property Character $artistePortfolio
 * @property UserAccount $user
 */
class FavouriteArtistePortfolio extends CActiveRecord {

    public $action;
    
    public static function getFavourites($userid){
        
        //use sql to get favourite Artiste_portfolios
        $sql = "SELECT artiste_portfolioid, name, b.url, a.url FROM artiste_portfolio AS a, photo AS b WHERE a.artiste_portfolioid in (SELECT artiste_portfolioid FROM favourite_artiste_portfolio WHERE userid=:userid) AND a.photoid = b.photoid";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid, PDO::PARAM_INT);
        $dataReader = $command->query();
        // bind the 1st column (artiste_portfolioid) with the $artiste_portfolioid variable
        $dataReader->bindColumn(1, $username);
        // bind the 2nd column (name) with the $name variable
        $dataReader->bindColumn(2, $email);
        // bind the 3rd column (url) with the $photoUrl variable
        $dataReader->bindColumn(3, $photoUrl);
        // bind the 2nd column (name) with the $url variable
        $dataReader->bindColumn(4, $url);

        $results = array();
        while ($dataReader->read() !== false) {
            $results[] = ArtistePortfolio::getSearchResult($username, $email, $photoUrl, $url);
        }
        
        return $results;
    }
    
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FavouriteSearchedArtistePortfolio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'favourite_artiste_portfolio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, artiste_portfolioid, action', 'required'),
            array('userid','exist','className'=>'UserAccount'),
            array('artiste_portfolioid','exist','className'=>'ArtistePortfolio'),
            array('userid, artiste_portfolioid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('userid, artiste_portfolioid', 'safe', 'on' => 'search'),
        );
    }

    public function set() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        if (!$this->validate()) {
            $log->logError("Data Validation Failed");
            return false;
        }

        $exist = FavouriteArtistePortfolio::model()->findByPK(array('userid'=>$this->userid, 'artiste_portfolioid'=>$this->artiste_portfolioid));
        switch ($this->action) {
            case 'add':
                if (is_null($exist)) {
                    return $this->save();
                }
                
                $log->logError("Cannot Add Favourite: Record Already Exists");
                break;
                
            case 'delete':
                if (!is_null($exist)) {
                    return $exist->delete();
                }
                
                $log->logError("Cannot Remove Favourite: Record Does Not Exist");
                break;
                
            default:
                $log->logError("No Action found for:".$this->action);
        }
        
        return false;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'artistePortfolio' => array(self::BELONGS_TO, 'ArtistePortfolio', 'artiste_portfolioid'),
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'userid' => 'Userid',
            'artiste_portfolioid' => 'Artiste Portfolioid',
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

        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('artiste_portfolioid', $this->artiste_portfolioid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}