<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wf_Propietarios_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Propietario', 'candepalleiro-management' ), //singular name of the listed records
			'plural'   => __( 'Propietarios', 'candepalleiro-management' ), //plural name of the listed records
			'ajax'     => true //does this table support ajax?
		] );

	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'bajapropietario':
	    	$c=($item['bajapropietario']>0)?'<i  title="'.__("Baja",'candepalleiro-management').'" class="border negro">'.__("B",'candepalleiro-management').'</i>':'';
			return $c;
			break;
			case 'p_nome':
			case 'id_propietario':
            case 'p_apelido':
			case 'tipo_documento':
			case 'nif':
			case 'telefono':
			case 'telefono2':
			case 'cp':
			case 'mail':
			case 'numero_vivienda':
			case 'pais':
			case 'provincia':
			case 'parroquia':
			case 'lugar':
			case 'concello':
				//return ucwords($item[$column_name]);
			//break;			
				return $item[ $column_name ];
			break;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
			break;
		}
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_p_apelido( $item ) {

		$restore_nonce = wp_create_nonce( 'sp_restore_propietario' );

		$title = '<strong>' . $item['p_apelido'] . '</strong>';

		$options=array();
		if($item['bajapropietario']==1)$options[]='<i  title="'.__("Baja de propietario",'candepalleiro-management').'" class="border negro">'.__("B",'candepalleiro-management').'</i>';

		if(!current_user_can('edit_canis_canis')):
		$actions =[];
		else:
		$actions = [
					'restore'      => sprintf('<a href="?page=%s&amp;post_type=%s&amp;action=%s&amp;codex=%s&amp;_wpnonce=%s"><i title="'.__('Restaurar','candepalleiro-management').'" class="fa fa-undo fa-spin"></i><span>&nbsp;'.__('Restaurar','candepalleiro-management').'</span></a>',$_REQUEST['page'],'papelera','restaurar',$item['id_propietario'],$restore_nonce),				
					];
		endif;

		//return $title . $this->row_actions( $actions );

		//Return the title contents
        return sprintf('%1$s <span style="color:silver">'.implode('&nbsp;',$options).'</span>%2$s',
            /*$1%s*/ $title,
            /*$2%s*/ $this->row_actions($actions)
        );
	}


	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-propietarios[]" value="%s" />', $item['id_propietario']
		);
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text		
			'p_apelido'    => __('Apellidos','candepalleiro-management'),			
			'p_nome'     => __('Nombre de Pila','candepalleiro-management'),			
			'id_propietario'     => __('Código','candepalleiro-management'),					
			'lugar'    => __('Lugar','candepalleiro-management'),
			'mail'    => __('Correo Electrónico','candepalleiro-management'),
			'provincia'    => __('Provincia','candepalleiro-management'),
			'concello'    => __('Ayuntamiento','candepalleiro-management'),
			'nif'  => __('NIF','candepalleiro-management').', '.__('CIF','candepalleiro-management').', '.__('Otros','candepalleiro-management'),
			'telefono' => __('Teléfono','candepalleiro-management'),
			'pais' => __('País','candepalleiro-management'),
		];

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'p_nome'     => array('p_nome',true),     //true means it's already sorted
			'id_propietario'     => array('id_propietario',false),
			'p_apelido'     => array('p_apelido',false),
            'nif'     => array('nif',false),  
			'lugar'     => array('lugar',false), 
			'cp'     => array('cp',false),  
			'provincia'     => array('provincia',false), 
			'concello'     => array('concello',false), 
			'numero_vivienda'     => array('numero_vivienda',false), 
			'parroquia'     => array('parroquia',false), 
			'mail'     => array('mail',false),  
			'tipo_documento'     => array('tipo_documento',false),  
			'pais'     => array('pais',false),  
			'telefono'     => array('telefono',false),  
			'telefono2'     => array('telefono2',false),
			'bajapropietario'     => array('bajapropietario',false),  
        );

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		if(!current_user_can('edit_canis_canis')):
		$actions =[];
		else:
		$actions = [			
			'restore-owner'    => __('Restaurar','candepalleiro-management'),
			'------'=>'------',
			'------'=>'',
			'------'=>'------',
			'------'=>'------',
			'delete-owner'    => __('Eliminar definitivamente','candepalleiro-management'),
		];
		endif;

		return $actions;
	}

	public function process_bulk_action() {
		//Detect when a bulk action is being triggered...
		if ( 'restore-owner' === $this->current_action()){
			$ids=array();
			if(isset($_REQUEST['bulk-propietarios']))
				$ids = esc_sql( $_REQUEST['bulk-propietarios'] );
			$ids = implode($ids,',');

			if(empty($ids)):
				_e( '<div class="error"><p>Debe seleccionar por lo menos un elemento de la lista.</p></div>', 'candepalleiro-management' );
			else:
				echo $ids;
				//$url=str_replace("restore-owner","restaurado",add_query_arg());
				global $wpdb;
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}candepalleiro_propietario SET trash=0 WHERE id_propietario IN (%s)",$ids
					)
				);
				wp_redirect("?page=propietarios&post_type=papelera");
				exit;
				
			endif;
		}


		//Detect when a bulk action is being triggered...
		if ( 'restaurar' === $this->current_action() and current_user_can('edit_canis_canis') ) {


			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_restore_propietario') ) {
				die( 'Fallo de la verificación del nonce' );
			}
			else {
				global $wpdb;
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}candepalleiro_propietario SET trash=0 
						 WHERE id_propietario IN (%s)",$_REQUEST['codex']
					)
				);
				wp_redirect("?page=propietarios&post_type=papelera");		
			}

		}

		// If the trash bulk action is triggered
		if ( 'delete-owner' === $this->current_action() and current_user_can('edit_canis_canis')){
			$ids=array();
			if(isset($_REQUEST['bulk-propietarios']))
				$ids = esc_sql( $_REQUEST['bulk-propietarios'] );
			$ids = implode($ids,',');
			if(empty($ids)):
				_e( '<div class="error"><p>Debe seleccionar por lo menos un elemento de la lista.</p></div>', 'candepalleiro-management' );
			else:
				global $wpdb;
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}candepalleiro_propietario 
						 WHERE id_propietario IN (%s)",$ids
					)
				);
				wp_redirect("?page=propietarios&post_type=papelera");
				exit;
			endif;
			//print_r($_REQUEST['bulk-customer']);
		}

		
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'propietarios_trash_per_page', 100 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count_search();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_propietarios( $per_page, $current_page );
	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public  function get_propietarios( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		// $querydata = $wpdb->get_results('SELECT '.$campos.' FROM `'.TABLE_CAN.'`
		// 	LEFT JOIN `'.TABLE_OWNER.'` ON ('.TABLE_CAN.'.id_propietario = '.TABLE_OWNER.'.id_propietario)  WHERE 1 
		// 	'.$where);

		$sql = "SELECT * FROM {$wpdb->prefix}candepalleiro_propietario WHERE trash>0";

		$sql .= " ".self::data_search()." "; //añadido al código original
		
		$wpdb->flush();

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}else{
			$sql .=' ORDER BY id_propietario desc';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	public function data_search(){
		$where='';
		if(isset($_GET['c'])):
			$criterio=$_GET['c'];
			$value=$_GET['v'];
			for($i=0;$i<count($criterio);$i++):
				if(!empty($criterio[$i])):
					$field=$criterio[$i];
					$searchAlphabet = $value[$i]; 	
					$where .= ' AND ' . $field . ' LIKE \'%'.$searchAlphabet.'%\' ';
				endif;
			endfor;			
		endif;

		if(isset($_GET['de']) and !empty($_GET['de'])):
			$where .= ' AND nacemento BETWEEN \''.$this->invert_date($_GET['de']).'\' AND \''.$this->invert_date($_GET['a']).'\' ';
		endif;

		if(isset($_GET['bajapropietario']) and !empty($_GET['bajapropietario'])):
			$field='bajapropietario';	
			$where .= ' AND ' . $field . ' = 1 ';
		endif;
		
		if(isset($_GET['no_bajapropietario']) and !empty($_GET['no_bajapropietario'])):
			$field='bajapropietario';	
			$where .= ' AND ' . $field . ' !=1 ';
		endif;

		return $where;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public  function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}candepalleiro_can",
			[ 'customer_id' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database search form.
	 *
	 * @return null|string
	 */
	public  function record_count($trash=0) {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}candepalleiro_propietario";
		$sql .=($trash==0)?" WHERE trash<1":" WHERE trash>0";

		return $wpdb->get_var( $sql );
	}
	
	/**
	 * Returns the count of records in the database search form. //añadido al código
	 *
	 * @return null|string
	 */
	public  function record_count_search() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}candepalleiro_propietario WHERE trash>0";
		$sql .= " ".self::data_search()." "; //añadido al código original
		return $wpdb->get_var( $sql );

	}


	function invert_date($data){
		//Distintas posibilidades que temos de busca
		//$data = "10-12-2008"
		//$data=12-2008
		//$data=2008
		//$data=10-12
		$origen=explode("-",$data);//descompoñemos o str data nun array, o caracter limitador é o guión
		$destino=array();
		$tam=count($origen)-1;
		for($i=0;$i<count($origen);$i++)://reconstruímos o array pero invertido
			$destino[]=$origen[$tam--];
		endfor;
		return implode("-",$destino);
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No hay clientes disponibles.', 'candepalleiro-management' );
	}
}


