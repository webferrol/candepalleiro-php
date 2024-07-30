<?php
//clase extendida de pdf
class PedigreeFpdf extends FPDF {
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
	
	public function __construct($option) {
		parent::__construct();
		$this->pedigree($option);		
    }
	
	
	function pedigree($id_can){
		if(empty($id_can)):
			$this->vacio();
		else:
			$this->hacerarbol($id_can);
		endif;
	}
	function vacio(){
		$this->ImpresionCertificado();
		$this->Output();
	}
	function hacerarbol($id_can){
		global $wpdb;
		$candata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."candepalleiro_can 
									LEFT JOIN ".$wpdb->prefix."candepalleiro_propietario ON (".$wpdb->prefix."candepalleiro_can.id_propietario = ".$wpdb->prefix."candepalleiro_propietario.id_propietario)  WHERE 1 
									AND id_can IN ($id_can)");
		//print_r($candata);
	foreach ($candata as $candatum ):
		//print_r($candata);
		
		$this->treeEmpty();
		//datos do can
		$this->nome=$candatum->nome;
		$this->rlx=$candatum->rlx;
		$this->microchip=$candatum->microchip;
		$this->nacemento=$candatum->nacemento;
		$this->sexo=$candatum->sexo;
		$this->capa=$candatum->capa;
		$this->criador=$candatum->criador;
		$this->lugarsolicitud=$candatum->lugarsolicitud;
		
		
		//datos do propietario
		$this->p_nome=$candatum->p_nome;
		$this->p_apelido=$candatum->p_apelido;
		$this->lugar=$candatum->lugar;
		$this->numero_vivienda=$candatum->numero_vivienda;
		$this->piso=$candatum->piso;
		$this->cp=$candatum->cp;
		$this->concello=$candatum->concello;
		$this->provincia=$candatum->provincia;
		$this->pais=$candatum->pais;
		$this->telefono=$candatum->telefono;
		
		//otros datos
		$this->ano=date( "y" );
		$this->mes=date("m");
		$this->dia=date("d");
		
		//tree
		$this->arbol=$this->getTree($candatum->id_father,$candatum->id_mother);
		
		$this->ImpresionCertificado();
				
		endforeach;
		$this->Output();
	}
	/** 
     * Vaciado da árbore xenealóxica
     * 
	 * @param int $id_can
	 * @return array $data
	 */
	 function treeEmpty(){
		 unset($this->arbolgenealogico);
		 $this->arbolgenealogico=array();
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
	
	
	/** 
     * obtención del árbol genealógico Chegamos ata 5 grados de consaguinidade     * 
	 * @param int $id_can
	 * @return array $data
	 */	
	 
	function getTree($id_father,$id_mother) {
		$parents=array($id_father,$id_mother,NULL);
		for($x=0;$x<2;$x++)://5 niveis
			$parents_1=$this->drawFather($parents[$x],array($x+1,$parents[2]),1);
			for($y=0;$y<2;$y++):
				$parents_2=$this->drawFather($parents_1[$y],array($y+1,$parents_1[2]),2);
				for($z=0;$z<2;$z++):
					$parents_3=$this->drawFather($parents_2[$z],array($z+1,$parents_2[2]),3);
					for($a=0;$a<2;$a++):
						$parents_4=$this->drawFather($parents_3[$a],array($a+1,$parents_3[2]),4);
						for($d=0;$d<2;$d++):
							$parents_5=$this->drawFather($parents_4[$d],array($d+1,$parents_4[2]),5);
						endfor;
					endfor;	
				endfor;	
			endfor;	
		endfor;		
		//print_r($this->arbolgenealogico);
		return $this->arbolgenealogico;		
	}
	
	function drawFather($id_parent,$array,$grado){
		global $wpdb;
		$parents=array(NULL,NULL);
		$parentdata='';
		$i=$array[0];
		$codigo=$array[1];
		//print_r("SELECT * FROM `{$wpdb->prefix}candepalleiro_can` WHERE id_can=".$id_parent."<br>");
		$id_parent=(!isset($id_parent) or is_null($id_parent))?-999:$id_parent;
		$parentdata = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}candepalleiro_can` WHERE id_can=".$id_parent,OBJECT);
		//print_r($parentdata);
		if($wpdb->num_rows>0):
		foreach ($parentdata as $parentdatum ):
			$this->arbolgenealogico["$codigo$i"]=array('grado'=>$grado,'id_can'=>$parentdatum->id_can,'nome'=>$parentdatum->nome,'rlx'=>$parentdatum->rlx);
			$parents[0]=$parentdatum->id_father;
			$parents[1]=$parentdatum->id_mother;
		endforeach;
		else:
			$this->arbolgenealogico["$codigo$i"]=array('grado'=>$grado,'id_can'=>'','nome'=>'','rlx'=>'');
		endif;
		
		
		$codigo=$codigo.$i;
		$parents[2]=$codigo;
		return $parents;
	}
	
	function ImpresionCertificado(){		
		$this->SetMargins(19, 20);
		$this->SetAutoPageBreak(true,0);
		$this->AddPage('L');
		//$this->data['arbol'] es un array asociativo
		
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/mediorural.jpg',10,15);

		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/xuntagalicia.png',300,190);
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/logo.png',720,10);
		//$this->Image('img/logo_candepalleiro.png',730,15);
		
		//primera línea
		$datos=array('',utf8_decode(__('XUNTA DE GALICIA','can-de-palleiro-fpdf')),utf8_decode(__('LIBRO XENEALÓXICO','can-de-palleiro-fpdf')));
		$width=array(15,240.5,240.5);
		$align=array('L','L','C');
		$font=array(array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'','size'=>10.7),array('family'=>'Times','style'=>'B','size'=>17.7));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		//segunda línea
		$datos=array('','',utf8_decode(__('CERTIFICADO DE INSCRIPCIÓN','can-de-palleiro-fpdf')));
		$width=array(15,240.5,240.5);
		$align=array('L','L','C');
		$font=array(array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'B','size'=>12.7));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		
		//tercera línea
		$datos=array('','',utf8_decode(__('CAN DE PALLEIRO','can-de-palleiro-fpdf')));
		$width=array(15,240.5,240.5);
		$align=array('L','L','C');
		$font=array(array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'B','size'=>12.7));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(25);
		
		//cuarta línea
		$datos=array(utf8_decode(__('NOME','can-de-palleiro-fpdf')),utf8_decode($this->nome),utf8_decode(__('Nº REXISTRO NO R.L.X','can-de-palleiro-fpdf')),$this->rlx,utf8_decode(__('MICROCHIP','can-de-palleiro-fpdf')),utf8_decode($this->microchip));
		$width=array(33.8,287.1,108.5,136.6,65.8,153.6);
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,7.7,NULL,$font);
		$this->Ln(14);
		
		//quinta línea
		$this->nacemento=(!empty($this->nacemento))?$this->invert_data($this->nacemento):$this->nacemento;
		if(empty($this->sexo)):
			$this->sexo='Definir sexo';
		else:
			$this->sexo=(strtolower($this->sexo)=='f')?'Femia':'Macho';
		endif;
		$datos=array(utf8_decode(__('DATA DE NACEMENTO','can-de-palleiro-fpdf')),$this->nacemento,utf8_decode(__('SEXO','can-de-palleiro-fpdf')),$this->sexo,utf8_decode(__('CAPA(COR)','can-de-palleiro-fpdf')),utf8_decode($this->capa),utf8_decode(__('CRIADOR','can-de-palleiro-fpdf')),utf8_decode($this->criador));
		$width=array(104.6,120.6,31.4,86.6,63.1,91.4,53.8,229.2);
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,7.7,NULL,$font);
		$this->Ln(14);
		
		//Fila 1
		$this->SetFillColor(178,220,236); 
		$nodo1111=(isset($this->arbol['1111']['nome']))?utf8_decode($this->arbol['1111']['nome']):'';
		$this->drawTree('','','','15 '.$nodo1111,array(0,0,0,1));
		
		//Fila 2
		$nodo111=(isset($this->arbol['111']['nome']))?utf8_decode($this->arbol['111']['nome']):'';
		$this->drawTree('','','7 '.$nodo111,'',array(0,0,1,0),13);
		
		
		//Fila 3
		$nodo1112=(isset($this->arbol['1112']['nome']))?utf8_decode($this->arbol['1112']['nome']):'';
		$this->drawTree('','','','16 '.$nodo1112,array(0,0,0,1));
		
		
		//Fila 4
		$nodo11=(isset($this->arbol['11']['nome']))?utf8_decode($this->arbol['11']['nome']):'';
		$this->drawTree('','3 '.$nodo11,'','',array(0,1,0,0),14);
		
		//Fila 5
		$nodo1121=(isset($this->arbol['1121']['nome']))?utf8_decode($this->arbol['1121']['nome']):'';
		$this->drawTree('','','','17 '.$nodo1121,array(0,0,0,1));
		
		//Fila 6
		$nodo112=(isset($this->arbol['112']['nome']))?utf8_decode($this->arbol['112']['nome']):'';		
		$this->drawTree('','',"8 $nodo112",'',array(0,0,1,0),13);
		
			
		//Fila 7
		$nodo1122=(isset($this->arbol['1122']['nome']))?utf8_decode($this->arbol['1122']['nome']):'';
		$this->drawTree('','','',"18 $nodo1122",array(0,0,0,1));
		
		//Fila 8
		$nodo1=(isset($this->arbol['1']['nome']))?utf8_decode($this->arbol['1']['nome']):'';	
		$this->drawTree("1 $nodo1",'','','',array(1,0,0,0),15);
		
		//Fila 9		
		$nodo1211=(isset($this->arbol['1211']['nome']))?utf8_decode($this->arbol['1211']['nome']):'';
		$this->drawTree('','','',"19 $nodo1211",array(0,0,0,1));
		
		//Fila 10
		$nodo121=(isset($this->arbol['121']['nome']))?utf8_decode($this->arbol['121']['nome']):'';		
		$this->drawTree('','',"9 $nodo121",'',array(0,0,1,0),13);
		
		//Fila 11
		$nodo1212=(isset($this->arbol['1212']['nome']))?utf8_decode($this->arbol['1212']['nome']):'';
		$this->drawTree('','','',"20 $nodo1212",array(0,0,0,1));
		
		//Fila 12
		$nodo12=(isset($this->arbol['12']['nome']))?utf8_decode($this->arbol['12']['nome']):'';		
		$this->drawTree('',"4 $nodo12",'','',array(0,1,0,0),14);
		
		//Fila 13
		$nodo1221=(isset($this->arbol['1221']['nome']))?utf8_decode($this->arbol['1221']['nome']):'';
		$this->drawTree('','','',"21 $nodo1221",array(0,0,0,1));
		
		//Fila 14
		$nodo122=(isset($this->arbol['122']['nome']))?utf8_decode($this->arbol['122']['nome']):'';			
		$this->drawTree('','',"10 $nodo122",'',array(0,0,1,0),13);		
		
		//Fila 15
		$nodo1222=(isset($this->arbol['1222']['nome']))?utf8_decode($this->arbol['1222']['nome']):'';	
		$this->drawTree('','','',"22 $nodo1222",array(0,0,0,1));
		
		//Fila 16
		$this->drawTree('','','','',array(0,0,0,0));
		
		//Fila 17		
		$nodo2111=(isset($this->arbol['2111']['nome']))?utf8_decode($this->arbol['2111']['nome']):'';		
		$this->drawTree('','','',"23 $nodo2111",array(0,0,0,1));
		
		//Fila 18
		$nodo211=(isset($this->arbol['211']['nome']))?utf8_decode($this->arbol['211']['nome']):'';		
		$this->drawTree('','',"11 $nodo211",'',array(0,0,1,0),13);
		
		//Fila 19
		$nodo2112=(isset($this->arbol['2112']['nome']))?utf8_decode($this->arbol['2112']['nome']):'';		
		$this->drawTree('','','',"24 $nodo2112",array(0,0,0,1));
		
		//Fila 20		
		$nodo21=(isset($this->arbol['21']['nome']))?utf8_decode($this->arbol['21']['nome']):'';
		$this->drawTree('',"5 $nodo21",'','',array(0,1,0,0),14);
		
		//Fila 21
		$nodo2121=(isset($this->arbol['2121']['nome']))?utf8_decode($this->arbol['2121']['nome']):'';		
		$this->drawTree('','','',"25 $nodo2121",array(0,0,0,1));
		
		//Fila 22
		$nodo212=(isset($this->arbol['212']['nome']))?utf8_decode($this->arbol['212']['nome']):'';		
		$this->drawTree('','',"12 $nodo212",'',array(0,0,1,0),13);
		
		//Fila 23
		$nodo2122=(isset($this->arbol['2122']['nome']))?utf8_decode($this->arbol['2122']['nome']):'';		
		$this->drawTree('','','',"26 $nodo2122",array(0,0,0,1));
		
		//Fila 24
		$nodo2=(isset($this->arbol['2']['nome']))?utf8_decode($this->arbol['2']['nome']):'';		
		$this->drawTree("2 $nodo2",'','','',array(1,0,0,0),15);
		
		//Fila 25
		$nodo2211=(isset($this->arbol['2211']['nome']))?utf8_decode($this->arbol['2211']['nome']):'';		
		$this->drawTree('','','',"27 $nodo2211",array(0,0,0,1));
		
		//Fila 26
		$nodo221=(isset($this->arbol['221']['nome']))?utf8_decode($this->arbol['221']['nome']):'';		
		$this->drawTree('','',"13 $nodo221",'',array(0,0,1,0),13);
		
		//Fila 27
		$nodo2212=(isset($this->arbol['2212']['nome']))?utf8_decode($this->arbol['2212']['nome']):'';		
		$this->drawTree('','','',"28 $nodo2212",array(0,0,0,1));
		
		//Fila 28
		$nodo22=(isset($this->arbol['22']['nome']))?utf8_decode($this->arbol['22']['nome']):'';		
		$this->drawTree('',"6 $nodo22",'','',array(0,1,0,0),14);
		
		//Fila 29
		$nodo2221=(isset($this->arbol['2221']['nome']))?utf8_decode($this->arbol['2221']['nome']):'';		
		$this->drawTree('','','',"29 $nodo2221",array(0,0,0,1));
		
		//Fila 30
		$nodo222=(isset($this->arbol['222']['nome']))?utf8_decode($this->arbol['222']['nome']):'';		
		$this->drawTree('','',"14 $nodo222",'',array(0,0,1,0),14);
		
		//Fila 31
		$nodo2222=(isset($this->arbol['2222']['nome']))?utf8_decode($this->arbol['2222']['nome']):'';
		$this->drawTree('','','',"30 $nodo2222",array(0,0,0,1));
		
		
		
			
			
		/**************
		*	SEGUNDA PAGINA
		***************/
		
		$this->SetMargins(19, 20);
		$this->SetAutoPageBreak(true,0);
		$this->AddPage('L');
		//$this->data['arbol'] es un array asociativo
		$arbol=$this->arbol;
		
		
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/mediorural.jpg',10,15);
		
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/xuntagalicia.png',300,190);
		$this->Image(str_replace('includes','img',dirname(__FILE__)).'/logo.png',720,10);
		
		//primera línea
		$datos=array('',utf8_decode(__('XUNTA DE GALICIA','can-de-palleiro-fpdf')));
		$width=array(15,240.5);
		$font=array(array('family'=>'Times','style'=>'','size'=>''),array('family'=>'Times','style'=>'','size'=>10.7));
		$this->drawRow($datos,$width,10,NULL,$font);
		$this->Ln(35);
		
		
		
		//segunda línea
		$datos=array(utf8_decode(__('NOME DO CAN','can-de-palleiro-fpdf')),utf8_decode($this->nome),utf8_decode(__('Nº REXISTRO NO R.L.X','can-de-palleiro-fpdf')),$this->rlx);
		$width=array(70.8,290.6,108.7,291.8);

		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//tercera línea		
		$this->SetFont('Times','B',12);
		$this->Cell(70.8,11,utf8_decode(__('DATOS DO PROPIETARIO','can-de-palleiro-fpdf')),0,'L');
		$this->Ln(17);
		
		//cuarta línea
		$datos=array('',utf8_decode(__('NOME','can-de-palleiro-fpdf')),utf8_decode($this->p_nome),utf8_decode(__('APELIDOS','can-de-palleiro-fpdf')),utf8_decode($this->p_apelido));
		$width=array(35.5,35.4,192,52.3,446.9);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//quinta línea
		$datos=array('',utf8_decode(__('RÚA/LUGAR','can-de-palleiro-fpdf')),utf8_decode($this->lugar),utf8_decode(__('Nº','can-de-palleiro-fpdf')),utf8_decode($this->numero_vivienda),utf8_decode(__('PISO','can-de-palleiro-fpdf')),utf8_decode($this->piso),utf8_decode(__('CP','can-de-palleiro-fpdf')),utf8_decode($this->cp),utf8_decode(__('CONCELLO','can-de-palleiro-fpdf')),utf8_decode($this->concello));
		$width=array(35.4,66.1,209.5,17.8,40.8,30.2,59.5,18.2,60.5,65,160.3);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//sexta línea
		$datos=array('',utf8_decode(__('PROVINCIA','can-de-palleiro-fpdf')),utf8_decode($this->provincia),utf8_decode(__('PAÍS','can-de-palleiro-fpdf')),utf8_decode($this->pais),utf8_decode(__('Nº TELÉFONO','can-de-palleiro-fpdf')),utf8_decode($this->telefono));
		$width=array(35.4,58,200.4,29.5,160.8,73.7,204.7);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//séptima línea		
		$this->lugarsolicitud=($this->lugarsolicitud=='')?'':$this->lugarsolicitud;
		$datos=array('',utf8_decode(__('EN','can-de-palleiro-fpdf')),utf8_decode($this->lugarsolicitud),utf8_decode(__('A','can-de-palleiro-fpdf')),utf8_decode($this->dia),utf8_decode(__('DE','can-de-palleiro-fpdf')),utf8_decode($this->mes),utf8_decode(__('DE 20','can-de-palleiro-fpdf')),utf8_decode($this->ano));
		$width=array(187,15.8,154.8,13.7,29.3,20.6,135.1,23.5,178.2);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(1);
		
		//línea añadida		
		$datos=array(utf8_decode(__('O PRESIDENTE DA COMISIÓN REITORA DO LIBRO XENEALÓXICO','can-de-palleiro-fpdf')));
		$width=array(800.5);
		$align='C';
		$font=array(array('family'=>'Times','style'=>'','size'=>'7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(5);
		//línea añadida		
		$datos=array(utf8_decode(__('P.P. (Por poder)','can-de-palleiro-fpdf')));
		$width=array(631);
		$align='C';
		$font=array(array('family'=>'Times','style'=>'','size'=>'7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(65);
		
		
		//octava línea
		$this->SetFont('Times','B',12);
		$this->Cell(70.8,11,utf8_decode(__('CAMBIOS DE PROPIETARIO','can-de-palleiro-fpdf')),0,'L');
		$this->Ln(15);
		
		//novena línea
		$datos=array('',utf8_decode(__('CON DATA','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('DE','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('DE 20','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('D/DONA','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('CEDE O ANIMAL IDENTIFICADO NESTE DOCUMENTO A','can-de-palleiro-fpdf')));
		$width=array(35.4,54.7,31.7,17.3,87.8,27.4,20.2,41.8,182.9,260);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//décima línea
		$datos=array('',utf8_decode(__('D/DONA','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('RÚA/LUGAR','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('Nº','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('PISO','can-de-palleiro-fpdf')));
		$width=array(35.4,43.2,296.6,61.9,184.3,14.4,49,28.8,57.6);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(14);
		
		//undécima línea
		$datos=array('',utf8_decode(__('PARROQUIA','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('CP','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('CONCELLO','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('PROVINCIA','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('PAÍS','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('Nº TELÉFONO','can-de-palleiro-fpdf')));
		$width=array(35.4,63.4,100.8,20.2,46.1,56.2,76.8,59.9,73.4,27.4,79.2,70.6,47.9);
		$align=NULL;
		$font=array(array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'));
		$this->drawRow($datos,$width,10,$align,$font);
		$this->Ln(16);
		
		//duodécima línea
		$datos=array('',utf8_decode(__('SINATURA ANTIGO PROPIETARIO','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('SINATURA NOVO PROPIETARIO','can-de-palleiro-fpdf')));
		$width=array(121.1,148.3,321.1,138.2);
		$align=NULL;
		$font=NULL;
		$this->style='B';
		$this->size='7.7';
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(15);
		
		//línea 13
		$datos=array('',utf8_decode(''),utf8_decode(''),utf8_decode(__('NIF','can-de-palleiro-fpdf')));
		$width=array(121.1,148.3,321.1,138.2);
		$align=NULL;
		$font=NULL;
		$this->style='B';
		$this->size='7.7';
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(60);
		
		//línea 14
		$datos=array(utf8_decode(__('NOTA: unha vez cuberto, debe remitirse OBRIGATORIAMENTE ó Clube da Raza para que este  cambio de propietario cause os efectos oportunos e se poda xestionar o novo Xustificante de Inscripción o nome do novo propietario.','can-de-palleiro-fpdf')));
		$width=array(600,148.3,321.1,138.2);
		$align=NULL;
		$font=NULL;
		$this->style='';
		$this->size='7';
		$this->drawRow($datos,$width,11,$align,$font);
		$this->Ln(5);		
		
		//línea 15
		$datos=array(utf8_decode(__('ENDEREZO E TELÉFONOS DE INTERESE','can-de-palleiro-fpdf')));
		$width=array(800.5);
		$align='C';
		$font=array(array('family'=>'Times','style'=>'B','size'=>'14.7'));
		$SetFillColor=array(array(178,220,236));
		$this->drawRow($datos,$width,20,$align,$font,$SetFillColor);
		$this->Ln(30);
		
		//línea 16
		$datos=array('',utf8_decode(__('Dirección Xeral de Gandaría, Agricultura e Industrias Agroalimentarias','can-de-palleiro-fpdf')),utf8_decode(__('CLUB DE RAZA CAN DE PALLEIRO','can-de-palleiro-fpdf')),utf8_decode(__('CENTRO DE RECURSOS ZOOXENÉTICOS','can-de-palleiro-fpdf')));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=NULL;
		$this->style='B';
		$this->size='8.7';
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);
		
		//línea 17
		$datos=array('',utf8_decode(__('Consellería do Medio Rural','can-de-palleiro-fpdf')),utf8_decode(__('617 786 008 - 629 864 754','can-de-palleiro-fpdf')),utf8_decode(__('DA XUNTA DE GALICIA','can-de-palleiro-fpdf')));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=NULL;
		$this->style='B';
		$this->size='8.7';
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);
		
		//línea 18
		$datos=array('',utf8_decode(__('Edificio administrativo núm. 4 San Caetano, 2ª planta','can-de-palleiro-fpdf')),utf8_decode(__('www.clubcandepalleiro.com','can-de-palleiro-fpdf')),utf8_decode(__('Pazo de Fontefiz','can-de-palleiro-fpdf')));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=array(array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);
		
		//línea 19
		$datos=array('',utf8_decode(__('15781 Santiago de Compostela','can-de-palleiro-fpdf')),utf8_decode(__('candepalleiro@clubcandepalleiro.com','can-de-palleiro-fpdf')),utf8_decode(__('32152 - Coles -Ourense','can-de-palleiro-fpdf')));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=array(array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'));
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);
		
		//línea 20
		$datos=array('',utf8_decode(__('TLFNO: 981 546 180','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(__('988 78 86 17','can-de-palleiro-fpdf')));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=array(array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'),array('family'=>'Times','style'=>'B','size'=>'8.7'));
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);
		
		//línea 21
		$datos=array('',utf8_decode(__('','can-de-palleiro-fpdf')),utf8_decode(''),utf8_decode(''));
		$width=array(35.4,240.5,257.8,244.8);
		$align=array('L','L','C','R');
		$font=NULL;
		$this->style='B';
		$this->size='8.7';
		$this->drawRow($datos,$width,9.4,$align,$font);
		$this->Ln(1);

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
     * Pintamos el árbol genealógico de lo perros
     * 
     * @param string $c1 datos de los  perros columna uno
	 * @param string $c2 datos de los perro2 columna dos
	 * @param string $c3 datos de los perro2 columna tres
     * @param string $c4 datos de los perro2 columna cuatro
	 * @param array $bg, colores de fondo de las celdas
	 * @param int $height, altura de la celda
     */
	function drawTree($c1,$c2,$c3,$c4,$bg,$height=10.1){
			$this->Cell(210.3,$height,$c1,0,0,'L',$bg[0]);
			$this->Cell(1.5,$height,'',0,0,'L');
			$this->Cell(228.4,$height,$c2,0,0,'L',$bg[1]);
			$this->Cell(1.5,$height,'',0,0,'L');
			$this->Cell(192.2,$height,$c3,0,0,'L',$bg[2]);
			$this->Cell(1.5,$height,'',0,0,'L');
			$this->Cell(186.1,$height,$c4,0,0,'L',$bg[3]);
			$this->Cell(1.5,$height,'',0,0,'L');
			$this->Ln(7);
			$this->Cell(779,5,'',0,0,'L',0);
			$this->Ln(7);
	}
	
}
//recollemos as chaves que nos chegan para o arbore xenealóxico
if(isset($_REQUEST['bulk-perros']) and !empty($_REQUEST['bulk-perros'])):
	global $wpdb;
	if(is_array($_REQUEST['bulk-perros'])):
		$id_can=implode(',',$_REQUEST['bulk-perros']);
	else:
		$id_can=$_REQUEST['bulk-perros'];
	endif;
endif;
//inicimaos a clase
new PedigreeFpdf($id_can);