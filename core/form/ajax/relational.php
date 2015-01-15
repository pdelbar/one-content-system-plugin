<?php
/**
 * Handles theAjax call for the multi-relational widget
 * @package one|content
 */
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );

$curDir = dirname(__FILE__);
$parts = explode( DS, $curDir );
$libpath = '';
for( $i = 0; $i < 6; $i++ )
{
	array_pop($parts);
	if( $i == 3 )
		$libpath = implode( DS, $parts );
}
$path = implode( DS, $parts );
define( 'JPATH_BASE',    $path );


// $ONE_SCRIPT_PATH = $libpath . DS . 'nano' . DS;
// define( 'ONE_SCRIPT_PATH', $ONE_SCRIPT_PATH );
// define( 'ONE_SCRIPT_CUSTOM_PATH', JPATH_BASE . DS . 'media' . DS . 'nano' );

/* Required Files */
require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

if( $withDB )
{
	/* To use Joomla's Database Class */
	require_once( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
}

$mainframe = JFactory::getApplication('site');

require_once(JPATH_BASE.'/plugins/system/one/lib/one.php');;
One::getInstance()->setCustomPath(JPATH_BASE.'/media/one');

// require_once( ONE_SCRIPT_PATH . 'tools' . DS . 'autoload.php' );
require_once( One::getInstance()->getPath() . '/tools.php' );


$scheme = $_POST[ 'searchscheme' ];
$tparts = explode( ':', $_POST[ 'target' ] );
$phrase = strip_tags( $_POST[ 'phrase' ] );
$selfId = ( isset( $_POST[ 'selfId' ] ) ) ? $_POST[ 'selfId' ] : NULL;

$query = One_Repository::selectQuery( $scheme );

$scheme = $query->getScheme();
$idAttr = $scheme->getIdentityAttribute()->getName();

if( !is_null( $selfId ) )
	$query->where( $idAttr, 'neq', $selfId ); // possibility to implement if the 2 schemes are the same in the manytomany relationship to exclude the current ID

$or = $query->addOr();
foreach( $tparts as $tpart )
{
	$or->where( $tpart, 'contains', $phrase );
}
if( $tparts > 0 )
{
	$or->where( 'joined', 'literal', ' CONCAT_WS( " ", ' . implode( ', ', $tparts ) . ' ) LIKE "%' . $phrase . '%"');
}
$results = $query->execute();


$toEcho = $_POST['dd'] . '=>%O%';
$gottenResults = array();

if( count( $results ) > 0 )
{
	foreach( $results as $result )
	{
		$showVal = '';
		foreach( $tparts as $tpart )
		{
			$showVal .= $result->$tpart . ' ';
		}
		$gottenResults[] = $result->$idAttr . '=' . $showVal;
	}
}

echo $toEcho . implode( '^,^', $gottenResults );