@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoController">
		<code ng-if="debug == true">
			<h3>FILTROS</h3>

			<p>@{{ filters }}</p>

			<h3>DATOS</h3>
			<h4>Detalle</h4>

			<p>@{{ datas.detalle }}</p>

			<h4>Actual</h4>

			<p>@{{ datas.actual }}</p>

			<h4>Historico</h4>

			<p>@{{ datas.historico }}</p>

			<h3>ERRORES</h3>

			<p> @{{ errors }}</p>
		</code>

		<div class="panel panel-default">
			<div class="panel-heading">
				Cuadro de Mando
				<span class="tools pull-right">
					<a class="fa fa-question" id="dashboardTour" href="javascript:"></a>
					<a class="fa fa-chevron-down" href="javascript:"></a>
				</span>
			</div>
			<div class="panel-body">
				<div class="row">

					{{-- BEGIN OPTIONS --}}
					<div class="col-md-2 text-center legend" style="color:white;">
						<div class="row">
							<div class="col-md-12 col-xs-12 col-sm-6">
								{{ HTML::dateRange('dateRange', array('class' => 'form-control', 'ng-model' => 'filters.date', 'ng-change' => 'updateDate()')) }}
							</div>
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(1)">
									<div class="panel" style="background-color:#37468E;">
										<div class="state-value">
											<div class="title">Cargo Fijo Planes</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(2)">
									<div class="panel" style="background-color:#374152;">
										<div class="state-value">
											<div class="title">Cargo Fijo Bolsas</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(3)">
									<div class="panel" style="background-color:#4AB67F;">
										<div class="state-value">
											<div class="title">Descuentos Cargo Fijo y trafico</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(4)">
									<div class="panel" style="background-color:#E70D2F;">
										<div class="state-value">
											<div class="title">Aplicación Unidades Libres Planes</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(5)">
									<div class="panel" style="background-color:#86A6C2;">
										<div class="state-value">
											<div class="title">Aplicación Unidades Libres Bolsas</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{-- END OPTIONS --}}

					{{-- BEGIN GRÁFICO ACTUAL --}}
					<div class="col-md-5">
						<h3 ng-if="isLoadingActual"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

						<div ng-if="errors.actual!= '' && isLoadingActual == false" class="alert alert-warning" role="alert">
							<strong>Warning!</strong> @{{ errors.actual }}.
						</div>

						<h3 ng-show="errors.actual == '' && isLoadingActual == false && datas.actual.length > 1">@{{ filters.titleActual }}</h3>
						<div>
							<div id="actual" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
						</div>
					</div>
					{{-- END GRÁFICO ACTUAL --}}

					{{-- BEGIN GRÁFICO DETALLE --}}
					<div class="col-md-5">
						<h3 ng-if="isLoadingDetalle"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

						<div ng-if="errors.detalle != '' && isLoadingDetalle == false" class="alert alert-warning" role="alert">
							<strong>Warning!</strong> @{{ errors.detalle }}.
						</div>

						<div id="detalle" style="width: 100%; height: 400px; background-color: #FFFFFF;" ng-show="errors.detalle == ''"></div>
					</div>
					{{-- END GRÁFICO DETALLE --}}
				</div>
			</div>
		</div>

		<section class="panel">
			<header class="panel-heading custom-tab dark-tab">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#hist" data-toggle="tab"><i class="fa fa-line-chart fa-lg"></i></a>
					</li>
				</ul>
			</header>
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane active" id="hist">
						<h3 ng-if="isLoadingHistorico"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

						<div ng-if="errors.historico != '' && isLoadingHistorico == false" class="alert alert-warning" role="alert">
							<strong>Warning!</strong> @{{ errors.historico }}.
						</div>

						<div id="historico" style="width: 100%; height: 400px; background-color: #FFFFFF;" ng-show="errors.historico == ''"></div>
					</div>
				</div>
			</div>
		</section>

	</div>
@endsection

@section('file-style')
	{{ HTML::style('js/bootstrap-datepicker/css/datepicker.css') }}
	{{ HTML::style('js/bootstrap-datepicker/css/datepicker-custom.css') }}
