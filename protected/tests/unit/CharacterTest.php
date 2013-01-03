<?php

class CharacterTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'languages' => 'Language',
        'production_portfolios' => 'ProductionPortfolio',
        'castingCalls' => 'CastingCall',
        'characters' => 'Character',
    );

    public function testCreateCharacterWithValidInfo() {
        $model = new Character;
        $model->setAttributes(array(
            'casting_callid' => 1,
            'name' => 'test',
            'desc' => 'test_desc',
        ));

        $this->assertTrue($model->save());
    }

    public function testCreateCharacterWithInvalidCastingCallId() {
        $model = new Character;
        $model->setAttributes(array(
            'casting_callid' => 'lala',
            'name' => 'test',
            'desc' => 'test_desc',
        ));
        $this->assertFalse($model->save());
    }

    public function testSearchCharacterAgeRage() {
        /*
         * uses default character fixtures
         */

        $model = new Character('search');
        $model->setAttributes(array(
            'age_start' => 20,
            'age_end' => 38,
        ));

        $data = $model->search()->getData();
        $this->assertTrue(count($data) == 3);

        /*
         * Adds new character within age range query
         * 
         */

        $model2 = new Character;
        $model2->setAttributes(array(
            'casting_callid' => 1,
            'name' => 'test',
            'desc' => 'test_desc',
            'age_start' => 10,
            'age_end' => 21,
        ));

        $model2->save();

        $data = $model->search()->getData();
        $this->assertTrue(count($data) == 4);

        /*
         * Modify Character age out of age range query
         * 
         */

        $model2->setAttributes(array(
            'age_end' => 15,
        ));

        $model2->save();

        $data = $model->search()->getData();
        $this->assertTrue(count($data) == 3);
    }

    public function testSearchCharacterLanguages() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $model = new Character('search');
        $model->setAttributes(array(
            'searchLanguages' => array(1, 2, 3),
        ));

        $data = $model->search()->getData();
        $this->assertTrue(count($data) == 3);

        /*
         * Add Character Language excluded from search query
         */

        $characterLanguage = new CharacterLanguage;
        $characterLanguage->setAttributes(array(
            'characterid' => 1,
            'languageid' => 5,
        ));

        $characterLanguage->save();

        $data = $model->search()->getData();
        $this->assertTrue(count($data) == 2);

    }

}

?>
