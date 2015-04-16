{{-- AngularJS --}}
{{ HTML::script('js/angular-1.3.2/angular.min.js') }}
{{ HTML::script('js/angular-1.3.2/i18n/angular-locale_es-cl.js') }}{{ HTML::script('js/angular-1.3.2/angular-cookies.min.js') }}{{ HTML::script('js/angular-1.3.2/angular-resource.min.js') }}

{{-- Plugins --}}
{{ HTML::style('js/ng-grid/ng-grid.css') }}
{{ HTML::script('js/ng-grid/ng-grid-2.0.14.min.js') }}
{{ HTML::script('js/angular-local-storage.js') }}

{{-- Main app --}}
{{ HTML::script('js/app.js') }}
<script>
    qaProcesosCriticos.factory('rootFactory', function () {
	    return {
		    {{--root: "{{ Request::root() }}",--}}
		    {{--store: "{{ storage_path() }}",--}}
	        public: "{{ public_path() }}",
	        serviciows: "{{ Config::get('webservice.url') }}"
        };
    });
</script>

{{-- Components app --}}
{{ HTML::script('js/factories.js') }}
{{ HTML::script('js/directives.js') }}
{{ HTML::script('js/controllers.js') }}