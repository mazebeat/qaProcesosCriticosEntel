@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoController">
		<div class="panel panel-default">
			<div class="panel-heading">
				Cuadro de Mando Individual
                <span class="tools pull-right">
                <a class="fa fa-question" id="dashboardTour" href="javascript:"></a>
                <a class="fa fa-chevron-down" href="javascript:"></a>
                </span>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<h4>Proceso: <strong>Aplicación Unidades Libres Planes</strong></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<table class="table table-hover text-center">
							<thead>
							<tr>
								<th></th>
								<th class="text-center">Correctos</th>
								<th class="text-center">Incorrectos</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td></td>
								<td>29</td>
								<td>11</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-8">
						<div id="actual" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
					</div>
				</div>
			</div>
		</div>
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
	</style>
@endsection

@section('file-script')

@endsection

@section('text-script')
	<script type="text/javascript">
		AmCharts.makeChart("actual", {
			"type": "serial",
			"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
			"categoryField": "category",
			"startDuration": 1,
			"categoryAxis": {
				"gridPosition": "start"
			},
			"colors": [
				"#374152",
				"#E70D2F"
			],
			"trendLines": [],
			"graphs": [
				{
					"balloonText": "<strong>[[title]]</strong> en [[category]]:[[value]]",
					"fillAlphas": 1,
					"id": "AmGraph-1",
					"title": "Correctos",
					"type": "column",
					"valueField": "column-1"
				},
				{
					"balloonText": "<strong>[[title]]</strong> en [[category]]:[[value]]",
					"fillAlphas": 1,
					"id": "AmGraph-2",
					"title": "Incorrectos",
					"type": "column",
					"valueField": "column-2"
				}
			],
			"guides": [],
			"valueAxes": [
				{
					"id": "ValueAxis-1",
					"title": "Cantidad"
				}
			],
			"allLabels": [],
			"balloon": {},
			"legend": {
				"useGraphSettings": true
			},
			"titles": [
				{
					"id": "Title-1",
					"size": 15,
					"text": "Cantidad de "
				}
			],
			"dataProvider": [
				{
					"category": "Febrero",
					"column-2": "11",
					"column-1": "29"
				}
			],
			"pathToImages": "http://www.amcharts.com/lib/3/images/",
			"amExport": {
				"buttonTitle": "Guardar",
				"imageFileName": "amCharts",
				"buttonAlpha": 1,
				"exportJPG": true,
				"exportPNG": true,
				"exportSVG": true,
				"exportPDF": true,
				"userCFG": {
					menuItems: [{
						textAlign: 'center',
						icon: 'http://www.amcharts.com/lib/3/images/export.png',
						iconTitle: 'Save chart as an image',
						onclick: function () {
						},
						items: [{
							title: 'JPG',
							format: 'jpg'
						}, {
							title: 'PNG',
							format: 'png'
						}, {
							title: 'XLS',
							format: 'svg'
						}]
					}],
					removeImagery: true
				}
			}
		});
		AmCharts.makeChart("detalle", {
			"type": "pie",
			"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
			"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
			"labelText": "[[percents]]% ",
			"innerRadius": "40%",
			"minRadius": 100,
			"colors": [
				"#374152",
				"#E70D2F",
				"#4AB67F",
				"#86A6C2"
			],
			"titleField": "category",
			"valueField": "column-1",
			"allLabels": [],
			"balloon": {},
			"legend": {
				"align": "center", "equalWidths": false,
				"markerType": "circle"
			},
			"titles": [],
			"dataProvider": [
				{
					"category": "Cargo Fijo",
					"column-1": "10"
				},
				{
					"category": "Unidades Libres y Planes",
					"column-1": "40"
				},
				{
					"category": "Descuentos Cargos Fijos",
					"column-1": "20"
				},
				{
					"category": "Unidades Libres y Bolsas",
					"column-1": "30"
				}
			],
			"pathToImages": "http://www.amcharts.com/lib/3/images/",
			"amExport": {
				"buttonTitle": "Guardar",
				"imageFileName": "amCharts",
				"buttonAlpha": 1,
				"exportJPG": true,
				"exportPNG": true,
				"exportSVG": true,
				"exportPDF": true,
				"userCFG": {
					menuItems: [{
						textAlign: 'center',
						icon: 'http://www.amcharts.com/lib/3/images/export.png',
						iconTitle: 'Save chart as an image',
						onclick: function () {
						},
						items: [{
							title: 'JPG',
							format: 'jpg'
						}, {
							title: 'PNG',
							format: 'png'
						}, {
							title: 'XLS',
							format: 'svg'
						}]
					}],
					removeImagery: true
				}
			}
		});
	</script>
@endsection
