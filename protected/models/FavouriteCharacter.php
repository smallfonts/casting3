<?php

/**
 * This is the model class for table "favourite_character".
 *
 * The followings are the available columns in table 'favourite_character':
 * @property string $userid
 * @property string $characterid
 *
 * The followings are the available model relations:
 * @property Character $character
 * @property UserAccount $user
 */
class FavouriteCharacter extends CActiveRecord {

     public $action;
    
     
    public static function getFavourites($userid){
        //use sql to get favourite Characters
        $sql = "SELECT c.characterid, cc.title, cc.casting_callid, c.name, c.desc, ccp.url FROM `casting_call` as cc, `photo` as ccp, `favourite_character` as fc, `character` as c WHERE fc.userid = :userid AND fc.characterid = c.characterid AND c.casting_callid = cc.casting_callid AND cc.photoid = ccp.photoid";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid, PDO::PARAM_INT);
        $dataReader = $command->query();
        // bind the 1st column (c.characterid) with the $characterid variable
        $dataReader->bindColumn(1, $characterid);
        // bind the 2nd column (cc.title) with the $title variable
        $dataReader->bindColumn(2, $title);
        // bind the 3rd column (cc.casting_callid) with the $casting_callid variable
        $dataReader->bindColumn(3, $casting_callid);
        // bind the 4th column (c.name) with the $name variable
        $dataReader->bindColumn(4, $name);
        // bind the 5th column (c.desc) with the $desc variable
        $dataReader->bindColumn(5, $desc);
        // bind the 6th column (ccp.url) with the $photoUrl variable
        $dataReader->bindColumn(6, $photoUrl);

        $results = array();
        while ($dataReader->read() !== false) {
            $results[] = Character::getSearchResult($characterid,$title,$casting_callid,$name,$desc,$photoUrl);
        }
        
        return $results;
    } 
     
     
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FavouriteSearchedCharacter the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'favourite_character';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, characterid, action', 'required'),
            array('userid','exist','className'=>'UserAccount'),
            array('characterid','exist','className'=>'Character'),
            array('userid, characterid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('userid, characterid', 'safe', 'on' => 'search'),
        );
    }

    public function set() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        if (!$this->validate()) {
            $log->logError("Data Validation Failed");
            return false;
        }

        $exist = FavouriteCharacter::model()->findByPK(array('userid'=>$this->userid, 'characterid'=>$this->characterid));
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
            'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'userid' => 'Userid',
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

        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('characterid', $this->characterid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}