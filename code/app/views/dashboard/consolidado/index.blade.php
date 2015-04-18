@extends('layouts.master')

@section('title')
	Inicio
@endsection

@section('content')
	<div ng-controller="consolidadoIndexController">
		<code ng-if="debug">
			<h3>FILTROS</h3>

			<p>[[ filters ]]</p>

			<h3>DATOS</h3>
			<h4>Detalle</h4>

			<p>[[ datas.detalle ]]</p>

			<h4>Actual</h4>

			<p>[[ datas.actual ]]</p>

			<h4>Historico</h4>

			<p>[[ datas.historico ]]</p>

			<h3>ERRORES</h3>

			<p>[[ errors ]]</p>
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

					{{-- BEGIN TITLE --}}
					<div class="col-md-6 text-center">
						<h3 ng-if="isLoadingActual == false">[[ filters.titleActual ]]</h3>
						<!--<h3 ng-if="errors.actual == '' && isLoadingActual == false && datas.actual.length > 1">[[ filters.titleActual ]]</h3>-->
					</div>
					{{--END TITLE --}}
				</div>
				{{-- END FILTERS --}}

				<div class="row">

					{{-- BEGIN OPTIONS --}}
					<div class="col-md-2 text-center legend" style="color:white;">
						<div class="row">
							<div class="col-md-12 col-xs-12 col-sm-6">
								<div class="options" ng-click="changeDashboard(1)">
									<div class="panel active" style="background-color:#37468E;">
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

					{{-- BEGIN TITLE --}}
					{{--<div class="col-md-10 text-center">--}}
					<!--<h3 ng-if="isLoadingActual == false">[[ filters.titleActual ]]</h3>-->
					{{--<!--<h3 ng-if="errors.actual == '' && isLoadingActual == false && datas.actual.length > 1">[[ filters.titleActual ]]</h3>-->--}}
					{{--</div>--}}
					{{--END TITLE --}}

					{{-- BEGIN GRÁFICO ACTUAL --}}
					<div class="col-md-5">
						<h3 ng-if="isLoadingActual"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

						<div ng-if="errors.actual != '' && isLoadingActual == false" class="alert alert-warning" role="alert">
							<strong>Warning!</strong> [[ errors.actual ]].
						</div>

						<div ng-if="errors.actual === ''">
							<div id="actual" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
						</div>
					</div>
					{{-- END GRÁFICO ACTUAL --}}

					{{-- BEGIN GRÁFICO DETALLE --}}
					<div class="col-md-5">
						<h3 ng-if="isLoadingDetalle"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

						<div ng-if="errors.detalle != '' && isLoadingDetalle == false" class="alert alert-warning" role="alert">
							<strong>Warning!</strong> [[ errors.detalle ]].
						</div>

						<div id="detalle" style="width: 100%; height: 400px; background-color: #FFFFFF;" ng-if="errors.detalle == ''"></div>
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
							<strong>Warning!</strong> [[ errors.historico ]].
						</div>

						<div id="historico" style="width: 100%; height: 400px; background-color: #FFFFFF;" ng-if="errors.historico == ''"></div>
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

		.options .panel.active {
			-webkit-filter: drop-shadow(0px 5px 0px rgba(0, 0, 0, 0.5));
			filter: drop-shadow(0px 5px 0px rgba(0, 0, 0, 0.5));
			-ms-filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=12, OffY=12, Color='#444')";
			filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=12, OffY=12, Color='#444')";
		}

		code {
			line-height: 0.9;
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

		$('.options .panel').click(function () {
			var $this = $(this);
			$this.parents('.legend').find('.options .panel.active').toggleClass('active');
			$this.toggleClass('active');
		});
	</script>
@endsection
