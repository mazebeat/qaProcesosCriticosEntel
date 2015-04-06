'use strict';

// homeControlleruserFactory
qaProcesosCriticos.controller('homeController', ['$scope', '$http', '$window', 'rootFactory', 'apiFactory', 'storageService', function ($scope, $http, $window, rootFactory, apiFactory, storageService) {
}]);

// adminController
qaProcesosCriticos.controller('adminController', ['$scope', '$http', '$window', 'rootFactory', 'apiFactory', 'chartService', 'storageService', function ($scope, $http, $window, rootFactory, apiFactory, chartService, storageService) {
}]);

// consolidadoController
qaProcesosCriticos.controller('consolidadoController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'pendingRequestsService', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, pendingRequestsService, chartService) {
    $scope.debug = false;

    var date = new Date();
    $scope.isLoading = false;
    $scope.datas = {
        detalle: {},
        actual: {},
        historico: {}
    };
    $scope.filters = {
        tipoDetalle: "cargofijoplan".toUpperCase(),
        date: parseInt(date.getMonth() + 1) + '-' + parseInt(date.getFullYear()),
        plan: 1,
        titleActual: '',
        mes: parseInt(date.getMonth() + 1),
        ano: parseInt(date.getFullYear())
    };
    $scope.errors = {
        detalle: '',
        actual: '',
        historico: ''
    };

    // VALIDATE REQUEST ERRORS
    $scope.hasError = function (data, obj) {

        //console.info(data);
        //console.log(angular.isObject(data), obj);
        //console.log(data != null, obj);
        //console.log(data.length, obj);
        //console.log(apiFactory.isUndefinedOrNull({}), obj);
        //console.log(!apiFactory.isObjectAndNotNull({}), obj);

        //if (apiFactory.isUndefinedOrNull(data)) {
        //    console.warn('Data is undefined', obj);
        //    return true;
        //}

        if (!apiFactory.isObjectAndNotNull(data)) {
            console.warn('Data is not a object or is empty', obj);
            return true;
        }

        if (data[0].hasOwnProperty('estado') && data[0].estado == false) {
            //console.warn('Found error into request', obj);
            return true;
        }

        return false;
    }

    // BEGIN UPDATE DATE, WHEN SELECT DATE FROM SELECT INPUT
    $scope.updateDate = function () {
        var date = apiFactory.splitString($scope.filters.date, '-');
        $scope.filters.ano = date[1];
        $scope.filters.mes = date[0];

        $scope.changeDashboard($scope.filters.plan);
    }

    // BEGIN CHANGE DASHBOARD, WHEN PRESS ANY BUTTON
    $scope.changeDashboard = function (id) {
        $scope.isLoading = true;

        switch (id) {
            case 1:
                $scope.filters.tipoDetalle = ("cargofijoplan").toUpperCase();
                $scope.filters.titleActual = ("cargo fijo plan").toUpperCase();
                break;

            case 2:
                $scope.filters.tipoDetalle = ("cargofijobolsa").toUpperCase();
                $scope.filters.titleActual = ("cargo fijo bolsa").toUpperCase();
                break;

            case 3:
                $scope.filters.tipoDetalle = ("descuentoalcargofijoytrafico").toUpperCase();
                $scope.filters.titleActual = ("descuento al cargo fijo y trafico").toUpperCase();
                break;

            case 4:
                $scope.filters.tipoDetalle = ("aplicacionunidadeslibresplan").toUpperCase();
                $scope.filters.titleActual = ("aplicacion unidades libres plan").toUpperCase();
                break;

            case 5:
                $scope.filters.tipoDetalle = ("aplicacionunidadeslibresbolsa").toUpperCase();
                $scope.filters.titleActual = ("aplicacion unidades libres bolsa").toUpperCase();
                break;
        }

        $scope.filters.plan = id;
        $scope.detalle();
        $scope.actual();
        $scope.isLoading = false;
    };

    // BEGIN GRÁFICO DETALLE
    $scope.detalle = function () {
        $scope.isLoadingDetalle = true;
        apiFactory.post('Grafico/postGraficoPie', {ano: $scope.filters.ano, mes: $scope.filters.mes})
            .then(function (data) {
                console.info('DETALLE', data, data.length);
                if ($scope.hasError(data, 'gDetalle')) {
                    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                        $scope.errors.detalle = data[0].message;
                    }
                    $scope.datas.detalle = {};
                } else {
                    $scope.errors.detalle = '';
                    $scope.datas.detalle = data;
                    chartService.processDataDetalle($scope.datas.detalle);
                }
            })
            .catch(function (error) {
                console.warn(error)
                $scope.errors.detalle = error.message;
                $scope.datas.detalle = {};
            })
            .finally(function () {
                $scope.isLoadingDetalle = false;
                detallePieChart.validateData();

            });
    };

    // BEGIN GRÁFICO ACTUAL
    $scope.actual = function () {
        $scope.isLoadingActual = true;
        apiFactory.post('Grafico/postGraficoBarra', {ano: $scope.filters.ano, mes: $scope.filters.mes, tipoDetalle: $scope.filters.tipoDetalle})
            .then(function (data) {
                console.info('ACTUAL', data, data.length);
                if ($scope.hasError(data, 'gActual')) {
                    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                        $scope.errors.actual = data[0].message;
                    }
                    $scope.datas.actual = {};
                } else {
                    $scope.errors.actual = '';
                    $scope.datas.actual = data;
                    chartService.processDataActual($scope.datas.actual);
                }
            })
            .catch(function (error) {
                console.warn(error);
                $scope.errors.actual = error.message;
                $scope.datas.actual = {};

            })
            .finally(function () {
                $scope.isLoadingActual = false;
                actualSerialChart.validateNow();
            });
    };

    // BEGIN GRÁFICO HISTÓRICO
    $scope.historico = function () {
        $scope.isLoadingHistorico = true;
        apiFactory.post('Grafico/postHistorial', {
            mesInicio: $scope.filters.imes,
            anoInicio: $scope.filters.iano,
            mesFin: $scope.filters.mes,
            anoFin: $scope.filters.ano,
            tipoDetalle: $scope.filters.tipoDetalle
        })
            .then(function (data) {
                //console.info('HISTORICO', data);
                if ($scope.hasError(data, 'gHtistorico')) {
                    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                        $scope.errors.historico = data[0].message;
                    }
                    $scope.datas.historico = {};
                } else {
                    $scope.errors.historico = '';
                    $scope.datas.historico = data;
                    chartService.processDataHistorico($scope.datas.historico);
                }
            })
            .catch(function (error) {
                console.warn(error);
                $scope.errors.historico = error.message;
                $scope.datas.historico = {};
            })
            .finally(function () {
                $scope.isLoadingHistorico = false;
                historicoSerialChart.validateData();

            });
    };

    // BEGIN INIT
    $scope.init = function () {
        $scope.changeDashboard($scope.filters.plan);

        var date = new Date();
        var desde = new Date(date.getFullYear(), parseInt(date.getMonth() - 13), 1);
        var hasta = new Date(date.getFullYear(), parseInt(date.getMonth() + 1), 0);

        desde = apiFactory.splitString(apiFactory.formatDates(desde), '-');
        hasta = apiFactory.splitString(apiFactory.formatDates(hasta), '-');

        $scope.filters.imes = parseInt(desde[1]);
        $scope.filters.iano = parseInt(desde[0]);
        //$scope.filters.mes = parseInt(hasta[1]);
        //$scope.filters.ano = parseInt(hasta[0]);

        $scope.historico();
        $scope.actual();
    };

    // CALL FUNCTION
    $scope.init();
    //pendingRequestsService.cancelAll();
}]);

