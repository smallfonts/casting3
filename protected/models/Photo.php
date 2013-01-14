<?php

Yii::import('application.extensions.SimpleImage.*');
require_once 'SimpleImage.php';

/**
 * This is the model class for table "photo".
 *
 * The followings are the available columns in table 'photo':
 * @property string $photoid
 * @property string $userid
 * @property string $url
 * @property string $statusid
 * The followings are the available model relations:
 * @property UserAccount $user
 * 
 */
class Photo extends CActiveRecord {

    public $image;
    public $ext;
    public $x1 = null;
    public $x2 = null;
    public $y1 = null;
    public $y2 = null;
    public $width;
    public $height;
    public $isIEUpload = false;
    public $isIECropping = false;
    public $ieFile;
    public $cropPhoto = true;
    //IE parameters
    //Resize Parameters for Uploaded Photo
    public $maxWidth = 500;
    public $maxHeight = 500;

    public function toArray() {
        return array(
            'url' => $this->url,
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Photo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('image', 'checkFile'),
            array('url, ext, x1, x2, y1, y2, width, height', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('photoid, userid, url', 'safe', 'on' => 'search'),
        );
    }

    //checks whether file has been submitted via uploadify with $_FILES (For IE browsers) or $_POST
    //
    public function checkFile() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('values x1:' . $this->x1 . ' x2:' . $this->x2 . ' y1:' . $this->y1 . ' y2: ' . $this->y2 . 'width: ' . $this->width . 'height : ' . $this->height . ' ext : ' . $this->ext . ' url: ' . $this->url);

        if (!empty($_FILES['c3_photo_upload'])) {
            $log->logInfo('ieUploadFile triggered');
            //image is submitted by IE for upload
            $this->isIEUpload = true;
            $this->ieFile = $_FILES['c3_photo_upload'];
        } elseif ($this->url != null && $this->x1 != null) {
            $log->logInfo('ieCropping triggered');
            //image is submitted by IE for cropping
            $this->isIECropping = true;
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
            'ArtistePortfolioPhoto' => array(self::HAS_MANY, 'ArtistePortfolioPhoto', 'photoid'),
        );
    }

    /*
     * Function to set photo to deleted status
     * 
     * 
     */

    public function setDelete() {
        $this->statusid = 4;
        $this->save();
    }

    /*
     * Function to set general photos 
     * 1. Photo is resized to max-width/max-height = 500px (for IE and safari browsers) (Chrome and Mozilla resizing is achieved in client side)
     * 2. Generate Thumbnails
     * 3. Uploaded to S3 bucket
     * 
     * does not have cropping features (setPhoto with cropping is achieved via savePhoto() function
     * 
     */

    public function setPhoto() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $basePath = realpath(Yii::app()->basePath . '/../images/photos');

