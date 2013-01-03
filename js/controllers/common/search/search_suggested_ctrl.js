/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var ssc;

function search_suggested_ctrl($scope){
    $('#main_scroll').unbind();
    ssc = $scope;
    $scope.page = -1;
    $scope.hasResults = true;
    $scope.suggested_loading = false;
    $scope.photoBaseUrl = c3MainCtrl.photoBaseUrl;
    $scope.baseUrl = c3MainCtrl.baseUrl;
    $scope.featuredCharacters = new Array();
    $scope.featuredArtistePortfolios = new Array();

    $scope.toggleFavouriteArtistePortfolio = function(artiste_portfolioid){
        var portfolios = $scope.getAllArtistePortfolios();
        for (var i in portfolios){
            var artistePortfolio = portfolios[i];
            if(artistePortfolio['artiste_portfolioid'] == artiste_portfolioid){
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
        
        smc.applyAll('ssc');
    }
    
    $scope.toggleFavouriteCharacter = function(characterid){
        for (var i in $scope.featuredCharacters){
            character = $scope.featuredCharacters[i];
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
        
        smc.applyAll('ssc');
    }
    
    $scope.sendMessage = function(userid){
        smc.sendMessage(userid);
    }
    
    $scope.invite = function(artiste_portfolioid,name,photoUrl){
        smc.invite(artiste_portfolioid,name,photoUrl);
    }
    
    $scope.inviteCallback = function(){
        svc.show();
    }
    
    $scope.inviteSubmitCallback = function(data,retVideoid){
        $.post(baseUrl+'/artiste/setApplicationVideo',{
            videoid : data.videoid,
            character_video_attachmentid: retVideoid
        });
        
        var video;
        for(var i in $scope.retVideos){
            if($scope.retVideos[i].id == retVideoid){
                video = $scope.retVideos[i];
                break;
            }
        }
        
        video.attachment = data.url;
        $('#vid_'+retVideoid).loading(false);
        $scope.$apply();
        
    }
    
    $scope.init = function(){
        $scope.update();
        $("#main_scroll").scroll(function(e){
            var clientHeight = e.target.clientHeight;
            var scrollHeight = e.target.scrollHeight;
            var scrollTop = e.target.scrollTop;
            if(scrollHeight - clientHeight - 30 < scrollTop){
                $scope.update();
            }
        });
    }
    
    $scope.update = function(){
        //render favourites
        //get role
        if($scope.suggested_loading || !$scope.hasResults) return
        $scope.suggested_loading = true;
        $scope.$apply();
        $scope.page += 1;
        
        switch(smc.roleid){
            case 1:
                $.get(baseUrl+'/common/getSuggested?p='+$scope.page,function(data){
                    var results = angular.fromJson(data);
                    if(results.length == 0){
                        $scope.hasResults = false;
                    } else {
                        $scope.featuredCharacters = $scope.featuredCharacters.concat(results);
                        for (var i in $scope.featuredCharacters){
                            var character = $scope.featuredCharacters[i];
                            character['favourite'] = smc.bindCharacterFavourite(character['characterid']);
                        }
                    }
                    $scope.suggested_loading = false;
                    $scope.$apply();
                });
                break;
            case 2: case 4:
  
                //get suggested artiste for casting call characters
                $.get(baseUrl+'/common/getSuggested?p='+$scope.page,function(data){
                    var results = angular.fromJson(data);
                    if(results.length == 0){
                        $scope.hasResults = false;
                    } else {
                        $scope.featuredArtistePortfolios = $scope.featuredArtistePortfolios.concat(results);
                        var portfolios = $scope.getAllArtistePortfolios();
                        for (var i in portfolios){
                            var artistePortfolio = portfolios[i];
                            artistePortfolio['favourite'] = smc.bindArtistePortfolioFavourite(artistePortfolio['artiste_portfolioid']);
                        }
                    }
                    $scope.suggested_loading = false;
                    $scope.$apply();
                });
                break;
        }
    }
    
    
    $scope.getAllArtistePortfolios = function(){
        var result = new Array();
        for(var i in $scope.featuredArtistePortfolios){
            var castingCall = $scope.featuredArtistePortfolios[i];
            for(var x in castingCall.characters){
                var character = castingCall.characters[x];
                for(var y in character.artistePortfolios){
                    var artistePortfolio = character.artistePortfolios[y];
                    result[result.length] = artistePortfolio;
                }
            }
        }
        
        return result;
    }   
    
 
}
