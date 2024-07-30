<?php
//if ( ! function_exists( 'wp_verify_nonce' ) ) {
//	require_once( ABSPATH . 'wp-includes/pluggable.php' );
//}
require_once(WFPROPIETARIOS_INCLUDES . 'class.propietario.database.php');
class Wf_Propietario_New{

    public static function edition(){
    	if(!current_user_can('edit_canis_canis'))
					wp_die('<div class="error"><p>'.__('No tiene permisos para esta opción').'</p></div>','candepalleiro-management');
    	
   		$object=new Owner_Config();

   		/*
    	*
    	*
    	* Chequeo del formulario
    	*
    	*
    	*/ 
   		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']=="owner-new" && check_admin_referer( 'new_owner','new_owner_nonce_field' ) ):
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
				$id=$object->insert();
			    $nonce=wp_create_nonce( 'wf_edit_propietario'.$id );
				wp_redirect(home_url().'/wp-admin/admin.php?page=propietarios&action=edit&codex='.$id.'&_wpnonce='.wp_create_nonce( 'wf_edit_propietario'.$id)); 
			endif;
		else:
			//si nunca entramos en el formulario dejamos que la opción de envío de spam
			//este marcado como 1
			//$object_customer->set_field('customer_send_spam',"1");
   		endif;
		/*
    	*
    	*
    	* Vista
    	*
    	*
    	*/ 
    	require_once(WFPROPIETARIOS_VIEWS.'propietario-new-view.php'); 	
   }

   

   
    public static function screen_option(){
    	//preparamos a barra de ferramentas axuda/nº de columnas
		$screen = get_current_screen();	
		
		$screen->add_help_tab( array(
			'id'	=> 'new-propietario-help',
			'title'	=> __('Nuevo Propietario','candepalleiro-management'),
			'content'	=> __('<p><i class="fa fa-exclamation-triangle"></i>&nbsp;Aquí insertaremos un PROPIETARIO de la raza autóctona Can de Palleiro. Hay una serie de campos obligatorios. Por favor lea atentamente las leyendas de los campos para una perfecta inserción de datos.','candepalleiro-management'),
		) );
		
		//engadimos o css do datepicker (calendario) para o formulario
		wp_enqueue_script('jquery-ui-datepicker');
    }
    public static function footer_scripts(){?>
		<script> postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}
}