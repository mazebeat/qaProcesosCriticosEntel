'use strict';

/* Init app */
var trackingCorreos = angular.module('trackingCorreos', [
    'ngGrid',
    'LocalStorageModule'
]);

/* Config app */
trackingCorreos.config(['$httpProvider', 'localStorageServiceProvider', function ($httpProvider, localStorageServiceProvider) {
    $httpProvider.defaults.useXDomain = true;
    $httpProvider.defaults.withCredentials = false;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
    $httpProvider.defaults.headers.common.Accept = "*/*";
    localStorageServiceProvider
        .setPrefix('__trkC')
        .setStorageType('sessionStorage')
        .setNotify(true, true);
}]);

trackingCorreos.filter('mayorCero', function () {
    return function (item) {
        if (item <= 0) {
            return 'NO';
        }
        if (item > 0) {
            return 'SI';
        } else {
            return 'NO';
        }
    };
});
