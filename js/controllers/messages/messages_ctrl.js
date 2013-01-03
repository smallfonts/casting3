/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var mc;

function messages_ctrl($scope){
    mc = $scope;

    $scope.messageSummary = new Array();
    $scope.page = 1;
    $scope.last_date = '1900-01-01 00:00:00';
    $scope.refresh = function(){
        $.get(baseUrl+'/messages/getMessageSummary',function(data){
            data = angular.fromJson(data);
            $scope.messageSummary = data;
            if($scope.messageSummary.length > 0){
                $scope.last_date = $scope.messageSummary[0].message.created;
            }
            for(var i in $scope.messageSummary){
                var tmpDate = stringToDate($scope.messageSummary[i].message.created,'yyyy-mm-dd hh:mm:ss');
                $scope.messageSummary[i].message.created = dateFormat(tmpDate,'mmm dS');
            }
            $scope.$apply();
        });
        
        
    }
    $scope.refresh();
    
    $("#summary").scroll(function(e){
        var clientHeight = e.target.clientHeight;
        var scrollHeight = e.target.scrollHeight;
        var scrollTop = e.target.scrollTop;
        if(scrollHeight - clientHeight - 30 < scrollTop){
            if(!$scope.loading){
                $scope.loading = true;
                $scope.$apply();
                $.get(baseUrl+'/messages/getMessageSummary?p='+$scope.page,function(data){
                    data = angular.fromJson(data);
                    $scope.messageSummary = $scope.messageSummary.concat(data);
                    $scope.$apply();
                    $scope.loading=false;
                });
            }
            
        }
    });
    
    
    $scope.getMessageStyle=function(index){
        if (index != 0) return 'opacity:0.8;';
        return '';
    }
    
    $scope.newMessage = function(){
        window.open(baseUrl+'/messages/new','','width=500,height=500');
    }
    
    $scope.reply = function(messageid){
        window.open(baseUrl+'/messages/new?reply='+messageid,'','width=500,height=500')
    }
    
    $scope.showMessage = function(messageid,index){
        $scope.messageSummary[index].status.statusid = 19;
        $.get(baseUrl+'/messages/getMessage?messageid='+messageid,function(data){
            $scope.messages = angular.fromJson(data);
            for(var i in $scope.messages){
                $scope.messages[i].body = decodeURIComponent($scope.messages[i].body);
                var tmpDate = stringToDate($scope.messages[i].sent, 'yyyy-mm-dd hh:mm:ss');
                $scope.messages[i].sent = dateFormat(tmpDate, "dddd, mmmm dS, yyyy, h:MM:ss TT");
            }
            $scope.$apply();
        });
    }
    
    //get unread messages
    $scope.getNew = function(){
        if($scope.last_date != undefined){
            $.get(baseUrl+'/messages/getNew?last_date='+$scope.last_date,function(data){
                data = angular.fromJson(data);
                if(data.length > 0){
                    $scope.last_date = data[0].message.created;
                    for(var i = data.length-1 ; i >= 0; i--){
                        //convert date
                        var tmpDate = stringToDate(data[i].message.created,'yyyy-mm-dd hh:mm:ss');
                        data[i].message.created = dateFormat(tmpDate,'mmm dS');
                        $scope.messageSummary.unshift(data[i]);
                    }
                    $scope.$apply();
                }
            });
        }
    }
    
    setInterval(function(){
        $scope.getNew();
    },10000);
    
    
}

