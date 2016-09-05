<?php
require_once ('classes/Cursos.php');
require_once ('classes/Users.php'); 

//M:5_first
// Incluimos clase Email:
if (!class_exists('PHPMailer'))
{
  include 'classes/class.phpmailer.php';
}

// Instancio el nuevo Core
$app = new App();

//creo la clase de foros que voy a usar para todas las funciones
$cursos = new Cursos();
$security = new Security();
    
//para el buscador de foros creo el string de busqueda, asi lo puedo pasar a todos los componentes y funciones que correspondan
$strSearchForo = '';
$intOperacion = isset($_POST['hidBuscarForos']) ? $_POST['hidBuscarForos'] : 0;
if ($intOperacion == 1)
{
  $strSearchForo = isset($_POST['txtBuscarForo']) ? htmlentities(addslashes($_POST['txtBuscarForo'])) : '';
}
$items = "";

/*
 * Get cursos.
 */

function getCursosAprobacion()
{

  global $cursos;

  $users = new Users();
  $items = $cursos->getCursos($_SESSION['in_mrm_id']);

  if (count($items) > 0):
    return $items;
  endif;

  return null;
}

function getCursos()
{

  global $cursos, $strSearchForo;
  $users = new Users();
  $params['search'] = $strSearchForo;
  $items = $cursos->getCursos($_SESSION['in_mrm_id']);


  if (count($items))
  {
    foreach ($items as $row)
    {


      $ex = explode(" ", $row->nombre_curso);
      $classForo = strtolower($ex[0]);

      if ($row->fecha_creado > '2013-12-02 00:00:00')
      {
        $faltan == 0 ? $classActive = "" : $classActive = " active";
        $faltan == 0 ? $faltan = "" : $faltan = $faltan;
      }
      else
      {
        $classActive = "";
        $faltan = "";
      }
      ?>

      <div class="foro-block">
        <div class="col1">
          <div class="foro-seccion topics <?= $classForo ?><?= $classActive ?>"></div>
          <div class="foro-titulo"><a href="capacitacion-detalle.php?id=<?php echo $row->id_curso; ?>"><?php echo $row->nombre_curso; ?></a></div>
          <!--<div class="foro-description"><?php echo $row->descripcion; ?></div>-->
        </div>
        <div class="col2">
          <div class="foro-description"><?php echo date('d/m/Y', strtotime($row->fecha_inicio)); ?></div>

        </div>
        <div class="col3">
          <div class="foro-description"><?php echo $row->facilitador; ?></div>
        </div>
        <div class="col4">
          <div class="foro-description"><?php echo $row->estado; ?></div>
        </div>
      </div>
      <?php
    }
  }
  else
  {
    ?>
    <div class="foro-block">
      <div class="forum-null">No se encontraron Cursos</div>
    </div>
    <?php
  }
}

function getSuscripcionesPendientes()
{
  global $cursos, $strSearchForo;
  $users = new Users();
  $params['search'] = $strSearchForo;
  $items = $cursos->solicitudSupervisor($_SESSION['in_mrm_id']);
  return (count($items)) ? $items : null;
}
 
/*
 * Get topics.
 */

function getCursosByUser()
{
  global $cursos;
  $users = new Users();
  $items = $cursos->getTopics(array('forum_id' => $_GET['id']));
  if (count($items))
  {
    foreach ($items as $row)
    {
      $lastPost = $cursos->getLastCommentByTopicID($row->id);
      if ($lastPost && count($lastPost))
      {
        foreach ($lastPost as $lp)
        {
          $arrLastPost['author_id'] = $lp->author_id;
          $arrLastPost['fecha_creado'] = $lp->fecha_creado;
        }
      }
      else
      {
        $arrLastPost['author_id'] = $row->author_id;
        $arrLastPost['fecha_creado'] = $row->fecha_creado;
      }

      $classForo = strtolower($cursos->getForumNameByID($_GET['id']));

      if ($row->fecha_creado > '2013-12-02 00:00:00')
      {
        $view = count($cursos->getTopicUserViewByTopicId($row->id, $_SESSION['in_mrm_id']));

        $view == 0 ? $classActive = " active" : $classActive = "";
      }
      else
      {
        $classActive = "";
      }
      ?>
      <div class="foro-block">
        <div class="col1">
          <div class="foro-seccion topics <?= $classForo ?><?= $classActive ?>"></div>
          <div class="foro-titulo"><a href="capacitacion-detalle.php?id=<?php echo $row->id; ?>"><?php echo $row->titulo; ?></a></div>
          <div class="foro-description"><?php echo $row->descripcion; ?></div>
        </div>

        <div class="col2">
          <span>Respuestas: <?php echo $row->comments_count; ?></span>
          <span>Vistas: <?php echo $row->views ?></span>
        </div>

        <div class="col3">
          <div class="autor-y-fecha">
            <div class="autor"><a href="nuestra-gente.php?id=<?php echo $arrLastPost['author_id']; ?>"><?php echo $users->getUserNameByID($arrLastPost['author_id']); ?></a></div>
            <div class="fecha"><?php echo date('d/m/y', strtotime($arrLastPost['fecha_creado'])); ?></div>
          </div>
          <a class="ir-a-post" title="Ir al post más reciente" href="capacitacion-detalle.php?id=<?php echo $row->id; ?>"></a>
        </div>
      </div>

      <?php
    }
  }
  else
  {
    ?>
    <div class="foro-block">
      <div class="forum-null">A&uacute;n no hay Cursos en los que este inscripto </div>
    </div>
    <?php
  }
}

