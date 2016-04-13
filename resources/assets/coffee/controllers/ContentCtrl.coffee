angular.module('app')

.directive 'contentlist', [
    ->
        scope:
            data: '='

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

        template: """
            <div class="list-group">
                <a href="write?edit={{id}}" class="list-group-item"
                   ng-repeat="(id,item) in data">
                    <h3 class="list-group-item-heading">
                        {{item.title}}
                    </h3>
                    <div class="list-group-item-text">
                       <span ng-class="{
                            'status':true,
                            'text-success':item.status=='published',
                            'text-warning':item.status=='draft',
                        }">
                            {{status(item.status)}}
                           at
                           {{time(item.created_at)}}
                        </span>

                        <br>
                        <p>{{item.summary}}</p>

                        <span class="label label-default"
                              ng-repeat="tag in item.tags">
                            {{tag}}
                        </span>
                    </div>
                </a>
            </div>
"""
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
            console.log(res)
            $scope.revealed = res.revealed;
            $scope.unrevealed = res.unrevealed;
            setTimeout(fixBottom, 100)
            return

        return
]