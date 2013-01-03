var eccc;

function getParameterByName(name,getParam)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(getParam);
    if(results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}


/*
 * Character Class
 *
 */
function Character(params){
    
    if(typeof params != 'undefined'){
        this.data = params.data;
        this.id = this.data.characterid;
        this.errors = params.errors;
    }
    
    if(typeof this.data.skills == 'undefined'){
        this.data.skills = new Array();
    } else if(this.data.skills.length > 0){
        this.skillRequirement = true;
    }
        
    if(typeof this.data.characterLanguages == 'undefined'){
        this.data.characterLanguages = new Array();
    } else if (this.data.characterLanguages.length > 0){
        this.languageRequirement = true;
    }
        
    if(typeof this.data.ethnicity == 'undefined'){
        this.data.ethnicity = new Array();
    } else if(typeof this.data.ethnicity.ethnicityid != 'undefined') {
        this.ethnicityRequirement = true;
    }
        
    if(typeof this.data.videoAttachments == 'undefined'){
        this.data.videoAttachments = new Array()
    } else if(this.data.videoAttachments.length > 0){
        this.videoRequirement = true;
    }
        
    if(typeof this.data.photoAttachments == 'undefined'){
        this.data.photoAttachments = new Array()
    } else if(this.data.photoAttachments.length > 0){
        this.photoRequirement = true;
    }
    
    //compile other requirements
    if(typeof this.data.otherRequirements == 'undefined'){
        this.data.otherRequirements = new Array();
    } else {
        for(var i in this.data.otherRequirements){
            this.data.otherRequirements[i].exists = true;
        }
    }
        
    if(typeof this.data.age_start != 'undefined') this.ageRequirement = true;
    if(typeof this.data.gender != 'undefined') this.genderRequirement = true;
    if(typeof this.data.nationality != 'undefined') this.nationalityRequirement = true;
    
    
    this.init = function(){
        //initialize select2 inputs
        eccc.initEthnicityInput('#accEthnicity_'+this.id,this.data);
        eccc.initSkillInput('#accSkill_'+this.id,this.data.characterid);
        eccc.initNationalityInput('#accNationality_'+this.id,this.data,'nationality');
        eccc.initLanguageInput('#accLanguages_'+this.id,this.data.characterid);
    }
    
}