function getBotonesSidebarCurso()
{
  global $cursos, $items;
  if (!isset($_SESSION['in_mrm_id']) || !is_numeric($_SESSION['in_mrm_id']))
  {
    return false;
    //echo "";
  }
  else
  {
    return ($cursos->isSupervisor($_SESSION['in_mrm_id'])) ? true : false;
    //echo ($cursos->isSupervisor($_SESSION['in_mrm_id'])) ? '<a href="capacitacion-inscripciones.php" class="supCursos"></a>' : '';
  }
}

/*
 * Can topic load.
 */

function canCursosLoad()
{
  global $cursos, $items;
  if (!isset($_SESSION['in_mrm_id']) || !is_numeric($_SESSION['in_mrm_id']))
  {
    header('location: capacitacion.php');
  }
  else
  {
    $items = $cursos->getCursos($_SESSION['in_mrm_id']);
    if (count($items) == 0)
    {
      header('location: capacitacion.php');
    }
    else
    {
      unset($items);
      unset($cursos);
    }
  }
}

/*
 * Display forum name.
 */

function getCursoHead($_id)
{
  global $cursos;
  $item = $cursos->getCurso((int) $_id);
  ?>

  <div class="foro-titulo">Curso: <?php echo $item->titulo; ?></div>
  <div class="foro-descripcion"><?php echo $item->descripcion; ?></div>

  <?php
}

function getCursoDetailHeader($id)
{
  global $cursos;
  if ($cursos)
  {
    ?><div class="current-section">Inicio >> Cursos >> <?php echo $cursos->getCursoNameByID($id) ?></div><?php
  }
  else
  {
    ?><div class="current-section">Inicio >> Cursos >> Curso no encontrado</div><?php
  }
}

/*
 * Format Periodicidad
 */

function formatPeriodicidad($periodicidad)
{

  $xp = explode(':', $periodicidad);

  switch ($xp[0]):
    case 'S' : $repeticion = 'Semanal';
      break;
    case 'Q' : $repeticion = 'Quincenal';
      break;
    case 'M' : $repeticion = 'Mensual';
      break;
  endswitch;
  
  if(isset($repeticion)):
  
		  $result['repeticion'] = $repeticion;
		  if(isset($xp[1])){
			$dias = explode(',', $xp[1]);

			if (is_array($dias)):
			  foreach ($dias as $i => $v):
				$diaTxt = convertDia($v);
				$result['dias'][$v] = $diaTxt;
			  endforeach;
			else:
			  $diaTxt = convertDia($dias);
			  $result['dias'][$dias] = $diaTxt;
			endif;
		  }
		 
		  return $result;
  endif;
  
  return false;
}

function convertDia($dia = null)
{

  switch ($dia):
    case 1 : $diaTxt = 'Lunes';
      break;
    case 2 : $diaTxt = 'Martes';
      break;
    case 3 : $diaTxt = 'Miercoles';
      break;
    case 4 : $diaTxt = 'Jueves';
      break;
    case 5 : $diaTxt = 'Viernes';
      break;
  endswitch;

  return $diaTxt;
}

function getCursoDetail($id = null)
{
  global $cursos;
  return $cursos->getCursoDetalle((int) $id);
}

function sendEmailCursoDenegado($id_curso)
{
  global $cursos, $configUrlAbsoluta, $app;
  $users = new Users();

  $it = $cursos->getInscripcion($id_curso);
  $item = $it[0];

  $titulo = utf8_decode($item->nombre_curso);
  $nombrede = $users->getUserNameByID($item->id_superior);
  $motivo = $item->motivo;

  if (isset($item))
  {

    $email = $users->getEmailByUserID($item->id_usuario)->email;
    $nombrepara = $users->getUserNameByID($item->id_usuario);
 
    $file = @fopen('template-mails/inscripcion_denegada/index.html', 'r');
    if ($file)
    {
      $strBody = fread($file, filesize('template-mails/inscripcion_denegada/index.html'));
      fclose($file);
    }


    $strBody = str_replace('@@@url@@@', $configUrlAbsoluta.'/template-mails/inscripcion_denegada/', $strBody);
    $strBody = str_replace('@@@titulo@@@', $titulo, $strBody);
    $strBody = str_replace('@@@motivo@@@', $motivo, $strBody);
 
    $params = array(
    'sender' => array('MRM // McCann' => EMAIL_PRINCIPAL) 
    ,'subject' => 'Plan de Capacitación - Solicitud de inscripción a "' . $titulo . '"'
    ,'body' => $strBody
    ,'addresses' => array($nombrepara => $email)
    );

    $res = $app->load('email', 'sendEmail', $params);
  }
}
 