// consolidadoIndividualController 
qaProcesosCriticos.controller('consolidadoIndividualController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
}]);

// consultaIndividualController 
qaProcesosCriticos.controller('consultaIndividualController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
    $scope.debug = false;

    var date = new Date();

    $scope.isLoading = false;
    $scope.datas = {
        clientePorEstado: {}
    };
    $scope.filters = {
        date: '', // parseInt(date.getMonth() + 1) + '-' + parseInt(date.getFullYear()),
        mes: parseInt(date.getMonth() + 1),
        ano: parseInt(date.getFullYear()),
        tipoDetalle: '',
        estado: '',
        cuenta: '',
        contrato: ''
    };
    $scope.errors = {
        estado: '',
        message: ''
    };

    // BEGIN UPDATE DATE, WHEN SELECT DATE FROM SELECT INPUT
    $scope.updateDate = function () {
        if ($scope.filters.date != '') {
            var date = apiFactory.splitString($scope.filters.date, '-');
            $scope.filters.ano = date[1];
            $scope.filters.mes = date[0];
        }
    }

    $scope.processData = function (data) {
        var array = apiFactory.createDataForTable(data);
        $.post('/gentable', $.param(array), function (data) {
            $('#tableresponse').html('').html(data);
        });
    }


    $scope.changeTipoDetalle = function (id) {
        switch (parseInt(id)) {
            case 1:
                $scope.filters.tipoDetalle = ("cargofijoplan").toUpperCase();
                break;

            case 2:
                $scope.filters.tipoDetalle = ("cargofijobolsa").toUpperCase();
                break;

            case 3:
                $scope.filters.tipoDetalle = ("descuentoalcargofijoytrafico").toUpperCase();
                break;

            case 4:
                $scope.filters.tipoDetalle = ("aplicacionunidadeslibresplan").toUpperCase();
                break;

            case 5:
                $scope.filters.tipoDetalle = ("aplicacionunidadeslibresbolsa").toUpperCase();
                break;
        }
    }

    // BEGIN GRÁFICO DETALLE
    $scope.clientePorEstado = function () {
        apiFactory.post('DatoCliente/postClientesPorEstado', {ano: $scope.filters.ano, mes: $scope.filters.mes, tipoDetalle: $scope.filters.tipoDetalle, estado: $scope.filters.estado})
            .then(function (data) {
                console.info('CLIENTE POR ESTADO', data);
                $scope.errors.estado = false;
                $scope.errors.detalle = '';
                $scope.datas.clientePorEstado = data;

                if (data.length > 1) {
                    $scope.processData(data);
                } else {
                    $('#tableresponse').html('');
                }
            })
            .catch(function (error) {
                console.warn(error)
                $scope.errors.estado = false;
                $scope.errors.message = error.message;
            })
            .finally(function () {
                $scope.isLoading = false;
            });
    };

    // BEGIN GRÁFICO DETALLE
    $scope.clientePorCuenta = function () {
        apiFactory.post('DatoCliente/postClientesPorCuenta', {
            ano: $scope.filters.ano,
            mes: $scope.filters.mes,
            tipoDetalle: $scope.filters.tipoDetalle,
            estado: $scope.filters.estado,
            cuenta: $scope.filters.cuenta,
            contrato: $scope.filters.contrato
        })
            .then(function (data) {
                console.info('CLIENTE POR ESTADO', data);
                $scope.errors.estado = false;
                $scope.errors.detalle = '';
                $scope.datas.clientePorEstado = data;

                $scope.processData(data);
            })
            .catch(function (error) {
                console.warn(error)
                $scope.errors.estado = false;
                $scope.errors.message = error.message;
            })
            .finally(function () {
                $scope.isLoading = false;
            });
    };

    // BEGIN SUBMIT FORM - ON CLICK EVENT
    $scope.submitForm = function (isValid) {
        $scope.isLoading = true;
        // check to make sure the form is completely valid
        if (isValid) {
            $scope.changeTipoDetalle($scope.filters.td);

            if ($scope.filters.cuenta != '') {
                $scope.clientePorCuenta();
            }

            if ($scope.filters.estado != '') {
                $scope.clientePorEstado();
            }
        } else {
            $scope.isLoading = false;
            $scope.errors.estado = false;
            $scope.errors.message = 'Formulario Invalido.';
        }
    };

}]);

// informeController 
qaProcesosCriticos.controller('informeController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
}]);

// cargadataController 
qaProcesosCriticos.controller('cargadataController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
}]);