class Wf_Propietarios_List_load{
	public static function init(){			
			
		global $Wftable;
	?>
		<div class="wrap">	    
			<?php	
		     printf('<h2 class="fa fa-trash"> %s </h2>',esc_html__('Papelera de propietarios','candepalleiro-management'));
		    ?>
		   <div id="display-search-form">
		        <div id="accordion">
		            <h3 class="clear"><span class="fa fa-search">&nbsp;<?php _e("Búsqueda",'candepalleiro-management');?></span></h3>
		             <?php 
		             self::add_search_form();
		             ?>  
		         </div>      
		    </div>
		    <ul class="subsubsub">
		        <li class="all"><a href="admin.php?page=propietarios"><?php _e("Todos",'candepalleiro-management');?> <span class="count">(<?php echo $Wftable->record_count();?>)</span></a> |</li>
		        <li class="trash"><a class="current" href="admin.php?page=propietarios&post_type=papelera"><?php _e("Papelera",'candepalleiro-management');?> <span class="count">(<?php echo $Wftable->record_count(1);?>)</span></a></li>
		    </ul> 
		    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		    <form id="list-filter" method="get">	
				<!-- For plugins, we also need to ensure that the form posts back to our current page -->
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type'];?>" />
				<!-- Now we can render the completed list table -->
				<?php 
				$Wftable->prepare_items();
				$Wftable->display();	
				?>
			</form>	
		</div>
	<?php
	}
	public static function add_search_form() {
		$bajapropietario=(isset($_REQUEST['bajapropietario']))?'checked="checked"':'';
		$no_bajapropietario=(isset($_REQUEST['no_bajapropietario']))?'checked="checked"':'';
	?>
		<form role="search" method="get" id="searchform" class="searchform" action="" >
		<div class="search-form-div">
        	<div class="panel">
            	<h3><span class="fa fa-question">&nbsp;<?php _e('Criterios de Búsqueda','candepalleiro-management');?> 1</span></h3>
                <div>
			    	<?php self::get_criteria(1);?>	
                </div>
             </div>
             <div class="panel">
            	<h3><span class="fa fa-question">&nbsp;<?php _e('Criterios de Búsqueda','candepalleiro-management');?> 2</span></h3>
                <div>
					<?php self::get_criteria(2);?>
                </div>
             </div>
            <div class="panel-all" id="alii">
            	<h3><span class="fa fa-plus-square-o">&nbsp;<?php _e('Otros','candepalleiro-management');?></span></h3>

                <ul>
                <li>
                <input type="checkbox" value="1" <?php echo $bajapropietario;?> name="bajapropietario" id="s5" />
				<label for="s5"><i  title="<?php _e("Baja",'can-de-palleiro');?>" class="border negro"><?php _e("B",'can-de-palleiro');?></i>&nbsp;<?php _e("Baja",'can-de-palleiro');?></label>               
                
				</li>
				<li>
               <input type="checkbox" value="1" <?php echo $no_bajapropietario;?> name="no_bajapropietario" id="s6" />
				<label for="s6"><?php _e('No Baja','can-de-palleiro');?></label>
				</li>
				</ul>

				
                <div class="submit">
            	<input type="submit" id="searchsubmit" class="button button-primary" value="<?php _e('Buscar','candepalleiro-management');?>" /> 
                </div>
            </div> 
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>" />		
		<input type="hidden" name="action" value="<?php echo $_REQUEST['action'];?>" />
		<input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type'];?>" />
		<input type="hidden" name="action2" value="search-data" />    
        </div>		
		</form>
    <?php	
	}
	
