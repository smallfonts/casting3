<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class BootstrapUtil {

    public static function import($test = false) {
        set_time_limit(0);
        $message = array();
        try {
            $message = BootstrapUtil::rebuildDatabase($message);
            $message = BootstrapUtil::bootstrapRole($message);
            $message = BootstrapUtil::bootstrapStatus($message);
            $message = BootstrapUtil::bootstrapLanguages($message);
            $message = BootstrapUtil::bootstrapLanguageProficiencies($message);
            $message = BootstrapUtil::bootstrapPhotos($message);
            $message = BootstrapUtil::bootstrapVideos($message);
            $message = BootstrapUtil::bootstrapUsers($message,$test);
            if ($test) {
                $message = BootstrapUtil::bootstrapCastingManagerPortfolio($message);
                $message = BootstrapUtil::bootstrapCastingCalls($message);
                $message = BootstrapUtil::bootstrapCharacters($message);
                $message = BootstrapUtil::bootstrapCharacterLanguages($message);
                $message = BootstrapUtil::bootstrapArtistePortfolio($message);
                $message = BootstrapUtil::bootstrapSpokenLanguages($message);
                $message = BootstrapUtil::bootstrapSkills($message);
                $message = BootstrapUtil::bootstrapArtistePortfolioSkills($message);
                $message = BootstrapUtil::bootstrapProfessions($message);
                $message = BootstrapUtil::bootstrapCharacterPhotoAttachment($message);
                $message = BootstrapUtil::bootstrapCharacterVideoAttachment($message);
                $message = BootstrapUtil::changeProductionHousePortfolio($message);
                $message = BootstrapUtil::bootstrapEthnicity($message);
                $message = BootstrapUtil::bootstrapProductionHouseUser($message);
                $message = BootstrapUtil::bootstrapCharacterApplication($message);
            }
        } catch (Exception $e) {
            $message[] = 'ERROR: ' . $e->getMessage();
            $message[] = 'Bootstrap Database Failed';
            return $message;
        }

        return $message;
    }

    public static function bootstrapLanguageProficiencies($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);

        $model = new LanguageProficiency;
        $model->language_proficiencyid = 1;
        $model->setAttributes(array(
            'name' => 'Basic',
        ));
        $model->save();
        $model = new LanguageProficiency;
        $model->language_proficiencyid = 2;
        $model->setAttributes(array(
            'name' => 'Conversational',
        ));
        $model->save();
        $model = new LanguageProficiency;
        $model->language_proficiencyid = 3;
        $model->setAttributes(array(
            'name' => 'Fluent',
        ));
        $model->save();
        $message[] = "Success: Language Proficiencies Set";
        return $message;
    }

    public static function bootstrapSpokenLanguages($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 1,
            'languageid' => 39,
            'language_proficiencyid' => 3,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 1,
            'languageid' => 99,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 2,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 3,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 3,
            'languageid' => 44,
            'language_proficiencyid' => 3,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 4,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 4,
            'languageid' => 99,
            'language_proficiencyid' => 1,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 4,
            'languageid' => 151,
            'language_proficiencyid' => 3,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 5,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        $spokenLanguage = new SpokenLanguage;
        $spokenLanguage->setAttributes(array(
            'artiste_portfolioid' => 6,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $spokenLanguage->save();

        return $message;
    }

    public static function bootstrapArtistePortfolio($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '1',
                ));

        $model->dob = '2004-05-08';
        $model->name = 'Rebecca Brown';
        $model->gender = "female";
        $model->save();

        $message[] = "Success: Saved artiste@artiste.com";

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '2',
                ));

        $model->dob = '2002-01-06';
        $model->name = 'Michael Tang';
        $model->gender = "male";
        $model->photoid = 1;
        $model->save();
        $message[] = "Success: Saved artiste2@artiste.com";

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '3',
                ));

        $model->dob = '1984-05-08';
        $model->name = 'Jack';
        $model->gender = "male";
        $model->save();

        $message[] = "Success: Saved 1@1.com";

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '4',
                ));

        $model->dob = '1993-05-08';
        $model->name = 'Jane';
        $model->gender = "female";
        $model->save();

        $message[] = "Success: Saved 2@2.com";

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '5',
                ));

        $model->dob = '1990-05-08';
        $model->name = 'Justin';
        $model->gender = "male";
        $model->save();

        $message[] = "Success: Saved 3@3.com";

        $model = ArtistePortfolio::model()->findByAttributes(array(
            'artiste_portfolioid' => '6',
                ));

        $model->dob = '1978-05-08';
        $model->name = 'Rina';
        $model->gender = "female";
        $model->save();

        $message[] = "Success: Saved 4@4.com";

        $id = 7;
        $count = 0;
        while (true) {
            $count++;
            $model = ArtistePortfolio::model()->findByAttributes(array(
                'artiste_portfolioid' => $id,
                    ));
            if (!is_null($model)) {
                $model->name = 'Reb' . $count;
                $model->gender = "female";
                $model->save();
                $id++;
            } else {
                break;
            }
        }

        return $message;
    }

    public static function bootstrapCharacterApplication($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);

        $model = new CharacterApplication;
        $model->artiste_portfolioid = 1;
        $model->characterid = 1;
        $model->application_date = date('Y-m-d');
        $model->statusid = 8;
        $model->save();

        $model = new CharacterApplication;
        $model->artiste_portfolioid = 2;
        $model->characterid = 1;
        $model->application_date = date('Y-m-d');
        $model->statusid = 8;
        $model->save();

        $message[] = "Success: Character Application Saved";

        return $message;
    }

    public static function bootstrapCharacterLanguages($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $characterLanguage = new CharacterLanguage;
        $characterLanguage->setAttributes(array(
            'characterid' => 1,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $characterLanguage->save();

        $characterLanguage = new CharacterLanguage;
        $characterLanguage->setAttributes(array(
            'characterid' => 2,
            'languageid' => 39,
            'language_proficiencyid' => 2,
        ));
        $characterLanguage->save();

        $message[] = "Success: Imported Character Languages";
        return $message;
    }

    public static function bootstrapCharacters($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $character = new Character;
        $character->characterid = 1;
        $character->setAttributes(array(
            'casting_callid' => 1,
            'name' => 'Pretty Gretel',
            'desc' => 'Gretel, sensing the witchs intent, pretends she does not understand what she means. Infuriated, the witch demonstrates, and Gretel instantly shoves the witch into the oven, slams and bolts the door shut, leaving "The ungodly witch to be burned to ashes", the witch screaming in pain until she dies. Gretel frees Hansel from the cage and the pair discover a vase full of treasure and precious stones. Putting the jewels into their clothing, the children set off for home. ',
            'gender' => 'female',
            'age_start' => 7,
            'age_end' => 10,
            'statusid' => 5,
        ));
        $character->save();
        $message[] = "Success: set character for casting_callid:1 characterid:1";
        $character = new Character;
        $character->characterid = 2;
        $character->setAttributes(array(
            'casting_callid' => 1,
            'name' => 'Handsome Hansel',
            'desc' => 'Hansel’s like a cat. He doesn’t want to be bothered with nonsense and he will run if you crowd him. He decides when and how he wants attention, which isn’t often, and lets you know. You can’t run up to him and expect him to let you pet him; he’ll turn tail and run from your forwardness, never to come out again, scared by your aggressive nature. Yes, a finicky cat that only wants the necessities in life from you and, once in awhile, a pat here and there is nice, but watch out, because it won’t take long for him to snap at you if you overdue it, and then you’ve lost his trust.',
            'gender' => 'male',
            'age_start' => 9,
            'age_end' => 12,
            'statusid' => 5,
        ));
        $character->save();

        $count = 1;
        while ($count != 100) {
            $count++;
            $character = new Character;
            $character->setAttributes(array(
                'casting_callid' => $count,
                'name' => 'Pretty Gretel',
                'desc' => 'Gretel, sensing the witchs intent, pretends she does not understand what she means. Infuriated, the witch demonstrates, and Gretel instantly shoves the witch into the oven, slams and bolts the door shut, leaving "The ungodly witch to be burned to ashes", the witch screaming in pain until she dies. Gretel frees Hansel from the cage and the pair discover a vase full of treasure and precious stones. Putting the jewels into their clothing, the children set off for home. ',
                'gender' => 'female',
                'age_start' => 7,
                'age_end' => 10,
                'statusid' => 5,
            ));
            $character->save();
        }

        $message[] = "Success: set character for casting_callid:1 characterid:2";
        return $message;
    }

    public static function bootstrapCastingCalls($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $castingCall = new CastingCall;
        $castingCall->casting_callid = 1;
        $castingCall->setAttributes(array(
            'production_portfolioid' => 1,
            'casting_manager_portfolioid' => 1,
            'title' => 'Hansel And Gretel',
            'desc' => 'Hansel and Gretel are the young children of a poor woodcutter. When a great famine settles over the land, the woodcutters second, abusive wife decides to take the children into the woods and leave them there to fend for themselves, so that she and her husband do not starve to death. The woodcutter opposes the plan but finally, and reluctantly, submits to his wifes scheme. They are unaware that in the childrens bedroom, Hansel and Gretel have overheard them. After the parents have gone to bed, Hansel sneaks out of the house and gathers as many white pebbles as he can, then returns to his room, reassuring Gretel that God will not forsake them. ',
            'application_start' => '2012-02-15',
            'application_end' => '2012-12-14',
            'audition_start' => '2012-02-15',
            'audition_end' => '2012-02-16',
            'project_start' => '2012-02-16',
            'project_end' => '2012-02-17',
            'location' => 'Singapore',
            'photoid' => 1,
            'statusid' => 5,
            'url' => 'castingCall1',
        ));
        $castingCall->save();

        $count = 0;
        while ($count != 100) {
            $count++;
            $castingCall = new CastingCall;
            $castingCall->setAttributes(array(
                'production_portfolioid' => 1,
                'casting_manager_portfolioid' => 1,
                'title' => 'Test ' . $count,
                'desc' => 'Hansel and Gretel are the young children of a poor woodcutter. When a great famine settles over the land, the woodcutters second, abusive wife decides to take the children into the woods and leave them there to fend for themselves, so that she and her husband do not starve to death. The woodcutter opposes the plan but finally, and reluctantly, submits to his wifes scheme. They are unaware that in the childrens bedroom, Hansel and Gretel have overheard them. After the parents have gone to bed, Hansel sneaks out of the house and gathers as many white pebbles as he can, then returns to his room, reassuring Gretel that God will not forsake them. ',
                'application_start' => '2012-02-15',
                'application_end' => '2012-12-14',
                'audition_start' => '2012-02-15',
                'audition_end' => '2012-02-16',
                'project_start' => '2012-02-16',
                'project_end' => '2012-02-17',
                'location' => 'Singapore',
                'photoid' => 1,
                'statusid' => 5,
                'url' => 'castingCall1',
            ));
            $castingCall->save();
        }


        $message[] = "Success: Casting Call for prod@prod.com created";
        return $message;
    }

    public static function bootstrapUsers($message, $test) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);

        $userAccount = new UserAccount;
        $userAccount->userid = 1;
        $userAccount->setAttributes(array(
            'email' => 'admin@admin.com',
            'email2' => 'admin@admin.com',
            'password' => '123',
            'password2' => '123',
            'roleid' => 3,
            'statusid' => 1,
        ));
        $userAccount->save();
        $message[] = "Success: Admin User Added Username:admin@admin.com Password:123";

        if ($test) {
            $userAccount = new UserAccount;
            $userAccount->userid = 2;
            $userAccount->setAttributes(array(
                'email' => 'artiste@artiste.com',
                'email2' => 'artiste@artiste.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:artiste@artiste.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 3;
            $userAccount->setAttributes(array(
                'email' => 'artiste2@artiste.com',
                'email2' => 'artiste2@artiste.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:artiste2@artiste.com Password:123";


            $userAccount = new UserAccount;
            $userAccount->userid = 4;
            $userAccount->setAttributes(array(
                'email' => 'prod@prod.com',
                'email2' => 'prod@prod.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 2,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Production House User Added Username:prod@prod.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 5;
            $userAccount->setAttributes(array(
                'email' => 'cm@cm.com',
                'email2' => 'cm@cm.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 4,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Casting Manager User Added Username:cm@cm.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 6;
            $userAccount->setAttributes(array(
                'email' => '1@1.com',
                'email2' => '1@1.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:1@1.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 7;
            $userAccount->setAttributes(array(
                'email' => '2@2.com',
                'email2' => '2@2.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:2@2.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 8;
            $userAccount->setAttributes(array(
                'email' => '3@3.com',
                'email2' => '3@3.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:3@3.com Password:123";

            $userAccount = new UserAccount;
            $userAccount->userid = 9;
            $userAccount->setAttributes(array(
                'email' => '4@4.com',
                'email2' => '4@4.com',
                'password' => '123',
                'password2' => '123',
                'roleid' => 1,
                'statusid' => 1,
            ));
            $userAccount->save();
            $message[] = "Success: Artiste User Added Username:4@4.com Password:123";

            $count = 0;
            while ($count != 100) {
                $count++;
                $userAccount = new UserAccount;
                $userAccount->setAttributes(array(
                    'email' => 'Rebecca@' . $count . '.com',
                    'email2' => 'Rebecca@' . $count . '.com',
                    'password' => '123',
                    'password2' => '123',
                    'roleid' => 1,
                    'statusid' => 1,
                ));
                $userAccount->save();
            }
        }



        return $message;
    }

    public static function bootstrapPhotos($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $model = new Photo;
        $model->scenario = 'manualInsert';
        $url = "anonymous.png";
        $log->logInfo("Inserting default anonymous photo: " . $url);
        $model->url = $url;
        $model->statusid = 1;
        $model->save();

        $model = new Photo;
        $model->scenario = 'manualInsert';
        $url = "casting_anonymous.png";
        $log->logInfo("Inserting default anonymous photo: " . $url);
        $model->url = $url;
        $model->statusid = 1;
        $model->save();
    }

    public static function bootstrapVideos($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $model = new Video;
        $model->videoid = 1;
        $model->userid = NULL;
        $model->url = 'QWXdMGblaMU';
        $model->save();
        $message[] = "Success: Videos Successfully Imported";
        return $message;
    }

    public static function bootstrapCastingManagerPortfolio($message) {
        $model = new CastingManagerPortfolio;
        $model->userid = 5;
        $model->mobile = '92710152';
        $model->first_name = 'Jenny';
        $model->last_name = 'Lim';
        $model->statusid = 1;
        $model->photoid = 1;
        $model->save();
        $message[] = "Success: Casting Manager Portfolio Successfully Imported";
        return $message;
    }

    public static function bootstrapProductionHouseUser($message) {
        $model = new ProductionHouseUser;
        $model->production_userid = 4;
        $model->cm_userid = 5;
        $model->save();
        $message[] = "Success: Production House User Successfully Imported";
        return $message;
    }

    public static function export() {
        $message = array();
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $version = 1;
        do {
            $file = Yii::app()->file->set('sql/casting3_' . $version . '.sql');
            $path = $file->realpath;
            $version++;
        } while ($file->exists);


        $m = array();
        $mainConfig = require dirname(__FILE__) . '/../../config/main.php';
        $mainDbConfig = $mainConfig['components']['db'];
        preg_match('~mysql:host=(.+);dbname=(.+)~is', $mainDbConfig['connectionString'], $m);
        $mainDbConfig['host'] = $m[1];
        $mainDbConfig['dbname'] = $m[2];
        if ($mainDbConfig['password'] != "")
            $mainDbConfig['password'] = "-p" . $mainDbConfig['password'];


        $command = "mysqldump -u " . $mainDbConfig['username'] . " " . $mainDbConfig['password'] . " --no-data " . $mainDbConfig['dbname'] . " > " . $path;
        $log->logInfo("Exporting Database :" . $command);
        exec($command);
        //add drop database to .sql file
        $dropDatabase = 'DROP DATABASE `casting3`;CREATE DATABASE `casting3` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;USE `casting3`;';
        //set auto increment to zero

        $file = Yii::app()->file->set($path);
        $output = preg_replace("/ENGINE=InnoDB AUTO_INCREMENT=.*/", "ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;", $file->contents);
        $file->setContents($dropDatabase . " " . $output);
        $log->logInfo("Export Ended");
        $message[] = "Success: Database Successfully Exported to " . $path;
        return $message;
    }

    public static function rebuildDatabase($message) {

        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $version = 0;
        do {
            $version++;
            $file = Yii::app()->file->set('sql/casting3_' . $version . '.sql');
            $path = $file->realpath;
        } while ($file->exists);

        $version--;
        $file = Yii::app()->file->set('sql/casting3_' . $version . '.sql');
        $path = $file->realpath;
        $log->logInfo('DBImport Path set to :' . $path);

        $m = array();
        $mainConfig = require dirname(__FILE__) . '/../../config/main.php';
        $mainDbConfig = $mainConfig['components']['db'];
        preg_match('~mysql:host=(.+);dbname=(.+)~is', $mainDbConfig['connectionString'], $m);
        $mainDbConfig['host'] = $m[1];
        $mainDbConfig['dbname'] = $m[2];
        if ($mainDbConfig['password'] != "")
            $mainDbConfig['password'] = "-p" . $mainDbConfig['password'];

        $command = "mysql -h " . $mainDbConfig['host'] . " -u " . $mainDbConfig['username'] . " " . $mainDbConfig['password'] . " < " . $path;
        $log->logInfo("Executing SQL file :" . $command);
        exec($command);
        $message[] = "Success: Database Successfully Imported from : " . $path;
        return $message;
    }

    public static function bootstrapLanguages($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $file = Yii::app()->file->set('bootstrap/languages.txt');
        if (!$file->exists) {
            $log->logError("File does not exist:" . $file->realpath);
            $message[] = "Error: File does not exist:" . $file->realpath;
            return $message;
        }

        $log->logInfo("Path to file is set :" . $file->realpath);
        $languages = file($file->realpath);
        foreach ($languages as $language) {
            $model = new Language;
            $model->setAttributes(array('name' => trim($language)));
            $model->save();
        }
        $message[] = "Success: Languages successfully imported";
        return $message;
    }

    public static function bootstrapProfessions($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $file = Yii::app()->file->set('bootstrap/professions2.txt');
        if (!$file->exists) {
            $log->logError("File does not exist:" . $file->realpath);
            $message[] = "Error: File does not exist:" . $file->realpath;
            return $message;
        }

        $log->logInfo("Path to file is set :" . $file->realpath);
        $professions = file($file->realpath);
        foreach ($professions as $profession) {
            $model = new Profession;
            $model->setAttributes(array('name' => trim($profession)));
            $model->save();
        }
        $message[] = "Success: Professions successfully imported";
        return $message;
    }

    public static function bootstrapRole($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        Role::model()->deleteAll();

        $arr = array(
            array(1, 'Artiste'),
            array(2, 'Production House'),
            array(3, 'Admin'),
            array(4, 'Casting Manager'),
        );

        foreach ($arr as $data) {
            $model = new Role;
            $model->setAttributes(array('roleid' => $data[0], 'name' => $data[1]));
            $model->save();
        }
        $message[] = "Success: Roles successfully imported";
        return $message;
    }

    public static function bootstrapStatus($message) {

        $arr = array(
            array(1, 'active'),
            array(2, 'locked'),
            array(3, 'suspended'),
            array(4, 'deleted', 'message_recipient'),
            array(5, 'publish'),
            array(6, 'un-publish'),
            array(7, 'incomplete'),
            array(8, 'submitted'),
            array(9, 'accepted'),
            array(10, 'rejected'),
            array(11, 'draft'),
            array(12, 'pending'),
            array(13, 'closed'),
            array(14, 'confirmed'),
            array(15, 'invited'),
            array(16, 'open'),
            array(17, 'closed'),
            array(18, 'unread', 'message_recipient'),
            array(19, 'read', 'message_recipient'),
        );

        foreach ($arr as $data) {
            $model = new Status;
            $model->setAttributes(array(
                'statusid' => $data[0],
                'name' => $data[1],
            ));
            $model->save();
        }
        $message[] = "Success: Statuses successfully imported";
        return $message;
    }

    public static function bootstrapSkills($message) {

        $arr = array(
            array(1, 'Martial Arts'),
            array(2, 'Driving'),
            array(3, 'Singing'),
            array(4, 'Dancing'),
        );

        foreach ($arr as $data) {
            $model = new Skill;
            $model->skillid = $data[0];
            $model->setAttributes(array(
                'name' => $data[1],
            ));
            $model->save();
        }
        $message[] = "Success: Skills successfully imported";
        return $message;
    }

    public static function bootstrapArtistePortfolioSkills($message) {
        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 3,
            'skillid' => 2,
        ));
        $artisteportfolioskills->save();

        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 3,
            'skillid' => 3,
        ));
        $artisteportfolioskills->save();

        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 4,
            'skillid' => 1,
        ));
        $artisteportfolioskills->save();

        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 4,
            'skillid' => 4,
        ));
        $artisteportfolioskills->save();

        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 5,
            'skillid' => 4,
        ));
        $artisteportfolioskills->save();

        $artisteportfolioskills = new ArtistePortfolioSkills;
        $artisteportfolioskills->setAttributes(array(
            'artiste_portfolioid' => 6,
            'skillid' => 3,
        ));
        $artisteportfolioskills->save();

        $message[] = "Success: ArtistePortfolio Skills successfully imported";
        return $message;
    }

    public static function bootstrapEthnicity($message) {

        $arr = array(
            array(1, 'Chinese'),
            array(2, 'Malay'),
            array(3, 'Indian'),
            array(4, 'Caucasian'),
        );

        foreach ($arr as $data) {
            $model = new Ethnicity;
            $model->ethnicityid = $data[0];
            $model->setAttributes(array(
                'name' => $data[1],
            ));
            $model->save();
        }
        $message[] = "Success: Ethnicity successfully imported";
        return $message;
    }

    public static function bootstrapCharacterPhotoAttachment($message) {
        $arr = array(
            array(1, 1, 'Pretty Gretel', 'Dress up in what you think Gretel would look like when the story first starts.'),
            array(2, 1, 'Gretel as a slave', 'Gretel turns into a slave for the horrible witch. Show us how you portray that!'),
            array(3, 2, 'Handsome Hansel', 'Dress up in what you think Hansel would look like when the story first starts.'),
        );

        foreach ($arr as $data) {
            $model = new CharacterPhotoAttachment;
            $model->setAttributes(array(
                'character_photo_attachmentid' => $data[0],
                'characterid' => $data[1],
                'title' => $data[2],
                'desc' => $data[3],
            ));
            $model->save();
        }
        $message[] = "Success: CharacterPhotoAttachment successfully imported";
        return $message;
    }

    public static function bootstrapCharacterVideoAttachment($message) {
        $arr = array(
            array(1, 1, 'Script for Gretel', 'Upload a video of yourself covering this script: What are we to do? How are we to get out of the forest? I don’t know how to do it. How do I get in the oven? Hänsel! Hänsel! The witch is dead! We are free!'),
            array(2, 2, 'Script for Hansel', 'Upload a video of yourself covering this script: Hush, Gretel. Let them fall asleep. I have a plan. Just wait a bit. When the moon has risen we will find our way. I am looking at my little pigeon, who is sitting on the roof of the house and wants to say goodbye to me.'),
        );

        foreach ($arr as $data) {
            $model = new CharacterVideoAttachment;
            $model->setAttributes(array(
                'character_video_attachmentid' => $data[0],
                'characterid' => $data[1],
                'title' => $data[2],
                'desc' => $data[3],
            ));
            $model->save();
        }
        $message[] = "Success: CharacterVideoAttachment successfully imported";
        return $message;
    }

    public static function changeProductionHousePortfolio($message) {
        $log = new Logger("BootstrapUtil");
        $log->setMethod(__FUNCTION__);
        $portfolio = ProductionPortfolio::model()->findByAttributes(array(
            'production_portfolioid' => 1,
                ));
        $log->logInfo("found portfolio :" . $portfolio->production_portfolioid);
        $portfolio->name = 'Oak3 Films';
        $portfolio->country = 'Singapore';
        $portfolio->address = '16 Tannery Lane';
        $portfolio->address2 = '#08-00';
        $portfolio->postalcode = '347778';
        $portfolio->email = 'info@oak3films.com';
        $portfolio->phone = '+65 6226 2338';
        $portfolio->description = 'Oak3 Films earns its reputation as a reliable and creative partner with international networks and broadcasters through its years of quality content. Oak3 carries with it a legendary emphasis on creativity artistry, while focusing on the highest standard in execution. It remains prolific in creating original content for the global market.';
        $portfolio->products = 'Film, Television, Factual Programming';
        $portfolio->website = 'http://www.oak3films.com';
        $log->logInfo('set portfolio');
        $portfolio->save();
        $log->logInfo('saved');
        $message[] = "Success: Production House Portfolio saved and set!";
        return $message;
    }

}

?>
