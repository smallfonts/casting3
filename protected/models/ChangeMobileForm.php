<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangeMobileForm extends CFormModel {

    public $mobile;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('mobile', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'mobile' => 'Mobile No',
        );
    }

    
    public function changeMobile(){
        $cm = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => Yii::app()->user->account->userid
        ));
        
        $cm->mobile = $this->mobile;
        return $cm->save();
    }

}
?>
