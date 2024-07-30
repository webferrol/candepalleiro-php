<?php
defined( 'ABSPATH' ) OR exit;
/**
*
* Plugin Name: Can de Palleiro PDF(Versión 2.0)
* Plugin URI: http://www.webferrol.com
* Description: Base de datos de candepalleiro: raza autóctona galega
* Version: 2.0
* Author: Xurxo González
* Author URI: http://www.webferrol.com
* Text Domain: can-de-palleiro-fpdf
* Domain Path: /languages/
**/

// Non mostraremos ningún tipo de información se o plugin é invocado directamente
if ( !function_exists( 'add_action' ) ) {
	echo 'Son un plugin e non podo facer moito se son chamado directamente.';
	exit;
}


/**
 * Register the plugin.
 *
 * Display the administration panel, insert JavaScript etc.
 */
class CanDePalleiro_PDF{

    /**
     * @var string
     */
    private $version = '2.0';


    /**
     * Constructor
     */
    public function __construct() {	

    	 /**
		 * Inicializamos menu
		 */
		 add_action( 'admin_menu', array( $this, 'load_menu' ));

		if(isset($_REQUEST['action']) and $_REQUEST['action']=='pdf-owner' and $_REQUEST['page']=='PDF'){
			require_once(str_replace('candepalleiro-pdf','candepalleiro-propietarios',plugin_dir_path(__FILE__)).'includes/class.propietario.database.php');
			global $owner;
			$owner=new Owner_Config($_REQUEST['owner']);
			$this->carga_pdf();
		}
		if(isset($_REQUEST['action']) and $_REQUEST['action']=='pdf-dog' and $_REQUEST['page']=='PDF'){
			require_once(str_replace('candepalleiro-pdf','candepalleiro-management',plugin_dir_path(__FILE__)).'includes/class.dog.database.php');
			global $can;
			$can=new Candepalleiro_Config($_REQUEST['dog']);
			$this->carga_perro_pdf();
		}

		if(isset($_REQUEST['action']) and $_REQUEST['action']=='pedigree' and $_REQUEST['page']=='PDF'){
			$this->pedigree_pdf();
		}

		if(isset($_REQUEST['action']) and $_REQUEST['action']=='list-of-dogs-pdf' and $_REQUEST['page']=='PDF'){
			$this->list_dogs_pdf();
		}
		if(isset($_REQUEST['action']) and $_REQUEST['action']=='list-of-owners-pdf' and $_REQUEST['page']=='PDF'){
			$this->list_owners_pdf();
		}

    }
    function load_menu(){
    	$hook=add_menu_page( __('Administración Can de Palleiro','candepalleiro-management'), __('Configuración PDF','candepalleiro-management'), 'menu_canis_canis', 'PDF', ['PDF_Init','init']);
    }





    function carga_pdf(){
		if (ob_get_length()) ob_end_clean();
		if(!class_exists("FPDF"))
			require_once(plugin_dir_path(__FILE__). 'fpdf.php');//por se xa foi declarada a clase
			require_once(plugin_dir_path(__FILE__) . 'includes/class.pdf.pdf-owner.php');
		exit;		
	}
	function carga_perro_pdf(){
		if (ob_get_length()) ob_end_clean();
		if(!class_exists("FPDF"))
			require_once(plugin_dir_path(__FILE__). 'fpdf.php');//por se xa foi declarada a clase
			require_once(plugin_dir_path(__FILE__) . 'includes/class.pdf.pdf-dog.php');
		exit;		
	}
	function pedigree_pdf(){
		if (ob_get_length()) ob_end_clean();
		if(!class_exists("FPDF"))
			require_once(plugin_dir_path(__FILE__). 'fpdf.php');//por se xa foi declarada a clase
			require_once(plugin_dir_path(__FILE__) . 'includes/class.pdf.pedigree.php');
		exit;	
	}
	function list_dogs_pdf(){
		if (ob_get_length()) ob_end_clean();
		if(!class_exists("FPDF"))
			require_once(plugin_dir_path(__FILE__). 'fpdf.php');//por se xa foi declarada a clase
			require_once(plugin_dir_path(__FILE__) . 'includes/class.pdf.list-of-dogs-pdf.php');
		exit;	
	}
	function list_owners_pdf(){
		if (ob_get_length()) ob_end_clean();
		if(!class_exists("FPDF"))
			require_once(plugin_dir_path(__FILE__). 'fpdf.php');//por se xa foi declarada a clase
			require_once(plugin_dir_path(__FILE__) . 'includes/class.pdf.list-of-owners-pdf.php');
		exit;	
	}
	 
}

class PDF_Init{
	function init(){
		 echo "<h1>ZONA DE ADMINISTRACIÓN PDF";
	}
}


//carga da clase
if (is_admin()):
	// Cargamos todos los plugins activos
	//$plugins = get_option('active_plugins');
	// Plugin que deseamos comprobar
	//$required_plugin = 'can-de-palleiro/can-de-palleiro.php';

	// booleano que activamos si el plugin está activo
	//$debug_queries_on = FALSE;

	// Comprobamos que el plugin está entre los activos.
	//if ( in_array( $required_plugin , $plugins ) ) {

 	// Activamos el booleano en caso de estar activo.
 	//$debug_queries_on = TRUE;
	//}
	//if(!$debug_queries_on)
		//wp_die( 'This plugin requires "Can de Palleiro (Versión 2.0)" active.' );
	new CanDePalleiro_PDF();
endif;