	//Esta función  carga o selector (select) do formulario de criterios de busca da táboa List Table
	public static function get_criteria($number){
		$id='c'.$number;
		$option=[
			""=>__('Sin criterios','can-de-palleiro'),
				"p_nome"=>__('Nombre de Pila','can-de-palleiro'),
				"p_apelido"=>__('Apellidos','can-de-palleiro'),
				"tipo_documento"=>__('Tipo de Documento','can-de-palleiro'),
				'nif'  => __('NIF','can-de-palleiro').', '.__('CIF','can-de-palleiro').', '.__('Otros','can-de-palleiro'),
				"telefono"=>__('Teléfono','can-de-palleiro'),
				"telefono2"=>__('Otro Teléfono','can-de-palleiro'),
				"pais"=>__('País','can-de-palleiro'),
				"provincia"=>__('Provincia','can-de-palleiro'),
				"concello"=>__('Ayuntamiento','can-de-palleiro'),
		];
		?>
        <label for="c<?php echo $number;?>"><?php _e('Criterio','candepalleiro-management')?> <?php echo $number;?></label>
		<select id="<?php echo $id;?>" name="c[]">
		<?php
		foreach ($option as $key=>$value):
			$selected=($_REQUEST['c'][$number-1]==$key)?"selected=\"selected\"":"";
		?>
	    <option <?php echo $selected;?> value="<?php echo $key;?>"><?php echo $value;?></option>
        <?php
		endforeach;
		?>
		</select>
        <input type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Criterios de Búsqueda','candepalleiro-management'));?>" value="<?php if(isset($_REQUEST['v'])) echo $_REQUEST['v'][$number-1];?>" name="v[]"  id="s<?php echo $number;?>" />
        <?php
	}

