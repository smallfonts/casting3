var vac;

function view_auditions_ctrl($scope, $http) {
    vac = $scope;
    $scope.baseUrl = baseUrl;
    $scope.auditions = jsonAuditions;
    $scope.castingCall = jsonCastingCall;
    
    for(var i in $scope.auditions){
        var start = stringToDate($scope.auditions[i].application_start,'yyyy-mm-dd');
        var end = stringToDate($scope.auditions[i].application_end,'yyyy-mm-dd');
        start = dateToString(start,'dd MMM yyyy');
        end = dateToString(end,'dd MMM yyyy');
        $scope.auditions[i].application_start = start;
        $scope.auditions[i].application_end = end;
    }
    
    $scope.getUrl = function(index){
        var audition = $scope.auditions[index];
        switch(audition.status.statusid){
            case '11':
                return baseUrl + '/audition/edit/' + audition.auditionid;
            case '16':
            case '14':
            case '17':
                return baseUrl + '/audition/viewConfirmed?auditionid=' + audition.auditionid;
        }
        return '';
    }
    
    $scope.alertClass = function(index){
        switch($scope.auditions[index].status.statusid){
            case '11': case '14':
                return 'alert-warning';
            case '16':
                return 'alert-success';
            case '17':
                return 'alert-error';
        }
        return '';
    }
}
