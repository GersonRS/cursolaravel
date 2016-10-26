angular.module('starter.controllers', [])

.controller('LoginCtrl', [
  '$scope', 'OAuth', '$state', '$ionicPopup', function($scope,OAuth,$state,$ionicPopup) {
  $scope.user = {
    username:'',
    password:''
  };
  $scope.login = function(){
    OAuth.getAccessToken($scope.user)
    .then(function(data){
      $state.go('client.checkout');
    },function(responseError){
      $ionicPopup.alert({
        title: 'Don\'t eat that!',
        template: 'It might taste good'
      });
    });
  };
}])

.controller('HomeCtrl', [
  "$scope","User","$ionicLoading", "$ionicPopup",
  function($scope,User,$ionicLoading, $ionicPopup) {
    
    $ionicLoading.show({
        template: "Carregando..."
    });

    User.query({},
      function(data){
        $scope.user = data;
        $ionicLoading.hide();
    },
      function(dataError){
            $ionicLoading.hide();
            $ionicPopup.alert({
                title: "Problemas em exibir os produtos",
                template: "dataError = " + JSON.stringify(dataError)
            });
      });
}])

.controller('ClientCheckoutCtrl', [
  '$scope','$state','$cart',
  function($scope,$state,$cart) {
    var cart = $cart.get();
    $scope.items = cart.items;
    $scope.total = cart.total;
}])

.controller('ClientCheckoutDetailCtrl', function($scope) {})

.controller("ClientViewProductCtrl", [
        "$scope", "$state", "Product", "$ionicLoading", "$cart", "$ionicPopup",
        function($scope, $state, Product, $ionicLoading, $cart, $ionicPopup){

            $scope.products = [];

            $ionicLoading.show({
                template: "Carregando..."
            });

            Product.query({}, function(data){
                $scope.products = data.data;
                $ionicLoading.hide();
            }, function(dataError){
                $ionicLoading.hide();
                $ionicPopup.alert({
                    title: "Problemas em exibir os produtos",
                    template: "dataError = " + JSON.stringify(dataError)

                });

            });

            $scope.addItem = function(item){
                item.amount = 1;
                $cart.addItem(item);//console.log('cart = ' + $cart);

                //cart.items.push(item);
                $state.go('client.checkout');
            };

}]);