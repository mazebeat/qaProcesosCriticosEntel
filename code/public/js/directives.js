'use strict';

qaProcesosCriticos
    .directive('postsPagination', function () {
        return {
            restrict: 'E',
            template: '<ul class="pagination">' +
            '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="getPosts(1)">&laquo;</a></li>' +
            '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="getPosts(currentPage-1)">&lsaquo; Prev</a></li>' +
            '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
            '<a href="javascript:void(0)" ng-click="getPosts(i)">{{i}}</a>' +
            '</li>' +
            '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="getPosts(currentPage+1)">Next &rsaquo;</a></li>' +
            '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="getPosts(totalPages)">&raquo;</a></li>' +
            '</ul>'
        };
    })
    .directive('uiLadda', [function () {
        return {
            link: function postLink(scope, element, attrs) {
                var Ladda = window.Ladda, ladda = Ladda.create(element[0]);
                scope.$watch(attrs.uiLadda, function (newVal, oldVal) {
                    if (angular.isNumber(oldVal)) {
                        if (angular.isNumber(newVal)) {
                            ladda.setProgress(newVal);
                        } else {
                            newVal && ladda.setProgress(0) || ladda.stop();
                        }
                    } else {
                        newVal && ladda.start() || ladda.stop();
                    }
                });
            }
        };
    }])
    .directive('ladda', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                if (element && element[0]) {
                    var l = Ladda.create(element[0]);
                    scope.$watch(attrs.ladda, function (newVal, oldVal) {
                        if (newVal !== undefined) {
                            if (newVal)
                                l.start();
                            else
                                l.stop();
                        }
                    });
                }
            }
        };
    })