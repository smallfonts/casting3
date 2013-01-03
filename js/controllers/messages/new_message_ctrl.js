/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var nmc;

function new_message_ctrl($scope){
    
    clearInterval(notifications);
    
    nmc = $scope;
    new nicEditor({
        maxHeight : 321
    }).panelInstance('messageBody');
    
    $scope.sendBtn = 'send';
    $scope.replyMessage = message;
    $scope.sendBtnIcon = 'icon-share-alt';
    
    
    $scope.message = {
        to:new Array()
    };
    $scope.to = to;
    
    if($scope.replyMessage.messageid != undefined){
        $scope.message.title = "Re: "+$scope.replyMessage.title;
        $scope.message.reply_messageid = $scope.replyMessage.messageid;
        $scope.to = $scope.replyMessage.messageid;
    }
    
    
    $scope.formatResult = function(result){
        var html = simpleTemplate('c3UserSearchResult',{
            '#name#' : result.name,
            '#attributes#' : 'src="'+photoBaseUrl+'/s'+result.photoUrl+'"',
            '#email#' : result.email
        });
        return html;
    }
    
    /*
         *  Initialization for 'to' input
         *
         */
        
    $('#c3Recipients').select2({
        multiple:true,
        minimumInputLength: 3,
        formatResult: nmc.formatResult,
        initSelection: function(element,callback){
            var query = {
                UserAccount: {
                    userid : element.val().split(",")
                }
            }
                
            $.get(baseUrl + "/common/getUserAccount?" + $.param(query),function(data){
                data = angular.fromJson(data);
                var results = new Array();
                for(var i in data){
                    results.push({
                        'id': data[i].userid,
                        'userid' : data[i].userid,
                        'email' : data[i].email,
                        'name' : data[i].name,
                        'text' : data[i].name,
                        'photoUrl' : data[i].photoUrl
                    });
                    $scope.message.to[$scope.message.to.length] = data[i].userid;
                    
                }
                $scope.$apply();
                callback(results);
            });
                
        },
        ajax : {
            url: baseUrl + "/common/getUserAccount",
            dataType: 'json',
            quietMillis: 200,
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {
                    UserAccount : {
                        name : term,
                        email : term
                    }
                };
            },
            results: function (data, page) {
                var results = new Array();
                for(var i in data){
                    results.push({
                        'id': data[i].userid,
                        'userid' : data[i].userid,
                        'email' : data[i].email,
                        'name' : data[i].name,
                        'text' : data[i].name,
                        'photoUrl' : data[i].photoUrl
                    });
                    
                }
                    
                return {
                    results: results
                };
            }
        }
        
    });
    
    if($scope.replyMessage.messageid != undefined){
        $('#c3Recipients').val($scope.replyMessage.sender.userid).trigger('change');
    } else if ($scope.to.length > 0){
        var recipients = '';
        for (var i in $scope.to){
            recipients += ","+$scope.to[i];
        }
        recipients = recipients.substring(1);
        $('#c3Recipients').val(recipients).trigger('change');
    }
    
    //onchange event handler
    $('#c3Recipients').change(function(data){
        if(data.added){
            $scope.message.to[$scope.message.to.length] = data.added.userid;
        } else if (data.removed){
            for(var i in $scope.message.to){
                if($scope.message.to[i] == data.removed.userid){
                    $scope.message.to.splice(i,1);
                    break;
                }
            }
        }
        
        $scope.$apply();
            
    });
    
    $scope.sendBtnClass =function(){
        if($scope.message.to != undefined && $scope.message.to.length > 0 && !$scope.sending){
            return '';
        } else {
            return ' disabled';
        }
    }
    
    $scope.sendMessage = function(){
        
        if($scope.message.to.length == 0) return;
        
        $scope.sendBtn = 'sending message...';
        $scope.sending = true;
        var body = encodeURIComponent($('.nicEdit-main').html());
        $scope.message.body = body;
        
        $.post(baseUrl+'/messages/send',{
            'Message' : $scope.message
        },function(data){
            $scope.sendBtn = 'Message Has Been Sent!';
            $scope.sendBtnIcon = 'icon-ok';
            $scope.$apply();
            setTimeout(function(){
                window.close(); 
            },2000);
        });
    }
}