function edit_casting_call_ctrl($scope, $http) {
    eccc = $scope;
    $scope.baseUrl = baseUrl;
    $scope.castingCall = jsonCastingCall;
    $scope.castingManagerPortfolio = castingManagerPortfolio;
    $scope.characters = new Array();
    
    //Language input
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.allLanguagesObj = jsonAllLanguages;
    $scope.dataLanguages = new Array();
    
    
    $scope.init = function(){
        $('#charInfo').tooltip({placement:'top',delay: { show: 500, hide: 100 } });
        
        $('#reqAttch').tooltip({
            placement:"top", delay: { show: 500, hide: 100 }
        });
        
        $('#addReqAttch').tooltip({
            placement:"left", delay: { show: 500, hide: 100 }
        });
        
        $('#c3_profilepic').hoverStatus('<h6><i class="icon-pencil"></i> Change Profile Pic</h6>');
        
        //checks whether server is reachable
        //notifies user when connection is broken
        //code located at /js/lib/check_alive/check_alive.js
        //template located at layouts/main.php
        c3CheckAlive();
        
        //prevent enter key from submitting form
        $('form').keypress(function(e){
            if (e.which == 13) {
                var tagName = e.target.tagName.toLowerCase(); 
                if (tagName !== "textarea") {
                    return false;
                }
            }
        });
        
        //date logic for application_start and end
        initDatePeriod(eccc.castingCall.data,'application_start','application_end');
        //date logic for audition start and end
        initDatePeriod(eccc.castingCall.data,'audition_start','audition_end');
        //date logic for project start end
        initDatePeriod(eccc.castingCall.data,'project_start','project_end');
        
        
        //initialize select2 for location
        $scope.initNationalityInput('#location',$scope.castingCall.data,'location');
        
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
        
        //initialize Characters
        for (var i in jsonCastingCall.data.characters){
            var character = new Character(jsonCastingCall.data.characters[i]);
            $scope.characters[$scope.characters.length] = character;
            $scope.$apply();
            character.init();
            
        }
        
        $scope.$apply();
        
        if($scope.characters.length > 0){
            var characteridTab = $scope.characters[0].data.characterid;
            $('#c3_eccc_character_tabs a[href="#eccc_'+characteridTab+'"]').tab('show');
        }
        
        if ($scope.castingManagerPortfolio.castingcall_guide == 1){
            var imagesArray = ["edit_castingcall_1.png", "edit_castingcall_2.png", "edit_castingcall_3.png", "edit_castingcall_4.png", "edit_castingcall_5.png", "edit_castingcall_6.png", "edit_castingcall_7.png", "edit_castingcall_8.png", "edit_castingcall_9.png", "edit_castingcall_10.png"];
            displayGuide(imagesArray);
        }
    }
    
    $scope.initEthnicityInput = function(id,dataNode){
        //initialize select2 for ethnicity
        $(id).select2({
            multiple:false,
            minimumInputLength: 3,
            createSearchChoice: function(term, data) { 
                if ($(data).filter(function() {
                    return this.text.localeCompare(term)===0;
                }).length===0) {
                    return {
                        id:term, 
                        text:term + ' (no such ethnicity recorded yet)'
                    };
                } 
            },
            initSelection: function(element,callback){
                if(typeof dataNode.ethnicity != 'undefined'){
                    var results = {
                        id : dataNode.ethnicity.ethnicityid,
                        text : dataNode.ethnicity.name
                    }
                    callback(results);
                }
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
        $(id).on('change',function(data){
            
            if(typeof data.val == 'undefined') return;
            
            //test if data.val is integer. if so, then data.val refers to ethnicityid
            var ethnicityid = parseInt(data.val);
            if(!isNaN(ethnicityid)){
                dataNode.ethnicityid = ethnicityid;
                dataNode.ethnicity = undefined;
            } else {
                dataNode.ethnicityid = undefined;
                dataNode.ethnicity = {
                    'new_ethnicity' : data.val
                }
            }
            
            eccc.$apply();
        });
        
        if(typeof dataNode.ethnicity.ethnicityid != 'undefined'){
            $(id).val('dummy').change();
            dataNode.ethnicityid = dataNode.ethnicity.ethnicityid;
        }
    }
    
    $scope.initNationalityInput = function(id,dataNode,dataNodeAttribute){
        
        //initialize select2 for nationality
        $(id).select2({
            'data' : countries,
            initSelection: function(element,callback){
                if(element.val() != 'dummy'){
                    var selection = { 
                        id : element.val(),
                        text : element.val()
                    };
                    callback(selection);
                } else {
                    callback({});
                }
            }
        });
        
        if(dataNode[dataNodeAttribute] && dataNode[dataNodeAttribute] != ""){
            $(id).val(dataNode[dataNodeAttribute]);
        }
        
        $(id).trigger('change');
        
        $(id).change(function(){
            dataNode[dataNodeAttribute] = $(id).val();
        });
    }
    
    $scope.initSkillInput = function(id,characterid){
        
        var character = $scope.getCharacter(characterid);
        
        /*
         *  Initialization of skills inpus
         */
        
        $(id).select2({
            multiple:true,
            minimumInputLength: 3,
            createSearchChoice: function(term, data) { 
                if ($(data).filter(function() {
                    return this.text.localeCompare(term)===0;
                }).length===0) {
                    return {
                        id:term,
                        tmpid: new Date().getTime(),
                        skillid: term,
                        name:term, 
                        text:term + ' (new entry)'
                    };
                } 
            },
            initSelection: function(element,callback){
                var skills = element.val().split('<,>');
                var results = new Array();
                for(var i in skills){
                    var arr = skills[i].split('<:>');
                    results.push({
                        'skillid' : arr[0],
                        'id': arr[0],
                        'text' : arr[1],
                        'name' :  arr[1]
                    });
                        
                }
                callback(results);
                
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
                        results.push({
                            'skillid' : data[i].skillid,
                            'id': data[i].skillid,
                            'text' : data[i].name,
                            'name' :  data[i].name
                        });
                    }
                    
                    return {
                        results: results
                    };
                }
            }
        });
        
        if(character.data.skills.length > 0){
            var existingSkills = "";
            for (var i in character.data.skills){
                character.data.skills[i].exists = true;
                existingSkills += "<,>"+character.data.skills[i]['skillid']+"<:>"+character.data.skills[i]['name'];
            }
            existingSkills = existingSkills.substring(3);
            $(id).val(existingSkills).trigger('change');
        }
        
        //onchange event handler
        $(id).change(function(data){
            var character = $scope.getCharacter($(this).attr('characterid'));
            if(data.added){
                character.data.skills[character.data.skills.length] = data.added;
            } else if (data.removed){
                for(var i in character.data.skills){
                    if(character.data.skills[i].skillid == data.removed.skillid){
                        if(character.data.skills[i].skillid == character.data.skills[i].name){
                            character.data.skills.splice(i,1);
                        } else {
                            character.data.skills[i].remove = true;
                        }
                        break;
                    }
                }
            }           
        });
    }
    
    $scope.initLanguageInput = function(id,characterid){
        /*
         *  Initialization for spoken languages input
         *
         */
        $(id).select2({
            multiple:true,
            data: $scope.dataLanguages,
            initSelection: function(element,callback){
                var selections = element.val().split(',');
                var selectedData = new Array();
                for (var i in selections){
                    var idInfo = selections[i].split(':');
                    var languageId = parseInt(idInfo[0]);
                    var proficiencyId = parseInt(idInfo[1]);
                    var index = (eccc.languageProficiencies.length * (languageId - 1)) + (proficiencyId - 1);
                    selectedData.push(eccc.dataLanguages[index]);
                }
                callback(selectedData);
            }
        });
        
        //populate existing languages for character
        var existingLanguages = "";
        var character = $scope.getCharacter(characterid);
        for(var i in character.data.characterLanguages){
            character.data.characterLanguages[i].exists = true;
            var curLanguage = character.data.characterLanguages[i];
            existingLanguages += "," + curLanguage.languageid + ":" + curLanguage.language_proficiencyid;
        }
        
        existingLanguages = existingLanguages.substring(1);
        $(id).val(existingLanguages).trigger('change');
       
        //onchange event handler
        $(id).change(function(data){
            
            var characterid = $(this).attr('characterid');
            var character = eccc.getCharacter(characterid);
            
            if(data.added){
                
                var characterLanguageExists = false;
                //find existing character languages
                for(var i in character.data.characterLanguages){
                    if(character.data.characterLanguages[i].languageid == data.added.languageid){
                        character.data.characterLanguages[i].remove = undefined;
                        characterLanguageExists = true;
                        break;
                    }
                }
                
                //add a new character language if it does not exist in the current list of character languages
                if(!characterLanguageExists){
                    character.data.characterLanguages[character.data.characterLanguages.length] = data.added;
                }
       
            } else if (data.removed){
                for(var i in character.data.characterLanguages){
                    if(character.data.characterLanguages[i].languageid == data.removed.languageid){
                        if(character.data.characterLanguages[i].exists){
                            character.data.characterLanguages[i].remove = true;
                        } else {
                            //if a language has been added but not saved yet, then remove from list of character languages
                            character.data.characterLanguages.splice(i,1);
                        }
                        break;
                    }
                }
            }
        });
    }
    
    /*
     * Methods for CRUD Video Attachments
     *
     */
    
    $scope.addVideoAttachment = function(characterid){
        var character = $scope.getCharacter(characterid);
        character.data.videoAttachments.push({
            'character_video_attachmentid' : 'new',
            'tmpid' : new Date().getTime(),
            'title' : "",
            'desc' : ""
        });

    }
    
    $scope.deleteVideoAttachment = function(characterid,character_video_attachmentid){
        var character = $scope.getCharacter(characterid);
        for(var i in character.data.videoAttachments){
            if(character.data.videoAttachments[i].character_video_attachmentid == character_video_attachmentid){
                if(character.data.videoAttachments[i].character_video_attachmentid == 'new'){
                    character.data.videoAttachments.splice(i,1);
                    break;
                } else {
                    character.data.videoAttachments[i].remove = true;
                    break;
                }
            }
        }
    }
    
    /*
     * Methods for CRUD photo Attachments
     *
     */
    
    $scope.addPhotoAttachment = function(characterid){
        var character = $scope.getCharacter(characterid);
        character.data.photoAttachments.push({
            'character_photo_attachmentid' : 'new',
            'tmpid' : new Date().getTime(),
            'title' : "",
            'desc' : ""
        });
    }
    
    $scope.deletePhotoAttachment = function(characterid,character_photo_attachmentid){
        var character = $scope.getCharacter(characterid);
        for(var i in character.data.photoAttachments){
            if(character.data.photoAttachments[i].character_photo_attachmentid == character_photo_attachmentid){
                if(character.data.photoAttachments[i].character_photo_attachmentid == 'new'){
                    character.data.photoAttachments.splice(i,1);
                    break;
                } else {
                    character.data.photoAttachments[i].remove = true;
                    break;
                }
            }
        }
    }
    
    /*
     * Methods for CRUD OtherRequirement
     */
    
    $scope.newOtherRequirement = function(characterid){
        var timestamp = new Date().getTime();
        var character = $scope.getCharacter(characterid);
        character.data.otherRequirements[character.data.otherRequirements.length] = {
            characterid : character.data.characterid,
            other_requirementid: timestamp,
            tmpid : timestamp
        };
    }
    
    $scope.deleteOtherRequirement = function(characterid,other_requirementid){
        var character = $scope.getCharacter(characterid);
        for(var i = 0 ; i < character.data.otherRequirements.length; i++){
            if(character.data.otherRequirements[i].other_requirementid == other_requirementid) {
                if(character.data.otherRequirements[i].exists){
                    character.data.otherRequirements[i].remove = true;
                } else {
                    character.data.otherRequirements.splice(i,1);
                }
                break;
            }
        }
    }
   
    
    /*
     * Methods for character management
     * (Also see Character class above)
     * 
     */
    
    //used by angular view to determine if there are non-deleted characters in edit casting call
    $scope.hasCharacters = function(){
        for(var x in $scope.characters){
            if($scope.characters[x].data.statusid != 4){
                return true;
            }
        }
        return false;
    }
    
    $scope.getCharacter = function(characterid){
        for(var i in $scope.characters){
            var character = $scope.characters[i];
            if (character.id == characterid || character.data.characterid == characterid) return $scope.characters[i];
        }
        return null;
    }
    
    $scope.addCharacter = function(){
        var timestamp = new Date().getTime();
        var defaultData = {
            data : {
                casting_callid: $scope.castingCall.data.casting_callid,
                name : "New Character",
                tmpid : timestamp,
                characterid : timestamp
            }
        };
        var character = new Character(defaultData);
        $scope.characters[$scope.characters.length] = character;
        $scope.$apply();
        character.init();
        
        setTimeout(function(){
            $('#c3_eccc_character_tabs a[href="#eccc_'+defaultData.data.characterid+'"]').tab('show');
        },100);
    };
    
    $scope.confirmDeleteCharacter = function(characterid){
        c3Confirm({
            header : "Delete Character",
            body : "Are you sure you want to delete this character?",
            onAccept: function(){
                eccc.deleteCharacter(characterid)
            }
        });
    }
    
    /* $scope.deleteCharacter = function(characterid){
        var character = $scope.getCharacter(characterid);
        //sets characterid to 4 (delete)
        character.data.statusid = 4;
        $scope.$apply();
    }
     */
    /*
     * Methods for remove requirements
     * 
     */
       
    $scope.deleteCharacter = function(characterid){
        //reqires confirmation
        var character = $scope.getCharacter(characterid);
        character.data.statusid = 4;
        character.data.remove = true;
        $scope.$apply();
    }
    
    $scope.hasCharacters = function(){
        for(var i in $scope.characters){
            if (!$scope.characters[i].data.remove){
                return true;
            }
        }
        
        return false;
    }


    $scope.setRequirement = function(characterid,requirement,isSet){
        var character = $scope.getCharacter(characterid);
        character[requirement+'Requirement'] = isSet;
    }
    
    $scope.deleteAgeRequirement = function(characterid){
        var character = $scope.getCharacter(characterid);
        $scope.setRequirement(characterid,'age',false);
        character.data.age_start = "";
        character.data.age_end = "";
    }
    
    $scope.deleteGenderRequirement = function(characterid){
        $scope.setRequirement(characterid,'gender',false);
        var character = $scope.getCharacter(characterid);
        character.data.gender = "";
    }
    
    $scope.deleteLanguageRequirement = function(characterid){
        $scope.setRequirement(characterid,'language', false);
        var character = $scope.getCharacter(characterid);
        for(var i = 0; i < character.data.characterLanguages.length;i++){
            if(character.data.characterLanguages[i].exists){
                character.data.characterLanguages[i].remove = true;
            } else {
                character.data.characterLanguages.splice(i,1);
                i--;
            }
        }
        $('#accLanguages_'+characterid).val('').change();
    }
    
    $scope.deleteSkillRequirement = function(characterid){
        $scope.setRequirement(characterid,'skill',false);
        var character = $scope.getCharacter(characterid);
        for(var i = 0; i < character.data.skills.length ; i++){
            if (!character.data.skills[i].exists){
                character.data.skills.splice(i,1);
                i--;
            } else {
                character.data.skills[i].remove = true;
            }
        }
        $('#accSkill_'+characterid).val('').change();
        
    }
    
    $scope.deleteEthnicityRequirement = function(characterid){
        $scope.setRequirement(characterid,'ethnicity',false);
        var character = $scope.getCharacter(characterid);
        delete character.data['ethnicity']['new_ethnicity'];
        delete character.data['ethnicityid'];
        $('#accEthnicity_'+characterid).val('dummy').change();
    }
    
    $scope.deleteNationalityRequirement = function(characterid){
        $scope.setRequirement(characterid,'nationality',false);
        var character = $scope.getCharacter(characterid);
        character.data.nationality = "";
        $('#accNationality_'+characterid).val('dummy').change();
    }

    /*
     *  Methods for setting casting call access settings
     */

    $scope.setCastingCallStatus = function(statusid){
        $scope.castingCall.data.statusid = statusid;
        $.post(baseUrl + '/castingCall/saveCastingCall',{
            CastingCall:$scope.castingCall.data
        },function(data){
            data = angular.fromJson(data);           
            $scope.castingCall.data.status = data.status;
            $scope.$apply();
        });
    }

    /*
     * Methods for submitting casting call form
     */
       
    $scope.submitAndView = function(){
        $scope.submitForm(function(){
            //check if casting call or characters have errors
            if(typeof eccc.castingCall.errors != 'undefined') return;
            this.location = baseUrl + '/castingCall/view/' + $scope.castingCall.data.url
        });
    }
    
    $scope.submitForm = function(callback){
        
        if(eccc.submitBtnDisabled == 'disabled') return;
        eccc.submitBtnDisabled = 'disabled';
        
        //Set undefined or null attributes of casting call to empty string
        for(var i in $scope.castingCall.data){
            $scope.castingCall.data[i] = typeof $scope.castingCall.data[i] == 'undefined' || $scope.castingCall.data[i] == null ? "" : $scope.castingCall.data[i];
        }
        
        //Set undefined or null attributes of character to empty string
        var characters = new Array();
        for(var i in $scope.characters){
            var character = $scope.characters[i];
            characters[characters.length] = character.data;
            for (var x in character.data){
                if(typeof character.data[x] == 'undefined' || character.data[x] == null){
                    character.data[x] = "";
                }
            }
            
            for(var x in character.data.otherRequirements){
                var otherRequirement = character.data.otherRequirements[x];
                for(var y in otherRequirement){
                    if(typeof otherRequirement[y] == 'undefined' || otherRequirement[y] == null) otherRequirement[y] = "";
                }
            }
            
        }
            
        //validate casting call data
        $.post(baseUrl + '/castingCall/saveCastingCall',{
            CastingCall:$scope.castingCall.data,
            Characters : characters
        },function(data){
            eccc.submitBtnDisabled = '';
            data = angular.fromJson(data);
            processResponse(data);
            $scope.castingCall.data.status = data.status;
            $scope.castingCall.errors = data.errors;
            
            
            //edit results for every character
            for(var i in data.characters){
                var characterData = data.characters[i];
                
                //checks if data.chracters has a tmpid and refers to a newly created character
                var character;
                if(typeof characterData.data.tmpid != 'undefined'){
                    for(var x in eccc.characters){
                        var curCharacter = eccc.characters[x];
                        if(typeof curCharacter.data.tmpid != 'undefined'
                            && curCharacter.data.tmpid == characterData.data.tmpid){
                            character = curCharacter;
                            character.data.characterid = characterData.data.characterid;
                            //set tmpid as undefined as character has been assigned a characterid
                            delete character.data['tmpid'];
                            break;
                        }
                    }
                } else {
                    //gets character by characterid
                    character = eccc.getCharacter(characterData.data.characterid);
                }

                character.data.status = characterData.data.status;
                character.errors = characterData.errors;
                
                //set all exist attribute of character.data.skills to true
                for(var x in character.data.skills){
                    var curSkill = character.data.skills[x];
                    curSkill.exists = true;
                    if(curSkill.skillid == curSkill.name){
                        //assign skillid to new skill
                        for (var y in characterData.data.skills){
                            var curSkillData = characterData.data.skills[y];
                            if(curSkillData.tmpid == curSkill.tmpid){
                                curSkill.skillid = curSkillData.skillid;
                            }
                        }
                    }
                }
                
                //Set all exist attribute of character.data.languages to true
                for(var x in character.data.characterLanguages){
                    character.data.characterLanguages[x].exists = true;
                }
                
                
                //Add Errors attribute to each character.data.otherRequirements if any
                for(var x in character.data.otherRequirements){
                    var otherRequirement = character.data.otherRequirements[x];
                    
                    //set exists to true
                    otherRequirement.exists = true;
                    
                    
                    //sets errors for character.data.otherRequirements if any
                    for(var z in characterData.data.otherRequirements){
                        var dataOtherRequirement = characterData.data.otherRequirements[z];
                        
                        if(typeof dataOtherRequirement.tmpid != 'undefined' && typeof otherRequirement.tmpid != 'undefined'){
                            if(otherRequirement.tmpid == dataOtherRequirement.tmpid){
                                delete otherRequirement['tmpid'];
                                otherRequirement.other_requirementid = dataOtherRequirement.other_requirementid;
                            }
                        }
                        
                        if(dataOtherRequirement.other_requirementid == otherRequirement.other_requirementid){
                            otherRequirement.errors = dataOtherRequirement.errors;
                        }
                    }
                }
                
                //Add Errors attribute to each character.data.videoAttachments if any
                for(var x in character.data.videoAttachments){
                    var videoAttachment = character.data.videoAttachments[x];
                    
                    //set exists to true
                    videoAttachment.exists = true;
                    
                    //sets errors for character.data.otherRequirements if any
                    for(var z in characterData.data.videoAttachments){
                        var dataVideoAttachment = characterData.data.videoAttachments[z];
                        
                        if(typeof dataVideoAttachment.tmpid != 'undefined' && typeof videoAttachment.tmpid != 'undefined'){
                            if(dataVideoAttachment.tmpid == videoAttachment.tmpid){
                                delete videoAttachment['tmpid'];
                                videoAttachment.character_video_attachmentid = dataVideoAttachment.character_video_attachmentid;
                            }
                        }
                        
                        if(dataVideoAttachment.character_video_attachmentid == videoAttachment.character_video_attachmentid){
                            videoAttachment.errors = dataVideoAttachment.errors;
                        }
                    }
                }
                
                //Add Errors attribute to each character.data.photoAttachments if any
                for(var x in character.data.photoAttachments){
                    var photoAttachment = character.data.photoAttachments[x];
                    
                    //set exists to true
                    photoAttachment.exists = true;
                    
                    //sets errors for character.data.otherRequirements if any
                    for(var z in characterData.data.photoAttachments){
                        var dataPhotoAttachment = characterData.data.photoAttachments[z];
                        
                        if(typeof dataPhotoAttachment.tmpid != 'undefined' && typeof photoAttachment.tmpid != 'undefined'){
                            if(dataPhotoAttachment.tmpid == photoAttachment.tmpid){
                                delete photoAttachment['tmpid'];
                                photoAttachment.character_photo_attachmentid = dataPhotoAttachment.character_photo_attachmentid;
                            }
                        }
                        
                        if(dataPhotoAttachment.character_photo_attachmentid == photoAttachment.character_photo_attachmentid){
                            photoAttachment.errors = dataPhotoAttachment.errors;
                        }
                    }
                }
            }
            
            $scope.$apply();
            if(callback) callback();
        });
    }
    
    /*
     *  Methods for tab management
     */
    
    $scope.setTabVars = function(params){
        for (var i in params){
            $scope[i] = params[i];
        }
    }

    /*
     * Methods for form validation methods
     * 
     */

    $scope.isError = function(attribute){
        if(attribute && attribute.length > 0) return 'c3-row-error';
        return '';
    }

    $scope.getCharacterTabClass = function(characterid){
        var character = $scope.getCharacter(characterid);
        if(typeof character.data.status == 'undefined') return '';
        switch(character.data.status.statusid){
            case '7':
                return 'alert-error';
            case '6':
                return 'alert-warning';
            case '5':
                return 'alert-success';
        }
        return '';
        
    }
    
    $scope.countCharacterStatus = function(statusid){
        var count = 0;
        for (i in $scope.characters){
            if($scope.characters[i].data.status.statusid == statusid) count++;
        }
        
        return count;
    }

    //loads modal to change profile pic
    //view: '../commo/setPhoto'
    //
    //

    $scope.changeProfilePicInitCallback = function(){
        var params = {};
        params.submitCallback = function(data){
            var photo = angular.fromJson(data);
            if(data) {
                eccc.castingCall.data.photoUrl = photo.url;
                eccc.castingCall.data.photoid = photo.photoid;
            }

            $('#c3_profilepic').loading(false);
            eccc.$apply();
        };
        params.casting_callid = $scope.castingCall.data.casting_callid;
        params.closeCallback = function(){
            $('#c3_profilepic').loading(false);
        };

        //parameters to enable set profile pic of portfolios and casting calls
        params.cropPhoto = true;

        //parameters to enable set casting call
        params.casting_callid = $scope.castingCall.data.casting_callid;

        sppc.show(params);
    }

    $scope.changeProfilePic = function(){
        $('#c3_profilepic').loading(true,function(){
            if(typeof sppc === 'undefined'){
                $scope.loadContent(baseUrl + '/common/setPhoto',function(){
                    sppcInit(function(){
                        eccc.changeProfilePicInitCallback();
                    });
                });
            } else {
                eccc.changeProfilePicInitCallback();
            }
        });
    }
    
    $scope.statusBtnClass = function(){
        switch($scope.castingCall.data.status.statusid){
            case '5':
                return 'btn-success';
                break;
            case '6':
                return 'btn-warning';
                break;
            case '7':
                return 'btn-error';
                break;
        }
        return '';
    }
}
