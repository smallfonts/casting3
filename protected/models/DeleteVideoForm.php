<?php

class DeleteVideoForm extends CFormModel {

    public $videoid;
  
    public function rules() {
        return array(
        );
    }
    
       /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'videoid' => 'Video ID',
        );
    }


    public function deleteVideo($videoid){
        $this->videoid = $videoid;
           //get video belonging to this user
        $video = Video::model()->findByAttributes(array(
            'videoid' => $videoid,
                ));
        return $video->delete();
    }

}
?>
