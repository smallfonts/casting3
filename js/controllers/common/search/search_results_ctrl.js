/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var src;

function search_results_ctrl($scope){    
    src = $scope;
    $scope.photoBaseUrl = smc.photoBaseUrl;
    $scope.baseUrl = smc.baseUrl;
    $scope.searchCharacterResults = '';
    $scope.searchArtistePortfolioResults = '';
    $scope.page = 0;
    $scope.result_loading = false;
    
    $('#main_scroll').unbind();
    $("#main_scroll").scroll(function(e){
        var clientHeight = e.target.clientHeight;
        var scrollHeight = e.target.scrollHeight;
        var scrollTop = e.target.scrollTop;
        if(scrollHeight - clientHeight - 10 < scrollTop){
            if($scope.result_loading) return;
            //console.info('loading');
            $scope.result_loading = true;
            $scope.page++;
            smc.setQuery({
                page: $scope.page
            });
            $scope.$apply();
        }
    });
    
    $scope.setResults = function(data,isScroll){
        
        //console.info(isScroll);
        
        if(!isScroll){
            $scope.page = 0;
            $scope.end_of_results = false;
            $('#main_scroll').scrollTop(0);
        } else {
            $scope.result_loading = false;
        }
        
        if(typeof data == 'undefined'){
            $scope.searchArtistePortfolioResults = '';
            $scope.searchCharacterResults = '';
            
        } else if(data.results.length == 0 && isScroll){
            $scope.end_of_results = true;
        } else {
            
            switch(data.type){
                case 'ArtistePortfolio':
                
                    if(isScroll){
                        $scope.searchArtistePortfolioResults = $scope.searchArtistePortfolioResults.concat(data.results);
                    }else{
                        $scope.searchArtistePortfolioResults = data.results;
                    }
                
                    for(var apIndex=0;apIndex<$scope.searchArtistePortfolioResults.length;apIndex++){
                        var artistePortfolio = $scope.searchArtistePortfolioResults[apIndex];
                        artistePortfolio['favourite'] = smc.bindArtistePortfolioFavourite(artistePortfolio['artiste_portfolioid']);
                    }
                
                    break;
                        
                case 'CastingCall':
                    if(isScroll){
                        $scope.searchCharacterResults = $scope.searchCharacterResults.concat(data.results);
                    }else{
                        $scope.searchCharacterResults = data.results;
                    }

                    for(var ccIndex=0;ccIndex<$scope.searchCharacterResults.length;ccIndex++){
                        var character = $scope.searchCharacterResults [ccIndex];
                        character['favourite'] = smc.bindCharacterFavourite(character['characterid']);
                    }
                    break;                   
            }
        }
        
        $scope.$apply();
    }
    
    $scope.toggleFavouriteArtistePortfolio = function(artiste_portfolioid){
        for(var i in $scope.searchArtistePortfolioResults){
            var artistePortfolio = $scope.searchArtistePortfolioResults[i];
            if(artistePortfolio.artiste_portfolioid == artiste_portfolioid){
                var artistePortfolioFavourite = artistePortfolio['favourite'];
                if(artistePortfolioFavourite['isFavourite']){
                    artistePortfolioFavourite['isFavourite'] = false;
                    smc.setFavouriteArtistePortfolio(artiste_portfolioid,false);
                } else {
                    artistePortfolioFavourite['isFavourite'] = true;
                    smc.setFavouriteArtistePortfolio(artiste_portfolioid,true);
                }
            }
        }
        
        smc.applyAll('src');
    }
    
    $scope.toggleFavouriteCharacter = function(characterid){
        for(var i in $scope.searchCharacterResults){
            var character = $scope.searchCharacterResults[i];
            if(character.characterid == characterid){
                var characterFavourite = character['favourite'];
                if(characterFavourite['isFavourite']){
                    characterFavourite['isFavourite'] = false;
                    smc.setFavouriteCharacter(characterid,false);
                } else {
                    characterFavourite['isFavourite'] = true;
                    smc.setFavouriteCharacter(characterid,true);
                }
            }
        }
        
        smc.applyAll('src');
    }
    
    $scope.sendMessage = function(userid){
        smc.sendMessage(userid);
    }
    
    $scope.invite = function(artiste_portfolioid,name,photoUrl){
        smc.invite(artiste_portfolioid,name,photoUrl);
    }
    
    $scope.hasNoResults = function(){
        if (
            ($scope.searchCharacterResults == '' ||  $scope.searchCharacterResults.length == 0) &&
            ($scope.searchArtistePortfolioResults == '' || $scope.searchArtistePortfolioResults.length ==0)
            ){
            return true;       
        }
        return false;
    }
    

}
