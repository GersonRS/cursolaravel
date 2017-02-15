angular.module('starter.controllers', [])

.controller("ClientMenuCtrl", [
        "$scope", "$state", "User", "UserData",
        function($scope, $state, User, UserData){
            $scope.user = UserData.get();
}])

.controller('LoginCtrl',[
        '$scope','OAuth','OAuthToken','$ionicPopup','$state','UserData','User',
        function($scope, OAuth,OAuthToken,$ionicPopup,$state,UserData,User){
  $scope.user = {
    username:'',
    password:''
  };
  $scope.login = function(){
    var promise = OAuth.getAccessToken($scope.user);
    promise.then(function(data){
      return User.authenticated({include:'client'}).$promise;
    })
    .then(function(data){
      UserData.set(data.data);
      $state.go('client.checkout');
    },function(responseError){
      UserData.set(null);
      OAuthToken.removeToken();
      $ionicPopup.alert({
        title:'Advertência',
        template:'Login ou senha inválidos'
      })
    });
  }
}])

.controller('HomeCtrl', [
  "$scope","User","$ionicLoading", "$ionicPopup","$state",
  function($scope,User,$ionicLoading, $ionicPopup,$state) {

    $ionicLoading.show({
        template: "Carregando..."
    });

    User.query({},
      function(data){
        $scope.user = data.data;
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
  '$scope','$state','$cart','ClientOrder','$ionicLoading','$ionicPopup','Cupom','$cordovaBarcodeScanner',
  function($scope,$state,$cart,ClientOrder,$ionicLoading,$ionicPopup,Cupom,$cordovaBarcodeScanner) {
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
      if ($scope.cupom.value >= total) {
        $ionicLoading.hide();
        $ionicPopup.alert({
            title: "Valor minimo do pedido não alcansado",
            template: "O valor do Cupom é maior que o valor dos pedidos. Adicione mais itens para se chegar ao valor minimo"
        });
      } else {
      ClientOrder.save({id:null},o,function(data){
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
      $state.go('client.pedidos');
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
.controller("ClientCheckoutPedidosView", [
        "$scope", "$stateParams", "ClientOrder", "$ionicLoading",
        function($scope, $stateParams, ClientOrder, $ionicLoading){

            $scope.order = {};

            $ionicLoading.show({
                template: "Carregando..."
            });

            ClientOrder.get({id:$stateParams.id,include: "items,cupom"}, function(data){
                $scope.order = data.data;
                $ionicLoading.hide();
            }, function(dataError){
                $ionicLoading.hide();
                $ionicPopup.alert({
                    title: "Problemas em exibir o detalhe do pedido",
                    template: "dataError = " + JSON.stringify(dataError)
                });
            });
}])

.controller("ClientCheckoutPedidos", [
  '$scope','$state','ClientOrder', "$ionicLoading", "$ionicPopup","$ionicActionSheet",
  function($scope,$state,ClientOrder, $ionicLoading, $ionicPopup, $ionicActionSheet){

    $scope.pedidos = {};

    $ionicLoading.show({
      template: "Carregando..."
    });

    $scope.doRefresh = function () {
      getOrders().then(function(data){
        $scope.pedidos = data.data;
        $scope.$broadcast('scroll.refreshComplete');
      }, function(dataError){
        $scope.$broadcast('scroll.refreshComplete');
        $ionicPopup.alert({
          title: "Problemas em exibir os pedidos",
          template: "serviço indisponivel - tente novamente"
        });
      });
    };

    $scope.openOrderDetail = function (order) {
      $state.go('client.view_order', {id: order.id});
    };

    $scope.showActionSheet = function (order) {
      $ionicActionSheet.show({
        buttons: [
          {text: "Ver Detalhes"},
          {text: 'Ver Entrega'}
        ],

        titleText: 'O que fazer?',
        cancelText: 'Cancelar',
        cancel: function () {
          //botão cancelar
        },

        buttonClicked: function (index) {
          switch (index){
            case 0:
            $state.go('client.view_order', {id: order.id});
            break;
            case 1:
            $state.go('client.view_delivery', {id: order.id});
            break;
          }
        }
      });
    };

    function getOrders(){
      return ClientOrder.query({
        id : null,
        orderBy: 'created_at',
        sortedBy: 'desc'
      }).$promise;
    }
    getOrders().then(function(data){
      $scope.pedidos = data.data;
      $ionicLoading.hide();
    }, function(dataError){
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

}])
.controller('DeliverymanMenuCtrl',[
  '$scope','$state','$ionicLoading','UserData',
  function($scope,$state,$ionicLoading,UserData){
    $scope.user = UserData.get();
    $scope.logout = function(){
      $state.go('logout');
    }

}])
.controller('DeliverymanOrderCtrl',[
  '$scope','$state','$ionicLoading','DeliverymanOrder',
  function($scope, $state, $ionicLoading, DeliverymanOrder){
    $scope.items = [];

    $ionicLoading.show({
      template:'Carregando...'
    });

    $scope.doRefresh = function(){
      getOrders().then(function(data){
        $scope.items = data.data;
        $scope.$broadcast('scroll.refreshComplete');
      },function(dataError){
        $scope.$broadcast('scroll.refreshComplete');
      });
    };

    $scope.openOrderDetail = function(order){
      $state.go('deliveryman.view_order',{id:order.id});
    };

    function getOrders(){
      return DeliverymanOrder.query({
        id:null,
        orderBy:'created_at',
        sortedBy:'desc'
      }).$promise;
    }

    getOrders().then(function(data){
      $scope.items = data.data;
      $ionicLoading.hide();
    },function(dataError){
      $ionicLoading.hide();
    });
}])
.controller('DeliverymanViewOrderCtrl',[
'$scope','$stateParams','DeliverymanOrder','$ionicLoading','$ionicPopup','$cordovaGeolocation',
    function($scope, $stateParams, DeliverymanOrder, $ionicLoading,$ionicPopup,$cordovaGeolocation){
    var watch, lat = null, long = null;
    $scope.order = {};
    $ionicLoading.show({
        template:'Carregando...'
    });

    DeliverymanOrder.get({id:$stateParams.id, include:"items,cupom"},function(data){
        $scope.order = data.data;
        $ionicLoading.hide();
    },function(dataError){
        $ionicLoading.hide();
    });

    $scope.goToDelivey = function(){
        $ionicPopup.alert({
            title:'Advertência',
            template:'Para parar a localização de OK.'
        }).then(function(){
            stopWatchPosition();
        });
        DeliverymanOrder.updateStatus({id:$stateParams.id},{status:1},function(){
            //geo localizacao
            var watchOptions = {
                timeout:3000,
                enableHighAccuracy:false
            };
            watch = $cordovaGeolocation.watchPosition(watchOptions);
            watch.then(null,
                function(responseError){
                    //err
                },function(position){
                    if(!lat){
                        lat = position.coords.latitude;
                        long = position.coords.longitude;
                    }else{
                        long -= 0.0444;
                    }

                    console.log('lat: '+lat+' e long: '+long);

                    DeliverymanOrder.geo({id:$stateParams.id},{
                        lat:lat,
                        long:long
                    });
                });
        });
    };

    $scope.efetuarEntrega = function(){
        DeliverymanOrder.updateStatus({id:$stateParams.id},{status:2},function(){
            $ionicPopup.alert({
                title:'Advertência',
                template:'Status atualizado com sucesso!'
            });
        },function(err){
            $ionicPopup.alert({
                title:'Advertência',
                template:'Erro ao atualizar status'
            });
        });
    };

    function stopWatchPosition(){
        if(watch && typeof watch=='object' && watch.hasOwnProperty('watchID')){
            $cordovaGeolocation.clearWatch(watch.watchId);
        }
    }

}]);