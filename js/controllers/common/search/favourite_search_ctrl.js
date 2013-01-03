/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var fsc;

function favourite_search_ctrl($scope){
    $('#main_scroll').unbind();
    fsc = $scope;
    $scope.photoBaseUrl = smc.photoBaseUrl;
    $scope.baseUrl = smc.baseUrl;
    $scope.favouriteCharacters = favouriteCharacters;
    $scope.favouriteArtistePortfolios = favouriteArtistePortfolios;
    
    $scope.init = function(){
        smc.favouritesCount = " ("+$scope.favouriteArtistePortfolios.length+")";
        
        for(var fsci in $scope.favouriteArtistePortfolios){
            artistePortfolio = $scope.favouriteArtistePortfolios[fsci];
            artistePortfolio['favourite'] = smc.bindArtistePortfolioFavourite(artistePortfolio.artiste_portfolioid);
        }
        
        for(var fsci in $scope.favouriteCharacters){
            character = $scope.favouriteCharacters[fsci];
            character['favourite'] = smc.bindCharacterFavourite(character.characterid);
        }
    }
    
    $scope.toggleFavouriteArtistePortfolio = function(artiste_portfolioid){
        for(i in $scope.favouriteArtistePortfolios){
            if($scope.favouriteArtistePortfolios[i].artiste_portfolioid == artiste_portfolioid){
                artistePortfolioFavourite = $scope.favouriteArtistePortfolios[i]['favourite'];
                if(artistePortfolioFavourite['isFavourite']){
                    artistePortfolioFavourite['isFavourite'] = false;
                    smc.setFavouriteArtistePortfolio(artiste_portfolioid,false);
                } else {
                    artistePortfolioFavourite['isFavourite'] = true;
                    smc.setFavouriteArtistePortfolio(artiste_portfolioid,true);
                }
            }
        }
        
        smc.applyAll();
    }
    
    $scope.sendMessage = function(userid){
        smc.sendMessage(userid);
    }
    
    $scope.invite = function(artiste_portfolioid,name,photoUrl){
        smc.invite(artiste_portfolioid,name,photoUrl);
    }
    
    $scope.toggleFavouriteCharacter = function(characterid){
        for (tfc in $scope.favouriteCharacters){
            character = $scope.favouriteCharacters[tfc];
            if(character['characterid'] == characterid){
                if(character['favourite']['isFavourite']){
                    character['favourite']['isFavourite'] = false;
                    smc.setFavouriteCharacter(character['characterid'],false);
                } else {
                    character['favourite']['isFavourite'] = true;
                    smc.setFavouriteCharacter(character['characterid'],true);
                }
            }
        }
        
        smc.applyAll();
    }
    
    $scope.hasNoResults = function(){
        if ($scope.favouriteCharacters === undefined && $scope.favouriteArtistePortfolios === undefined){
            return true;
        } else{
            if ($scope.favouriteCharacters == '' && $scope.favouriteArtistePortfolios == ''){
                return true;
            }
        }
        return false;
    }
}
