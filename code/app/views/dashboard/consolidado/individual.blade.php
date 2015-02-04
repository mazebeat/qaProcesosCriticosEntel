@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoController">
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
					<div class="col-md-4">
						<form class="form">
							<div class="form-group">
								<label class="" for="q">Seleccione un plan: </label>
								<select class="form-control">
									<option value="plan1">Plan n°1</option>
									<option value="plan2">Plan n°2</option>
									<option value="plan3">Plan n°3</option>
									<option value="plan4">Plan n°4</option>
									<option value="plan5">Plan n°5</option>
								</select>
							</div>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-6">
						<div id="actual" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
					</div>
					<div class="col-md-4">
						<div id="detalle" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="historico" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
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
	</style>
@endsection

@section('file-script')
	{{--Spineer JS --}}
	{{ HTML::script('js/fuelux/js/spinner.min.js') }}
	{{ HTML::script('js/spinner-init.js') }}
	{{--Datepicker JS --}}
	{{ HTML::script('js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
	{{ HTML::script('js/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js', array('charset' => 'UTF-8')) }}
	{{--Datepicker init --}}
	{{ HTML::script('js/pickers-init.js') }}
@endsection

@section('text-script')
	<script type="text/javascript">

		AmCharts.makeChart("actual",
								{
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
											"balloonText": "[[title]] of [[category]]:[[value]]",
											"fillAlphas": 1,
											"id": "AmGraph-1",
											"title": "Correctos",
											"type": "column",
											"valueField": "column-1"
										},
										{
											"balloonText": "[[title]] of [[category]]:[[value]]",
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
											"text": "Febrero 2015"
										}
									],
									"dataProvider": [
										{
											"category": "Febrero",
											"column-2": "7",
											"column-1": "65"
										}
									]
								}
		);

		AmCharts.makeChart("detalle",
								{
									"type": "pie",
									"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
									"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
									"labelText": "[[percents]]% ",
									"innerRadius": "40%",
									"minRadius": 100,
									"colors": [
										"#374152",
										"#E70D2F"
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
											"category": "Unidades Libres",
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
									]
								}
		);

		AmCharts.makeChart("historico",
								{
									"type": "serial",
									"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
									"categoryField": "category",
									"colors": [
										"#374152",
										"#E70D2F"
									],
									"startDuration": 1,
									"categoryAxis": {
										"gridPosition": "start",
										"titleFontSize": 2
									},
									"trendLines": [],
									"graphs": [
										{
											"balloonText": "Porcentaje de <strong>[[title]]</strong> en  [[category]]: [[value]]%",
//											"fillAlphas": 1,
											"id": "AmGraph-1",
											"stackable": false,
											"switchable": false,
											"title": "Correctos",
											"type": "line",
											"bullet": "round",
											"lineThickness": 2,
											"bulletSize": 10,
											"valueField": "Correctos"
										},
										{
											"balloonText": "Porcentaje de <strong>[[title]]</strong> en  [[category]]: [[value]]%",
//											"fillAlphas": 1,
											"id": "AmGraph-2",
											"stackable": false,
											"switchable": false,
											"title": "Incorrectos",
											"type": "line",
											"bullet": "round",
											"lineThickness": 2,
											"bulletSize": 10,
											"valueField": "Incorrectos"
										}
									],
									"guides": [],
									"valueAxes": [
										{
											"id": "ValueAxis-1",
											"maximum": 0,
											"radarCategoriesEnabled": false,
											"stackType": "100%",
											"unit": "%",
											"autoGridCount": false,
											"title": "Porcentaje"
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
											"text": "Historial de Procesos 2015"
										}
									],
									"dataProvider": [
										{
											"category": "Enero",
											"Correctos": 8,
											"Incorrectos": 5
										},
										{
											"category": "Febrero",
											"Correctos": "65",
											"Incorrectos": 7
										},
										{
											"category": "Marzo",
											"Correctos": 2,
											"Incorrectos": "32"
										},
										{
											"category": "Abril",
											"Correctos": "72",
											"Incorrectos": "1"
										},
										{
											"category": "Mayo",
											"Correctos": "86",
											"Incorrectos": "78"
										},
										{
											"category": "Junio",
											"Correctos": "2",
											"Incorrectos": "56"
										},
										{
											"category": "Julio",
											"Correctos": "3",
											"Incorrectos": "3",
											"column-1": "45",
											"column-2": "64"
										},
										{
											"category": "Agosto",
											"Correctos": "87",
											"Incorrectos": "24"
										},
										{
											"category": "Septiembre",
											"Correctos": "2",
											"Incorrectos": "8"
										},
										{
											"category": "Octubre",
											"Correctos": "34",
											"Incorrectos": "5"
										}
									]
								}
		);
	</script>
@endsection
