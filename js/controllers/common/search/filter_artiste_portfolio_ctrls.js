/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var fapnc;
var faplc;
var fapgc;
var fapac;
var fapnac;
var fapsc;
function filter_artiste_portfolio_name_ctrl($scope){
    fapnc = $scope;
    
    if(smc.searchQuery.ArtistePortfolio.name){
        $scope.name = smc.searchQuery.ArtistePortfolio.name;
    }
    
    $scope.addName = function(){
        if(fapnc.name != ""){
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'name',
                value : fapnc.name
            });
        } else {
            smc.removeQuery('ArtistePortfolio','name');
        }
    }
}

function filter_artiste_portfolio_age_ctrl($scope){
    fapcAge = $scope;
    //gets search query parameters
    if(smc.searchQuery.ArtistePortfolio.searchAge){
        if(typeof smc.searchQuery.ArtistePortfolio.searchAge.min != 'undefined'
            && smc.searchQuery.ArtistePortfolio.searchAge.min != ""){
            smc.searchQuery.ArtistePortfolio.searchAge.min = parseInt(smc.searchQuery.ArtistePortfolio.searchAge.min);
        }
        if(typeof smc.searchQuery.ArtistePortfolio.searchAge.max != 'undefined'
            && smc.searchQuery.ArtistePortfolio.searchAge.max != ""){
            smc.searchQuery.ArtistePortfolio.searchAge.max = parseInt(smc.searchQuery.ArtistePortfolio.searchAge.max);
        }
        $scope.searchAge = smc.searchQuery.ArtistePortfolio.searchAge;
    }
    
    $scope.addAges = function(){
        var minAge = $('#fapcMinAge').val();
        var maxAge = $('#fapcMaxAge').val();
        
        if(minAge != "" || maxAge != ""){
            var ages = {
                min: minAge,
                max: maxAge
            }
            
            $scope.age = ages;
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'searchAge',
                value : $scope.age
            });

            
        } else {
            smc.removeQuery('ArtistePortfolio','searchAge');
        }
     
    }
//    $scope.resetAge = function(){
//        $scope.age=new Array();
//        smc.setQuery('ArtistePortfolio','searchAges',$scope.age);
//    }
}
function filter_artiste_portfolio_gender_ctrl($scope){
    fapgc = $scope;
    //gets search query parameters
    if(smc.searchQuery.ArtistePortfolio.searchGender){
        $scope.gender = smc.searchQuery.ArtistePortfolio.searchGender;
        for(var i in $scope.gender){
            switch($scope.gender[i].toLowerCase()){
                case "male":
                    $('#c3_gender_m').attr('checked','checked');
                    break;
                case "female":
                    $('#c3_gender_f').attr('checked','checked');
                    break;
            }
        }
    }
    $scope.queryGender = function(){

        $scope.gender = new Array();
       
        if($('#c3_gender_f').attr('checked')=='checked') {
            $scope.gender[$scope.gender.length] = "Female";
        }
        if($('#c3_gender_m').attr('checked')=='checked') {
            $scope.gender[$scope.gender.length] = "Male";
        }
        
        if($scope.gender.length == 0){
            smc.removeQuery('ArtistePortfolio','searchGender');
        } else {
            smc.setQuery({
                model : 'ArtistePortfolio',
                attribute : 'searchGender',
                value : $scope.gender
            });         
        }

    }
}

