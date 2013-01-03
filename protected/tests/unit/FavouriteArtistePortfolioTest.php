<?php

class FavouriteArtistePortfolioTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'languages' => 'Language',
        'production_portfolios' => 'ProductionPortfolio',
        'castingCalls' => 'CastingCall',
        'characters' => 'Character',
        'favouriteArtistePortfolios' => 'FavouriteArtistePortfolio',
    );
    
    public function testAddFavouriteWithValidInfo(){
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>1,
            'artiste_portfolioid'=>1,
            'action'=>'add',
        ));
        
        $this->assertTrue($model->set());
    }
    
    public function testRemoveFavouriteWithExistingFavourite(){
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>1,
            'artiste_portfolioid'=>1,
            'action'=>'add',
        ));
        
        $this->assertTrue($model->set());
        
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>1,
            'artiste_portfolioid'=>1,
            'action'=>'delete',
        ));
        
        $this->assertTrue($model->set());
    }
    
    public function testAddDuplicateFavouriteEntry(){
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>1,
            'artiste_portfolioid'=>1,
            'action'=>'add',
        ));
        
        $this->assertTrue($model->set());
        
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>1,
            'artiste_portfolioid'=>1,
            'action'=>'add',
        ));
        
        $this->assertFalse($model->set());
    }
    
    public function testRemoveNonExistingFavouriteEntry(){
        $model = new FavouriteArtistePortfolio;
        $model->setAttributes(array(
            'userid'=>2,
            'artiste_portfolioid'=>1,
            'action'=>'delete',
        ));
        
        $this->assertFalse($model->set());
    }

}

?>
