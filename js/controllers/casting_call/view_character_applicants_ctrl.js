var vcac;

function view_character_applicants_ctrl($scope, $http) {
    vcac = $scope;
    $scope.baseUrl = baseUrl;
    $scope.character = jsonCharacter;
    $scope.castingCall = jsonCastingCall;
    $scope.applicants = jsonApplicants;
}
