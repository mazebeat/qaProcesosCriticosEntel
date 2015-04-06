'use strict';

// INIT APP
var qaProcesosCriticos = angular.module('qaProcesosCriticos', [
    'LocalStorageModule',
    'ngCookies'
    ]);

// RUN
//qaProcesosCriticos.run(['$http', '$cookies', function($http, $cookies) {
//  $http.defaults.headers.post['X-CSRFToken'] = $cookies.csrftoken;
//}]);

// CONFIG
qaProcesosCriticos.config(['$httpProvider', 'localStorageServiceProvider', function ($httpProvider, localStorageServiceProvider) {
    // Enable cross domain calls
    $httpProvider.defaults.useXDomain = true;

    // Remove the header user to identify ajax call that would prevent CORS form working
    delete $httpProvider.defaults.headers.common['X-Requested-With'];

    $httpProvider.defaults.headers.post['Content-Type']  = 'application/x-www-form-urlencoded;  charset=UTF-8';

    $httpProvider.defaults.withCredentials = false;
    $httpProvider.defaults.headers.common.Accept = "*/*"
    $httpProvider.defaults.timeout = 10000;

    localStorageServiceProvider
    .setPrefix('__trkC')
    .setStorageType('sessionStorage')
    .setNotify(true, true);
}]);

// FILTER
qaProcesosCriticos.filter('mayorCero', function () {
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