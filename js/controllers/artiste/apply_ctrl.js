var ac;

function apply_ctrl($scope, $http) {
    ac = $scope;
    $scope.artistePortfolio = jsonArtistePortfolio;
    $scope.application = jsonApplication;
    $scope.character = jsonCharacter;
    $scope.castingCall = jsonCastingCall;
    $scope.photoReqs = jsonPhotoReqs;
    $scope.videoReqs = jsonVideoReqs;
    $scope.appPhotos = jsonAppPhotos;
    $scope.appVideos = jsonAppVideos;
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.retPhotos = new Array();
    $scope.retVideos = new Array();
    
    $scope.init = function(){
        
        //prevent enter key from submitting form
        $('form').keypress(function(e){
            if (e.which == 13) {
                var tagName = e.target.tagName.toLowerCase(); 
                if (tagName !== "textarea") {
                    return false;
                }
            }
        });
        
        
        var character = $scope.character;
        character.requirements = new Array();
            
        /*
         *  Populate Gender Requirement
         */
            
        if(typeof character.gender != 'undefined') character.requirements.push({
            name:'Gender',
            data:character.gender
        });
            
        /*
         * Populate Age Requirement
         * 
         */
            
        if(typeof character.age_start != 'undefined' || typeof character.age_end != 'undefined'){
            if(typeof character.age_end != 'undefined'){
                character.requirements.push({
                    name:'Age',
                    data:character.age_start + ' to ' + character.age_end
                });
            } else if (typeof character.age_end == 'undefined') {
                character.requirements.push({
                    name:'Age',
                    data: '> ' + character.age_start
                });
            } else {
                character.requirements.push({
                    name:'Age',
                    data: '< ' + character.age_end
                });
            }
        }
            
        /*
         * Populate language requirement
         * 
         */
            
        if(character.characterLanguages.length > 0){
            var languageData = "";
            for(var x in character.characterLanguages){
                characterLanguage = character.characterLanguages[x];
                var language = $scope.getLanguage(characterLanguage.languageid);
                var languageProficiency = $scope.getLanguageProficiency(characterLanguage.language_proficiencyid);
                languageData += ', '+language.name+' (' + languageProficiency.name+ ')';
            }
            languageData = languageData.substring(1);
            character.requirements.push({
                name:'Languagues',
                data:languageData
            })
        }
            
        /*
         * Populate Nationality Requirement
         * 
         */
            
        if(typeof character.nationality != 'undefined'){
            character.requirements.push({
                name: 'Nationality',
                data: character.nationality
            });
        }
            
        /*
         *  Populate Ethnicity Requirement
         */
            
        if(typeof character.ethnicity.name != 'undefined'){
            character.requirements.push({
                name: 'Ethnicity',
                data: character.ethnicity.name
            });
        }
            
        /*
         *  Populate Skills Requirement
         */
            
        if(character.skills.length > 0){
            var skillString = '';
            for(var i in character.skills){
                skillString += ', ' + character.skills[i].name;
            }
            skillString = skillString.substring(1);
            character.requirements.push({
                name: 'Skills',
                data: skillString
            });
        }
            
        /*
         * Populate Other Requirement
         * 
         */
            
        if(character.otherRequirements.length > 0){
            for (var i in character.otherRequirements){
                character.requirements.push({
                    name:character.otherRequirements[i].requirement,
                    data:character.otherRequirements[i].desc
                });
            }
        }
        
        /*
         *
         * Load Photo and Video attachments / requirements
         * 
         */
        var photoReqs = $scope.photoReqs;
        var appPhotos = $scope.appPhotos;
        var videoReqs = $scope.videoReqs;
        var appVideos = $scope.appVideos;
        
        for (var i in photoReqs){
            //for each photo requirements, check for exisiting attachment
            var found = false;
            for (var j in appPhotos){
                if (photoReqs[i].character_photo_attachmentid == appPhotos[j].character_photo_attachmentid){
                    //if a match is found, create an object with the attachment url
                    $scope.retPhotos.push({
                        id:photoReqs[i].character_photo_attachmentid,
                        attachment:appPhotos[j].photourl,
                        title:photoReqs[i].title,
                        desc:photoReqs[i].desc
                    });
                    found = true;
                }
            }
            //if not found, place empty photo
            if (!found){
                $scope.retPhotos.push({
                    id:photoReqs[i].character_photo_attachmentid,
                    attachment:null,
                    title:photoReqs[i].title,
                    desc:photoReqs[i].desc
                });
            }
        }
        
        ac.$apply();
        
        for (var i in videoReqs){
            //for each photo requirements, check for exisiting attachment
            var found = false;
            for (var j in appVideos){
                if (videoReqs[i].character_video_attachmentid == appVideos[j].character_video_attachmentid){
                    //if a match is found, create an object with the attachment url
                    $scope.retVideos.push({
                        id:videoReqs[i].character_video_attachmentid,
                        attachment:appVideos[j].videourl,
                        title:videoReqs[i].title,
                        desc:videoReqs[i].desc
                    });
                    found = true;
                }
            }
            //if not found, place empty photo
            if(!found){
                $scope.retVideos.push({
                    id:videoReqs[i].character_video_attachmentid,
                    attachment:null,
                    title:videoReqs[i].title,
                    desc:videoReqs[i].desc
                });
            }
        }
         
        ac.$apply();
       
        $("div.apply").click(
            function()
            {
                window.location = $(this).attr("url");
                event.preventDefault();
            });
        
        ac.$apply();
        
       
        
    //End of Init Method
    }
    
    
    $scope.getLanguageProficiency = function(language_proficiencyid){
        for (var i in $scope.languageProficiencies){
            if ($scope.languageProficiencies[i].language_proficiencyid == language_proficiencyid) return $scope.languageProficiencies[i].name;
        }
        
        return null;
    }
    
    $scope.getLanguage = function(languageid){
        for(var i in jsonAllLanguages){
            if(jsonAllLanguages[i].languageid == languageid) return jsonAllLanguages[i];
        }
        return null;
    }
    
    
    /*
     *  Methods for set Featured Photo
     *
     *
     */
    
    $scope.addFeaturedPhoto = function(featuredPhoto,index){

        console.info('<addFeaturedPhoto> - start featuredPhotoid:'+featuredPhoto.photoid);
        console.info('<addFeaturedPhoto> - start featuredPhotoid:'+featuredPhoto.url);
        //find retPhoto where retPhoto.id == x ----
        var retPhoto;
        var retPhotos = $scope.retPhotos;
        for( var i in retPhotos){
            if (retPhotos[i].id == index){
                retPhoto = retPhotos[i];
            }
        }
        retPhoto.attachment = featuredPhoto.url;
        $scope.appPhotos.push({
            photoid:featuredPhoto.photoid
        });
        $scope.$apply();
    //console.info('<addFeaturedPhoto> - end');
    }
    
    $scope.changeFeaturedPhotoInitCallback = function(index){
        params = {};
        params.cropPhoto = false;
        params.submitCallback = function(data){
            var photo = angular.fromJson(data);
            $.post(baseUrl + '/artiste/setApplicationPhoto',{
                ApplicationPhoto: {
                    character_photo_attachmentid : index,
                    photoid : photo.photoid
                }
            },function(){
                ac.addFeaturedPhoto(angular.fromJson(data),index);
                $scope.uploadPhotoClass ='';
                $scope.$apply();
            });
            
        };
        params.closeCallback  = function(){
            $scope.uploadPhotoClass = '';
            $scope.$apply();
        };
        params.order = index;
        sppc.show(params);
    }
    
    
    $scope.uploadPhotoClass = '';
    $scope.changeFeaturedPhoto = function(index){
        
        if($scope.uploadPhotoClass=='disabled'){
            return;
        }
        
        $scope.uploadPhotoClass = 'disabled';
        
        if(typeof sppc === 'undefined'){
            $scope.loadContent('../common/setPhoto',function(){
                sppcInit(function(){
                    ac.changeFeaturedPhotoInitCallback(index);
                });
            });
        } else {
            ac.changeFeaturedPhotoInitCallback(index);
        }
    }
    
    $scope.saveDraft = function(){
        console.info('<saveDraft> - start');
        
        console.info('<saveDraft> - characterid:' + $scope.character.characterid);
        
        $.post(baseUrl + '/artiste/saveApplicationDraft',{
            characterid : $scope.character.characterid
        },function(data){
            data = angular.fromJson(data);
            $scope.$apply();
            processResponse(data);
        });
        console.info('<saveDraft> - end');
       
        console.info('<submitApplication> - data: ' + data);
           
        
    }
    
    $scope.submitApplication = function(){
        
        
        
        $.post(baseUrl + '/artiste/submitApplication',{
            characterid : $scope.character.characterid
        },function(data){
            $scope.$apply();
            console.info('<submitApplication> - data: ' + data);
            data = angular.fromJson(data);
            
            if (data.status == "Saved"){
                window.location = baseUrl + '/artiste/applicationSubmitted';
            }else{
                processResponse(data);
            }
            
        });
       
        
        
        
    }
    $scope.confirmWithdrawApplication = function(){
        c3Confirm({
            header: "Withdraw Application",
            body: "Are you sure you want to withdraw this application?",
            onAccept: function(){
                ac.withdrawApplication();
            }
        });
    }
    
    $scope.withdrawApplication = function(){    
        $.post(baseUrl + '/artiste/withdrawApplication',{
            appid : $scope.application.character_applicationid
        },function(data){
            window.location = baseUrl + '/artiste/viewApplications';
        });
    }
    
    $scope.hasPhotos = function(){
        if ($scope.photoReqs.length > 0){
            return true;
        }
        return false;
    }
    
    $scope.hasVideos = function(){
        if ($scope.videoReqs.length > 0){
            return true;
        }
        return false;
    }
    
    $scope.addVideoCallback = function(){
        svc.show();
    }
    
    $scope.submitVideoCallback = function(data,retVideoid){
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
        $scope.addVideoClass = '';
        $scope.$apply();
        
    }
    
    $scope.editPortfolio = function(){
        c3Confirm({
            'header' : 'Redirect To Edit Portfolio Page',
            'body' : 'We will redirect you to another page to edit your portfolio.<br/> If you would like to return to this page again, click on the <strong>Applications</strong> tab above',
            'onAccept' : function(){
                window.location = baseUrl + '/artiste/editPortfolio'
            }
        });
    }
    
    $scope.addVideoClass = '';
    
    $scope.addVideo = function(id){
        if($scope.addVideoClass == 'disabled'){
            return;
        }
        $scope.addVideoClass = 'disabled';
        var video;
        for(var i in $scope.retVideos){
            if($scope.retVideos[i].id == id){
                video = $scope.retVideos[i];
                break;
            }
        }
        if(typeof svc === 'undefined'){
            $scope.loadContent('../common/setVideo',function(){
                svcInit({
                    onSubmitCallback: function(data){
                        ac.submitVideoCallback(data,id)
                    },
                    onCloseCallback: function(){
                        $scope.addVideoClass = '';
                        $scope.$apply();
                    },
                    videoTitle : video.title,
                    videoDescription : video.desc
                },function(){
                    ac.addVideoCallback()
                });
            });
        } else {
            ac.addVideoCallback();
        }
    }
    
    
    
    
}

