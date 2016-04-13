angular.module('app').controller 'TagCtrl', [
    '$scope'
    '$http'
    ($scope, $http)->
        $http.get('tag')
        .success (res)->
            console.log(res)
            $scope.tags = res.tags;
            return

        return
]