<?php 
session_start(); 
$strNombrePagina = getPageName($_SERVER['PHP_SELF']);

if ($strNombrePagina != 'login.php' && $strNombrePagina != 'recordar-pass.php' && $strNombrePagina != 'logout.php' && $strNombrePagina != 'send_birthday_emails.php')
{
  if (!isset($_SESSION['in_mrm_id']) || !is_numeric($_SESSION['in_mrm_id']) || $_SESSION['in_mrm_id'] == 0)
  {
    $queryString = (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '');
    $_SESSION['http_referer'] = $strNombrePagina . $queryString;
      
    header('location: login.php'); exit();
  }
}

/*
 * Database connection.
 */

require_once (__DIR__ . '/../includes/config.php');
require_once (__DIR__ . '/../classes/ez_sql_core.php');
require_once (__DIR__ . '/../classes/ez_sql_mysql.php');
require_once (__DIR__ . '/../classes/Module.php');

/*
 * Integration with new MRM_App Core
 */
require_once (__DIR__ . '/../components/MRM_App/bin/config/config.php');
require_once (__DIR__ . '/../components/MRM_App/bin/core/App.php');

$dbconnect = new ezSQL_mysql($configBaseDeDatos['user'], $configBaseDeDatos['pass'], $configBaseDeDatos['base'], $configBaseDeDatos['host']);
$dbconnect->query('SET NAMES "utf8"');


/*
 * What to do.
 */

function whatToDo()
{
  return (isset($_GET['action'])) ? $_GET['action'] : '';
}

/*
 * Get header.
 */

function getHeader()
{
  global $docTitle;
  include_once('template-parts/header.php');
}

/*
 * Get footer.
 */

function getFooter()
{
  global $strNombrePagina;
  include_once('template-parts/footer.php');
}

/*
 * Get sidebar.
 */

function getSidebar()
{
  include_once('template-parts/sidebar.php');
}

/*
 * Get birthdays.
 */

function getBirthdays()
{
  include_once('template-parts/birthdays.php');
}

/* FUNCTIONS GENERALS */

function getPageName($url)
{
  $arrURL = explode('/', $url);
  if (is_array($arrURL))
  {
    return $arrURL[count($arrURL) - 1];
  }
  else
  {
    return '';
  }
}

function escape($texto)
{
  if (!get_magic_quotes_gpc())
  {
    return mysql_real_escape_string($texto);
  }
  else
  {
    return $texto; //si el server tiene activadas las magic_quotes
  }
}

function send_email($strTo, $strSubject, $strBody, $intPriority, $strFrom)
{
  $strHead = 'From: MRM Intranet <' . $strFrom . '>\r\n';
  $strHead .= "MIME-Version: 1.0\r\n";
  $strHead .= "Content-type: text/html; charset=iso-8859-1\r\n";

  if (@mail($strTo, $strSubject, $strBody, $strHead))
  {
    return true;
  }
  else
  {
    return false;
  }
}

/**
 * Global runtime options.
 */
function get_option($_oname)
{
  global $dbconnect;
  return $dbconnect->get_var('SELECT option_value from in_options WHERE option_name="' . $_oname . '"');
}

function add_option($_oname, $_ovalue)
{
  return update_option($_oname, $_ovalue);
}

function update_option($_oname, $_ovalue)
{
  global $dbconnect;
  $dbconnect->query('INSERT INTO in_options (option_name, option_value) VALUES ("' . $_oname . '", "' . $_ovalue . '") ON DUPLICATE KEY UPDATE option_name="' . $_oname . '", option_value="' . $_ovalue . '"');
  return (bool) $dbconnect->rows_affected;
}

function delete_option($_oname, $_ovalue)
{
  global $dbconnect;
  $dbconnect->query('DELETE from in_options WHERE option_name="' . $_oname . '" LIMIT 1');
}

/**
 * Head's external resources load.
 */
function get_site_version()
{
  return 'v=0.1';
}

function add_style($_href)
{
  global $configUrlAbsoluta;
  echo '<link rel="stylesheet" type="text/css" href="' . $configUrlAbsoluta . $_href . '?' . get_site_version() . '" />';
}

function add_script($_src)
{
  global $configUrlAbsoluta;
  echo '<script type="text/javascript" src="' . $configUrlAbsoluta . $_src . '?' . get_site_version() . '"></script>';
}

?>