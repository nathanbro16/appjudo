var appjudo = angular.module('appjudo',['ngRoute','ngSanitize']);

appjudo.config(function($routeProvider) {
    $routeProvider
        .when('/', {templateUrl: 'partials/home.html'})
        .otherwise({redirectTo : '/' })
    
});