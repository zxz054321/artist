angular.module('app').controller 'WriteCtrl', [
    '$scope'
    '$http'
    ($scope, $http)->
        $scope.data = {}
        $scope.check = {}
        $scope.checked = {}
        $scope.id = getQueryString('edit')

        if $scope.id
            $http.get("content/#{$scope.id}")
            .success (res)->
#                console.log(res)
                raw = res.raw

                window.simplemde.value(raw.content)

                delete raw.content
                $scope.data = raw
                #                console.log($scope.data)
                return

        $scope.setType = (type)->
            $scope.data.status = type

        $scope.onBlur = ->
            route = $scope.data.route
            if route
                $http.get('content/check?route=' + route)
                .success (res)->
#                    console.log(res)
                    $scope.check.route = !res.exists
                    $scope.checked.route = true

        $scope.submit = ->
            $scope.data.content = window.simplemde.value()
            console.log($scope.data)
            request = ->
                if $scope.id
                    $http.put('content/' + $scope.id, $scope.data)
                else
                    $http.post('content/store', $scope.data)
            request().success (res)->
                console.log(res)
                $scope.result = res.result
                $scope.checked.submit = true

                if !$scope.result
                    $scope.err = res.err
                    return

                cleanup = ->
                    $scope.checked.submit = false
                    return
                setTimeout cleanup, 2000
                return
            return

        return
]
