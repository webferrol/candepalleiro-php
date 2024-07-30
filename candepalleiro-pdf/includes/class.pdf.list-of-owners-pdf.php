<?php
//pendiente de revisión
//recollemos as chaves 
//$can e o obxeto cos datos de can que cargamos en owner.php na función pdfowner()
//inicimaos a clase
class ListarPropietariosFpdf extends FPDF {
	private $data=array("load_view"=>false);
	private $RGB=array(255,255,255);
	private $border=0;
	private $align='L';
	private $fill=false;
	private $family='Times';
	private $style='';
	private $size='7.7';
	public function __construct() {
		parent::__construct();	
		$this->print_info();
		
    }
	
	function print_info(){
		global $wpdb;
		$where='';
		$bajapropietario=false;
		$no_bajapropietario=true;
		$criterio=array();
		$value=array();
		$confirmado=false;
		$no_confirmado=false;
		$con_defectos=false;
		$baja=false;
		$no_baja=false;
		$sin_defectos=false;
		$orderby='p_nome';
		$order='asc';
		$url=substr(rawurldecode($_REQUEST['_wp_http_referer']),strlen($_SERVER['PHP_SELF'].'?'));
		//print_r($url.'<br>');
		$url_array=explode("&",$url);
		//print_r($url_array);
		for($i=0;$i<count($url_array);$i++):
			$parameter=array();
			$parameter=explode("=",$url_array[$i]);
			//print_r($parameter);
			switch($parameter[0]):
				case 'c[]':
				case 'c[0]':
				case 'c[1]':
				case 'c[2]':
					if(!empty($parameter[1])):
						$criterio[]=$parameter[1];
					endif;
				break;
				case 'bajapropietario':
					$bajapropietario=true;
				break;
				case 'no_bajapropietario':
					$nobajapropietario=true;
				break;
				case 'orderby':
					$orderby=$parameter[1];
				break;
				case 'order':
					$order=$parameter[1];
				break;
				case 'v[]':
				case 'v[0]':
				case 'v[1]':
				case 'v[2]':
					if(!empty($parameter[1])):
						$value[]=urldecode($parameter[1]);
					endif;
				break;
			endswitch;
			//echo "<br>";
		endfor;
		//print_r($url_array);
		//$url=wp_slash($_REQUEST['_wp_http_referer']);
		//print_r($url);
		//echo $_SERVER['PHP_SELF'];
		$where .=' AND trash<1';
		if(true):
			for($i=0;$i<count($criterio);$i++):
				if(!empty($criterio[$i])):
					$field=$criterio[$i];
					$searchAlphabet = $value[$i]; 	
					$where .= ' AND ' . $field . ' LIKE \'%'.$searchAlphabet.'%\' ';
				endif;
			endfor;			
		endif;
		
		if($bajapropietario):
			$field='bajapropietario';	
			$where .= ' AND ' . $field . ' = 1 ';
		endif;
		
		if($no_bajapropietario):
			$field='bajapropietario';	
			$where .= ' AND ' . $field . ' !=1 ';
		endif;
		
		
		$querydata = $wpdb->get_results(
			'
			SELECT * FROM `'.$wpdb->prefix.'candepalleiro_propietario`
			LEFT JOIN `'.$wpdb->prefix.'candepalleiro_can` ON ('.$wpdb->prefix.'candepalleiro_can.id_propietario = '.$wpdb->prefix.'candepalleiro_propietario.id_propietario)  WHERE 1 
			'.$where." ORDER BY $orderby $order"
		);	
		
		//exit( var_dump( $wpdb->last_query ) );
		
		
		//imprimimos
		$this->border='B';
		$this->SetMargins(25.2, 26.9);
		$this->SetAutoPageBreak(true,0);
		$this->AddPage('L');
		$config= array(utf8_decode(__('Nombre','can-de-palleiro-fpdf')),utf8_decode(__('Apellidos','can-de-palleiro-fpdf')),utf8_decode(__('NIF','can-de-palleiro-fpdf')),utf8_decode(__('Telf','can-de-palleiro-fpdf')),utf8_decode(__('Provincia','can-de-palleiro-fpdf')),utf8_decode(__('Ayuntamiento','can-de-palleiro-fpdf')),utf8_decode(__('CP','can-de-palleiro-fpdf')));
		$this->style="B";
		$this->size="12";
		$this->drawRow($config,array(152,152,100,97,97,152,20),14);
		
		$this->style="";
		$this->size="7.7";
		//print_r($querydata);
		if($wpdb->num_rows>0):
		foreach($querydata as $can){
			$config= array(utf8_decode($can->p_nome),utf8_decode($can->p_apelido),utf8_decode($can->nif),utf8_decode($can->telefono),utf8_decode($can->provincia),utf8_decode($can->concello),utf8_decode($can->cp));
			$this->drawRow($config,array(152,152,100,97,97,152,20),14);
		}
		endif;
		//Closure line
   		$this->Cell(array_sum($config),0,'','T');
		$this->Output();
	}
	/** 
     * Pintamos una fila de datos
     * 
     * @param array $datos datos contenidos en un array, son las columnas de datos que forman la fila que se pinta 
	 * @param array $width array con las medidas en pt de cada columna de datos
	 * @param int $height altura de cada fila
     * @param array $aling, si esta vacío coge el valor por defecto de la una variable privada que es 'L' es decir izquierda
	 * @param array $font, array que contiene la familia (Courier,Helvetica or Arial,Times,Symbol,ZapfDingbats), estilo (B -> bold,I -> italic, U -> underline), y el tamño de la fuente
	 * @param array $SetFillColor colores contenidos en cada celda de fila de datos por defecto en NULL
     */
	function drawRow($datos,$width,$height=7,$align=NULL,$font=NULL,$SetFillColor=NULL){
		for($i=0;$i<count($datos);$i++){
			if($font!=NULL)
				$this->SetFont($font[$i]['family'],$font[$i]['style'],$font[$i]['size']);
			else
				$this->SetFont($this->family,$this->style,$this->size);
				
			if($SetFillColor!=NULL)
				$this->SetFillColor($SetFillColor[$i][0],$SetFillColor[$i][1],$SetFillColor[$i][2]);
				
			if($i==(count($datos)-1)){
				$this->Cell($width[$i],$height,$datos[$i],$this->border,1,($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
			else{
				$this->Cell($width[$i],$height,$datos[$i],$this->border,0,($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
		}
	}
	
	/** 
	 * invertir orden de fecha
	 * 
	 * @param string $data fecha pasada
	 * @param string $key caracter que separa los dígitos de fecha ej. aaaa/mm/dd aaaa-mm-/dd
	 * @return string fecha invertida
	 */
	function invert_data($data,$key='-'){
		$data=explode($key,$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}

new ListarPropietariosFpdf();