function filter_artiste_portfolio_languages_ctrl($scope){
    faplc = $scope;
    $scope.languageProficiencies = jsonLanguageProficiencies;
    $scope.allLanguagesObj = jsonAllLanguages;
    $scope.dataLanguages = new Array();
    
    //populate languages
    for (var i in $scope.allLanguagesObj){
        for (var x in $scope.languageProficiencies){
            $scope.dataLanguages[$scope.dataLanguages.length] = {
                id: $scope.allLanguagesObj[i]["languageid"] + ":" + $scope.languageProficiencies[x]["language_proficiencyid"],
                languageid: $scope.allLanguagesObj[i]["languageid"],
                language_proficiencyid: $scope.languageProficiencies[x]["language_proficiencyid"],
                text:$scope.allLanguagesObj[i]["name"] + " ("+$scope.languageProficiencies[x]["name"]+")"
            }
        }
    }
    
    $('#faplcLanguage').select2({
        allowClear: true,
        multiple:true,
        data: $scope.dataLanguages,
        initSelection: function(element,callback){
            $('#faplcLanguage').val('');
            var lang = faplc.searchLanguages;
            var results = new Array();
            for(var i in lang){
                var textLang = lang[i]["name"];
                var langJson = lang[i]["json"];
                var data = angular.fromJson(langJson);
                var langid = data["languageid"] + ":" + data["language_proficiencyid"];
                var result = {
                    id: langid,
                    text: textLang
                }
                results[results.length] = result;
            }
            callback(results);    
        }
    });
    
    if(smc.searchQuery.ArtistePortfolio.searchLanguages){
        $scope.searchLanguages = smc.searchQuery.ArtistePortfolio.searchLanguages;
        $('#faplcLanguage').val('dummy').trigger('change');
    } else {
        $scope.searchLanguages = new Array();
    }
    
    $scope.setLanguage = function(data){
        var allData = data.val;
        var language = {};
        $scope.searchLanguages = new Array();
        
        for(var i in allData){
            allData[i] = allData[i].split(':');
            var languageid = allData[i][0];
            var language_proficiencyid = allData[i][1];
            
            language = {
                languageid: languageid,
                language_proficiencyid: language_proficiencyid
            }
            
            $scope.searchLanguages.push(language);
        }
        
        smc.setQuery({
            model : 'ArtistePortfolio',
            attribute : 'searchLanguages',
            value : $scope.searchLanguages
        });    
        
    }
    
    $scope.getLanguage = function (languageid,proficiencyid){
        return $scope.allLanguagesObj[parseInt(languageid)-1]["name"]+' ('+ $scope.languageProficiencies[parseInt(proficiencyid) - 1]["name"] +')';
    }
    
    $('#faplcLanguage').change(function(data){
        faplc.setLanguage(data);
    });
}
    
function filter_artiste_portfolio_nationality_ctrl($scope){
    fapnac = $scope;

    //initialize select2 for nationality
    $('#fapncNationality').select2({
        allowClear: true,
        data : countries,
        initSelection: function(element,callback){
            var selection = {
                id : element.val(),
                text : element.val()
            }
            
            callback(selection);
        }
    });
    
    //gets search query parameters
    if(smc.searchQuery.ArtistePortfolio.nationality){
        $('#fapncNationality').val(smc.searchQuery.ArtistePortfolio.nationality).trigger('change');
    }
    
    $scope.addNationality = function(data){
        $scope.nationality = data.val;
        smc.setQuery({
            model : 'ArtistePortfolio',
            attribute : 'nationality',
            value : $scope.nationality
        }); 
    }
        
    $('#fapncNationality').change(function(data){
        fapnac.addNationality(data);
    });
}
  
