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
				<div class="btn-toolbar">
					<button class="btn btn-primary">New User</button>
					<button class="btn">Import</button>
					<button class="btn">Export</button>
				</div>
				<div class="well">
					<table class="table">
						<thead>
						<tr>
							<th>#</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Username</th>
							<th style="width: 36px;"></th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>1</td>
							<td>Mark</td>
							<td>Tompson</td>
							<td>the_mark7</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Ashley</td>
							<td>Jacobs</td>
							<td>ash11927</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Audrey</td>
							<td>Ann</td>
							<td>audann84</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<tr>
							<td>4</td>
							<td>John</td>
							<td>Robinson</td>
							<td>jr5527</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<tr>
							<td>5</td>
							<td>Aaron</td>
							<td>Butler</td>
							<td>aaron_butler</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<tr>
							<td>6</td>
							<td>Chris</td>
							<td>Albert</td>
							<td>cab79</td>
							<td>
								<a href="user.html"><i class="icon-pencil"></i></a>
								<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="pagination">
					<ul>
						<li><a href="#">Prev</a></li>
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">Next</a></li>
					</ul>
				</div>
				<div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Delete Confirmation</h3>
					</div>
					<div class="modal-body">
						<p class="error-text">Are you sure you want to delete the user?</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						<button class="btn btn-danger" data-dismiss="modal">Delete</button>
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
	<style></style>
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
