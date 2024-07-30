<?php
defined( 'ABSPATH' ) OR exit;
/**
*
* Plugin Name: Gestión Club de Can de Palleiro Propietarios(Versión 3.1)
* Plugin URI: http://www.webferrol.com
* Description: Plugin para guardar información sobre los propietarios de los perros de la raza Can de Palleiro.
* Version: 3.1
* Author: Xurxo González Tenreiro
* Author URI: http://www.webferrol.com
* Text Domain: candepalleiro-management
* Domain Path: /languages/
**/

// Non mostraremos ningún tipo de información se o plugin é invocado directamente
if ( !function_exists( 'add_action' ) ) {
	echo 'Soy un plugin y no puedo hacer mucho si soy llamado directamente. Line 17: candepalleiro-management.php ';
	exit;
}

/**
 * Constantes
 */	
global $wpdb;
define('WFPROPIETARIOS_DIR', plugin_dir_path(__FILE__));
define('WFPROPIETARIOS_INCLUDES', WFPROPIETARIOS_DIR.'includes'.'/');
define('WFPROPIETARIOS_VIEWS', WFPROPIETARIOS_DIR.'views'.'/');


/**
 * Carga de la clase principal
 */	
require_once WFPROPIETARIOS_INCLUDES."init.php";


function my_load_scripts() {
    wp_enqueue_script( 'my_js', plugin_dir_url(__FILE__) . 'assets/js/eventos.js', array('jquery') );

    wp_localize_script( 'my_js', 'ajax_var', array(
        'url'    => admin_url( 'admin-ajax.php' ),
        'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
        'action' => 'check-propietario'
    ) );
}

add_action( 'admin_enqueue_scripts', 'my_load_scripts' );

function my_event_list_cb() {
    global $wpdb;    
    $data;
    // Check for nonce security
    $nonce = sanitize_text_field( $_POST['nonce'] );
    
    if ( ! wp_verify_nonce( $nonce, 'my-ajax-nonce' ) ) {
        die ( 'Busted!');
    }

    $result = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix ."candepalleiro_propietario WHERE tipo_documento='".$_POST['tipo']."' AND nif like '". $_POST['value'] ."'");   

    if (empty($result)) {
        $data = array(
            'status' => true,
        );    
    } else {
        $data = array(
            'status' => false,
            'message' => 'El documento utilizado ya existe: ' . $result[0]->p_nome . " " . $result[0]->p_apelido,
        );
    }

	wp_send_json( $data );

	exit();
    //wp_die();
}

add_action( 'wp_ajax_nopriv_check-propietario', 'my_event_list_cb' );
add_action( 'wp_ajax_check-propietario', 'my_event_list_cb' );