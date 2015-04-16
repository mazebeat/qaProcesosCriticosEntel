@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoIndividualController">
		<code ng-if="debug">
			<p>Filtros: @{{ filters }}</p>

			<p>Table: @{{ table }}</p>

			<p>Datos: @{{ datas }}</p>

			<p>Errores: @{{ errors }}</p>
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

				{{-- BEGIN FILTERS --}}
				<div class="row">
					<div class="col-md-2 col-xs-12 col-sm-6">
						<label for="dateRange">
							<small>Fecha</small>
						</label>
						{{ HTML::dateRange('dateRange', array('class' => 'form-control', 'ng-model' => 'filters.date', 'ng-change' => 'updateDate()')) }}
					</div>
					<div class="col-md-2 col-xs-12 col-sm-6">
						<label for="typeDocument">
							<small>Tipo Documento</small>
						</label>
						{{ Form::select('typeDocument', array('CG_NORMAL_BOLETA' => 'Boleta', 'CG_NORMAL_FACTURA' => 'Factura'), 'cg_normal_boleta', array('class' => 'form-control', 'ng-model' => 'filters.typeDocument', 'ng-change' => 'changeDocumentType()')) }}
					</div>
				</div>
				{{-- END FILTERS --}}
				<div class="row">
					<div class="col-md-2">
						<table class="table table-hover table-condense legend">
							<thead>
							<tr>
								<th>#</th>
								<th>Estado</th>
								<th class="text-right">Cantidad</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>1</td>
								<td>Buenos</td>
								<td class="text-right">@{{ table.qBuenos }}</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Errores</td>
								<td class="text-right">@{{ table.qErrores }}</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Observaciones</td>
								<td class="text-right">@{{ table.qObservaciones }}</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<div id="actual" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
					</div>
					<div class="col-md-4">
						<div id="detalle" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
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
		var actualSerialChart2 = new AmCharts.AmSerialChart();
		var detallePieChart2 = new AmCharts.AmPieChart();
		{{--AmCharts.makeChart("actual", {--}}
		{{--"type": "serial",--}}
		{{--"categoryField": "category",--}}
		{{--"colors": [--}}
		{{--"#374152",--}}
		{{--"#12DC49",--}}
		{{--"#C5AE70",--}}
		{{--"#E70D2F",--}}
		{{--"#86A6C2",--}}
		{{--],--}}
		{{--"startDuration": 1,--}}
		{{--"categoryAxis": {--}}
		{{--"gridPosition": "start",--}}
		{{--"titleFontSize": 2--}}
		{{--},--}}
		{{--"trendLines": [],--}}
		{{--"graphs": [--}}
		{{--{--}}
		{{--"balloonText": "<strong>[[title]]</strong> en [[category]]: [[value]]",--}}
		{{--"fillAlphas": 1,--}}
		{{--"stackable": false,--}}
		{{--"id": "AmGraph-1",--}}
		{{--"title": "Correctos",--}}
		{{--"type": "column",--}}
		{{--"valueField": "column-1"--}}
		{{--},--}}
		{{--{--}}
		{{--"balloonText": "<strong>[[title]]</strong> en [[category]]: [[value]]",--}}
		{{--"fillAlphas": 1,--}}
		{{--"stackable": false,--}}
		{{--"id": "AmGraph-2",--}}
		{{--"title": "Incorrectos",--}}
		{{--"type": "column",--}}
		{{--"valueField": "column-2"--}}
		{{--}--}}
		{{--],--}}
		{{--"guides": [],--}}
		{{--"valueAxes": [--}}
		{{--{--}}
		{{--"id": "ValueAxis-1",--}}
		{{--"maximum": 0,--}}
		{{--//					"radarCategoriesEnabled": false,--}}
		{{--"stackType": "100%",--}}
		{{--"unit": "%",--}}
		{{--//					"autoGridCount": false,--}}
		{{--"title": "Porcentaje"--}}
		{{--}--}}
		{{--],--}}
		{{--"allLabels": [],--}}
		{{--"balloon": {},--}}
		{{--"legend": {--}}
		{{--"useGraphSettings": true--}}
		{{--},--}}
		{{--"titles": [--}}
		{{--{--}}
		{{--"id": "Title-1",--}}
		{{--"size": 15,--}}
		{{--"text": "Abril 2015"--}}
		{{--}--}}
		{{--],--}}
		{{--"dataProvider": [--}}
		{{--{--}}
		{{--"category": "Abril",--}}
		{{--"column-2": "775",--}}
		{{--"column-1": "165"--}}
		{{--}--}}
		{{--],--}}
		{{--"pathToImages": "http://www.amcharts.com/lib/3/images/",--}}
		{{--"amExport": {--}}
		{{--"buttonTitle": "Guardar",--}}
		{{--"imageFileName": "amCharts",--}}
		{{--"buttonAlpha": 1,--}}
		{{--"exportJPG": true,--}}
		{{--"exportPNG": true,--}}
		{{--"exportSVG": true,--}}
		{{--"exportPDF": true,--}}
		{{--"userCFG": {--}}
		{{--menuItems: [{--}}
		{{--textAlign: 'center',--}}
		{{--icon: 'http://www.amcharts.com/lib/3/images/export.png',--}}
		{{--iconTitle: 'Save chart as an image',--}}
		{{--onclick: function () {--}}
		{{--},--}}
		{{--items: [{--}}
		{{--title: 'JPG',--}}
		{{--format: 'jpg'--}}
		{{--}, {--}}
		{{--title: 'PNG',--}}
		{{--format: 'png'--}}
		{{--}, {--}}
		{{--title: 'XLS',--}}
		{{--format: 'svg'--}}
		{{--}]--}}
		{{--}],--}}
		{{--removeImagery: true--}}
		{{--}--}}
		{{--}--}}
		{{--});--}}
		{{--AmCharts.makeChart("detalle", {--}}
		{{--"type": "pie",--}}
		{{--"pathToImages": "http://cdn.amcharts.com/lib/3/images/",--}}
		{{--"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]] registros</b> ([[percents]]%)</span>",--}}
		{{--"labelText": "[[percents]]% ",--}}
		{{--"innerRadius": "40%",--}}
		{{--"minRadius": 100,--}}
		{{--"colors": [--}}
		{{--"#374152",--}}
		{{--"#E70D2F",--}}
		{{--"#12DC49",--}}
		{{--"#86A6C2",--}}
		{{--"#C5AE70"--}}
		{{--],--}}
		{{--"titleField": "category",--}}
		{{--"valueField": "column-1",--}}
		{{--"allLabels": [],--}}
		{{--"balloon": {},--}}
		{{--"legend": {--}}
		{{--"align": "center",--}}
		{{--"equalWidths": false,--}}
		{{--"markerType": "circle"--}}
		{{--},--}}
		{{--"titles": [],--}}
		{{--"dataProvider": [--}}
		{{--{--}}
		{{--"category": "OK",--}}
		{{--"column-1": "10"--}}
		{{--},--}}
		{{--{--}}
		{{--"category": "Pendientes",--}}
		{{--"column-1": "40"--}}
		{{--},--}}
		{{--{--}}
		{{--"category": "On Hold",--}}
		{{--"column-1": "19"--}}
		{{--},--}}
		{{--{--}}
		{{--"category": "Bug",--}}
		{{--"column-1": "28"--}}
		{{--},--}}
		{{--{--}}
		{{--"category": "Cerrado",--}}
		{{--"column-1": "3"--}}
		{{--}--}}
		{{--],--}}
		{{--"pathToImages": "http://www.amcharts.com/lib/3/images/",--}}
		{{--"amExport": {--}}
		{{--"buttonTitle": "Guardar",--}}
		{{--"imageFileName": "amCharts",--}}
		{{--"buttonAlpha": 1,--}}
		{{--"exportJPG": true,--}}
		{{--"exportPNG": true,--}}
		{{--"exportSVG": true,--}}
		{{--"exportPDF": true,--}}
		{{--"userCFG": {--}}
		{{--menuItems: [{--}}
		{{--textAlign: 'center',--}}
		{{--icon: 'http://www.amcharts.com/lib/3/images/export.png',--}}
		{{--iconTitle: 'Save chart as an image',--}}
		{{--onclick: function () {--}}
		{{--},--}}
		{{--items: [{--}}
		{{--title: 'JPG',--}}
		{{--format: 'jpg'--}}
		{{--}, {--}}
		{{--title: 'PNG',--}}
		{{--format: 'png'--}}
		{{--}, {--}}
		{{--title: 'XLS',--}}
		{{--format: 'svg'--}}
		{{--}]--}}
		{{--}],--}}
		{{--removeImagery: true--}}
		{{--}--}}
		{{--}--}}
		{{--});--}}
	</script>
@endsection