	//Esta función serve para recoller a data de nacemento entre dous datas
	public function get_date_of_birth(){
		?>
        <label for="de">ENTRE</label>
        <input type="text" placeholder="<?php echo sprintf(__('Escribir %s aquí','candepalleiro-management'),__('dd-mm-aaaa','candepalleiro-management'));?>"  name="de"  id="de" value="<?php if(isset($_REQUEST['de'])) echo $_REQUEST["de"];?>" />
        <br />
        <label for="a">Y</label>
        <input type="text" placeholder="<?php echo sprintf(__('Escribir %s aquí','candepalleiro-management'),__('dd-mm-aaaa','candepalleiro-management'));?>" value="<?php if(isset($_REQUEST['a'])) echo $_REQUEST["a"];?>" name="a"  id="a" />
        <?php
	}

	/**
     * screen_options()
     */
	public static function screen_option(){	
		global $Wftable;
		$screen = get_current_screen();	
		$screen->add_help_tab( array(
					'id'	=> 'list-owners-help2',
					'title'	=> __('Vista previa','candepalleiro-management'),
					'content'	=> '<p>' . __( 'Esta pantalla te da acceso al listado de propietarios de la raza autóctona Can de palleiro. Puedes personalizar el formato de esta pantalla para acomodarlo a tu sistema de trabajo.','candepalleiro-management') . '</p>',
				) );
				$screen->add_help_tab( array(
					'id'	=> 'list-owners-help',
					'title'	=> __('Leyendas de la página','candepalleiro-management'),
					'content'	=> '<ul>
					
										<li><i  title="'.__("Baja",'candepalleiro-management').'" class="border negro">'.__("B",'candepalleiro-management').'</i>&nbsp;'.__("Baja",'candepalleiro-management').': '.__("Propietario que es dado de baja pero sigue existiendo en la base de datos.",'candepalleiro-management').'</li>
									</ul>',
				) );
		//Engadir as opcións de pantalla
		$option = 'per_page'; 
		$args = array(
			'label' => __('Número de elementos por página:','candepalleiro-management'),
			'default' => 10,
			'option' => 'propietarios_trash_per_page'
		);
		add_screen_option( $option, $args );

		
		$Wftable=new Wf_Propietarios_List();
		
	}
}