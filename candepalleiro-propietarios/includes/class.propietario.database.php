<?php
if (!defined("WEBFERROL_APLICACION")) die();
if(!class_exists('Database'))
	require_once(WFCANDEPALLEIRO_INCLUDES . 'class.database.php');
if(!class_exists('Validate'))
	require_once(WFCANDEPALLEIRO_INCLUDES . 'class.validate.php');
class Owner_Config extends Database{ 
	private $propietario=array();  //array que tendrá todos los atributos de la tabla propietario de la db
	private $saved=false;
	private $saved_label='strong';
	
	/**
     * Constructor
     */
    public function __construct($id=NULL) {	
		parent::__construct();
		//si no es nulo cargamos la tabla
		if(!is_null($id)):
		$this->propietario=$this->get_register_table('candepalleiro_propietario',array('id_propietario'=>$id));		
		else: //buscamos los campos de la tabla
		$this->set_fields_propietario();
			//print_r($this->propietario);
		endif;
	}
	
	function set_fields_propietario(){
		$this->propietario=$this->get_name_fields_table_array_associative('candepalleiro_propietario','');	
	}
	
	function get_propietario(){
		return $this->propietario;  //retorna el array asociativo
	}
	function set_propietario($propietario){
		$this->propietario=$propietario;  
	}
	
	function get_field($field){
		return $this->propietario[$field];
	}
	
	function set_field($field,$value){
		if(isset($this->propietario[$field]))
			$this->propietario[$field]=$value;
	}
	
	function set_POST(){
		foreach($this->propietario as $key=>$value):
			if(isset($_POST[$key]))
				$this->propietario[$key]=$_POST[$key];
		endforeach;
	}
	
	function is_saved(){
		return $this->saved;
	}
	function get_saved($message){
	   if($this->saved)
		 echo '<div class="updated"><p><'.$this->saved_label.' class="candepalleiro_saved">'.$message.'</'.$this->saved_label.'></p></div>';
	}
	
	
	//get last id register
	 public function get_last_id_propietario() {	
			$sql='SELECT * FROM `'.TABLE_OWNER.'` ORDER BY id_propietario DESC LIMIT 1';
			$this->propietario=$this->get_register_tables($sql);
	}
	
	function insert(){
		global $wpdb;
		foreach($this->propietario as $key=>$value):
			switch($key):
				case 'id_propietario':
				break;
				case 'bajajapropietario':
					$data[$key]=$_POST[$key];
					$data_format[]='%d';
				break;
				case 'datebaja':
				break;
				case 'p_nome':
				case 'p_apelido':
				case 'nif':
					$data[$key]=trim($this->upper($_POST[$key]));
					$data_format[]='%s';
				break;
				default:
					$data[$key]=trim($_POST[$key]);
					$data_format[]='%s';
				break;
			endswitch;
		endforeach;
		$wpdb->insert($wpdb->prefix.'candepalleiro_propietario',$data,$data_format);
		//exit( var_dump( $wpdb->last_query ) );
		return $wpdb->insert_id;		
	}
	
	function update($cod){
		global $wpdb;
		$id['id_propietario']=$cod;
		$id_format[]='%d';
		//print_r($id);
		//print_r($id_format);
		/*
		$wpdb->update( 
			'table', 
			array( 
				'column1' => 'value1',	// string
				'column2' => 'value2'	// integer (number) 
			), 
			array( 'ID' => 1 ), 
			array( 
				'%s',	// value1
				'%d'	// value2
			), 
			array( '%d' ) 
		);
		*/
		foreach($this->propietario as $key=>$value):
			switch($key):
				case 'id_propietario':
				case 'trash':
				break;
				case 'bajapropietario':
					$data[$key]=$_POST[$key];
					$data_format[]='%d';
					$data['datebaja']=date('Y-m-d H:i:s');
					$data_format[]='%s';
				break;
				case 'datebaja':
					//$date=$this->invert_date($_POST[$key],'/','-');
					//print_r($date);
					//$data[$key]=$date['str'];
					//$data_format[]='%s';
				break;
				case 'p_nome':
				case 'p_apelido':
				case 'nif':
					$data[$key]=trim($this->upper($_POST[$key]));
					$data_format[]='%s';
				break;
				default:
					$data[$key]=trim($_POST[$key]);
					$data_format[]='%s';
				break;
			endswitch;
		    //echo "$key:".$_POST[$key].' -';
			//if($key!='propietario_registered') //este campo no se modifica por lo tanto no se sobre escribe
		   		$this->propietario[$key]=$_POST[$key];
		endforeach;
		//print_r($data);
		//print_r($data_format);
		$wpdb->update(
			$wpdb->prefix.'candepalleiro_propietario',
			$data,
			$id,
			$data_format,
			$id_format
		);
		$this->saved=true;
		//para consultar la última consulta y ver posible errores
		//exit( var_dump( $wpdb->last_query ) );
	}
	
	
	/**
     * param $where=array('id_mother'=>1,id_father=2);
     */
	
