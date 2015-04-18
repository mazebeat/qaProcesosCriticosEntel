@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoIndividualController">
		<code ng-if="debug">
			<p>Filtros: [[ filters ]]</p>

			<p>Table: [[ table ]]</p>

			<p>Datos: [[ datas ]]</p>

			<p>Errores: [[ errors ]]</p>
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
								<td class="text-right">[[ table.qBuenos ]]</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Errores</td>
								<td class="text-right">[[ table.qErrores ]]</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Observaciones</td>
								<td class="text-right">[[ table.qObservaciones ]]</td>
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
	</script>
@endsection
