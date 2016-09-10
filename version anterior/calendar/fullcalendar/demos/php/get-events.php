<?php

//--------------------------------------------------------------------------------------------------
// This script reads event data from a JSON file and outputs those events which are within the range
// supplied by the "start" and "end" GET parameters.
//
// An optional "timezone" GET parameter will force all ISO8601 date stings to a given timezone.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------

// Require our Event class and datetime utilities
require dirname(__FILE__) . '/utils.php';

// Short-circuit if the client did not give us a date range.
if (!isset($_GET['start']) || !isset($_GET['end'])) {
	die("Please provide a date range.");
}

// Parse the start/end parameters.
// These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
// Since no timezone will be present, they will parsed as UTC.
$range_start = parseDateTime($_GET['start']);
$range_end = parseDateTime($_GET['end']);

// Parse the timezone parameter if it is present.
$timezone = null;
if (isset($_GET['timezone'])) {
	$timezone = new DateTimeZone($_GET['timezone']);
}
    
// Read and parse our events JSON file into an array of event data arrays.
//$json = file_get_contents(dirname(__FILE__) . '/../json/events.json');


$json = array();
$row_array = array();
$fetch = getCursosByDate($range_start, $range_end);
 
if (count($fetch))
{
  foreach ($fetch as $i => $obj)
  { 
	/*
	echo "<pre>";
	print_r($obj);
	echo "</pre>";
	*/
    switch ($obj['id']%3):
      case 1 : $color = '#4f2d7f';
        $textColor = '#FFF';
        break;
      case 2 : $color = '#1D6285';
        $textColor = '#FFF';
        break;
      case 0 : $color = '#D5BC3A';
        $textColor = '#333';
        break;
    endswitch;

	$data['title'] =  "  - ".$obj['id']." registro";
    $data['start'] = date('Y-m-d', strtotime($obj['fecha_inicio'])) . 'T' .date('H:i:s', strtotime($obj['fecha_inicio']));
    $data['end'] = date('Y-m-d', strtotime($obj['fecha_fin'])) . 'T' .date('H:i:s', strtotime($obj['fecha_fin']));
    //$data['url'] = "http://maps.google.com/maps?q=-".$obj['latitud'].",+-".$obj['longitud']."+(TrackMe ".$obj['id']." a las ".date('H:i:s', strtotime($obj['fecha_inicio'])).")";
    $data['color'] = $color;
    $data['textColor'] = $textColor;

    array_push($json, $data);
	/*
    if (!empty($obj->periodicidad) && $obj->periodicidad !== ':'):

      $xp = explode(':', $obj->periodicidad);
      $tipo_periodicidad = $xp[0];
      $dias = explode(',', $xp[1]);

      $fechaInicio = new DateTime($obj->fecha_inicio);
      $fechaFinal = new DateTime(date('Y-m-d',strtotime($obj->fecha_fin)).' '.$obj->hora_fin);

      //Periodicidad Semanal 

      if ($tipo_periodicidad == 'S'):

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($fechaInicio, $interval, $fechaFinal);

        foreach ($period as $dt):
          $dia_semana = $dt->format('N');
          if (in_array($dia_semana, $dias) && $dt->format('Y-m-d') >= $range_start && $dt->format('Y-m-d') <= $range_end):

            $data['start'] = $dt->format('Y-m-d') . 'T' . date('H:i:s', strtotime($obj->hora_inicio));
            $data['end'] = $dt->format('Y-m-d') . 'T' . date('H:i:s', strtotime($obj->hora_fin));

            if ($data['start'] !== date('Y-m-d', strtotime($obj->fecha_inicio)) . 'T' . date('H:i:s', strtotime($obj->hora_inicio))):
              array_push($json, $data);
            endif;

          endif;
        endforeach;
      endif;

      if ($tipo_periodicidad == 'Q'):
        $intervalString = '+2 week';
      endif;

      if ($tipo_periodicidad == 'M'):		
        $diaXp = explode('_', $xp[1]);
        $diaMes = $diaXp[0];
        $repeMes = $diaXp[1];
        
        switch($diaMes):
          case '1' : $dia = 'mon'; break;
          case '2' : $dia = 'tue'; break;
          case '3' : $dia = 'wed'; break;
          case '4' : $dia = 'thu'; break;
          case '5' : $dia = 'fri'; break;          
        endswitch;
        
        $intervalString = $repeMes.' '.$dia.' of +1 month';
        
      endif;

      if (isset($intervalString)):
        while (true):
          $proxima = $fechaInicio->modify($intervalString);

          if ($proxima->format('Y-m-d') > $range_end || $proxima->format('Y-m-d') > $fechaFinal->format('Y-m-d')):
            break;
          endif;
          
          $data['start'] = $proxima->format('Y-m-d') . 'T' . date('H:i:s', strtotime($obj->hora_inicio));
          $data['end'] = $proxima->format('Y-m-d') . 'T' . date('H:i:s', strtotime($obj->hora_fin));
          array_push($json, $data);
        endwhile;
      endif;

    endif;
	*/
  }
}
        

// Send JSON to the client.
echo json_encode($json);