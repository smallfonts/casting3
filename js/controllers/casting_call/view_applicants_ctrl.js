var vac;

function view_applicants_ctrl($scope, $http) {
    vac = $scope;
    $scope.baseUrl = baseUrl;
    $scope.characters = jsonCharacters;
    $scope.languageProficiencies = jsonLanguageProficiencies;
    
    
    //process artistePortfolios to add in character name
    //
    for(var i in $scope.characters){
        for(var x in $scope.characters[i].artistePortfolios){
            $scope.characters[i].artistePortfolios[x].characterName = $scope.characters[i].name;
        }
    }
    
    $scope.castingCall = jsonCastingCall;
    $scope.portfolio = undefined;
    
    $scope.artistePortfolios=new Array();
    $scope.characters.unshift({
        characterid:0,
        name:'ALL'
    });
    
    $scope.selectedCharacter = 0;
    
    $scope.getLanguageProficiency = function(language_proficiencyid){
        for (var i in $scope.languageProficiencies){
            if ($scope.languageProficiencies[i].language_proficiencyid == language_proficiencyid) return $scope.languageProficiencies[i].name;
        }
        
        return null;
    }
    
    $scope.showApplicants = function(){
        $scope.artistePortfolios = new Array();
        if($scope.selectedCharacter == 0){
            //if selectedCharacter == 0, then display all characters
            for(var i in $scope.characters){
                if($scope.characters[i].artistePortfolios != undefined){
                    $scope.artistePortfolios = $scope.artistePortfolios.concat($scope.characters[i].artistePortfolios);
                }
            }
        } else {
            $scope.artistePortfolios = $scope.characters[$scope.selectedCharacter].artistePortfolios;
        }
        $scope.$apply();
        $("[rel=tooltip]").tooltip();
    }
    
    $scope.init = function(){
        $scope.showApplicants();
    }
    
    $scope.sendMessage = function(userid){
        window.open(baseUrl+'/messages/new?to[]='+userid,'','width=500,height=500');
    }
    
    $scope.viewPortfolio = function(){
        window.open(baseUrl+'/artiste/portfolio/'+$scope.portfolio.url);
    }
    
    $scope.toggleFavourite = function(artiste_portfolioid){
        var action = '';
        var artisteFound = false;
        for(var i in $scope.characters){
            for(var x in $scope.characters[i].artistePortfolios){
                var artistePortfolio = $scope.characters[i].artistePortfolios[x];
                if(artistePortfolio.artiste_portfolioid == artiste_portfolioid){
                    if(artistePortfolio.isFavourite){
                        artistePortfolio.isFavourite = false;
                        action = 'delete';
                    } else {
                        artistePortfolio.isFavourite = true;
                        action = 'add';
                    }
                    
                    artisteFound = true;
                }
            }
        }
        
        if(artisteFound){
            $.post(baseUrl+'/common/setFavouriteArtistePortfolio',{
                'FavouriteArtistePortfolio' : {
                    'artiste_portfolioid' : artiste_portfolioid,
                    'action' : action
                }
            });
        }
    }
    
    $scope.showPortfolio = function(characterid,artiste_portfolioid){
        for(var i in $scope.characters){
            if($scope.characters[i].characterid == characterid){
                var artistes = $scope.characters[i].artistePortfolios;
                for(var x in artistes){
                    if(artistes[x].artiste_portfolioid == artiste_portfolioid){
                        $scope.portfolio = artistes[x];
                        
                        
                        $('#c3rating').rating({
                            onClick : function(data){
                                $scope.portfolio.characterApplication.rating = data;
                            },
                            maxvalue : 5,
                            curvalue : $scope.portfolio.characterApplication.rating,
                        });
                        
                        
                        return
                    }
                }
            }
        }
            
    }
    
    $scope.saveFeedback = function(){
        $.post(baseUrl+'/audition/saveFeedback',{
            'CharacterApplication':{
                'character_applicationid': $scope.portfolio.characterApplication.character_applicationid,
                'comments':$scope.portfolio.characterApplication.comments,
                'rating' : $scope.portfolio.characterApplication.rating
            }
        },function(data){
            data = angular.fromJson(data);
            processResponse(data);
        });
    }
    
    
    
    
}
