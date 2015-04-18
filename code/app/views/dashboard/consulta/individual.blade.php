@extends('layouts.master')

@section('title')
	Consulta Individual
@endsection

@section('content')
	<div ng-controller="consultaIndividualController">
		<code ng-if="debug == true">
			<p>Filtros: [[ filters ]]</p>

			<p>Datos: [[ datas ]]</p>

			<p>Errores: [[ errors ]]</p>
		</code>


		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Consulta Individual
                        <span class="tools pull-right">
                            <a class="fa fa-chevron-down" href="javascript:"></a>
				        </span>
					</div>
					<div class="panel-body">
						<form role="form" name="myForm" novalidate ng-submit="submitForm(myForm.$valid)">
							<div class="row">
								<div class="form-group col-md-1">
									{{ Form::label('typeDocument', 'Tipo Doc (*)', array('class' => 'control-label')) }}
									{{ Form::select('typeDocument', array('' => ' ', 'CG_NORMAL_BOLETA' => 'Boleta', 'CG_NORMAL_FACTURA' => 'Factura'), 'cg_normal_boleta', array('class' => 'form-control', 'ng-model' => 'filters.typeDocument', 'ng-change' => 'changeDocumentType()', 'required')) }}
									<small class="help-block">{{ $errors->first('typeDocument') }}</small>
								</div>
								<div class="form-group col-md-2">
									{{ Form::label('dateRange', 'Fecha (*)', array('class' => 'control-label')) }}
									{{ HTML::dateRange('dateRange', array('class' => 'form-control', 'ng-model' => 'filters.date', 'ng-change' => 'updateDate()', 'required')) }}
									<small class="help-block">{{ $errors->first('dateRange') }}</small>
								</div>
								<div class="form-group col-md-2">
									{{ Form::label('td', 'Tipo Detalle (*)', array('class' => 'control-label')) }}
									{{ Form::select('td', array('' => ' ', '1' => 'Cargo Fijo Planes', 'Cargo Fijo Bolsas', 'Descuentos Cargo Fijo y trafico', 'Aplicación Unidades Libres Planes', 'Aplicación Unidades Libres Bolsas'), Input::old('td'), array('class' => 'form-control', 'ng-model' => 'filters.td', 'required'))  }}
									<small class="help-block">{{ $errors->first('td') }}</small>
								</div>
								<div class="form-group col-md-1">
									{{ Form::label('estado', 'Estado (*)', array('class' => 'control-label')) }}
									{{ Form::select('estado', array('' => ' ', 'OK' => 'OK', 'ERROR' => 'Error', 'OBSERVACION' => 'Observacion'), Input::old('estado'), array('class' => 'form-control', 'ng-model' => 'filters.estado', 'required'))  }}
									<small class="help-block">{{ $errors->first('estado') }}</small>
								</div>
								<div class="form-group col-md-2">
									{{ Form::label('cuenta', 'Cuenta', array('class' => 'control-label')) }}
									{{ Form::text('cuenta', Input::old('cuenta'), array('class' => 'form-control', 'ng-model' => 'filters.cuenta'))  }}
									<small class="help-block">{{ $errors->first('cuenta') }}</small>
								</div>
								<div class="form-group col-md-2">
									{{ Form::label('contrato', 'Contrato', array('class' => 'control-label')) }}
									{{ Form::text('contrato', Input::old('contrato'), array('class' => 'form-control', 'ng-model' => 'filters.contrato'))  }}
									<small class="help-block">{{ $errors->first('contrato') }}</small>
								</div>
								<div class="form-group col-md-2 col-offset-md-8" style="margin-top: 24px;">
									<button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in" ng-disabled="myForm.$invalid">Consultar</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Resultados
                        <span class="tools pull-right">
                            <a class="fa fa-chevron-down" href="javascript:"></a>
				        </span>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-2 pull-right">
										<form action="#" method="get" class="form-inline" role="form" ng-if="errors.message == '' && isLoading == false">
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
									<div class="col-md-5">
										<h3 ng-if="isLoading"><em>Loading{{ HTML::image('images/loaders/loader28.gif') }}</em></h3>

										<div ng-if="errors.message != '' && isLoading == false" class="alert alert-warning" role="alert">
											<strong>Warning!</strong> [[ errors.message ]].
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<div id="tableresponse"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Deja tu observación...</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<span class="pull-right">Fecha: <kbd>{{ \Carbon::now() }}</kbd></span>
						</div>
					</div>
					<div class="row">
						<div class="form">
							<div class="form-group">
								<div class="col-sm-12">
									<label id="userLabel" for="usuarioResponsable">Responsable </label>
									<input type="text" name="usuarioResponsable" id="usuarioResponsable" id-user="{{ Auth::user()->id  }}" value="{{ Auth::user()->nombre }}" class="form-control" readonly/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label id="commentLabel" for="comment">Observaciones </label>
									<textarea class="form-control ckeditor" name="comment" placeholder="Observaciones..." maxlength="240" rows="7"></textarea>
									<span class="help-block"><p id="characterLeft" class="help-block">Has pasado el limite!</p></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button class="btn btn-success" id="btnSubmit" name="btnSubmit" type="button">Enviar</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('file-style')@endsection

@section('text-style')
	<style>
		.table tbody tr td {
			vertical-align: middle;
			padding: 5px;
		}
	</style>
@endsection

@section('file-script')
	{{ HTML::script('js/ckeditor/ckeditor.js') }}
@endsection

@section('text-script')
	<script>
		var max = $('#comment').attr('maxlength');
		console.log(max);
		$('p#characterLeft').text(max + ' characters left');
		$('#comment').keydown(function () {
			console.info('enter keydown');
			var len = $(this).val().length;
			if (len >= max) {
				$('p#characterLeft').text('You have reached the limit');
			}
			else {
				var ch = max - len;
				console.debug(ch);
				$('p#characterLeft').text(ch + ' characters left');
				$('#btnSubmit').removeClass('disabled');
			}
		});
	</script>@endsection
