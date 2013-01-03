var vpc;
function view_portfolio_ctrl($scope, $http) {
    vpc=$scope;
    $scope.portfolio = jsonPortfolio;
    $scope.languageProficiencies = jsonLanguageProficiencies;
    
    //display progress
    setTimeout(function(){
        $('#c3-progress-bar').css('width',vpc.portfolio.completeness+'%');
    },500);
    $scope.getLanguageProficiency = function(id){
        for (i in $scope.languageProficiencies){
            if ($scope.languageProficiencies[i].language_proficiencyid == id) return $scope.languageProficiencies[i].name;
        }
        
        return 'Unregistered';
    }
    
    $scope.getProgressBarClass = function(){
        
        if($scope.portfolio.completeness < 20){
            return "progress-danger";
        } else if ($scope.portfolio.completeness < 50){
            return "progress-warning";
        } else if ($scope.portfolio.completeness < 80){
            return "progress-info";
        }
        
        return "progress-success";
    }
    
    $scope.showComma = function(ind){
        if(ind == 0){
            return false;
        }
        return true;
    }
    
    $scope.sendMessage = function(){
        window.open(baseUrl+'/messages/new?to[]='+$scope.portfolio.userid,'','width=500,height=500');
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
}

function view_videos_ctrl($scope, $http) {
    $scope.portfolio = jsonPortfolio;
    $scope.profilePic = jsonProfilePic;
    $scope.featuredPhotos = jsonFeaturedPhotos;
    $scope.videos = jsonVideos;
    $scope.formModalLoaded=false;
    $scope.open = function(url){
        if(!$scope.formModalLoaded){
            $scope.loadContent('../common/modalSetVideo',function(){
                mInit(function(){
                    c3_m.show(url);
                });
            });
            $scope.formModalLoaded=true;
        }else{
            c3_m.show(url);
        }
    }
    
}