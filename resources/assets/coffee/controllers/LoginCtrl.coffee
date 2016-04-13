angular.module('app').controller 'LoginCtrl', [
    '$scope'
    '$http'
    ($scope, $http)->
        $scope.data = {}
        $scope.submit = ->
#            console.log($scope.data)
            $http.post('../auth/login', $scope.data)
            .success (res)->
                if !res.match
                    $scope.match = false
                    return

                window.location = 'dashboard'
                return
            return

        return
]