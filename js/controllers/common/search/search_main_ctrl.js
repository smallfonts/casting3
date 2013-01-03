/* 
 * To change this template, choose Tools | Template
 * and open the template in the editor.
 */

var smc;
var test;
var ssrc;

//container to store search criteria
function SearchQuery(data){
    
    if(data){
        this.ArtistePortfolio = data.ArtistePortfolio;
        this.Character = data.Character;
        this.verify = data.verify;  
    } else {
        this.ArtistePortfolio = {};
        this.Character = {};
        this.verify = '1234qwer';     
    }
}

function search_main_ctrl($scope, $http){
    
    smc = $scope;
    
    //role will determine which are the default search type and search filters
    $scope.roleid = roleid;
    
    //c3Timer to regular query frequency
    $scope.smcTimer = new c3Timer({
        timeout:700 //frequency of search (every 700ms)
    });
    
    //favourites
    $scope.favouriteArtistePortfolios = favouriteArtistePortfolios;
    $scope.favouriteCharacters = favouriteCharacters;
    
    //results count (displayed on pills)
    $scope.resultCount = "";
    $scope.favouritesCount = "";
    
    //loading display settings
    $scope.smcFeaturedLoading = true;
    $scope.smcSuggestedLoading = true;
    $scope.smcResultsLoading = true;
    $scope.smcQuerying = false;
    
    $scope.searchQuery = new SearchQuery();
    $scope.searchTypes = new Array(
    {
        name:'Casting Calls',
        value:'1'
    },

    {
        name:'Artistes',
        value:'4'
    }
    );
    
    /*
     * Query Methods
     * 
     */
    
    $scope.removeQuery = function(model,attribute){
        delete $scope.searchQuery[model][attribute];
        
        var hasQuery = false;
        for(var i in $scope.searchQuery){
            if(i != 'verify'){
                for (var x in $scope.searchQuery[i]){
                    hasQuery = true;
                    break;
                }
            }
        }
        
        if(hasQuery){
            //serializes json to url hash
            
            location.hash = angular.toJson($scope.searchQuery);
        } else {
            location.hash = "";
        }
    }
    
    //sets query criteria to searchQuery object
    $scope.setQuery = function(opts){
        //console.info('setting query');
        var model = opts.model;
        var attribute = opts.attribute;
        var value = opts.value;
        var page = opts.page;
        
        if(model){
            switch(model){
                case 'ArtistePortfolio':
                    $scope.searchQuery.ArtistePortfolio[attribute] = value;
                    break;
                case 'Character':
                    $scope.searchQuery.Character[attribute] = value;
                    break;
            }
        }
        var isScroll = true;
        if(typeof page == 'undefined'){
            page = 0;
            isScroll = false;
        }
        
        $scope.query(
        {
            ArtistePortfolio : $scope.searchQuery.ArtistePortfolio,
            Character : $scope.searchQuery.Character,
            Page : page
        },isScroll
        );
            
        //serializes json to url hash
        location.hash = angular.toJson($scope.searchQuery);
        
    }
    
    
    
    //content from partials
    $scope.featuredContents = '1';
    $scope.favouriteContents;
    $scope.resultContents;
    
    //Default Values displayed when artiste/production house user visists search page
    //rendered by $scope.init() function
    $scope.defaults = {
        //The default selected search
        searchMain:'',
        searchDropdown:new Array()
    }
    
    /*
     * Init Method
     * 
     */
    
    $scope.init = function(){
        
        //determine default values
        //clones an object
        $scope.defaults.searchDropdown = $scope.searchTypes.slice(0);
        switch($scope.roleid){
            case 1:
                $scope.defaults.searchMain = $scope.searchTypes[0];
                $scope.defaults.searchDropdown.splice(0,1);
                
                $scope.loadContent(baseUrl + '/common/castingCallSearchFilters',function(){
                    ccsfInit();    
                },'smcFilters');
                break;                
                
            case 2: case 4:
                $scope.defaults.searchMain = $scope.searchTypes[1];
                $scope.defaults.searchDropdown.splice(1,1);
                $scope.loadContent(baseUrl + '/common/artistePortfolioSearchFilters',function(){
                    apsfInit();
                },'smcFilters');
                
                break;
        }

        
        if(location.hash != ""){

            try {
                $scope.searchQuery = new SearchQuery(angular.fromJson(unescape(location.hash.substr(2))));
            } catch(err){
                //console.info(err);
            }

            if($scope.searchQuery.verify || $scope.searchQuery.verify=='1234qwer'){
                $scope.query(
                {
                    ArtistePortfolio : $scope.searchQuery.ArtistePortfolio,
                    Character : $scope.searchQuery.Character
                }
                );
            }
        } else {
            //load suggested results if no search query was made
            $scope.loadSuggested();   
        }
    }
    
    $scope.loadSuggested = function(){
        //console.info('Loading Suggested');
        $scope.smcFeaturedLoading = true;
        $scope.loadContent(baseUrl + '/common/searchSuggested',function(){
            sscScriptLoader = undefined;
            sscInit(function(){
                smc.smcFeaturedLoading = false;
                smc.$apply();
            });
        },'smcFeatured');
    }
    
    $scope.smcLatestLoading = false;
    $scope.loadLatest = function(){
        $scope.smcLatestLoading = true;
        $scope.loadContent(baseUrl + '/common/searchLatest',function(){
            slcScriptLoader = undefined;
            slcInit(function(){
                smc.smcLatestLoading = false;
                smc.$apply();
            });
        },'smcLatest');
    }
    
    $scope.loadFavourites = function(){
        //console.info('Loading Favourites');
        $scope.smcFavouritesLoading = true;
        $scope.loadContent(baseUrl + '/common/favourites',function(){
            fscInit(function(){
                smc.smcFavouritesLoading = false;
                smc.$apply();
            });
        },'smcFavourites');
    }
    
    //get query results
    $scope.searchResultsInit = false;
    $scope.loadSearchResults = function(){
        if ($scope.searchResultsInit) src.$apply();
    }
    
    $scope.lastQuery = undefined;
    $scope.query = function(params,isScroll){
        
        //check if timer has passed its timeout from the last query
        if($scope.smcTimer.ready){
            
            //starts the timer
            $scope.smcTimer.start();
            
            if(typeof $scope.lastQuery == 'undefined'){
                $scope.lastQuery = params
            } else if($scope.lastQuery == params) {
                return;
            } else {
                $scope.lastQuery = params;
            }
            
            //console.info('isQuerying');
            
            $('#c3_smc_tab a[href="#smcResultsTab"]').tab('show');
            $scope.smcQuerying = true;
            $scope.smcResultsLoading = true;
            if(params == '') {
                if(typeof src != 'undefined'){
                    src.setResults();
                    $scope.resultCount = 0;
                }
                return;
            }
        
            $.post(baseUrl + '/common/query',params,function(data){
                data = angular.fromJson(data);
                if(typeof src == 'undefined'){
                    if($scope.srcLoading) return;
                    $scope.srcLoading = true;
                    $scope.loadContent(baseUrl + '/common/searchResults',function(){
                        $scope.srcLoading = false;
                        $scope.resultCount = " ("+data.results.length+")";
                        srcInit(function(){
                            src.setResults(data,isScroll);
                            smc.smcResultsLoading = false;
                            smc.$apply();
                        });
                    },'smcResults');
                } else {
                    smc.smcResultsLoading = false;
                    smc.$apply();
                    src.setResults(data,isScroll);
                }
            
            });
            
        } else {
            //execute query method when the timeout expires
            $scope.smcTimer.onExpire = function(){
                smc.query(params,isScroll);
            }
        }
        
        
    }
    
    /*
     * Favourite Methods
     * 
     */
    
    $scope.setFavouriteArtistePortfolio = function(artiste_portfolioid,isFavourite){
        action = isFavourite ? 'add' : 'delete';
        obj = {
            FavouriteArtistePortfolio: {
                artiste_portfolioid: artiste_portfolioid,
                action: action
            }  
        };
        $.get($scope.baseUrl+'/common/setFavouriteArtistePortfolio?'+$.param(obj));
    }
    
    //returns an artiste_portfolio object which has a "is_favourite" attribute
    $scope.bindArtistePortfolioFavourite = function(id) {
        for (var bapf in $scope.favouriteArtistePortfolios){
            if($scope.favouriteArtistePortfolios[bapf]['artiste_portfolioid'] == id) {
                if(typeof $scope.favouriteArtistePortfolios[bapf]['isFavourite'] != 'undefined'){
                    return $scope.favouriteArtistePortfolios[bapf];
                } else {
                    $scope.favouriteArtistePortfolios[bapf]['isFavourite']=true;
                    return $scope.favouriteArtistePortfolios[bapf];
                }
            }
        }

        //if artiste is not found in favouriteArtistePortfolios.. it means it has not been "favourited"
        //create a new object to represent favourite object
        favouriteArtistePortfolioObj = {
            artiste_portfolioid : id,
            isFavourite : false
        }
        
        //add to array for future references
        $scope.favouriteArtistePortfolios[$scope.favouriteArtistePortfolios.length] = favouriteArtistePortfolioObj;
        return favouriteArtistePortfolioObj;
    }
    
    $scope.setFavouriteCharacter = function(characterid, isFavourite){
        action = isFavourite ? 'add' : 'delete';
        obj = {
            FavouriteCharacter: {
                characterid: characterid,
                action: action
            }  
        };
        $.get($scope.baseUrl+'/common/setFavouriteCharacter?'+$.param(obj));
    }
    
    $scope.bindCharacterFavourite = function(id){
        for (var bcf in $scope.favouriteCharacters){
            if($scope.favouriteCharacters[bcf]['characterid'] == id) {
                if(typeof $scope.favouriteCharacters[bcf]['isFavourite'] != 'undefined'){
                    return $scope.favouriteCharacters[bcf];
                } else {
                    $scope.favouriteCharacters[bcf]['isFavourite']=true;
                    return $scope.favouriteCharacters[bcf];
                }
            }
        }
        
        //if character is not found, it means it has not been "favourited"
        //create a new object to represent the favourite object
        favouriteCharacter = {
            characterid: id,
            isFavourite:false
        }
        
        $scope.favouriteCharacters[$scope.favouriteCharacters.length] = favouriteCharacter;
        return favouriteCharacter;
    }
    
    $scope.applyAll = function(ctrl){
        if(ctrl != 'src' && typeof src != 'undefined') src.$apply();
        if(ctrl != 'ssc' && typeof ssc != 'undefined') ssc.$apply();
    }
    
    $scope.invite = function(artiste_portfolioid,artiste_name,photoUrl){
        if(typeof iac === 'undefined'){
            $scope.loadContent(baseUrl + '/castingmanager/inviteArtistePage',function(){
                iacInit({
                    artiste_portfolioid: artiste_portfolioid,
                    name : artiste_name,
                    photoUrl: photoUrl
                },function(){
                    iac.show();
                });
            });
        } else {
            iac.setParams({
                artiste_portfolioid: artiste_portfolioid,
                name: artiste_name,
                photoUrl: photoUrl
            });
            iac.show();
        }
    }
 
    if(smc.searchQuery.ArtistePortfolio.sortAttr){
        $scope.sortAttr = smc.searchQuery.ArtistePortfolio.sortAttr;
    }
    if(smc.searchQuery.Character.sortAttr){
        $scope.sortAttr = smc.searchQuery.Character.sortAttr;
    }
    
    $scope.sendMessage = function(userid){
        window.open(baseUrl+'/messages/new?to[]='+userid,'','width=500,height=500');
    }

    $scope.sortResult = function(){
        if($('#sort_expDesc').attr("selected") == "selected" && $('#sort_expDesc').attr("selected") != "undefined"){
            $scope.sortAttr = new Array();
            $scope.sortAttr[$scope.sortAttr.length] = "years_of_experience";
            $scope.sortAttr[$scope.sortAttr.length] = "desc";
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'sortAttr',
                value : smc.sortAttr
            });
            //console.info("experience clicked " + $scope.sortAttr);
        } else if ($('#sort_expAsc').attr("selected") == "selected" && $('#sort_expAsc').attr("selected") != "undefined"){
            $scope.sortAttr = new Array();
            $scope.sortAttr[$scope.sortAttr.length] = "years_of_experience";
            $scope.sortAttr[$scope.sortAttr.length] = "asc";
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'sortAttr',
                value : smc.sortAttr
            });
            //console.info("experience clicked " + $scope.sortAttr);
        }else if ($('#sort_nameDesc').attr("selected") == "selected" && $('#sort_nameDesc').attr("selected") != "undefined"){
            $scope.sortAttr = new Array();
            $scope.sortAttr[$scope.sortAttr.length] = "name";
            $scope.sortAttr[$scope.sortAttr.length] ="desc";
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'sortAttr',
                value : smc.sortAttr
            });
            //console.info("name clicked " + $scope.sortAttr);
        }else if ($('#sort_nameAsc').attr("selected") == "selected" && $('#sort_nameAsc').attr("selected") != "undefined"){
            $scope.sortAttr = new Array();
            $scope.sortAttr[$scope.sortAttr.length] = "name";
            $scope.sortAttr[$scope.sortAttr.length] ="asc";
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'sortAttr',
                value : smc.sortAttr
            });
            //console.info("name clicked " + $scope.sortAttr);
        }
        else{
            smc.removeQuery('ArtistePortfolio','sortAttr');
        }
    }
    
    $('#sort_options_artistes').change(function(data){
        smc.sortResult(data);
    });

}
