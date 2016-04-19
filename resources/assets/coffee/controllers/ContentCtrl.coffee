angular.module('app')

.directive 'contentlist', [
    ->
        scope:
            data: '=' #two way binding

        controller: [
            '$scope'
            ($scope)->
                $scope.status = (str) ->
                    if str == 'draft'
                        str += 'ed'
                    str.replace /(\w)/, (v)->
                        v.toUpperCase()

                $scope.time = (timestamp)->
                    new Date(timestamp * 1000).toLocaleString()
        ]

        templateUrl : 'contentlist.ng'
]

.controller 'ContentCtrl', [
    '$scope'
    '$http'
    ($scope, $http)->
        $scope.ifShowHeader = (item)->
            if typeof item == 'undefined'
                return false

            item.constructor == Object

        $http.get('content')
        .success (res)->
#            console.log(res)
            $scope.revealed = res.revealed;
            $scope.unrevealed = res.unrevealed;
            setTimeout(fixBottom, 100)
            return

        return
]