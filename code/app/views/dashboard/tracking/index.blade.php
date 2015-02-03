@extends('layouts.master')

@section('title')
	Tracking
@endsection

@section('content')
	<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
	<div ng-controller="trackingController">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						Tracking
                        <span class="tools pull-right">
                            <a class="fa fa-question" href="#"></a>
							<a class="fa fa-chevron-down" href="javascript:"></a>
						</span>
					</div>
					<div class="panel-body">
						<form role="form" name="trackingForm" ng-submit="submit()">
							<div class="row">
								<div class="form-group col-xs-3 col-md-3"
								     ng-class="{ 'has-error' : trackingForm.fecha.$invalid && !trackingForm.fecha.$pristine }">
									{{ Form::label('fecha', 'Fecha (*)', array('class' => 'control-label')) }}
									<input type="text" name="fecha" value="{{ Input::old('fecha') }}" size="16"
									       ng-model='tracking.fecha'
									       data-date-minviewmode="months" data-date-viewmode="months"
									       data-date-format="yyyy-mm"
									       class="form-control form-control-inline input-medium default-date-picker ng-dirty ng-invalid"
									       autocomplete='off'
									       required>
									<small class="help-block">{{ $errors->first('fecha') }}</small>
								</div>
								<div class="form-group col-xs-3 col-md-3"
								     ng-class="{ 'has-error' : trackingForm.campana.$invalid && !trackingForm.campana.$pristine }">
									{{ Form::label('negocio', 'Negocio (*)', array('class' => 'control-label')) }}
									<select name="negocio" class="form-control ng-dirty ng-invalid"
									        ng-model="tracking.negocio"
									        ng-options="item as item for item in negocios"
									        ng-change="loadCamps()"
									        required>
										<option value="" selected>Seleccione un Negocio</option>
									</select>
									<small class="help-block">{{ $errors->first('campana') }}</small>
								</div>
								<div class="form-group col-xs-3 col-md-3"
								     ng-class="{ 'has-error' : trackingForm.campana.$invalid && !trackingForm.campana.$pristine }">
									{{ Form::label('campana', 'Campaña', array('class' => 'control-label')) }}
									<select name="campana" class="form-control ng-dirty ng-invalid"
									        ng-model="tracking.campana"
									        ng-options="item.id as item.campana for item in campanas">
										<option value="" selected>Seleccione una Campaña</option>
									</select>
									<small class="help-block">{{ $errors->first('campana') }}</small>
								</div>
								<div class="form-group col-xs-1 col-md-1" style="margin-top: 24px;">
									{{ Form::label('consultar', 'Consultar', array('class' => 'control-label sr-only' )) }}
									<button id="trackingFormButton" type="submit" class="ladda-button btn btn-success"
									        data-style="zoom-in"
									        ng-disabled="trackingForm.$invalid">
										Consultar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<section class="panel" ng-show="result">
					<header class="panel-heading custom-tab dark-tab">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tabla">Detalle</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#grafico">Gráfico</a>
							</li>
							<li class="pull-right"></li>
						</ul>
					</header>
					<div class="panel-body">
						<div class="tab-content">
							<div id="tabla" class="tab-pane active">
								<div id="tablaTracking" class="table-responsive">
									<table class="table">
										<thead>
										<tr>
											<th></th>
											<th>Fecha</th>
											<th>Campaña</th>
											<th>Ciclo</th>
											<th>Q doc. Emitidos</th>
											<th>Q Físicos</th>
											<th>Q Electrónicos</th>
											<th>Visualizacion Mail</th>
											<th>Visualizacion Portal</th>
											<th>Lecturas Email</th>
											<th>No Leídos</th>
											<th>Retenidos</th>
											<th>Env. Fallidos</th>
										</tr>
										</thead>
										<tbody>
										<tr ng-repeat="row in result">
											<td>
												<a
														ng-click="descargaExcel()"
														class="readyDetail ladda-button btn btn-link"
														data-style="zoom-in">
														<span class="ladda-label">
															<i class="fa fa-download fa-fw"></i>
														</span>
												</a>

												{{--<form method="post" id="FormularioExportacion">--}}
												{{--<button type="submit" class="botonExcel">Export</button>--}}
												{{--<input type="hidden" id="fecha" name="fecha"--}}
												{{--value="@{{ row.fecha }}"/>--}}
												{{--<input type="hidden" id="idCampana" name="idCampana"--}}
												{{--value="@{{ row.idCampana }}"/>--}}
												{{--<input type="hidden" id="campana" name="campana"--}}
												{{--value="@{{ row.NCampana  }}"/>--}}
												{{--<input type="hidden" id="datos_a_enviar" name="datos_a_enviar"/>--}}
												{{--</form>--}}
											</td>
											<td>@{{row.ano + '-' + row.mes | date:'yyyy-MM'}}</td>
											<td>@{{row.nombre_campana}}</td>
											<td>@{{row.ciclo}}</td>
											<td>@{{row.qemitidos}}</td>
											<td>@{{row.qfisicos}}</td>
											<td>@{{row.qelectronicos}}</td>
											<td>@{{ 0 }}</td>
											<td>@{{ 0 }}</td>
											<td>@{{row.qleidos}}</td>
											<td>@{{row.qnoleidos}}</td>
											<td>@{{row.qrebotes}}</td>
											<td>@{{row.qenviosfallidos}}</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="grafico" class="tab-pane">
								<div id="resumenTracking"
								     style="width: 100%; height: 400px;  background-color: #FFFFFF;"></div>
							</div>
						</div>
					</div>
				</section>

				<div id="detailDiv" style="display: none"></div>

				<div class="modal fade" id="mDetail" tabindex="-1" role="dialog" aria-labelledby="mDetailLabel"
				     aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
											aria-hidden="true">&times;</span></button>
								<h3 class="modal-title" id="mDetailLabel">Detalle </h3>
							</div>
							<div class="modal-body">
								<h4 class="modal-subtitle text-center" id="mDetailLabel2"></h4>

								<div id="gDetail" style="width: 100%; height: 400px;  background-color: #FFFFFF;"></div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
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
		.table {
			table-layout: fixed;
		}

		.table th, .table td {
			word-wrap: break-word;
		}
	</style>
@endsection

@section('file-script')
	<!--pickers plugins-->
	{{ HTML::script('js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
	{{ HTML::script('js/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js', array('charset' => 'UTF-8')) }}
	{{ HTML::script('js/bootstrap-daterangepicker/moment.min.js') }}

	<!--pickers initialization-->
	{{ HTML::script('js/pickers-init.js') }}

	{{--{{ HTML::script('js/excellentexport.min.js') }}--}}
	{{--{{ HTML::script('js/download.js') }}--}}
@endsection

@section('text-script')
	<script type="text/javascript">
		var chart = new AmCharts.AmPieChart();
		var gDetail = new AmCharts.AmPieChart();
		var readyDetail = Ladda.create(document.querySelector('.readyDetail'));
		var trackingButton = Ladda.create(document.querySelector('#trackingFormButton'));

		Ladda.bind('.ladda-button');

		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			var news = $(e.target);
			var olds = $(e.relatedTarget);

			if (news.attr('href') == '#grafico') {
				chart.validateNow();
//                chart.animateAgain();
			}
		})
	</script>
@endsection
