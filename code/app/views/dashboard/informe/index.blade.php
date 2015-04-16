@extends('layouts.master')

@section('title')
	Informes
@endsection

@section('content')
	<div ng-controller="informeController">
		<code ng-if="debug">
			<p>Filtros: @{{ filters }}</p>

			<p>Datos: @{{ datas }}</p>

			<p>Errores: @{{ errors }}</p>
		</code>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Informes
						<span class="tools pull-right">
						<a class="fa fa-question" href="#"></a>
						<a class="fa fa-chevron-down" href="javascript:"></a>
						</span>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2 pull-right">
										<form action="#" method="get" class="form-inline" role="form">
											<div class="form-group">
												<label class="" for="q"><i class="fa fa-filter fa-fw"></i>Filtrar: </label>

												<div class="input-group">
													<input class="form-control system-search" id="" name="q" required>
													<span class="input-group-btn">
														<button type="submit" class="btn btn-default">
															<i class="glyphicon glyphicon-search"></i>
														</button>
													</span>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-2 col-xs-12 col-sm-6">
										<label for="dateRange">
											<small>Fecha</small>
										</label>
										{{ HTML::dateRange('dateRange', array('class' => 'form-control', 'ng-model' => 'filters.date', 'ng-change' => 'updateDate()')) }}
									</div>
									<div class="col-md-2 col-xs-12 col-sm-6">
										<label for="dateRange">
											<small>Herramientas</small>
										</label><br>

										<div class="btn-group" role="group" aria-label="...">
											<button type="button" class="btn btn-info" ng-click="updateInforms()"><i class="fa fa-search fa-lg"></i></button>
											<button type="button" class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i></button>
											<button type="button" class="btn btn-default"><i class="fa fa-ellipsis-h fa-lg"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div ng-if="errors.message != '' && !isLoadingActual" class="alert alert-warning" role="alert">
									<strong>Warning!</strong> @{{ errors.message }}.
								</div>
								<h3 ng-if="isLoadingActual"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover table-list-search">
										<thead>
										<tr>
											<th class="text-center" style="width: 35px;">
												<i class="fa fa-check fa-lg"></i>
											</th>
											<th class="" style="width: 30px;"></th>
											<th class="filename" style="width: 80%;">Nombre</th>
											<th class="text-center" style="width: 15%;">Tipo Documento</th>
											<th class="text-center" style="width: 50px;"></th>
										</tr>
										</thead>
										<tbody>
										<tr ng-repeat="inf in filters.informs">
											<td class="icheck">
												<div class="square-blue single-row">
													<div class="checkbox">
														<input type="checkbox" id="check@{{ inf.data.id }}">
													</div>
												</div>
											</td>
											<td class=""><i class="fa fa-file-excel-o fa-lg"></i></td>
											<td class="filename">@{{ inf.data.nombre  }}</td>
											<td class="">@{{ inf.data.documento }}</td>
											<td class="">
												<button type="button" class="btn btn-info btn-sm" ng-click="downloadInform(inf.data.id, inf.data.nombre)">
													<i class="fa fa-download"></i>
												</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('file-style')
	{{--datepicker--}}
	{{ HTML::style('js/bootstrap-datepicker/css/datepicker.css') }}
	{{ HTML::style('js/bootstrap-datepicker/css/datepicker-custom.css') }}

	<!--icheck-->
	{{ HTML::style('js/iCheck/skins/square/_all.css') }}
	{{ HTML::style('js/iCheck/skins/square/blue.css') }}
@endsection

@section('text-style')
	<style>
		.table {
			table-layout: fixed;
		}

		.table th, .table td {
			word-wrap: break-word;
		}

		.table tbody tr td {
			vertical-align: middle;
			padding: 5px;
		}

		.filename {
			font-weight: bold;
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

	{{--icheck--}}
	{{ HTML::script('js/iCheck/icheck.min.js') }}
	{{ HTML::script('js/icheck-init.js') }}
@endsection

@section('text-script')
	<script type="text/javascript"></script>
@endsection
