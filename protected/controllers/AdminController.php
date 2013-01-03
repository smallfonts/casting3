<?php

class AdminController extends Controller {

    public $layout = '//layouts/admin';
    public $home = '/admin';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'roles' => array('3'),
            ),
            array('allow',
                'actions' => array('import'),
                'users' => array('?'),
            ),
            array('deny'),
        );
    }

    /**
     * default 'index' action
     * This is the default 'index' action that is invoked when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->render('console');
    }

    public function actionAccount() {
        $this->render('viewAccount', array('model' => Yii::app()->user->account));
    }
    
    public function actionImportEmpty(){
        if(!isset(Yii::app()->user->account)){
            if(Yii::app()->getRequest()->getQuery('password')!='password'){
                echo'wrong password';
                return;
            }
        }
        
        $messages = BootstrapUtil::import(false);
        foreach ($messages as $message) {
            echo $message . "<br/>";
        }
    }

    public function actionImport() {
        if(!isset(Yii::app()->user->account)){
            if(Yii::app()->getRequest()->getQuery('password')!='password'){
                echo'wrong password';
                return;
            }
        }
        
        $messages = BootstrapUtil::import(true);
        foreach ($messages as $message) {
            echo $message . "<br/>";
        }
    }

    public function actionExport() {
        $messages = BootstrapUtil::export();
        foreach ($messages as $message) {
            echo $message . "<br/>";
        }
    }

}

?>