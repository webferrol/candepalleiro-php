<?php
/**
 * Creación de la clase principal del plugin.
 *
 */
class CanDePalleiroPropietarios{

    /**
     * Constructor
     */
    public function __construct() {	
        
        /**
		 * app_out_buffer, load_languages,init_scripts_stylesheet
		 */	
        $this->add_action_esenciales();	

		 /**
		 * Añadimos filtro: imprescindible para el funcionamiento de la cantidad 
		 * de registros de las tablas wp-list-table
		 */	
		 add_filter('set-screen-option', array($this,'table_set_option'), 10, 3);
		 /**
		 * Inicializamos menu
		 */
		 add_action( 'admin_menu', array( $this, 'load_menu' ));


		 /**
		 * Añadimos filtro
		 */			
        //$this->add_filters();			
    }

    /**
     * Método de carga del menú
     */
    function load_menu(){
    	
    	switch($this->current_action()):
    		case 'bulk-spam':
    		break;
    		case 'edit':
    			require_once(WFPROPIETARIOS_INCLUDES.'class-propietario-edit.php');
    			//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
				$hook=add_menu_page( __('Administración Can de Palleiro','customer-management'), __('Propietarios','customer-management'), 'menu_canis_canis', 'propietarios', ['Propietario_Edition','edition']);

				//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
				add_submenu_page('propietarios',__('Administración Can de Palleiro','customer-management'), __('Listado de propietarios','customer-management'),'menu_canis_canis','propietarios',['Propietario_Edition','edition']);
				add_action( "load-".$hook, ['Propietario_Edition','screen_option']);
				add_action( "admin_footer-".$hook,['Propietario_Edition','footer_scripts']);
    		break;     		
    		default:

    		if(($this->current_page()=="propietarios") and isset($_REQUEST['post_type']) and $_REQUEST['post_type']=='papelera'):
    			require_once(WFPROPIETARIOS_INCLUDES.'class-wp-list-table-trash-propietarios.php');
    		else:
    			require_once(WFPROPIETARIOS_INCLUDES.'class-wp-list-table-propietarios.php');
    		endif;
    		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$hook=add_menu_page( __('Administración Can de Palleiro','candepalleiro-management'), __('Propietarios','candepalleiro-management'), 'menu_canis_canis', 'propietarios', ['Wf_Propietarios_List_load','init']);
			      add_submenu_page('propietarios',__('Administración Can de Palleiro','candepalleiro-management'), __('Listado propietarios','candepalleiro-management'), 'menu_canis_canis', 'propietarios', ['Wf_Propietarios_List_load','init']);
			add_action( "load-".$hook, ['Wf_Propietarios_List_load','screen_option']);
    		break;
    	endswitch;
    	/**
	     * Más opciones
	     */
    	
    	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    	//Nuevo propieatio
    	if(current_user_can('edit_canis_canis')):
    	require_once(WFPROPIETARIOS_INCLUDES.'class-propietario-new.php');
		$hook=add_submenu_page('propietarios',__('Administración Can de palleiro: Nuevo propietario','customer-management'),__('Nuevo propietario','customer-management'),'menu_canis_canis','nuevo-propietario',['Wf_Propietario_New','edition']);
		add_action( "load-".$hook, ['Wf_Propietario_New','screen_option']);
		add_action( "admin_footer-".$hook,['Wf_Propietario_New','footer_scripts']);
		endif;

	}
    /**
     * añadido de add_action imprescindibles
     */
     function add_action_esenciales() {
		//vaciamos o buffer para cando insertamos ou eliminamos un rexistro
		add_action('init', [$this,'app_output_buffer']);			
    }

    

	
	
	/**
     * Obtención de la variable pasada por URL action o action2: filtrado para cargar la página.
     */	
	 
	 public function current_action() {
	 
		if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
			return $_REQUEST['action'];
	 
		if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
			return $_REQUEST['action2'];	 
		return false;
	}
	
	/**
     * Obtención de la variable pasada por URL page: filtrado para cargar la página.
     */	
	public function current_page() {
	 
		if ( isset( $_REQUEST['page'] ) && -1 != $_REQUEST['page'] )
			return $_REQUEST['page']; 
		return false;
	}	
	
	
	
	
	/**
     * funcións chamadas dende o gancho add_action()
     */	
	function app_output_buffer(){
		ob_start();
	}
	

	
	
	
	
		
	
	
	/**
     * Filtros da clase 
     */
	function add_filters(){
		//O tipo de contido por defecto para o email é enviado a través da función wp_mail como 'text/plain' o cal non permite 
		//o uso de HTML. Sen embargo, ti podes utilizr o filtro wp_mail_content_type para cambiar o contido por defecto do email.					
		//add_filter( 'wp_mail_content_type',array($this,'set_html_content_type'));
		//filtro para configurar as opcións de pantalla do listado		
	}

	function table_set_option($status, $option, $value){	
			return $value;
	}
	
	
	
	/**
     * Funcións chamadas dende  add_filter()
     */
	function set_html_content_type() {
		return 'text/html';	
	}
	
}


if (is_admin()):
	new CanDePalleiroPropietarios();
endif;