@endsection

@section('text-style')
	<style>
		.table tbody tr td {
			vertical-align: middle;
			padding: 5px;
		}

		.legend .row .panel {
			height: 60px;
			margin-bottom: 5px;
		}

		.legend .row .panel:first-child {
			margin-top: 10px;
		}

		.legend .row .panel .state-value .title {
			vertical-align: middle;
			text-align: center;
			padding-top: 10px;
		}

		.options {
			color: #ffffff;
			text-decoration: none;
		}

		.options .panel:hover {
			-webkit-filter: drop-shadow(0px 5px 0px rgba(0, 0, 0, 0.5));
			filter: drop-shadow(0px 5px 0px rgba(0, 0, 0, 0.5));
			-ms-filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=12, OffY=12, Color='#444')";
			filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=12, OffY=12, Color='#444')";
		}

		code {
			line-height: 1.25;
			display: inline-block;
			color: lightslategray;
		}

	</style>
@endsection

@section('file-script')

@endsection

@section('text-script')
	<script type="text/javascript">
		var actualSerialChart = new AmCharts.AmSerialChart();
		var detallePieChart = new AmCharts.AmPieChart();
		var historicoSerialChart = new AmCharts.AmSerialChart();

//		$(function () {
//			actualSerialChart.validateNow();
//			detallePieChart.validateNow();
//			historicoSerialChart.validateNow();
//		});



		// {
		// 	"balloonText": "Porcentaje de <strong>[[title]]</strong> en  [[category]]: [[percents]]%",
		// 	"fillAlphas": 1,
		// 	"id": "AmGraph-2",
		// 	"stackable": false,
		// 	"switchable": false,
		// 	"title": "Incorrectos",
		// 	"type": "line",
		// 	"bullet": "round",
		// 	"lineThickness": 2,
		// 	"bulletSize": 10,
		// 	"valueField": "Incorrectos"
		// }
		// ];

		//     AmCharts.makeChart("detalle", {
		//         "type": "pie",
		//         "pathToImages": "http://cdn.amcharts.com/lib/3/images/",
		//         "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
		//         "labelText": "[[percents]]% ",
		//         "innerRadius": "50%",
		//         "minRadius": 100,
		//         "colors": [
		//         "#374152",
		//         "#E70D2F",
		//         "#12DC49",
		//         "#86A6C2"
		//         ],
		//         "labelsEnabled": false,
		//         "titleField": title,
		//         "valueField": value,
		//         "allLabels": [],
		//         "balloon": {},
		//         "legend": {
		//             "align": "center",
		//             "equalWidths": true,
		//             "markerType": "circle"
		//         },
		//         "titles": [],
		//         "dataProvider": data,
		//         "pathToImages": "http://www.amcharts.com/lib/3/images/",
		//         "amExport": {
		//             "buttonTitle": "Guardar",
		//             "imageFileName": "amCharts",
		//             "buttonAlpha": 1,
		//             "exportJPG": true,
		//             "exportPNG": true,
		//             "exportSVG": true,
		//             "exportPDF": true,
		//             "userCFG": {
		//                 menuItems: [{
		//                     textAlign: 'center',
		//                     icon: 'http://www.amcharts.com/lib/3/images/export.png',
		//                     iconTitle: 'Save chart as an image',
		//                     onclick: function () {
		//                     },
		//                     items: [{
		//                         title: 'JPG',
		//                         format: 'jpg'
		//                     }, {
		//                         title: 'PNG',
		//                         format: 'png'
		//                     }, {
		//                         title: 'XLS',
		//                         format: 'svg'
		//                     }]
		//                 }],
		//                 removeImagery: true
		//             }
		//         }
		//     });

		// AmCharts.makeChart("actual", {
		// 	"type": "serial",
		// 	"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
		// 	"categoryField": "category",
		// 	"startDuration": 1,
		// 	"categoryAxis": {
		// 		"gridPosition": "start"
		// 	},
		// 	"colors": [
		// 	"#374152",
		// 	"#E70D2F"
		// 	],
		// 	"trendLines": [],
		// 	"graphs": graphActual,
		// 	"guides": [],
		// 	"valueAxes": [
		// 	{
		// 		"id": "ValueAxis-1",
		// 		"title": "Cantidad"
		// 	}
		// 	],
		// 	"allLabels": [],
		// 	"balloon": {},
		// 	"legend": {
		// 		"useGraphSettings": true
		// 	},
		// 	"titles": [
		// 	{
		// 		"id": "Title-1",
		// 		"size": 15,
		// 		"text": "Marzo 2015"
		// 	}
		// 	],
		// 	"dataProvider": dataActual,
		// 	"pathToImages": "http://www.amcharts.com/lib/3/images/",
		// 	"amExport": {
		// 		"buttonTitle": "Guardar",
		// 		"imageFileName": "amCharts",
		// 		"buttonAlpha": 1,
		// 		"exportJPG": true,
		// 		"exportPNG": true,
		// 		"exportSVG": true,
		// 		"exportPDF": true,
		// 		"userCFG": {
		// 			menuItems: [{
		// 				textAlign: 'center',
		// 				icon: 'http://www.amcharts.com/lib/3/images/export.png',
		// 				iconTitle: 'Save chart as an image',
		// 				onclick: function () {
		// 				},
		// 				items: [{
		// 					title: 'JPG',
		// 					format: 'jpg'
		// 				}, {
		// 					title: 'PNG',
		// 					format: 'png'
		// 				}, {
		// 					title: 'XLS',
		// 					format: 'svg'
		// 				}]
		// 			}],
		// 			removeImagery: true
		// 		}
		// 	}
		// });

		// AmCharts.makeChart("historico", {
		// 	"type": "serial",
		// 	"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
		// 	"categoryField": "category",
		// 	"colors": [
		// 	"#374152",
		// 	"#E70D2F"
		// 	],
		// 	"startDuration": 1,
		// 	"categoryAxis": {
		// 		"gridPosition": "start",
		// 		"titleFontSize": 2
		// 	},
		// 	"trendLines": [],
		// 	"graphs": graphHistorico,
		// 	"guides": [],
		// 	"valueAxes": [
		// 	{
		// 		"id": "ValueAxis-1",
		// 		"maximum": 0,
		// 		"radarCategoriesEnabled": false,
		// 		"stackType": "100%",
		// 		"unit": "%",
		// 		"autoGridCount": false,
		// 		"title": "Porcentaje"
		// 	}
		// 	],
		// 	"allLabels": [],
		// 	"balloon": {},
		// 	"legend": {
		// 		"useGraphSettings": true
		// 	},
		// 	"titles": [
		// 	{
		// 		"id": "Title-1",
		// 		"size": 15,
		// 		"text": "Historial de Procesos 2015"
		// 	}
		// 	],
		// 	"chartCursor": {
		// 		"oneBalloonOnly": false
		// 	},
		// 	"dataProvider": dataHistorico,
		// 	"pathToImages": "http://www.amcharts.com/lib/3/images/",
		// 	"amExport": {
		// 		"buttonTitle": "Guardar",
		// 		"imageFileName": "amCharts",
		// 		"buttonAlpha": 1,
		// 		"exportJPG": true,
		// 		"exportPNG": true,
		// 		"exportSVG": true,
		// 		"exportPDF": true,
		// 		"userCFG": {
		// 			menuItems: [{
		// 				textAlign: 'center',
		// 				icon: 'http://www.amcharts.com/lib/3/images/export.png',
		// 				iconTitle: 'Save chart as an image',
		// 				onclick: function () {
		// 				},
		// 				items: [{
		// 					title: 'JPG',
		// 					format: 'jpg'
		// 				}, {
		// 					title: 'PNG',
		// 					format: 'png'
		// 				}, {
		// 					title: 'XLS',
		// 					format: 'svg'
		// 				}]
		// 			}],
		// 			removeImagery: true
		// 		}
		// 	}
		// });

	</script>
@endsection
