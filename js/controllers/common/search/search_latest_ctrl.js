/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var slc;

function search_latest_ctrl($scope){    
    slc = $scope;
    $scope.photoBaseUrl = smc.photoBaseUrl;
    $scope.baseUrl = smc.baseUrl;
    $scope.searchCastingCallResults = new Array();
    $scope.searchArtistePortfolioResults = new Array();
    $scope.page = 0;
    $scope.result_loading = false;
    $scope.end_of_results = false;
    $('#main_scroll').unbind();
    $("#main_scroll").scroll(function(e){
        var clientHeight = e.target.clientHeight;
        var scrollHeight = e.target.scrollHeight;
        var scrollTop = e.target.scrollTop;
        if(scrollHeight - clientHeight - 10 < scrollTop){
            if($scope.result_loading || $scope.end_of_results) return;
            //console.info('loading');
            $scope.result_loading = true;
            $scope.page++;
            $scope.update();
            $scope.$apply();
        }
    });
    
    $scope.init = function(){
        $scope.page = 0;
        $scope.update();
    }
    
    
    $scope.update = function(){
        $.get($scope.baseUrl + '/common/searchLatestCastingCalls?p='+$scope.page,function(data){
            $scope.result_loading = false;
            data = angular.fromJson(data);
            if (data.results.length == 0) {
                $scope.end_of_results = true;
                $scope.$apply();
            } else {
                $scope.setResults(data);
            }
        });
    }
    
    $scope.setResults = function(data){
        switch(data.type){
            
            case 'ArtistePortfolio':
                    
                $scope.searchArtistePortfolioResults = $scope.searchArtistePortfolioResults.concat(data.results);
                for(var apIndex=0;apIndex<$scope.searchArtistePortfolioResults.length;apIndex++){
                    var artistePortfolio = $scope.searchArtistePortfolioResults[apIndex];
                    artistePortfolio['favourite'] = smc.bindArtistePortfolioFavourite(artistePortfolio['artiste_portfolioid']);
                }
                
                break;
                
            case 'CastingCall':
                
                for(var i in data.results){
                    var tmpDate = stringToDate(data.results[i].created,'yyyy-mm-dd');
                    data.results[i].created = dateFormat(tmpDate,'dS mmmm, yyyy');
                }
                
                $scope.searchCastingCallResults = $scope.searchCastingCallResults.concat(data.results);
                
                break;
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
            ($scope.searchCastingCallResults == '' ||  $scope.searchCastingCallResults.length == 0) &&
            ($scope.searchArtistePortfolioResults == '' || $scope.searchArtistePortfolioResults.length ==0)
            ){
            return true;       
        }
        return false;
    }
    

}
