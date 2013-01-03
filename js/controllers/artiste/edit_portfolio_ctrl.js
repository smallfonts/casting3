var epc;
function edit_portfolio_ctrl($scope, $http) {
    epc = $scope;
    $scope.portfolio = jsonPortfolio;
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.allLanguagesObj = jsonAllLanguages;
    $scope.dataLanguages = new Array();
    
    $scope.dob = {};
    $scope.dob.days = ['Day',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
    $scope.dob.months = [
        {val:-1,name:'Month'},
        {val:0,name:'Jan'},
        {val:1,name:'Feb'},
        {val:2,name:'Mar'},
        {val:3,name:'Apr'},
        {val:4,name:'May'},
        {val:5,name:'Jun'},
        {val:6,name:'Jul'},
        {val:7,name:'Aug'},
        {val:8,name:'Sep'},
        {val:9,name:'Oct'},
        {val:10,name:'Nov'},
        {val:11,name:'Dec'},
    ];
    //generate years
    $scope.curDate = new Date();
    var maxAge = 120;
    var ageYear = $scope.curDate.getFullYear();
    $scope.dob.years = ['Year'];
    while($scope.curDate.getFullYear() - ageYear <= maxAge){
        $scope.dob.years[$scope.dob.years.length] = ageYear;
        ageYear--;
    }
    
    
    $scope.init = function(){
        
        //checks whether server is reachable
        //notifies user when connection is broken
        //code located at /js/lib/check_alive/check_alive.js
        //template located at layouts/main.php
        //c3CheckAlive();
        
        
        
         //check if there's a need to show guides and overlays
        if ($scope.portfolio.portfolio_guide == 1){
            var imagesArray = ["edit_portfolio_1.png", "edit_portfolio_2.png", "edit_portfolio_3.png", "edit_portfolio_4.png", "edit_portfolio_5.png", "edit_portfolio_6.png", "edit_portfolio_7.png"];
            displayGuide(imagesArray);
        }
        
        
        //prevent enter key from submitting form
        $('form').keypress(function(e){
            if (e.which == 13) {
                var tagName = e.target.tagName.toLowerCase(); 
                if (tagName !== "textarea") {
                    return false;
                }
            }
        });
        
        //check birthdate
        $scope.dob.date = stringToDate($scope.portfolio.dob,'yyyy-mm-dd');
        if(typeof $scope.dob.date != 'undefined'){
            $scope.dob.selectedDay = $scope.dob.date.getDate();
            $scope.dob.selectedMonth = $scope.dob.date.getMonth();
            $scope.dob.selectedYear = $scope.dob.date.getFullYear();
        } else {
            $scope.dob.selectedDay='Day';
            $scope.dob.selectedMonth=-1;
            $scope.dob.selectedYear='Year';
        }
        
        //initialize select2 for nationality
        $('#nationality').select2({
            'data' : countries,
            initSelection: function(element,callback){
                selection = { 
                    id : element.val(),
                    text : element.val()
                };
                callback(selection);
            }
        });
        
        if($scope.portfolio.nationality){
            $('#nationality').val($scope.portfolio.nationality).trigger('change');
        }
        
        /*
         *  Initialization for spoken languages input
         *
         */
        
        //populate languages
        for (var i in $scope.allLanguagesObj){
            for (var x in $scope.languageProficiencies){
                $scope.dataLanguages[$scope.dataLanguages.length] = {
                    id: $scope.allLanguagesObj[i]["languageid"] + ":" + $scope.languageProficiencies[x]["language_proficiencyid"],
                    name: $scope.allLanguagesObj[i]["name"],
                    languageid: $scope.allLanguagesObj[i]["languageid"],
                    language_proficiencyid: $scope.languageProficiencies[x]["language_proficiencyid"],
                    text:$scope.allLanguagesObj[i]["name"] + " ("+$scope.languageProficiencies[x]["name"]+")"
                }
            
            }
        }

        
        $('#epLanguages').select2({
            multiple:true,
            data: $scope.dataLanguages,
            initSelection: function(element,callback){
                selections = element.val().split(',');
                selectedData = new Array();
                for (i in selections){
                    idInfo = selections[i].split(':');
                    languageId = parseInt(idInfo[0]);
                    proficiencyId = parseInt(idInfo[1]);
                    index = (epc.languageProficiencies.length * (languageId - 1)) + (proficiencyId - 1)
                    selectedData.push(epc.dataLanguages[index]);
                }
                
                callback(selectedData);
            }
        });
        
        //populate existing languages
        //
        existingLanguages = ""
        for(i in $scope.portfolio.languages){
            curLanguage = $scope.portfolio.languages[i];
            existingLanguages += "," + curLanguage.languageid + ":" + curLanguage.language_proficiencyid
        }
        existingLanguages = existingLanguages.substring(1);
        $('#epLanguages').val(existingLanguages).trigger('change');
        
        //onchange event handler
        $('#epLanguages').change(function(data){
            if(data.added){
                if(epc.spokenLanguageExists(data.added.languageid)){
                    epc.editLanguage(data.added);
                    selectedLanguages = $('#epLanguages').val();
                    selectedLanguages = selectedLanguages.substring(0,selectedLanguages.length-(data.added.languageid.length+3));
                    selectedLanguageIndex = selectedLanguages.indexOf(data.added.languageid+":");
                    selectedLanguage = selectedLanguages.substring(selectedLanguageIndex,selectedLanguageIndex+(data.added.languageid.length+2));
                    selectedLanguages = selectedLanguages.replace(selectedLanguage,data.added.languageid+":"+data.added.language_proficiencyid);
                    setTimeout(function(){
                        $('#epLanguages').val(selectedLanguages).change();
                    },1);
     
                } else {
                    epc.addLanguage(data.added);
                    
                }
            } else if (data.removed){
                epc.deleteLanguage(data.removed);
            }
            
        });
        
        
        /*
         *  Initialization for profession input
         *
         */
        
        $('#epProfessions').select2({
            multiple:true,
            minimumInputLength: 3,
            createSearchChoice:function(term, data) {
                if ($(data).filter(function() {
                    return this.text.localeCompare(term)===0;
                }).length===0) {
                    return {
                        id:term, 
                        name:term, 
                        text:term
                    };
                
                }
            },
            initSelection: function(element,callback){
                professionids = element.val().split(',');
                query = {
                    Profession: {
                        professionid: professionids
                    }
                }
                
                $.get(baseUrl + "/common/getProfessions?" + $.param(query),function(data){
                    data = angular.fromJson(data);
                    for(i in data){
                        data[i].id = data[i].professionid;
                        data[i].text = data[i].name;
                    }
                    callback(data);
                });
                
            },
            ajax : {
                url: baseUrl + "/common/getProfessions",
                dataType: 'json',
                quietMillis: 200,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        Profession : {
                            name : term
                        }
                    };
                },
                results: function (data, page) {
                    
                    for(i in data){
                        data[i].id = data[i].professionid;
                        data[i].text = data[i].name;
                    }
                    
                    return {
                        results: data
                    };
                }
            }
        });
        
        existingProfessions = '';
        for (i in $scope.portfolio.professions){
            existingProfessions += ","+$scope.portfolio.professions[i]['professionid'];
        }
        
        existingProfessions = existingProfessions.substring(1);
        $('#epProfessions').val(existingProfessions).trigger('change');
        
        
        //onchange event handler
        $('#epProfessions').change(function(data){
            if(data.added){
                epc.addProfession(data.added);
            } else if (data.removed){
                epc.deleteProfession(data.removed);
            }
            
        });
        
        /*
         *  Initialization for ethnicity input
         *
         *
         *
         */
        
        
        $('#epEthnicity').select2({
            multiple:false,
            allowClear: true,
            createSearchChoice: function(term, data) { 
                if ($(data).filter(function() {
                    return this.text.localeCompare(term)===0;
                }).length===0) {
                    return {
                        id:term, 
                        text:term
                    };
                } 
            },
            initSelection: function(element,callback){
                var results = {
                    id : $scope.portfolio.ethnicity.ethnicityid,
                    text : $scope.portfolio.ethnicity.name
                }
                callback(results);
            },
            ajax : {
                url: baseUrl + "/common/getEthnicities",
                dataType: 'json',
                quietMillis: 200,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        Ethnicity : {
                            name : term
                        }
                    };
                },
                results: function (data, page) {
                    var results = new Array();
                    for(var i in data){
                        results.push({
                            'id': data[i].ethnicityid,
                            'text' : data[i].name
                        });
                    }
                    return {
                        results: results
                    };
                }
            }
        });
        
        
        //onchange event handler
        $('#epEthnicity').on('change',function(data){
            //test if data.val is integer. if so, then data.val refers to ethnicityid
            var ethnicityid = parseInt(data.val);
            if(!isNaN(ethnicityid)){
                $('#epcEthnicityid').val(ethnicityid);
                $('#epcNewEthnicity').val('');
            } else {
                $('#epcEthnicityid').val('');
                $('#epcNewEthnicity').val(data.val);
            }
            epc.$apply();
        });
        
        if($scope.portfolio.ethnicity){
            $('#epEthnicity').val('dummy').change();
            $('#epcEthnicityid').val($scope.portfolio.ethnicity.ethnicityid);
        }
        
        
        /*
         *  Initialization for skills input
         *
         */
        
        $('#epSkills').select2({
            multiple:true,
            minimumInputLength: 3,
            createSearchChoice: function(term, data) { 
                if ($(data).filter(function() {
                    return this.text.localeCompare(term)===0;
                }).length===0) {
                    if(term.toLowerCase() != 'martial arts' && term.toLowerCase() != 'driving') return {
                        id:term, 
                        name:term, 
                        text:term
                    };
                } 
            },
            initSelection: function(element,callback){
                skillids = element.val().split(',');
                query = {
                    Skill: {
                        skillid: skillids
                    }
                }
                
                $.get(baseUrl + "/common/getSkills?" + $.param(query),function(data){
                    data = angular.fromJson(data);
                    results = new Array();
                    for(i in data){
                        if(data[i].skillid != 1 && data[i].skillid != 2){
                            results.push({
                                'skillid' : data[i].skillid,
                                'id': data[i].skillid,
                                'text' : data[i].name,
                                'name' :  data[i].name
                            });
                        }
                    }
                    callback(results);
                });
                
            },
            ajax : {
                url: baseUrl + "/common/getSkills",
                dataType: 'json',
                quietMillis: 200,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        Skill : {
                            name : term
                        }
                    };
                },
                results: function (data, page) {
                    results = new Array();
                    for(i in data){
                        //supress skillid 1 and 2
                        if(data[i].skillid != 1 && data[i].skillid != 2){
                            results.push({
                                'skillid' : data[i].skillid,
                                'id': data[i].skillid,
                                'text' : data[i].name,
                                'name' :  data[i].name
                            });
                        }
                    }
                    
                    return {
                        results: results
                    };
                }
            }
        });
        
        existingSkills = '';
        for (i in $scope.portfolio.skills){
            existingSkills += ","+$scope.portfolio.skills[i]['skillid'];
        }
        existingSkills = existingSkills.substring(1);
        $('#epSkills').val(existingSkills).trigger('change');
        
        
        //onchange event handler
        $('#epSkills').change(function(data){
            if(data.added){
                epc.addSkill(data.added);
            } else if (data.removed){
                epc.deleteSkill(data.removed);
            }
            
        });
        
        //hover status for change profile pic and video
        $('#epc_profileVideo').hoverStatus('<h6><i class="icon-pencil"></i> Change Video</h6>',{persistent:true});
        $('#c3_profilepic').hoverStatus('<h6><i class="icon-pencil"></i> Change Profile Pic</h6>',{persistent:true});
        $('#c3_profilepic').tooltip({
            placement:"bottom"
        });
        $('.featuredPhotos').hoverStatus('<h6><i class="icon-pencil"></i></h6>',{persistent:true});
       
        $('#urlTip').tooltip({
            placement:"right"
        });      
        
       
        $scope.$apply();
    //End of Init Method
    }
    
    $scope.setDOB = function(){
        if($scope.dob.selectedDay != 'Day' && $scope.dob.selectedMonth != -1 && $scope.dob.selectedYear != 'Year'){
            var tmpDate = new Date();
            tmpDate.setDate($scope.dob.selectedDay);
            tmpDate.setMonth($scope.dob.selectedMonth);
            tmpDate.setYear($scope.dob.selectedYear);
            $scope.portfolio.dob = dateToString(tmpDate,'yyyy-mm-dd');
            $('#portfolioDOB').attr('value',$scope.portfolio.dob);
        } else {
            $('#portfolioDOB').attr('value','');
        }
    }
    
    /*
     *  Methods for set Featured Photo
     *
     *
     */
    
    $scope.addFeaturedPhoto = function(featuredPhoto,index){
        
        //console.info('<addFeaturedPhoto> - start featuredPhotoid:'+featuredPhoto.photoid);
        $scope.portfolio.featuredPhotos[index] = featuredPhoto;
        $('#featuredPhoto'+index).loading(false);
        $scope.$apply();
    //console.info('<addFeaturedPhoto> - end');
    }
    
    $scope.changeFeaturedPhotoInitCallback = function(index){
        params = {};
        params.cropPhoto = false;
        params.submitCallback = function(data){
            var photo = angular.fromJson(data);
            $.post(baseUrl + '/common/setFeaturedPhoto',{
                ArtistePortfolioPhoto: {
                    photoid : photo.photoid,
                    order : index
                }
            },function(){
                epc.addFeaturedPhoto(angular.fromJson(data),index); 
                $('#featuredPhoto'+index).loading(false);
            });
        };
        params.closeCallback = function(){
            $('#featuredPhoto'+index).loading(false);
        };
        params.order = index;
        sppc.show(params);
    }
    
    $scope.changeFeaturedPhoto = function(index){
        $('#featuredPhoto'+index).loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent('../common/setPhoto',function(){
                    sppcInit(function(){
                        epc.changeFeaturedPhotoInitCallback(index);
                    });
                });
            } else {
                epc.changeFeaturedPhotoInitCallback(index);
            }
        
        });
    }
    
    
    
    //loads modal to change profile pic
    //view: '../common/setPhoto'
    //
    //

    $scope.changeProfilePicInitCallback = function(){
        params = {};
        params.cropPhoto = true;
        params.submitCallback = function(data){
            console.info(data);
            var photo = angular.fromJson(data);
            $.post(baseUrl + '/common/setProfilePic',{
                Photo: {
                    photoid: photo.photoid
                }
            },function(){
                if(data) epc.portfolio.photoUrl = photo.url;
                $('#c3_profilepic').loading(false);
                epc.$apply(); 
            });
        };
        params.closeCallback = function(){
            $('#c3_profilepic').loading(false);
        };
        sppc.show(params);
    }
    
    $scope.changeProfilePic = function(){
        $('#c3_profilepic').loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent('../common/setPhoto',function(){
                    sppcInit(function(){
                        epc.changeProfilePicInitCallback();
                    });
                });
            } else {
                epc.changeProfilePicInitCallback();
            }
        });
    }
    
    /*
    * Methods for Languages
    *
    */
    
    $scope.addLanguage = function(obj) {
        $http.get(baseUrl+'/artiste/addSpokenLanguage/'+angular.toJson(obj));
        epc.portfolio.languages.push(obj);
    }
    
    $scope.editLanguage = function(obj) {
        $http.get(baseUrl+'/artiste/editSpokenLanguage/'+angular.toJson(obj));
    }
    
    $scope.deleteLanguage = function(obj) {     
        $http.get(baseUrl+'/artiste/deleteSpokenLanguage/'+angular.toJson(obj));
        for (i in $scope.portfolio.languages){
            if ($scope.portfolio.languages[i].languageid == obj.languageid){
                $scope.portfolio.languages.splice(i,1);
                break;
            }
        }
    }
    
    $scope.spokenLanguageExists = function(languageid){
        for(i in epc.portfolio.languages){
            if(epc.portfolio.languages[i].languageid == languageid) return true;
        }
        return false;
    }
    
    /*
    * 
    * methods for skills
    * 
    */
   
    $('.toggleSkillsCheckbox').click(function(){
        if($(this).attr("checked")){
            $scope.addSkill($(this).attr("value"));
        } else {
            $scope.deleteSkill($(this).attr("value"));
        }
    });
    
    
    $scope.addSkill=function(obj){
        $.post(baseUrl+'/artiste/addSkill/',{
            Skill: obj
        });
    };
    
    $scope.deleteSkill=function(obj){
        $.post(baseUrl+'/artiste/deleteSkill/',{
            Skill: obj
        });
    }
    
    /*
    * 
    * methods for professions
    * 
    */
    
    
    $scope.addProfession=function(obj){
        $.post(baseUrl+'/artiste/addProfession',{
            Profession: obj
        });
    };
    
    
    $scope.deleteProfession=function(obj){
        $.post(baseUrl+'/artiste/deleteProfession',{
            Profession: obj
        });
    }
    
    
    $scope.addVideoCallback = function(){
        svc.show();
    }
    
    $scope.submitVideoCallback = function(data){
        $('#epc_profileVideo').loading(false);
        $scope.portfolio.video.videoid = data.videoid;
        $scope.portfolio.video.url = data.url;
        
        $.post(baseUrl+'/artiste/setFeaturedVideo',{
            Video:data
        });
        
        $scope.$apply();
    }
    
    $scope.isFirstTime = function(){
        return $scope.portfolio.portfolio_guide == 1;
    }
    
    
    $scope.changeProfileVideo = function(){
        $('#epc_profileVideo').loading(true,function(){
            if(typeof svc === 'undefined'){
                $scope.loadContent('../common/setVideo',function(){
                    svcInit({
                        onSubmitCallback: function(data){
                            epc.submitVideoCallback(data)
                        },
                        onCloseCallback: function(){
                            $('#epc_profileVideo').loading(false);
                        },
                        videoTitle : 'My Profile Video',
                        videoDescription : 'My Profile Video Description'
                    },function(){
                        epc.addVideoCallback()
                    });
                });
            } else {
                epc.addVideoCallback();
            }
        });
    }
    
}