	function getDogs($where){
		global $wpdb;
		$data=array(
					'table'=>$wpdb->prefix.'candepalleiro_can',
					'where'=>$where,
					'order_by'=>array('nome'),
					);
		return $this->get_all_registers($data);			
	}
	
	/** 
     * Asignación de los perros para los propietarios
     * 
	 * @param $id_propietario id_del_propietario
	 * @param $dog array con los ids de los perros
	 */
	function asign_dogs($dog,$id_propietario){
		global $wpdb;
		/*
		$wpdb->query(
				"
				UPDATE $wpdb->posts 
				SET post_parent = 7
				WHERE ID = 15 
					AND post_status = 'static'
				"
		);
		*/
		foreach($dog as $value):
		    $where=implode(',',$dog);
		endforeach;	
		$wpdb->query(
				"
				UPDATE ".$wpdb->prefix."candepalleiro_can
				SET id_propietario = NULL
				WHERE id_propietario = $id_propietario;
				"
		);	
		$wpdb->query(
				"
				UPDATE ".$wpdb->prefix."candepalleiro_can
				SET id_propietario = $id_propietario
				WHERE id_can IN ($where);
				"
		);
		$this->saved=true;
		//exit( var_dump( $wpdb->last_query ) );
	}
	
	function get_rows_trash($trash=1){
		global $wpdb;
		return $wpdb->get_var( "SELECT COUNT(*) FROM ".TABLE_OWNER." WHERE trash=$trash" );
		//exit( var_dump( $wpdb->last_query ) );		
	}	
	
	
	function upper($content){

		mb_internal_encoding('UTF-8');
		if(!mb_check_encoding($content, 'UTF-8')
			OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {
		
			$content = mb_convert_encoding($content, 'UTF-8'); 
		}

		// LE COURRIER DE SÁINT-HYÁCINTHE
		return mb_convert_case($content, MB_CASE_UPPER, "UTF-8"); 
	}
	
	/** 
	 * invertir orden de fecha entrada ejemplo 07/08/2014   --->   07    08   2014      --->  str=>
	 * 
	 * @param string $data fecha pasada
	 * @param string $separator before caracter que separa los dígitos de fecha ej. aaaa/mm/dd aaaa-mm-/dd
	 * @param string $new_separator after caracter que separa los dígitos de fecha ej. aaaa/mm/dd aaaa-mm-/dd
	 * @param Array $order orden de salida del string que formará el campo date
	 * @return Array ( [array] => Array ( [0] => 06 [1] => 04 [2] => 2014 ) [str] => 2014-04-06 )
	 */
	function invert_date($data,$separator='-',$new_separator='/',$order=array(2,1,0)){
		$uno=$order[0];$dos=$order[1];$tres=$order[2];
		$data=explode($separator,$data);
		//return Array ( [array] => Array ( [0] => 06 [1] => 04 [2] => 2014 ) [str] => 2014-04-06 )
		return array('array'=>$data,'str'=>$data[$uno].$new_separator.$data[$dos].$new_separator.$data[$tres]);
	}
	
}