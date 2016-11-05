// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', [
  'ionic', 'starter.controllers', 'starter.services', 'angular-oauth2','ngResource','ngCordova'
  ])
.constant('appConfig',{
  baseUrl: 'http://192.168.1.3:8000'
})
.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider, OAuthProvider, OAuthTokenProvider, appConfig, $provide) {

    OAuthProvider.configure({
      baseUrl: appConfig.baseUrl,
      clientId: 'appid01',
      clientSecret: 'secret', // optional
      grantPath: '/oauth/access_token'
    });

    OAuthTokenProvider.configure({
      name: 'token',
      options: {
        secure: false
      }
    });

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider

  // setup an abstract state for the clients directive
  .state('client', {
    url: '/client',
    abstract: true,
    templateUrl: 'templates/client.html'
  })

  .state('client.checkout', {
    cache: false,
    url: '/checkout',
    views: {
      'client.checkout': {
        templateUrl: 'templates/client/checkout.html',
        controller: 'ClientCheckoutCtrl'
      }
    }
  })
  .state('client.checkout_item_detail', {
    url: '/checkout/detail/:index',
    views: {
      'client.checkout': {
        templateUrl: 'templates/client/checkout_item_detail.html',
        controller: 'ClientCheckoutDetailCtrl'
      }
    }
  })
  .state('client.view_products', {
    url: '/view_products',
    views: {
      'client.view_products': {
        templateUrl: 'templates/client/view_products.html',
        controller: 'ClientViewProductCtrl'
      }
    }
  })
  .state('client.checkout_successful', {
    cache: false,
    url: '/checkout/successful',
    views: {
      'client.checkout': {
        templateUrl: 'templates/client/checkout_successful.html',
        controller: 'ClientCheckoutSuccessful'
      }
    }
  })
  .state('client.checkout_pedidos', {
    cache: false,
    url: '/checkout/pedidos',
    views: {
      'client.checkout': {
        templateUrl: 'templates/client/checkout_pedidos.html',
        controller: 'ClientCheckoutPedidos'
      }
    }
  })

  .state('home', {
    url: '/home',
    templateUrl: 'templates/home.html',
    controller: 'HomeCtrl'
  })

  .state('login', {
    url: '/login',
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl'
  });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/login');

  $provide.decorator('OAuthToken', ['$localStorage', '$delegate', function ($localStorage, $delegate) {
            Object.defineProperties($delegate, {
               setToken:{
                    value: function (data) {
                        return $localStorage.setObject('token', data);
                    },
                   enumerable: true,
                   configurable: true,
                   writable: true
               },
                getToken:{
                    value: function () {
                        return $localStorage.getObject('token');
                    },
                    enumerable: true,
                    configurable: true,
                    writable: true
                },
                removeToken:{
                    value: function () {
                        $localStorage.setObject('token', null);
                    },
                    enumerable: true,
                    configurable: true,
                    writable: true
                }
            });
            return $delegate;
  }]);

})
.service('cart',function(){
  this.items = [];
});
