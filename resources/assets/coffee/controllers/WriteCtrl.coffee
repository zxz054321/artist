angular.module('app')
.controller 'WriteCtrl', [
    '$scope'
    '$http'
    ($scope, $http)->
        $scope.data = {}
        $scope.check = {}
        $scope.checked = {}
        $scope.id = getQueryString('edit')

        # fetch content====================
        if $scope.id
            $http.get("content/#{$scope.id}")
            .success (res)->
                raw = res.raw
                window.simplemde.value(raw.content)

                delete raw.content
                $scope.data = raw
                $scope.oldRoute = raw.route

                return

        $scope.setType = (type)->
            $scope.data.status = type

        $scope.onRouteBlur = ->
            route = $scope.data.route
            if route and (route isnt $scope.oldRoute)
                $http.get('content/check?route=' + route)
                .success (res)->
#                    console.log(res)
                    $scope.check.route = !res.exists
                    $scope.checked.route = true

        $scope.delete = ->
            if confirm('Are you sure you want to delete this content?')
                $http.delete('content/' + $scope.id)
                .success (res)->
                    if res.result
                        window.location = 'contents'
                        return

                    $scope.err = res.err
                    $scope.checked.submit = true

        $scope.submit = ->
            $scope.data.content = window.simplemde.value()

            request = ->
                if $scope.id
                    $http.put('content/' + $scope.id, $scope.data)
                else
                    $http.post('content/store', $scope.data)

            request().success (res)->
#                console.log(res)
                $scope.result = res.result
                $scope.checked.submit = true

                if !$scope.result
                    $scope.err = res.err
                    return

                if !$scope.id
                    window.location = 'contents'
                    return

                cleanup = ->
                    $scope.checked.submit = false
                    return
                setTimeout cleanup, 2000
                return
            return

        return
]
