<?php
//recollemos as chaves 
//$can e o obxeto cos datos de can que cargamos en owner.php na función pdfowner()
//inicimaos a clase
class PropietarioFpdf extends FPDF {
	private $data=array("load_view"=>false);
	private $RGB=array(255,255,255);
	private $border=0;
	private $align='L';
	private $fill=false;
	private $family='Times';
	private $style='';
	private $size='7.7';
	
	//data treefamily
	var $nome="";
	var $rlx="";
	var $microchip="";
	var $nacemento="";
	var $sexo="";
	var $capa="";
	var $criador="";
	var $lugarsolicitud="";
	var $p_nome="";
	var $p_apelido="";
	var $lugar="";
	var $numero_vivienda="";
	var $piso="";
	var $cp="";
	var $concello="";
	var $provincia="";
	var $pais="";
	var $telefono="";
	var $ano="";
	var	$mes="";
	var	$dia="";
	var $arbol=array();
	
	public function __construct($row) {
		parent::__construct();	
		$this->print_info($row);
		
    }
	public function print_info($row){
		$this->SetMargins(50, 34);
		$this->SetAutoPageBreak(true,0);		
		$this->AddPage();	
		//imagenes linea
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/line.gif',300,255); //imagenes línea
		/** pdf_dog
		 * 
		 * 
		 * @param string CABECERA DE LA PÁGINA FICHA DEL PERRO
		 * 
		 * 
		 */
		
		$this->border='0';
		$datos=array('',utf8_decode(__('Información del propietario','can-de-palleiro-fpdf')));
		$width=array(260,250);
		$align=array('','R');
		$font=array(array('family'=>'','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'18'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		/** pdf_dog
		 * 
		 * 
		 * @param string PINTAMOS SUBRAYADO DE ENCABEZADO
		 * 
		 * 
		 */
		$this->border='0';
		$datos=array('','','');
		$width=array(220,90,200);
		$align=array('','','R');
		$font=array(array('family'=>'','style'=>'','size'=>''),array('family'=>'','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'18'));
		$SetFillColor=array(array(255,255,255),array(255,255,255),array(0,0,0));
		$this->setRow($datos,$width,5,$align,$font,$SetFillColor);	
		/** pdf_dog
		 * 
		 * 
		 * @param string PINTAMOS UNA LÍNEA
		 * 
		 * 
		 */
		$this->border='1';
		$datos=array('','','');
		$width=array(220,120,170);
		$align=array('','','R');
		$font=array(array('family'=>'','style'=>'','size'=>''),array('family'=>'','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'1'));
		$SetFillColor=array(array(0,0,0),array(0,0,0),array(0,0,0));
		$this->setRow($datos,$width,1,$align,$font,$SetFillColor);
		$this->Ln(20);
		
		/** PIE
		 * 
		 * 
		 * @param string NOMBRE Y APELLIDOS
		 * 
		 * 
		 */
		$this->border='0';
		$datos=array(utf8_decode(__('Nombre','can-de-palleiro-fpdf')),'',utf8_decode(__('Apellidos','can-de-palleiro-fpdf')),'',utf8_decode(__('NIF','can-de-palleiro-fpdf')));
		$width=array(194,6,220,6,90);
		$align=array('','','','','');
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
	
		$border=array(1,0,1,0,1);  
		$datos=array(utf8_decode($row['p_nome']),'',utf8_decode($row['p_apelido']),'',utf8_decode($row['nif']));
		$width=array(194,6,220,6,90);
		$align=array('','','','','');
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(12);	
		
		/** PIE
		 * 
		 * 
		 * @param string CORREO ELECTRONICO, TELEFONO 1, TELÉFONO 2
		 * 
		 * 
		 */
		$this->border='0';
		$datos=array(utf8_decode(__('Correo electrónico','can-de-palleiro-fpdf')),'',utf8_decode(__('Teléfono','can-de-palleiro-fpdf')),'',utf8_decode(__('Teléfono 2','can-de-palleiro-fpdf')));
		$width=array(180,6,164,6,154);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$border=array(1,0,1,0,1);  
		$datos=array(utf8_decode($row['mail']),'',utf8_decode($row['telefono']),'',utf8_decode($row['telefono2']));
		$width=array(180,6,164,6,154);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(12);	
		
		/** PIE
		 * 
		 * 
		 * @param string Pais,NULL,código postal
		 * 
		 * 
		 */
		 
		$this->border='0';
		$datos=array(utf8_decode(__('País','can-de-palleiro-fpdf')),'','','',utf8_decode(__('Código Postal','can-de-palleiro-fpdf')));
		$width=array(120,6,194,6,184);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$border=array(1,0,0,0,1);  
		$datos=array(utf8_decode($row['pais']),'','','',utf8_decode($row['cp']));
		$width=array(120,6,194,6,184);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(12);		
		
		
		/** PIE
		 * 
		 * 
		 * @param string DIRECCIÓN
		 * 
		 * 
		 */
		 
		$this->border='0';
		$datos=array(utf8_decode(__('Lugar','can-de-palleiro-fpdf')));
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$border=array(1,0,1);  //quinta línea
		$datos=array(utf8_decode($row['lugar']));
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(34);	
		
		
		/** PIE
		 * 
		 * 
		 * @param string NUMERO Y CONCELLO
		 * 
		 * 
		 */
		$this->border='0';
		$datos=array(utf8_decode(__('Número de vivienda','can-de-palleiro-fpdf')),'',utf8_decode(__('Ayuntamiento','can-de-palleiro-fpdf')));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$border=array(1,0,1); 
		//$datos=array(($this->data_nacemento=='0000-00-00')?'':date('d m Y', strtotime($row['data_nacemento)),'',utf8_decode($row['profesion));
		$datos=array(utf8_decode($row['numero_vivienda']),'',utf8_decode($row['concello']));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(12);
		
		/** PIE
		 * 
		 * 
		 * @param string PISO Y PROVINCIA
		 * 
		 * 
		 */	
		
		
		$this->border='0';
		$datos=array(utf8_decode(__('Piso','can-de-palleiro-fpdf')),'',utf8_decode(__('Provincia','can-de-palleiro-fpdf')));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
				
		
		$border=array(1,0,1,1);  
		$datos=array(utf8_decode($row['piso']),'',utf8_decode($row['provincia']));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(12);
		
		
		/** PIE
		 * 
		 * 
		 * @param string Parroquia
		 * 
		 * 
		 */
		 
		$this->border='0';
		$datos=array('','',utf8_decode(__('Parroquia','can-de-palleiro-fpdf')));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'),array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$border=array(1,0,1); 
		//$datos=array(($this->data_nacemento=='0000-00-00')?'':date('d m Y', strtotime($this->data_nacemento)),'',utf8_decode($this->profesion));
		$datos=array('','',utf8_decode($row['parroquia']));
		$width=array(230,36,164);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'),array('family'=>'Arial','style'=>'','size'=>''),array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,18,$align,$font,NULL,$border);
		$this->Ln(66);		
		
		/** PIE
		 * 
		 * 
		 * @param string ANOTACIÓNS
		 * 
		 * 
		 */
		 
		$this->border='0';
		$datos=array(utf8_decode(__('Notas','can-de-palleiro-fpdf')));
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$this->border=1;  
		$datos=array(utf8_decode($row['notas']));
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setMulticell($datos,$width,18,$align,$font);
		$this->Ln(12);	
		
		
		/** PIE
		 * 
		 * 
		 * @param string OBSERVACIONS
		 * 
		 * 
		 */
		
		$this->border='0';
		$datos=array(utf8_decode(__('Observaciones','can-de-palleiro-fpdf')));
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'12'));
		$this->setRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		$this->border=1;  
		$datos=array('');
		$width=array(510);
		$align=NULL;
		$font=array(array('family'=>'Arial','style'=>'','size'=>'8'));
		$this->setRow($datos,$width,120,$align,$font);
		$this->Ln(12);						
		
			
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
	function setRow($datos,$width,$height=7,$align=NULL,$font=NULL,$SetFillColor=NULL,$border=NULL){
		for($i=0;$i<count($datos);$i++){
			if($font!=NULL)
				$this->SetFont($font[$i]['family'],$font[$i]['style'],$font[$i]['size']);
			else
				$this->SetFont($this->family,$this->style,$this->size);
				
			if($SetFillColor!=NULL)
				$this->SetFillColor($SetFillColor[$i][0],$SetFillColor[$i][1],$SetFillColor[$i][2]);
				
			if($i==(count($datos)-1)){
				$this->Cell($width[$i],$height,$datos[$i],($border==NULL)?$this->border:$border[$i],1,($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
			else{
				$this->Cell($width[$i],$height,$datos[$i],($border==NULL)?$this->border:$border[$i],0,($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
		}
	}
	
	function setMulticell($datos,$width,$height=7,$align=NULL,$font=NULL,$SetFillColor=NULL,$border=NULL){
		    //print_r($font);
			//print_r($font[0]['family']);
			for($i=0;$i<count($datos);$i++){
			if($font!=NULL)
				$this->SetFont($font[$i]['family'],$font[$i]['style'],$font[$i]['size']);
			else
				$this->SetFont($this->family,$this->style,$this->size);
				
			if($SetFillColor!=NULL)
				$this->SetFillColor($SetFillColor[$i][0],$SetFillColor[$i][1],$SetFillColor[$i][2]);
				
			if($i==(count($datos)-1)){
				$this->MultiCell($width[$i],$height,$datos[$i],($border==NULL)?$this->border:$border[$i],($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
			else{
				$this->MultiCell($width[$i],$height,$datos[$i],($border==NULL)?$this->border:$border[$i],($align==NULL)?$this->align:$align[$i],($SetFillColor==NULL)?$this->fill:true);
			}
		}
	}
}
global $owner;
new PropietarioFpdf($owner->get_propietario());