        if ($this->isIEUpload) {
            $fileParts = pathinfo($this->ieFile['name']);
            $fileName = $this->generateFileName($fileParts['extension']);
            $path = $basePath . '/' . $fileName;
            $result = $this->saveIEUploadPhoto($path, $fileName);
            if ($result) {
                $this->url = $fileName;
                $this->saveAndUploadPhoto(null, $path, $fileName);
                $this->save();
            }
        } else {

            // 1.saves photo to filesystem (Photo has been resized)
            //
            //
                
            $this->image = CUploadedFile::getInstance($this, 'image');
            $fileName = $this->generateFileName('png');
            $path = $basePath . "/" . $fileName;

            $this->url = $fileName;
            $this->image->saveAs($path);

            $this->saveAndUploadPhoto(null, $path, $fileName);
            $this->save();
        }
    }

    /*
     * Function to set profile picture, this includes image resizing features for IE, Safari browsers
     * (Picture resizing on Chrome and Firefox is done on client side)
     * For IE9 and Safari browsers, the photo is uploaded for resizing and returned for cropping
     * 
     * 
     */

    public function savePhoto() {

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        $basePath = realpath(Yii::app()->basePath . '/../images/photos');
        if ($this->isIEUpload) {

            $fileParts = pathinfo($this->ieFile['name']);

            $fileName = $this->generateFileName($fileParts['extension']);
            if ($this->cropPhoto) {
                $fileName = 'tmp' . $fileName;
            }

            $path = $basePath . '/' . $fileName;

            $result = $this->saveIEUploadPhoto($path, $fileName);

            if ($result) {

                if ($this->cropPhoto) {

                    //moving file to S3 Bucket
                    //
                    //
                    FileUploadUtil::uploadPhoto($path, $fileName);
                } else {
                    $this->saveAndUploadPhoto(null, $path, $fileName);
                }
                $this->save();
                $log->logInfo('File has been uploaded to S3 Bucket');
            }
        } elseif ($this->isIECropping) {

            $log->logInfo('Performing Crop for IE');
            $fileName = $this->url;

            $image = $this->cropImage($basePath . '/' . $fileName);


            //2. create new filename for image
            $fileName = $this->generateFileName('png');
            $path = $basePath . "/" . $fileName;
            $this->url = $fileName;

            $this->saveAndUploadPhoto($image, $path, $fileName);
            $this->save();
        } else {

            // 1.saves photo to filesystem
            //
            //
                
            $this->image = CUploadedFile::getInstance($this, 'image');
            
            $log->logInfo($this->image);
            
            $fileName = $this->generateFileName('png');
            $path = $basePath . "/" . $fileName;

            $this->url = $fileName;
            $this->image->saveAs($path);
            $image = null;

            //2.crops photo if coordinates are given
            if ($this->x1 != null) {
                $image = $this->cropImage($path);
            }

            $this->saveAndUploadPhoto($image, $path, $fileName);
            $this->save();
        }
    }

    public function saveIEUploadPhoto($path, $fileName) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('Uploading File for IE');
        $tempFile = $this->ieFile['tmp_name'];
        $log->logInfo($tempFile);

        // Validate the file type
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
        $fileParts = pathinfo($this->ieFile['name']);

        $log->logInfo('Photo paths is set to: ' . $path);
        if (in_array($fileParts['extension'], $fileTypes)) {
            //set photo url
            //  
            $this->url = $fileName;


            //save file into filesystem
            //    
            $log->logInfo('Moving Photo from: ' . $tempFile . ' to ' . $path);
            move_uploaded_file($tempFile, $path);
            $log->logInfo('Photo has been saved in ' . $path);

            //Resize Image according to defined max width and max height
            //   
            $image = new SimpleImage();
            $image->load($path);

            if ($image->getWidth() > $this->maxWidth) {
                $image->resizeToWidth($this->maxWidth);
            }

            if ($image->getHeight() > $this->maxHeight) {
                $image->resizeToHeight($this->maxHeight);
            }

            $image->save($path);
            $log->logInfo('Image Resizing Complete');
            return true;
        } else {
            $log->logInfo('File type (' . $fileParts['extension'] . ') is invalid');
            return false;
        }
    }

    /* 1. Saves image in temp local directory 
     * 2. Create Thumbnails for photo
     * 3. Uploads photo to S3 photoBucket
     * 4. Temp image is deleted subsequently
     *
     * $image -> $image object retrieved from php createImagePng 
     *          (IF IMAGE IS SET TO NULL, THEN IMAGE WILL BE ASSUMED TO BE SAVED AND EXISTS IN $PATH)
     * $path -> Picture to which the photo is located (includes filename)
     * $fileName -> Just the fileName of the photo
     */

    public function saveAndUploadPhoto($image, $path, $fileName) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //saving photo in temp folder
        //
        if (!is_null($image)) {
            imagepng($image, $path, 9);
        }

        //create Thumbnails
        $lrgThumbnailPath = $this->createThumbnails(150, 'l', $fileName);
        $medThumbnailPath = $this->createThumbnails(100, 'm', $fileName);
        $smlThumbnailPath = $this->createThumbnails(50, 's', $fileName);

        //upload photo to S3
        FileUploadUtil::uploadPhoto($path, $fileName);
        FileUploadUtil::uploadPhoto($lrgThumbnailPath, 'l' . $fileName);
        FileUploadUtil::uploadPhoto($medThumbnailPath, 'm' . $fileName);
        FileUploadUtil::uploadPhoto($smlThumbnailPath, 's' . $fileName);
        $log->logInfo('Photos Successfully updated');


        //delete photo in temp folder
        Yii::app()->file->set($path)->delete();
        Yii::app()->file->set($lrgThumbnailPath)->delete();
        Yii::app()->file->set($medThumbnailPath)->delete();
        Yii::app()->file->set($smlThumbnailPath)->delete();
        $log->logInfo('Temp Photos have been deleted');
    }

    /*
     * Generates Thumbnails for photo
     * $pxHeight -> the height to which the thumbnail should be set to.
     * $prefix -> a prefix to $fileName which the thumbnail is saved as.
     * $fileName -> fileName that is the photo to be created a thumbnail for.
     * 
     * returns Thumbnail file path
     */

    public function createThumbnails($shortestPx, $prefix, $fileName) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $basePath = realpath(Yii::app()->basePath . '/../images/photos');
        $path = $basePath . '/' . $fileName;
        $newPath = $basePath . '/' . $prefix . $fileName;
        $image = new SimpleImage();
        $image->load($path);

        if ($image->getWidth() > $image->getHeight()) {
            $image->resizeToHeight($shortestPx);
        } else {
            $image->resizeToWidth($shortestPx);
        }

        $image->save($newPath);

        $log->logInfo('Thumbnail Created at : ' . $newPath);
        return $newPath;
    }

    /*
     * Generates a unique FileName for a photo
     * 
     */

    public function generateFileName($extension) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $fileName = time() . CryptoUtil::generateToken(rand(10, 20)) . "." . $extension;
        $log->logInfo('Generated Filename: ' . $fileName);
        return $fileName;
    }

    /*
     * Crops an image to a specific width and height
     * $path -> Path to the file that is to be cropped
     * returns cropped image path
     */

    public function cropImage($path) {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $targ_w = $this->width;
        $targ_h = $this->height;

        $cropImage = null;

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        $log->logInfo('File Type Is:' . $ext);
        switch ($ext) {
            case 'png':
                $log->logInfo('image is PNG');
                $cropImage = imagecreatefrompng($path);
                break;
            case 'jpg': case 'jpeg':
                $log->logInfo('image is JPEG');
                $cropImage = imagecreatefromjpeg($path);
                break;
            case 'gif':
                $log->logInfo('image is GIF');
                $cropImage = imagecreatefromgif($path);

                break;
            case 'bmp':
                $log->logInfo('image is BMP');
                $cropImage = imagecreatefromwbmp($path);

                break;
        }

        $dst_r = ImageCreateTrueColor($this->width, $this->height);

        switch ($ext) {
            case "png":
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($dst_r, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($dst_r, $background);

                // turning off alpha blending (to ensure alpha channel information 
                // is preserved, rather than removed (blending with the rest of the 
                // image in the form of black))
                imagealphablending($dst_r, false);

                // turning on alpha channel information saving (to ensure the full range 
                // of transparency is preserved)
                imagesavealpha($dst_r, true);

                break;
            case "gif":
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($dst_r, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($dst_r, $background);

                break;
        }

        imagecopyresampled($dst_r, $cropImage, 0, 0, $this->x1, $this->y1, $targ_w, $targ_h, $this->width, $this->height);
        $log->logInfo('Image Cropped Successfully and saved to :' . $dst_r);
        return $dst_r;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'image' => 'Image',
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

        $criteria->compare('photoid', $this->photoid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('url', $this->url, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}