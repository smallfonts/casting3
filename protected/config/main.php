<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Casting3',
    'defaultController' => 'site',
    // preloading 'log' component
    'preload' => array(
        'log',
        'bootstrap',
        'zend',
    ),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.util.*',
        'application.components.*',
        'application.vendors.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'pa55w0rd!',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => false,
            'generatorPaths' => array(
                'bootstrap.gii',
                'ext.giitemplates'
            ),
        ),
    ),
    // application components
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                'artiste/portfolio/<url>' => 'artiste/portfolio',
                'production/portfolio/<url>' => 'production/portfolio',
                'artiste/addSpokenLanguage/<json>' => 'artiste/addSpokenLanguage',
                'artiste/addSkill/<json>' => 'artiste/addSkill',
                'artiste/addProfession/<json>' => 'artiste/addProfession',
                'artiste/deleteSkill/<json>' => 'artiste/deleteSkill',
                'artiste/deleteProfession/<json>' => 'artiste/deleteProfession',
                'artiste/updateSpokenLanguage/<json>' => 'artiste/updateSpokenLanguage',
                'artiste/deleteSpokenLanguage/<json>' => 'artiste/deleteSpokenLanguage',
                'artiste/editSpokenLanguage/<json>' => 'artiste/editSpokenLanguage',
                'admin/import/<password>' => 'admin/import',
                'castingCall/view/<url>' => 'castingCall/view',
                'castingCall/edit/<url>' => 'castingCall/edit',
                'castingCall/applicants/<url>' => 'castingCall/applicants',
                'castingCall/auditions/<url>' => 'castingCall/auditions',
                'castingCall/applicants/<url>/<characterid>' => 'castingCall/characterApplicants',
                'audition/new/<url>' => 'audition/new',
                'audition/edit/<url>' => 'audition/edit',
                'audition/apply/<url>' => 'audition/apply',
                'audition/export/<auditionid>/<filename>' => 'audition/export',
                'common/selectArtiste/<url>' => 'common/selectArtiste',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        
        'mustache'=>array(
            'class'=>'ext.mustache.components.MustacheApplicationComponent',
            // Default settings (not needed)
            'templatePathAlias'=>'application.templates',
            'templateExtension'=>'mustache',
            'extension'=>true,
        ),
        
        'file' => array(
            'class' => 'application.extensions.file.CFile',
        ),
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
        ),
        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin' => false,
        ),
        'zend' => array(
            'class' => 'ext.Zend.EZendAutoloader',
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        // uncomment the following to use a MySQL database

        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=casting3',
            //Uncomment line 99 and comment line 97 when doing Selenium testing. Revert to original when done testing.
            //'connectionString' => 'mysql:host=localhost;dbname=casting3test',
            'emulatePrepare' => true,
            'username' => 'root',
            //  'password' => '12345',
            'password' => '',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'smu.timberwerkz.dev@gmail.com',
        //adminPassword (used by cron jobs)
        'password' => 'pa55w0rd!',
        //Amazon Web Services (AWS) Keys
        'awsAccessKey' => 'AKIAJNTS364A226FFC5Q',
        'awsSecretKey' => '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',
        //s3 buckets
        'photoBucket' => 'c3-dev',
        'photoBaseUrl' => 'https://s3-ap-southeast-1.amazonaws.com/c3-dev',
    ),
);