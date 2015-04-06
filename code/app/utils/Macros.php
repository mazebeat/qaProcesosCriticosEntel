<?php namespace App\Util;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Request;

HTML::macro('button', function ($type = 'button', $name, $options = array()) {
	if (!isset($options['type'])) {
		$options['type'] = $type;
	}

	if (array_key_exists('id', $options)) {
		return $options['id'];
	}

	return '<button ' . HTML::attributes($options) . '>' . $name . '</button>';
});

HTML::macro('activeLink', function ($url) {
	return Request::is($url) ? 'active current' : '';
});

HTML::macro('activeState', function ($urls = array()) {
	if (count($urls) > 0) {
		for ($i = 0; $i < count($urls); $i++) {
			if (Request::path() == $urls[$i]) {
				echo "active current";
			}
		}
	}
});

Form::macro('selectYear2', function ($name, $startYear = null, $endYear = null, $options = array()) {
	if ($endYear == null) {
		$endYear = Carbon::now()->year;
	}
	if ($endYear == null) {
		$endYear = 1980;
	}

	$years = range($endYear, $startYear);
	$list  = array_combine($years, $years); // [2013 => 2013]

	if (!isset($options['name'])) {
		$options['name'] = $name;
	}

	$html   = array();
	$html[] = '<option value=""></option>';

	foreach ($list as $value => $display) {
		$html[] = sprintf('<option value="%d">%d</option>', $value, $display);
	}

	$options = HTML::attributes($options);
	$list    = implode('', $html);

	return "<select{$options}>{$list}</select>";
});

\Form::macro('checkbox2', function ($name, $title, $value, $class = 'default', $check = false, $options = array()) {
	$output = '<div class="ckbox ckbox-%s">%s%s</div>';
	if (!isset($options['name'])) {
		$options['name'] = $name;
	}

	if (!isset($options['id'])) {
		$options['id'] = $name;
	}

	return sprintf($output, $class, Form::checkbox($name, $value, $check, $options), Form::label($name, $title, $options));
});

\HTML::macro('tableize', function ($structure, $data, $headers = true) {

	$html = '';

	if ($headers) {
		$html .= '<table id="detailTable">';
		$html .= '<thead>';
		$html .= '<tr>';
		foreach ($structure as $title) {
			$html .= '<th>' . utf8_decode($title) . '</th>';
		}
		$html .= '<th>' . utf8_decode('Leído') . '</th>';
		$html .= '<th>' . utf8_decode('Retenido') . '</th>';
		$html .= '<th>' . utf8_decode('Fallido') . '</th>';

		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
	}

	foreach ($data as $item) {
		$html .= '<tr>';
		foreach ($structure as $key => $value) {
			$html .= '<td>' . utf8_decode($item->$key) . '</td>';
		}

		$html .= '<td>NO</td>';
		$html .= '<td>NO</td>';
		$html .= '<td>NO</td>';

		$html .= '</tr>';
	}

	if ($headers) {
		$html .= '</tbody>';
		$html .= '</table>';
	}

	return $html;
});

\HTML::macro('dateRange', function ($name, $options = array()) {
	$months = array(
		1 => 'Enero',
		'Febrero',
		'Marzo',
		'Abril',
		'Mayo',
		'Junio',
		'Julio',
		'Agosto',
		'Septiembre',
		'Octubre',
		'Noviembre',
		'Diciembre'
	);

	if (!isset($options['name'])) {
		$options['name'] = $name;
	}

	if (!isset($options['id'])) {
		$options['id'] = $name;
	}

	$html   = array();
	$html[] = '<option value="">Seleccione una fecha</option>';

	for ($i = 0; $i < 13; $i++) {
		$month      = date("n", mktime(0, 0, 0, date("n") - $i, date("d"), date("Y")));
		$month_word = $months[date("n", mktime(0, 0, 0, date("n") - $i, date("d"), date("Y")))];
		$year       = date("Y", mktime(0, 0, 0, date("n") - $i, date("d"), date("Y")));

		if ($i == 0) {
			$html[] = sprintf('<option value="%s" selected>%s</option>', ($month . '-' . $year), ($month_word . ' ' . $year));
		}
		else {
			$html[] = sprintf('<option value="%s">%s</option>', ($month . '-' . $year), ($month_word . ' ' . $year));
		}
	}

	$options = \HTML::attributes($options);
	$list    = implode('', $html);

	return "<select{$options}>{$list}</select>";
});

\HTML::macro('gentable', function ($name, array $list = array(), array $head = array(), $isSort = false, $sort = array(), $options = array()) {
	try {
		if (is_array($list) && count($list) <= 0) {
			throw new \Exception('The list is empty.<br>');
		}

		if (is_array($list[0]) && is_array($head)) {
			$b = count($head);
			$c = count($list[0]);
			if ($b > $c) {
				throw new \Exception('The quantity of thead is more than of list quantity.<br>');
			}

			if ($b > $c) {
				throw new \Exception('The quantity of list is more than of thead quantity.<br>');
			}
		}
		else {
			throw new \Exception('The content of the list should be an array.<br>');
		}

		if (!isset($options['name'])) {
			$options['name'] = $name;
		}

		if (!isset($options['id'])) {
			$options['id'] = $name;
		}

		$options = \HTML::attributes($options);

		$result = '<table ' . $options . '>';
		$result .= '<thead>';
		$result .= '<tr>';

		$head = array_flatten(array_flatten($head));
		foreach ($head as $h) {
			$result .= '<th style="width: 10%; text-align: center">' . ($h) . '</th>';
		}

		$result .= '<th>Observaciones</th>';

		$list = ($list);

		$result .= '</tr>';
		$result .= '</thead>';
		$result .= '<tbody>';

		if ($isSort) {
			if (is_array($sort) && count($sort) == 4) {
				$list = $this->arrayOrderBy($list, $sort[0], $sort[1], $sort[2], $sort[3]);
			}
			else {
				if (is_array($list[0]) && count($list[0]) >= 2) {
					$temp = array_keys($list[0]);
					$list = $this->arrayOrderBy($list, $temp[0], SORT_ASC, $temp[1], SORT_ASC);
				}
			}
		}

		foreach ($list as $key => $value) {
			$result .= '<tr>';

			if (is_array($value)) {
				foreach ($value as $k => $v) {
					if (\Str::lower($v) == 'error') {
						$result .= '<td style="text-align: center;"><h4><span class="label label-danger">' . \Str::upper(($v)) . '</span></h4></td>';
					}
					elseif (\Str::lower($v) == 'ok') {
						$result .= '<td style="text-align: center;"><h4><span class="label label-success">' . \Str::upper(($v)) . '</span></h4></td>';
					}
					elseif (\Str::lower($v) == 'observacion') {
						$result .= '<td style="text-align: center;"><h4><span class="label label-warning">' . \Str::upper(($v)) . '</span></h4></td>';
					}
					else {
						$result .= '<td style="text-align: center;">' . ($v) . '</td>';
					}
				}
				$result .= '<td style="text-align: center;"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalComment"><i class="fa fa-comment-o"></i></button></td>';
			}

			$result .= '</tr>';
		}
		$result .= '</tbody></table>';

		return $result;
	} catch (\Exception $e) {
		return 'ERROR: ' . ' ' . $e->getMessage() . ' <br>';
	}

});