function filter_artiste_portfolio_profession_ctrl($scope){
    fappc = $scope;
    //gets search query parameters
    if(smc.searchQuery.ArtistePortfolio.searchProfessions){
        $scope.searchProfessions = smc.searchQuery.ArtistePortfolio.searchProfessions;
    }
    /*
 *  Initialization for profession input
 */
      
    $('#fappcProfessions').select2({
        allowClear: true,
        multiple:false,
        initSelection: function(element,callback){
            professionid = fappc.searchProfessions;
            query = {
                Profession : {
                    professionid: professionid
                }
            }
                
            $.get(baseUrl + "/common/getProfessions?" + $.param(query),function(data){
                data = angular.fromJson(data);
                var results = {};
                for(var i in data){
                    results = {
                        id: data[i].professionid,
                        text: data[i].name
                    }
                }
                callback(results);
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
    
    if(smc.searchQuery.ArtistePortfolio.searchProfessions){
        $('#fappcProfessions').val(smc.searchQuery.ArtistePortfolio.searchProfessions).trigger('change');
    }

    $scope.addProfessions= function(data){
        $scope.searchProfessions = data.val;
        smc.setQuery({
            model : 'ArtistePortfolio',
            attribute : 'searchProfessions',
            value : $scope.searchProfessions
        });
    }
        
    $('#fappcProfessions').change(function(data){
        fappc.addProfessions(data);
    });
    
//    $scope.resetProfession = function(){
//        $scope.professions = new Array();
//        smc.setQuery('ArtistePortfolio','searchProfessions',$scope.professions);
//    }
    
}
function filter_artiste_portfolio_skills_ctrl($scope){
    fapsc = $scope;
    //Populate skills
    $('#fapscSkills').select2({
        allowClear: true,
        multiple:true,
        initSelection: function(element,callback){
            $('#fapscSkills').val('');
            var searchQuery = {
                Skill : {
                    searchSkillids : fapsc.searchSkills
                }
            }
            $.get(baseUrl + "/common/getSkills?"+$.param(searchQuery),function(data){
                data = angular.fromJson(data);
                results = new Array();
                for(var i in data){
                    results.push({
                        'skillid' : data[i].skillid,
                        'id': data[i].skillid,
                        'text' : data[i].name,
                        'name' :  data[i].name
                    });
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
                    
                for(i in data){
                    data[i].id = data[i].skillid;
                    data[i].text = data[i].name;
                }
                    
                return {
                    results: data
                };
            }
        }
    });
    
    if(smc.searchQuery.ArtistePortfolio.searchSkills){
        $scope.searchSkills = smc.searchQuery.ArtistePortfolio.searchSkills;
    } else {
        $scope.searchSkills = new Array();
    }
    
    if(smc.searchQuery.ArtistePortfolio.searchSkills){
        $('#fapscSkills').val('dummy').trigger('change');
    }
    
    //Add Skills
    $scope.addSkills= function(data){
        $scope.searchSkills = data.val;
        for(var i in $scope.searchSkills){
            $scope.searchSkills[i] = {
                skillid : $scope.searchSkills[i]
            }
        }
        smc.setQuery({
            model : 'ArtistePortfolio',
            attribute : 'searchSkills',
            value : $scope.searchSkills
        });
    }
    $('#fapscSkills').change(function(data){
        fapsc.addSkills(data);
    });
    
    
//Reset Skills
//    $scope.resetSkill = function(){
//        $scope.languages=new Array();
//        smc.setQuery('ArtistePortfolio','searchSkills',$scope.skills);
//    }
}

function filter_artiste_portfolio_ethnicity_ctrl($scope){
    fapec = $scope;
    $scope.allEthnicities = jsonEthnicities;
    $scope.ethnicity = new Array();
    
    if(smc.searchQuery.ArtistePortfolio.ethnicity){
        $scope.ethnicity = smc.searchQuery.ArtistePortfolio.searchEthnicity;
    } else {
        $scope.ethnicity = new Array();
    }
    
    //Populate Ethnicities
    for (var i in $scope.allEthnicities){
        $scope.ethnicity[$scope.ethnicity.length] = {
            id: $scope.allEthnicities[i]['name'],
            text: $scope.allEthnicities[i]['name']
        }
    }
    
    $('#fapecEthnicity').select2({
        allowClear: true,
        multiple:false,
        data: $scope.ethnicity,
        initSelection: function(element,callback){
            var selection = {
                id : smc.searchQuery.ArtistePortfolio.searchEthnicity,
                text : smc.searchQuery.ArtistePortfolio.searchEthnicity
            };
            callback(selection);
        }
    });
    
    if(smc.searchQuery.ArtistePortfolio.searchEthnicity){
        $('#fapecEthnicity').val(smc.searchQuery.ArtistePortfolio.searchEthnicity).trigger('change');
    }
    
    //Add Ethnicity
    $('#fapecEthnicity').change(function(data){
        fapec.addEthnicity(data);
    });
    
    $scope.addEthnicity= function(data){
        $scope.ethnicity = data.val;
        smc.setQuery({
            model : 'ArtistePortfolio',
            attribute : 'searchEthnicity',
            value : $scope.ethnicity
        });
    }
    
}