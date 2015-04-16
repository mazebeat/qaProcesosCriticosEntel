'use strict';

qaProcesosCriticos
    // homeControlleruserFactory --> LISTO
    .controller('homeController', ['$scope', '$http', '$window', 'rootFactory', 'apiFactory', function ($scope, $http, $window, rootFactory, apiFactory) {
        $scope.debug = false;

        $scope.user = {
            username: '',
            password: ''
        };
        $scope.errors = {
            valid: true,
            message: ''
        }
        $scope.isLoading = false;


        // BEGIN GRÁFICO ACTUAL
        $scope.validUser = function () {
            $scope.isLoadingActual = true;
            loginButton.start();

            apiFactory.post('Login/postValidar', {user: $scope.user.username, pass: $scope.user.password})
                .then(function (data) {
                    //console.info('USER', data);
                    if (data.estado) {
                        $scope.errors.valid = true;
                        $scope.errors.message = '';

                        var postData = {
                            id: data.data.id,
                            type: data.data.type,
                            nombre: data.data.nombre,
                            privilegio: data.data.privilegio,
                            usuario: data.data.usuario,
                            password: data.data.password
                        }

                        $http.post('/login', $.param(postData))
                            .then(function (response) {
                                if (response.status == 200 && response.data.estado) {
                                    $window.location.href = '/dashboard'
                                }
                            })
                            .catch(function (error) {
                                console.warn(error);
                                $scope.errors.valid = false;
                                $scope.errors.message = error.message;
                                $scope.user = {
                                    username: '',
                                    password: ''
                                }
                            })
                            .finally(function () {
                                $scope.isLoadingActual = false;
                                loginButton.stop();
                            });
                    } else {
                        $scope.errors.valid = false;
                        $scope.errors.message = 'USUARIO INCORRECTO';
                    }
                })
                .catch(function (error) {
                    console.warn(error);
                    $scope.errors.valid = false;
                    $scope.errors.message = error.message;
                    $scope.user = {
                        username: '',
                        password: ''
                    }
                })
                .finally(function () {
                    $scope.isLoadingActual = false;
                    loginButton.stop();
                });
        };

    }])

    // adminController
    .controller('adminController', ['$scope', '$http', '$window', 'rootFactory', 'apiFactory', 'chartService', 'storageService', function ($scope, $http, $window, rootFactory, apiFactory, chartService, storageService) {
    }])

    // consolidadoController --> LISTO
    .controller('consolidadoIndexController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'pendingRequestsService', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, pendingRequestsService, chartService) {
        $scope.debug = false;

        var date = new Date();
        $scope.isLoading = false;
        $scope.datas = {
            detalle: {},
            actual: {},
            historico: {}
        };
        $scope.filters = {
            tipoDetalle: "CARGOFILOPLAN",
            date: parseInt(date.getMonth() + 1) + '-' + parseInt(date.getFullYear()),
            plan: 1,
            titleActual: '',
            typeDocument: 'CG_NORMAL_BOLETA',
            mes: parseInt(date.getMonth() + 1),
            ano: parseInt(date.getFullYear())
        };
        $scope.errors = {
            detalle: '',
            actual: '',
            historico: ''
        };

        // BEGIN ASIGNATE ERROR MESSAGE
        $scope.asignMsgError = function (obj, message) {
            switch (obj) {
                case 'detalle':
                    $scope.errors.detalle = message;
                    break;

                case 'actual':
                    $scope.errors.actual = message;
                    break;

                case 'historico':
                    $scope.errors.historico = message;
                    break;

            }
        }

        // VALIDATE REQUEST ERRORS
        $scope.hasError = function (data, obj) {
            var message = '';
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
                message = 'Data is not a object or is empty'
                //$scope.asignMsgError(obj, message)
                return true;
            }

            if (data[0].hasOwnProperty('estado') && data[0].estado == false) {
                console.warn('Found error into request', obj);
                message = 'Found error into request';
                //$scope.asignMsgError(obj, message)
                return true;
            }

            return false;
        }

        // BEGIN UPDATE DATE, WHEN SELECT DATE FROM SELECT INPUT
        $scope.updateDate = function () {
            var date = apiFactory.splitString($scope.filters.date, '-');
            $scope.filters.ano = date[1];
            $scope.filters.mes = date[0];

            if ($scope.filters.plan != 0 && !$scope.isLoading) {
                $scope.changeDashboard($scope.filters.plan);
            }
        }

        // BEGIN CHANGE DOCUMENT TYPE SELECT
        $scope.changeDocumentType = function () {
            if ($scope.filters.plan != 0 && !$scope.isLoading) {
                $scope.changeDashboard($scope.filters.plan);
            }
        }

        // BEGIN CHANGE DASHBOARD, WHEN PRESS ANY BUTTON
        $scope.changeDashboard = function (id) {
            $scope.isLoading = true;
            $scope.filters.plan = id;

            switch (id) {
                case 1:
                    $scope.filters.tipoDetalle = "CARGOFIJOPLAN";
                    $scope.filters.titleActual = "CARGO FIJO PLAN";
                    break;

                case 2:
                    $scope.filters.tipoDetalle = "CARGOFIJOBOLSA";
                    $scope.filters.titleActual = "CARGO FIJO BOLSA";
                    break;

                case 3:
                    $scope.filters.tipoDetalle = "DESCUENTOALCARGOFIJOYTRAFICO";
                    $scope.filters.titleActual = "DESCUENTO AL CARGO FIJO Y TRÁFICO";
                    break;

                case 4:
                    $scope.filters.tipoDetalle = "APLICACIONUNIDADESLIBRESPLAN";
                    $scope.filters.titleActual = "APLICACIÓN UNIDADES LIBRES PLAN";
                    break;

                case 5:
                    $scope.filters.tipoDetalle = "APLICACIONUNIDADESLIBRESBOLSA";
                    $scope.filters.titleActual = "APLICACIÓN UNIDADES LIBRES BOLSA";
                    break;
            }

            $scope.actual();
            $scope.detalle();
            $scope.historico();

            $scope.isLoading = false;
        };

        // BEGIN GRÁFICO DETALLE
        $scope.detalle = function () {
            $scope.isLoadingDetalle = true;
            //apiFactory.post('Grafico/postGraficoPie', {ano: $scope.filters.ano, mes: $scope.filters.mes, documento: $scope.filters.typeDocument})
            apiFactory.post('Grafico/postGraficoBarra', {ano: $scope.filters.ano, mes: $scope.filters.mes, tipoDetalle: $scope.filters.tipoDetalle, documento: $scope.filters.typeDocument})
                .then(function (data) {
                    //console.info('DETALLE', data, data.length);
                    if ($scope.hasError(data, 'detalle')) {
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
                    //console.warn(error)
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
            $scope.isLoadingActual = false;
            apiFactory.post('Grafico/postGraficoBarra', {ano: $scope.filters.ano, mes: $scope.filters.mes, tipoDetalle: $scope.filters.tipoDetalle, documento: $scope.filters.typeDocument})
                .then(function (data) {
                    //console.info('ACTUAL', data, data.length);
                    if ($scope.hasError(data, 'actual')) {
                        if (data.length > 0 && data[0].hasOwnProperty('message')) {
                            $scope.errors.actual = data[0].message;
                        }
                        $scope.datas.actual = {};
                    } else {
                        $scope.isLoadingActual = false;
                        $scope.errors.actual = '';
                        $scope.datas.actual = data;
                        chartService.processDataActual($scope.datas.actual);
                    }
                })
                .catch(function (error) {
                    //console.warn(error);
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
                mesFin: $scope.filters.fmes,
                anoFin: $scope.filters.fano,
                tipoDetalle: $scope.filters.tipoDetalle,
                documento: $scope.filters.typeDocument
            })
                .then(function (data) {
                    //console.info('HISTORICO', data);
                    if ($scope.hasError(data, 'historico')) {
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
                    //console.warn(error);
                    $scope.errors.historico = error.message;
                    $scope.datas.historico = {};
                })
                .finally(function () {
                    $scope.isLoadingHistorico = false;
                    historicoSerialChart.validateNow();

                });
        };

        // BEGIN INIT
        $scope.init = function () {
            var date = new Date();

            var desde = new Date(date.getFullYear(), parseInt(date.getMonth() - 13), 1);
            var hasta = new Date(date.getFullYear(), parseInt(date.getMonth() + 1), 0);
            desde = apiFactory.splitString(apiFactory.formatDates(desde), '-');

            hasta = apiFactory.splitString(apiFactory.formatDates(hasta), '-');
            //console.log(desde, hasta)
            $scope.filters.imes = parseInt(desde[1]);
            $scope.filters.iano = parseInt(desde[0]);

            $scope.filters.fmes = parseInt(hasta[1]);
            $scope.filters.fano = parseInt(hasta[0]);

            $scope.changeDashboard($scope.filters.plan);
        };

        // CALL FUNCTION
        $scope.init();
    }
    ])

    // consolidadoIndividualController
    .controller('consolidadoIndividualController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
        $scope.debug = false;

        var date = new Date();
        $scope.isLoading = false;
        $scope.datas = {
            detalle: {},
            actual: {}
        };
        $scope.table = {
            qBuenos: 0,
            qErrores: 0,
            qObservaciones: 0
        };
        $scope.filters = {
            date: parseInt(date.getMonth() + 1) + '-' + parseInt(date.getFullYear()),
            typeDocument: 'CG_NORMAL_BOLETA',
            mes: parseInt(date.getMonth() + 1),
            ano: parseInt(date.getFullYear())
        };
        $scope.errors = {
            estado: true,
            messagel: ''
        };

        // BEGIN ASIGNATE ERROR MESSAGE
        $scope.asignMsgError = function (obj, message) {
            switch (obj) {
                case 'detalle':
                    $scope.errors.detalle = message;
                    break;

                case 'actual':
                    $scope.errors.actual = message;
                    break;

                case 'historico':
                    $scope.errors.historico = message;
                    break;

            }
        }

        // VALIDATE REQUEST ERRORS
        $scope.hasError = function (data, obj) {
            var message = '';

            if (!apiFactory.isObjectAndNotNull(data)) {
                console.warn('Data is not a object or is empty', obj);
                message = 'Data is not a object or is empty'
                $scope.asignMsgError(obj, message)
                return true;
            }

            if (data[0].hasOwnProperty('estado') && data[0].estado == false) {
                console.warn('Found error into request', obj);
                message = 'Found error into request';
                //$scope.asignMsgError(obj, message)
                return true;
            }

            return false;
        }

        // BEGIN UPDATE DATE, WHEN SELECT DATE FROM SELECT INPUT
        $scope.updateDate = function () {
            var date = apiFactory.splitString($scope.filters.date, '-');
            $scope.filters.ano = date[1];
            $scope.filters.mes = date[0];

            if ($scope.filters.plan != 0 && !$scope.isLoading) {
                $scope.changeConsolidadoI();
            }
        }

        // BEGIN CHANGE DOCUMENT TYPE SELECT
        $scope.changeDocumentType = function () {
            if ($scope.filters.plan != 0 && !$scope.isLoading) {
                $scope.changeConsolidadoI();
            }
        }

        // BEGIN GRÁFICO DETALLE --> LISTO
        $scope.detalle = function () {
            $scope.isLoadingDetalle = true;
            apiFactory.post('Grafico/postConsolidadoMes', {ano: $scope.filters.ano, mes: $scope.filters.mes, documento: $scope.filters.typeDocument})
                .then(function (data) {
                    //console.info('DETALLE', data, data.length);
                    //if ($scope.hasError(data, 'detalle')) {
                    //    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                    //        $scope.errors.detalle = data[0].message;
                    //    }
                    //    $scope.datas.detalle = {};
                    //} else {
                    $scope.errors.detalle = '';
                    $scope.datas.detalle = data;
                    chartService.processDataDetalle2($scope.datas.detalle);
                    //}
                })
                .catch(function (error) {
                    //console.warn(error)
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
            $scope.isLoadingActual = false;
            apiFactory.post('Grafico/postConsolidadoMes', {ano: $scope.filters.ano, mes: $scope.filters.mes, documento: $scope.filters.typeDocument})
                .then(function (data) {
                    //console.info('ACTUAL', data, data.length);
                    //if ($scope.hasError(data, 'actual')) {
                    //    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                    //        $scope.errors.actual = data[0].message;
                    //    }
                    //    $scope.datas.actual = {};
                    //} else {
                    $scope.isLoadingActual = false;
                    $scope.errors.actual = '';
                    $scope.datas.actual = data;
                    chartService.processDataActual2($scope.datas.actual);
                    //}
                })
                .catch(function (error) {
                    //console.warn(error);
                    $scope.errors.actual = error.message;
                    $scope.datas.actual = {};

                })
                .finally(function () {
                    $scope.isLoadingActual = false;
                    actualSerialChart.validateNow();
                });
        };

        $scope.createTable = function () {
            console.info('CREATE TABLE');
            apiFactory.post('Grafico/postConsolidadoMes', {ano: $scope.filters.ano, mes: $scope.filters.mes, documento: $scope.filters.typeDocument})
                .then(function (data) {
                    //console.info('DETALLE', data, data.length);
                    //if ($scope.hasError(data, 'detalle')) {
                    //    if (data.length > 0 && data[0].hasOwnProperty('message')) {
                    //        $scope.errors.detalle = data[0].message;
                    //    }
                    //    $scope.datas.detalle = {};
                    //} else {
                    $scope.errors.detalle = '';
                    $scope.datas.detalle = data;
                    angular.forEach($scope.datas.detalle.data, function (v, k) {
                        console.info(v, k);

                        if (k === 'qBuenos') {
                            $scope.table.qBuenos = v;
                        }

                        if (k === 'qErrores') {
                            $scope.table.qErrores = v;
                        }

                        if (k === 'qObservaciones') {
                            $scope.table.qObservaciones = v;
                        }
                    });
                    //}
                })
                .catch(function (error) {
                    //console.warn(error)
                    $scope.errors.detalle = error.message;
                    $scope.datas.detalle = {};
                })
                .finally(function () {
                    $scope.isLoadingDetalle = false;
                    detallePieChart.validateData();
                });

        };

        // BEGIN CHANGE DASHBOARD, WHEN PRESS ANY BUTTON
        $scope.changeConsolidadoI = function () {
            $scope.isLoading = true;

            $scope.actual();
            $scope.detalle();
            $scope.createTable();

            $scope.isLoading = false;
        };

        // BEGIN INIT
        $scope.init = function () {
            $scope.changeConsolidadoI();
        };

        // CALL FUNCTION
        $scope.init();
    }])

    // consultaIndividualController
    .controller('consultaIndividualController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
        $scope.debug = false;

        var date = new Date();

        $scope.isLoading = false;
        $scope.datas = {
            clientePorEstado: {}
        };
        $scope.filters = {
            typeDocument: '',
            date: '',
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

        // BEGIN PROCESS DATA
        $scope.processData = function (data) {
            var array = apiFactory.createDataForTable(data);
            $.post('/gentable', $.param(array), function (data) {
                $('#tableresponse').html('').html(data);
            });
        }

        // BEGIN CHANGE TIPO DETALLE
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
            apiFactory.post('DatoCliente/postClientesPorEstado', {
                mes: $scope.filters.mes,
                ano: $scope.filters.ano,
                documento: $scope.filters.typeDocument,
                tipoDetalle: $scope.filters.tipoDetalle,
                estado: $scope.filters.estado
            })
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
                //documento: $scope.filters.typeDocument,
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

                if ($scope.filters.cuenta != '' || $scope.filters.contrato != '') {
                    $scope.clientePorCuenta();
                } else {
                    $scope.clientePorEstado();
                }
            } else {
                $scope.isLoading = false;
                $scope.errors.estado = false;
                $scope.errors.message = 'Formulario Invalido.';
            }
        };

    }])

    // informeController
    .controller('informeController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
        $scope.debug = false;

        var date = new Date();
        $scope.isLoading = false;
        $scope.filters = {
            date: parseInt(date.getMonth() + 1) + '-' + parseInt(date.getFullYear()),
            mes: parseInt(date.getMonth() + 1),
            ano: parseInt(date.getFullYear()),
            informs: {}
        };
        $scope.errors = {
            estado: true,
            message: ''
        };

        // BEGIN UPDATE DATE, WHEN SELECT DATE FROM SELECT INPUT
        $scope.updateDate = function () {
            var date = apiFactory.splitString($scope.filters.date, '-');
            $scope.filters.ano = date[1];
            $scope.filters.mes = date[0];
        }

        // BEGIN SEARCH ALL FROM WS METHOD
        $scope.searchInforms = function () {
            apiFactory.post('Documento/postListaDocumentos', {ano: $scope.filters.ano, mes: $scope.filters.mes})
                .then(function (data) {
                    console.info('INFORMES', data);

                    if (data.length <= 0) {
                        $scope.errors.estado = false;
                        $scope.errors.message = 'No se han encontrado informes para la fecha ' + $scope.filters.date;
                    } else {
                        $scope.filters.informs = data
                        $scope.errors.estado = true;
                        $scope.errors.message = '';
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

        // BEGIN UPDATE INFORMS LIST
        $scope.updateInforms = function () {
            $scope.isLoading = true;
            $scope.searchInforms();
        }

        if (!window.atob) {
            var tableStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
            var table = tableStr.split("");

            window.atob = function (base64) {
                if (/(=[^=]+|={3,})$/.test(base64)) throw new Error("String contains an invalid character");
                base64 = base64.replace(/=/g, "");
                var n = base64.length & 3;
                if (n === 1) throw new Error("String contains an invalid character");
                for (var i = 0, j = 0, len = base64.length / 4, bin = []; i < len; ++i) {
                    var a = tableStr.indexOf(base64[j++] || "A"), b = tableStr.indexOf(base64[j++] || "A");
                    var c = tableStr.indexOf(base64[j++] || "A"), d = tableStr.indexOf(base64[j++] || "A");
                    if ((a | b | c | d) < 0) throw new Error("String contains an invalid character");
                    bin[bin.length] = ((a << 2) | (b >> 4)) & 255;
                    bin[bin.length] = ((b << 4) | (c >> 2)) & 255;
                    bin[bin.length] = ((c << 6) | d) & 255;
                }
                ;
                return String.fromCharCode.apply(null, bin).substr(0, bin.length + n - 4);
            };

            try {
                window.btoa = function (bin) {
                    for (var i = 0, j = 0, len = bin.length / 3, base64 = []; i < len; ++i) {
                        var a = bin.charCodeAt(j++), b = bin.charCodeAt(j++), c = bin.charCodeAt(j++);
                        if ((a | b | c) > 255) throw new Error("String contains an invalid character");
                        base64[base64.length] = table[a >> 2] + table[((a << 4) & 63) | (b >> 4)] +
                        (isNaN(b) ? "=" : table[((b << 2) & 63) | (c >> 6)]) +
                        (isNaN(b + c) ? "=" : table[c & 63]);
                    }
                    return base64.join("");
                };
            } catch (err) {
                window.btoa = function (bin) {
                    for (var i = 0, j = 0, len = bin.length / 3, base64 = []; i < len; ++i) {
                        var a = bin.charCodeAt(j++), b = bin.charCodeAt(j++), c = bin.charCodeAt(j++);
                        if ((a | b | c) > 255) throw new Error("String contains an invalid character");
                        base64[base64.length] = table[a >> 2] + table[((a << 4) & 63) | (b >> 4)] +
                        (isNaN(b) ? "=" : table[((b << 2) & 63) | (c >> 6)]) +
                        (isNaN(b + c) ? "=" : table[c & 63]);
                    }
                    return base64.join("");
                };
            }

        }

        $scope.hexToBase64 = function (str) {
            try {
                return btoa(String.fromCharCode.apply(null,
                        str.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" "))
                );
            } catch (erro) {
                return btoa(String.fromCharCode.apply(null,
                        str.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" "))
                );
            }
        }

        // BEGIN DOWNLOAD ONE INFORM
        $scope.downloadInform = function (id, name) {
            var idInform = id;

            apiFactory.post('Documento/postDocumento', {id: idInform})
                .then(function (data) {
                    if (data.estado) {
                        $scope.errors.estado = true;
                        $scope.errors.message = '';

                        var pom = document.createElement('a');
                        try {
                            var base = $scope.hexToBase64(data.data.value);
                            pom.setAttribute('href', 'data:text/csv;charset=utf-8;base64,' + base);
                        } catch (err) {
                            var base = $scope.hexToBase64(data.data.value);
                            pom.setAttribute('href', 'data:text/csv;charset=utf-8;base64,' + base);
                        }
                        pom.setAttribute('download', name);

                        pom.style.display = 'none';
                        document.body.appendChild(pom);

                        pom.click();

                        document.body.removeChild(pom);
                    } else {
                        $scope.errors.estado = false;
                        $scope.errors.message = error.message;
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
        }
    }])

    // cargadataController
    .controller('cargadataController', ['$scope', '$http', '$q', 'storageService', 'rootFactory', 'apiFactory', 'chartService', function ($scope, $http, $q, storageService, rootFactory, apiFactory, chartService) {
    }])

    .controller('postController', ['$http', '$scope', function ($http, $scope) {

        $scope.posts = [];
        $scope.totalPages = 0;
        $scope.currentPage = 1;
        $scope.range = [];

        $scope.getPosts = function (pageNumber) {

            if (pageNumber === undefined) {
                pageNumber = '1';
            }

            $http.get('/posts-json?page=' + pageNumber).success(function (response) {

                $scope.posts = response.data;
                $scope.totalPages = response.last_page;
                $scope.currentPage = response.current_page;

                // Pagination Range
                var pages = [];

                for (var i = 1; i <= response.last_page; i++) {
                    pages.push(i);
                }

                $scope.range = pages;

            });

        };

    }])