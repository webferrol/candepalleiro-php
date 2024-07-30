<?php
//if ( ! function_exists( 'wp_verify_nonce' ) ) {
//	require_once( ABSPATH . 'wp-includes/pluggable.php' );
//}
require_once(WFPROPIETARIOS_INCLUDES . 'class.propietario.database.php');
class Propietario_Edition{

    public static function edition(){
    	// In our file that handles the request, verify the nonce.
		$nonce = esc_attr( $_REQUEST['_wpnonce'] );
		if ( ! wp_verify_nonce( $nonce, 'wf_edit_propietario'.$_REQUEST['codex'] ) ) {
				die( '<div class="error">ERROR DE RECONOCIMIENTO DE REGISTO.</div>' );
				
		}
		$object=new Owner_Config($_GET['codex']);
		$dogs=$object->getDogs(array("id_propietario=".$_REQUEST['codex']));//perros en propiedad	
   		/*
    	*
    	*
    	* Comprobamos si venimos de una página clase/vista de inserción 
    	*
    	*/ 
   		if(isset($_REQUEST['action2']) and $_REQUEST['action2']=='insert'):
   			$message='<div id="message" class="updated below-h2"><p>'.__( 'Datos salvados correctamente', 'candepalleiro-management' ).'</p></div>';
   	    endif;
   		/*
    	*
    	*
    	* Chequeo del formulario
    	*
    	*
    	*/ 
   		if ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2']=="bulk-edit" && check_admin_referer( 'edit_propietario'.$_REQUEST['codex'],'edit_propietario_nonce_field' ) ):
   			$object->set_POST();
   			//validate
			$config = array(
					array('p_nome',__('Nombre de Pila','candepalleiro-management'),'empty'),
						array('p_apelido',__('Apellidos','candepalleiro-management'),'empty'),
						array('nif',__('Documento','candepalleiro-management'),($_REQUEST['tipo_documento']=='nif')?'empty|dni_nie':'empty'),
						array('mail',__('Correo Electrónico','candepalleiro-management'),'email'),
			);
			$validate=new Validate($config);
			if($validate->validate()):
				$object->update($_REQUEST['codex']);
				wp_redirect(add_query_arg());
			endif;
   		endif;
		/*
    	*
    	*
    	* Vista
    	*
    	*
    	*/ 
    	if(!current_user_can('edit_canis_canis')):
    	require_once(WFPROPIETARIOS_VIEWS.'read/propietario-edit-read-view.php');
    	else:
    	require_once(WFPROPIETARIOS_VIEWS.'propietario-edit-view.php');
    	endif;
   }

  

   
    public static function screen_option(){
    	//preparamos a barra de ferramentas axuda/nº de columnas
		$screen = get_current_screen();	
		
		$screen->add_help_tab( array(
			'id'	=> 'new-edition-help',
			'title'	=> __('Zona de edición','candepalleiro-management'),
			'content'	=> __('<p><i class="fa fa-exclamation-triangle"></i>&nbsp;Zona de edición de los registrados pertenecientes a la raza autóctona de Can de Palleiro.','candepalleiro-management'),
		) );
		
		/* User  choose between 1 or 2 columns (default 2) */
		add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
		/* Enqueue WordPress' script for handling the metaboxes */
		wp_enqueue_script('postbox'); 
		//engadimos o css do datepicker (calendario) para o formulario
		wp_enqueue_script('jquery-ui-datepicker');
    }
    public static function footer_scripts(){?>
		<script> postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}
}