function sendEmailCursoAprobado($id_curso)
{
  global $cursos, $configUrlAbsoluta, $app;
  $users = new Users();


  $it = $cursos->getInscripcion($id_curso);
  $item = $it[0];
    
  $titulo = utf8_decode($item->nombre_curso);
  $usuario = utf8_decode($item->nombre_usuario);
  $fecha_inicio = '';
  $horario = '';
  $ubicacion = ''; 
   $tokenCurso = $item->token; 
  if (isset($item))
  {
    $destinatarios = $cursos->getSectorRRHH();
    $email = "rrhh@mrm.com.ar";
    

    //$email = $users->getEmailByUserID($item->id_rrhh)->email;
    $nombrepara = "Recursos Humanos";
 
    $file = @fopen('template-mails/inscripcion_rrhh/index.html', 'r');
    if ($file)
    {
      $strBody = fread($file, filesize('template-mails/inscripcion_rrhh/index.html'));
      fclose($file);
    }
    $linkcomentario = $configUrlAbsoluta . "capacitacion-detalle.php?id=" . $id_curso;
    $link_confirma = $configUrlAbsoluta . "capacitacion-action-rrhh.php?token=$tokenCurso&action=aprobar";
    $link_rechaza = $configUrlAbsoluta . "capacitacion-action-rrhh.php?token=$tokenCurso&action=rechazar";

    $strBody = str_replace('@@@url@@@', $configUrlAbsoluta.'/template-mails/inscripcion_rrhh/', $strBody);
    $strBody = str_replace('@@@titulo@@@', $titulo, $strBody);
    $strBody = str_replace('@@@nombrede@@@', $usuario, $strBody);
     $strBody = str_replace('@@@link_confirma@@@', $link_confirma, $strBody);
    $strBody = str_replace('@@@link_rechaza@@@', $link_rechaza, $strBody);
     
  $params = array(
    'sender' => array('MRM // McCann' => EMAIL_PRINCIPAL) 
    ,'subject' => 'Plan de Capacitación - Nueva solicitud pendiente de aprobación'
    ,'body' => $strBody
    ,'addresses' => array($nombrepara => $email)
    );

    $res = $app->load('email', 'sendEmail', $params);
  }
}

function sendEmailCursoInscripto($id_curso, $id_usuario)
{
  global $cursos, $configUrlAbsoluta, $app;
  $users = new Users();
 
  $it = $cursos->getInscripcionUsuario($id_curso, $id_usuario);
  $item = $it[0];
 
  $titulo = $item->nombre_curso;
  $nombrede = $item->name.' '.$item->lastname;
  $tokenCurso = $item->token; 
  
  $id_superior = $cursos->getSupervisorIdByUser($item->id_usuario);
  $nombredesup = $users->getUserNameByID($id_superior);
  
  $linkcomentario = $configUrlAbsoluta . "capacitacion-detalle.php?id=" . $id_curso;
  $link_confirma = $configUrlAbsoluta . "capacitacion-action.php?token=$tokenCurso&action=aprobar";
  $link_rechaza = $configUrlAbsoluta . "capacitacion-action.php?token=$tokenCurso&action=rechazar";

  if (isset($nombredesup))
  {

    $email = $users->getEmailByUserID($id_superior)->email;
    $nombrepara = $users->getUserNameByID($id_superior);
    
    $file = @fopen('template-mails/inscripcion_lider/index.html', 'r');
    if ($file)
    {
      $strBody = fread($file, filesize('template-mails/inscripcion_lider/index.html'));
      fclose($file);
    }


    $strBody = str_replace('@@@url@@@', $configUrlAbsoluta.'/template-mails/inscripcion_lider/', $strBody);
    $strBody = str_replace('@@@titulo@@@', utf8_decode($titulo), $strBody);
    $strBody = str_replace('@@@nombrede@@@', utf8_decode($nombrede), $strBody);
    $strBody = str_replace('@@@link_confirma@@@', $link_confirma, $strBody);
    $strBody = str_replace('@@@link_rechaza@@@', $link_rechaza, $strBody);

    $params = array(
    'sender' => array('MRM // McCann' => EMAIL_PRINCIPAL) 
    ,'subject' => 'Plan de Capacitación - Nueva solicitud pendiente de aprobación'
    ,'body' => $strBody
    ,'addresses' => array($nombredesup => $email)
    );

    $res = $app->load('email', 'sendEmail', $params);


  }
  
  
}
?>