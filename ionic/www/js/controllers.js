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
        title: 'Erro ao fazer Login',
        template: 'login e/ou senha invalidos'
      });
    });
  };
}])

.controller('HomeCtrl', [
  "$scope","User","$ionicLoading", "$ionicPopup","$state",
  function($scope,User,$ionicLoading, $ionicPopup,$state) {
    
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
                title: "Usuario não Logado",
                template: "Faça login primeiro"
            });
            $state.go('login');
      });
}])

.controller('ClientCheckoutCtrl', [
  '$scope','$state','$cart','Order','$ionicLoading','$ionicPopup','Cupom','$cordovaBarcodeScanner',
  function($scope,$state,$cart,Order,$ionicLoading,$ionicPopup,Cupom,$cordovaBarcodeScanner) {
    var cart = $cart.get();
    $scope.cupom = cart.cupom;
    $scope.items = cart.items;
    $scope.total = $cart.getTotalFinal();
    $scope.removeItem = function(i){
      $cart.removeItem(i);
      $scope.items.splice(i,1);
      $scope.total = $cart.getTotalFinal();
    };
    $scope.openListProduct = function(){
      $state.go('client.view_products');
    };
    $scope.openProductDetail = function(i){
      $state.go('client.checkout_item_detail',{index:i});
    };
    $scope.save = function(){
      var o = {items: angular.copy($scope.items)};
      angular.forEach(o.items,function(item){
        item.product_id = item.id;
      });
      $ionicLoading.show({
          template: "Carregando..."
      });
      if ($scope.cupom.value) {
        o.cupom_code = $scope.cupom.code;
      };
      var total = $scope.total+$scope.cupom.value;
      if ($scope.cupom.value > total) {
        $ionicLoading.hide();
        $ionicPopup.alert({
            title: "Valor minimo do pedido não alcansado",
            template: "O valor do Cupom é maior que o valor dos pedidos. Adicione mais itens para se chegar ao valor minimo"
        });
      } else {
      Order.save({id:null},o,function(data){
        $ionicLoading.hide();
        $state.go('client.checkout_successful');
      },function(dataError){
        $ionicLoading.hide();
        $ionicPopup.alert({
            title: "pedido não realizado",
            template: "dataError = " + JSON.stringify(dataError)
        });
      });
      }
    };
    $scope.readBarCode = function(){
      getValueCupom(2918);
        // $cordovaBarcodeScanner
        //   .scan()
        //   .then(function(barcodeData) {
        //       getValueCupom(barcodeData.text);
        // }, function(error) {
        //   $ionicPopup.alert({
        //     title: "Advertencia",
        //     template: "não foi possivel ler o codigo de barras - tente novamente"
        //   });
        // });
    };

    $scope.removeCupom = function(){
      $cart.removeCupom();
      $scope.cupom = $cart.get().cupom;
      $scope.total = $cart.getTotalFinal();
    };

    function getValueCupom(code)
    {
        $ionicLoading.show({
            template: 'Carregando...'
        });
        Cupom.get({code: code}, function (data) {
            $cart.setCupom(data.data.code, data.data.value);
            $scope.cupom = $cart.get().cupom;
            $scope.total = $cart.getTotalFinal();
            $ionicLoading.hide();
        }, function (responseError) {
                $ionicLoading.hide();
                $ionicPopup.alert({
                    title: 'Advertencia',
                    template: 'Cupom Inválido'
                })
          });
    };
}])

.controller('ClientCheckoutSuccessful', [
  '$scope','$state','$cart',
  function($scope,$state,$cart) {
    var cart = $cart.get();
    $scope.cupom = cart.cupom;
    $scope.items = cart.items;
    $scope.total = $cart.getTotalFinal();
    $cart.clear();
    $scope.openListOrder = function(){
      $state.go('client.checkout_pedidos');
    };
}])

.controller('ClientCheckoutDetailCtrl', [
  '$scope','$state','$stateParams','$cart',
  function($scope,$state,$stateParams,$cart) {
    $scope.product = $cart.getItem($stateParams.index);
    $scope.updateQtd = function(){
      $cart.updateQtd($stateParams.index,$scope.product.amount);
      $state.go('client.checkout');
    }
}])

.controller('ClientCheckoutPedidos', [
  '$scope','$state','Orders', "$ionicLoading", "$ionicPopup",
  function($scope,$state,Orders, $ionicLoading, $ionicPopup) {
    $scope.pedidos = [];

    Orders.query({}, function(data){
        $scope.pedidos = data.data;
        $ionicLoading.hide();
    }, function(dataError){
        $ionicLoading.hide();
        $ionicPopup.alert({
            title: "Problemas em exibir os pedidos",
            template: "serviço indisponivel - tente novamente"
        });
    });
}])

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
                $cart.addItem(item);
                //cart.items.push(item);
                $state.go('client.checkout');
            };

}]);