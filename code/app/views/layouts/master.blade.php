<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="Aplicación Reportes Despachos">
	<meta name="author" content="Intelidata">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
	<title>@yield('title')</title>
	@yield('file-style')
	{{ HTML::style('css/style.css') }}
	{{ HTML::style('css/style_responsive.css') }}
	{{ HTML::style('js/ladda/dist/ladda-themeless.min.css') }}
	{{ HTML::script('js/modernizr.min.js') }}
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --><!--[if lt IE 9]>{{  HTML::script('js/html5shiv.js') }}{{  HTML::script('js/respond.min.js') }}<![endif]-->
	@yield('text-style')
</head>
<body id="{{ \App\Util\SiteHelpers::bodyId() }}" class="horizontal-menu-page" ng-app="qaProcesosCriticos">
<section>
	@include('layouts.preloader')
	<div class="wrapper-top">
		<div class="row">
			<div class="col-xs-3 col-sm-3 col-md-4 col-lg-4">
				{{ HTML::image('images/negocios/ENTEL.png', 'Intelidata', array('class' => 'img-responsive', 'style' => 'max-height: 60px' )) }}
			</div>
			<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
				<h2 class="text-center">{{ Config::get('api.title')  }}</h2>
			</div>
		</div>
	</div>
	@include('layouts.master_modules.menu')
	<div class="wrapper">
		@yield('content')
	</div>
	<footer class="sticky-footer">
		@include('layouts.master_modules.footer')
	</footer>
</section>
{{-- BASE JS --}}
{{ HTML::script('js/jquery-1.10.2.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.3.min.js') }}
{{ HTML::script('js/jquery-migrate-1.2.1.min.js') }}
{{ HTML::script('js/underscore.js') }}
{{ HTML::script('js/jquery.cookies.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{-- LADDA JS --}}
{{ HTML::script('js/ladda/dist/spin.min.js') }}
{{ HTML::script('js/ladda/dist/ladda.min.js') }}
{{-- FILESAVER JS --}}
{{ HTML::script('js/FileSaver.js') }}
{{-- AMCHARTS JS --}}
{{ HTML::script('js/amcharts/amcharts.js') }}
{{ HTML::script('js/amcharts/pie.js') }}
{{ HTML::script('js/amcharts/serial.js') }}
{{ HTML::script('js/amcharts/exporting/amexport_combined.js') }}
{{ HTML::script('js/amcharts/lang/es.js') }}
<style>
	.amcharts-graph-graph2 .amcharts-graph-stroke {
		stroke-dasharray: 4px 5px;
		stroke-linejoin: round;
		stroke-linecap: round;
		-webkit-animation: am-moving-dashes 1s linear infinite;
		animation: am-moving-dashes 1s linear infinite;
	}

	@-webkit-keyframes am-moving-dashes {
		100% {
			stroke-dashoffset: -28px;
		}
	}

	@keyframes am-moving-dashes {
		100% {
			stroke-dashoffset: -28px;
		}
	}
</style>
@yield('file-script')
{{ HTML::script('js/scripts.js') }}
@include('layouts.angularjs')
@yield('text-script')
</body>
</html>
