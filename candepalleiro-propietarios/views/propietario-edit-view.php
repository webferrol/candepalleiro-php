<div class="wrap">
    <?php
    printf('<h2><span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x"></i>
</span> %s %s</h2>',esc_html__('Update'),esc_html__('Propietario'));
    ?>  
    
    <!-- form --> 
    <form name="my_form" method="post">
    <input type="hidden" name="action2" value="bulk-edit">
    <?php wp_nonce_field( 'edit_propietario'.$_REQUEST['codex'], 'edit_propietario_nonce_field' ); 
    
    
    
    /* Used to save closed meta boxes and their order */
    //wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
    //wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
    

    <?php if(isset($_POST['p_nome'])) echo $validate->get_message('p_nome');?>
    <?php if(isset($_POST['p_apelido'])) echo $validate->get_message('p_apelido');?>
    <?php if(isset($_POST['nif'])) echo $validate->get_message('nif');?>
    <?php if(isset($_POST['mail'])) echo $validate->get_message('mail');?>
    <!-- Rest of admin page here -->
    <!-- poststuff -->
    <div id="poststuff">
        <!-- #post-body .metabox-holder goes here -->
        <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
            <!-- meta box containers here -->
            <div id="post-body-content">
                <!-- #post-body-content -->
                
                
                <!-- Nombre Pila -->
                <div id="titlediv" class="field-div">
                    <div id="titlewrap">
                        <label class="screen-reader-text" id="title-prompt-text"  for="title"><i class="fa fa-asterisk"></i>&nbsp;<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Nombre de Pila','candepalleiro-management'));?></label>
                        <input required="required" type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Nombre de Pila','candepalleiro-management'));?>" name="p_nome" size="30" value="<?php echo esc_attr($object->get_field('p_nome'));?>" id="title" autocomplete="off">
                        <legend class="fa fa-info-circle required">&nbsp;<?php _e('Requerido','candepalleiro-management');?></legend>
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="field-div">
                        <label id="" for="microchip"><?php _e('Apellidos','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <input required="required" class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Apellidos','candepalleiro-management'));?>" name="p_apelido" size="30" value="<?php echo esc_attr($object->get_field('p_apelido'));?>" id="p_apelido" autocomplete="off">
                        <legend class="fa fa-info-circle required">&nbsp;<?php _e('Requerido','candepalleiro-management');?></legend>
                </div>


                <!-- Tipo de documento -->
                <div class="field-div">
                        <label id="" for="tipo_documento"><?php _e("Tipo de Documento",'candepalleiro-management');?>&nbsp;<i class="fa fa-file-o"></i></label>
                        <select class="select" name="tipo_documento" id="tipo_documento">
                        <?php
                        $tipo_documento=array("nif"=>__('NIF','candepalleiro-management'),"cif"=>__('CIF','candepalleiro-management'),"otros"=>__('Otros','candepalleiro-management'));
                        $spam=$object->get_field('tipo_documento');
                        foreach($tipo_documento as $key=>$value):
                        $selected=($spam==$key)?'selected="selected"':'';
                        echo "<option $selected value=\"$key\">$value</option>";
                        endforeach;
                        ?>          
                        </select>  
                </div> 


                <!-- Documento -->
                <div class="field-div">
                        <label id="" for="nif"><?php _e('Documento','candepalleiro-management');?>&nbsp;<i class="fa fa-file-o"></i></label>
                        <input required="required" class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Documento','candepalleiro-management'));?>" name="nif" size="30" value="<?php echo esc_attr($object->get_field('nif'));?>" id="nif" autocomplete="off">
                        <legend class="fa fa-info-circle required">&nbsp;<?php _e('Requerido','candepalleiro-management');?></legend>
                        <legend class="fa fa-info-circle">&nbsp;<?php _e('Documento de la Persona Física/Jurídica','candepalleiro-management');?>.</legend>
                </div>  

                <!-- Email -->
                <div class="field-div">
                        <label id="" for="mail"><?php _e('Correo electrónico','candepalleiro-management');?>&nbsp;<i class="fa fa-envelope-o"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Correo electrónico','candepalleiro-management'));?>" name="mail" size="30" value="<?php echo esc_attr($object->get_field('mail'));?>" id="mail" autocomplete="off">
                </div>          


                <!-- Teléfono -->
                <div class="field-div">
                        <label id="" for="telefono"><?php _e('Teléfono','candepalleiro-management');?>&nbsp;<i class="fa fa-phone"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Teléfono','candepalleiro-management'));?>" name="telefono" size="30" value="<?php echo esc_attr($object->get_field('telefono'));?>" id="telefono" autocomplete="off">
                </div>

                <!-- Teléfono 2-->
                <div class="field-div">
                        <label id="" for="telefono2"><?php _e('Segundo teléfono','candepalleiro-management');?>&nbsp;<i class="fa fa-phone"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Segundo teléfono','candepalleiro-management'));?>" name="telefono2" size="30" value="<?php echo esc_attr($object->get_field('telefono2'));?>" id="telefono2" autocomplete="off">
                </div>  

               
                <!-- Código postal-->
                <div class="field-div">
                        <label id="" for="cp"><?php _e('Código postal','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Código postal','candepalleiro-management'));?>" name="cp" size="30" value="<?php echo esc_attr($object->get_field('cp'));?>" id="cp" autocomplete="off">
                </div> 

                <!-- Número de vivienda-->
                <div class="field-div">
                        <label id="" for="numero_vivienda"><?php _e('Número de vivienda','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Número de vivienda','candepalleiro-management'));?>" name="numero_vivienda" size="30" value="<?php echo esc_attr($object->get_field('numero_vivienda'));?>" id="numero_vivienda" autocomplete="off">
                </div>


                <!-- Piso-->
                <div class="field-div">
                        <label id="" for="piso"><?php _e('Piso','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Piso','candepalleiro-management'));?>" name="piso" size="30" value="<?php echo esc_attr($object->get_field('piso'));?>" id="piso" autocomplete="off">
                </div>
                  


                <!-- País -->
                <div class="field-div">        
                        <label id="" for="pais"><?php _e('País','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <select class="select" name="pais" id="pais">
                        <?php
                        $countries = array( 'Europa'=>array( "ES"=>"España", "AL"=>"Albania", "DE"=>"Alemania", "AD"=>"Andorra", "AM"=>"Armenia", "AT"=>"Austria", "AZ"=>"Azerbaiyán", "BE"=>"Bélgica", "BY"=>"Bielorrusia", "BA"=>"Bosnia y Herzegovina", "BG"=>"Bulgaria", "CY"=>"Chipre", "VA"=>"Ciudad del Vaticano (Santa Sede)", "HR"=>"Croacia", "DK"=>"Dinamarca", "SK"=>"Eslovaquia", "SI"=>"Eslovenia", "EE"=>"Estonia", "FI"=>"Finlandia", "FR"=>"Francia", "GE"=>"Georgia", "GR"=>"Grecia", "HU"=>"Hungría", "IE"=>"Irlanda", "IS"=>"Islandia", "IT"=>"Italia", "XK"=>"Kosovo", "LV"=>"Letonia", "LI"=>"Liechtenstein", "LT"=>"Lituania", "LU"=>"Luxemburgo", "MK"=>"Macedonia, República de", "MT"=>"Malta", "MD"=>"Moldavia", "MC"=>"Mónaco", "ME"=>"Montenegro", "NO"=>"Noruega", "NL"=>"Países Bajos", "PL"=>"Polonia",  "PT"=>"Portugal", "UK"=>"Reino Unido", "CZ"=>"República Checa", "RO"=>"Rumanía",  "RU"=>"Rusia", "SM"=>"San Marino", "SE"=>"Suecia",  "CH"=>"Suiza", "TR"=>"Turquía", "UA"=>"Ucrania", "YU"=>"Yugoslavia", ), "África"=>array( "AO"=>"Angola", "DZ"=>"Argelia", "BJ"=>"Benín", "BW"=>"Botswana", "BF"=>"Burkina Faso", "BI"=>"Burundi", "CM"=>"Camerún", "CV"=>"Cabo Verde", "TD"=>"Chad", "KM"=>"Comores", "CG"=>"Congo", "CD"=>"Congo, República Democrática del", "CI"=>"Costa de Marfil", "EG"=>"Egipto", "ER"=>"Eritrea", "ET"=>"Etiopía", "GA"=>"Gabón", "GM"=>"Gambia", "GH"=>"Ghana", "GN"=>"Guinea", "GW"=>"Guinea Bissau", "GQ"=>"Guinea Ecuatorial", "KE"=>"Kenia", "LS"=>"Lesoto", "LR"=>"Liberia", "LY"=>"Libia", "MG"=>"Madagascar", "MW"=>"Malawi", "ML"=>"Malí", "MA"=>"Marruecos", "MU"=>"Mauricio", "MR"=>"Mauritania", "MZ"=>"Mozambique", "NA"=>"Namibia", "NE"=>"Níger",   "NG"=>"Nigeria", "CF"=>"República Centroafricana", "ZA"=>"República de Sudáfrica", "RW"=>"Ruanda", "EH"=>"Sahara Occidental", "ST"=>"Santo Tomé y Príncipe", "SN"=>"Senegal",   "SC"=>"Seychelles",  "SL"=>"Sierra Leona", "SO"=>"Somalia", "SD"=>"Sudán", "SS"=>"Sudán del Sur", "SZ"=>"Suazilandia", "TZ"=>"Tanzania", "TG"=>"Togo", "TN"=>"Túnez", "UG"=>"Uganda", "DJ"=>"Yibuti", "ZM"=>"Zambia",   "ZW"=>"Zimbabue", ), "Oceanía"=>array( "AU"=>"Australia", "FM"=>"Micronesia, Estados Federados de", "FJ"=>"Fiji", "KI"=>"Kiribati", "MH"=>"Islas Marshall", "SB"=>"Islas Salomón", "NR"=>"Nauru", "NZ"=>"Nueva Zelanda", "PW"=>"Palaos", "PG"=>"Papúa Nueva Guinea", "WS"=>"Samoa", "TO"=>"Tonga", "TV"=>"Tuvalu",  "VU"=>"Vanuatu",  ), "Sudamérica"=>array( "AR"=>"Argentina", "BO"=>"Bolivia", "BR"=>"Brasil", "CL"=>"Chile", "CO"=>"Colombia", "EC"=>"Ecuador", "GY"=>"Guayana", "PY"=>"Paraguay", "PE"=>"Perú", "SR"=>"Surinam", "TT"=>"Trinidad y Tobago", "UY"=>"Uruguay", "VE"=>"Venezuela", ), "Norteamérica y Centroamérica"=>array( "AG"=>"Antigua y Barbuda", "BS"=>"Bahamas", "BB"=>"Barbados", "BZ"=>"Belice", "CA"=>"Canadá", "CR"=>"Costa Rica", "CU"=>"Cuba", "DM"=>"Dominica", "SV"=>"El Salvador", "US"=>"Estados Unidos", "GD"=>"Granada", "GT"=>"Guatemala", "HT"=>"Haití", "HN"=>"Honduras", "JM"=>"Jamaica", "MX"=>"México", "NI"=>"Nicaragua", "PA"=>"Panamá", "PR"=>"Puerto Rico", "DO"=>"República Dominicana", "KN"=>"San Cristóbal y Nieves", "VC"=>"San Vicente y Granadinas", "LC"=>"Santa Lucía", ), "Asia"=>array( "AF"=>"Afganistán", "SA"=>"Arabia Saudí", "BH"=>"Baréin", "BD"=>"Bangladesh", "MM"=>"Birmania", "BT"=>"Bután", "BN"=>"Brunéi", "KH"=>"Camboya", "CN"=>"China", "KP"=>"Corea, República Popular Democrática de", "KR"=>"Corea, República de", "AE"=>"Emiratos Árabes Unidos", "PH"=>"Filipinas", "IN"=>"India", "ID"=>"Indonesia", "IQ"=>"Iraq",  "IR"=>"Irán", "IL"=>"Israel", "JP"=>"Japón", "JO"=>"Jordania", "KZ"=>"Kazajistán", "KG"=>"Kirguizistán", "KW"=>"Kuwait", "LA"=>"Laos", "LB"=>"Líbano", "MY"=>"Malasia", "MV"=>"Maldivas", "MN"=>"Mongolia", "NP"=>"Nepal", "OM"=>"Omán", "PK"=>"Paquistán", "QA"=>"Qatar", "SG"=>"Singapur", "SY"=>"Siria", "LK"=>"Sri Lanka", "TJ"=>"Tayikistán", "TH"=>"Tailandia", "TP"=>"Timor Oriental", "TM"=>"Turkmenistán", "UZ"=>"Uzbekistán", "VN"=>"Vietnam", "YE"=>"Yemen", ),  );
                        foreach($countries as $key=>$value):
                        echo "<optgroup label=\"$key\">";
                        foreach($value as $k=>$v):
                        $selected=($object->get_field('pais')==$v)?'selected="selected"':'';
                        echo "<option $selected value=\"$v\">$v</option>";
                        endforeach;
                        echo "</optgroup>";
                        endforeach;      
                        ?> 
                        </select>    
                </div>

                <!-- Provincia -->
                <div class="field-div">        
                        <label id="" for="pais"><?php _e('Provincia','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <select class="select" name="provincia" id="provincia">
                        <?php
                        $Provincia = array(  '28' =>'A Coruña','2' =>'Álava',  '3' =>'Albacete',  '4' =>'Alicante/Alacant',  '5' =>'Almerí',  '6' =>'Asturias',  '7' =>'Ávila',  '8' =>'Badajoz',  '9' =>'Barcelona',  '10' =>'Burgos',  '11' =>'Cáceres',  '12' =>'Cádiz',  '13' =>'Cantabria',  '14' =>'Castellón/Castelló',  '15' =>'Ceuta',  '16' =>'Ciudad Real',  '17' =>'Córdoba',  '18' =>'Cuenca',  '19' =>'Girona',  '20' =>'Las Palmas',  '21' =>'Granada',  '22' =>'Guadalajara',  '23' =>'Guipúzcoa',  '24' =>'Huelva',  '25' =>'Huesca',  '26' =>'Illes Balears',  '27' =>'Jaén',  '29' =>'La Rioja',  '30' =>'León',  '31' =>'Lleida',  '32' =>'Lugo',  '33' =>'Madrid',  '34' =>'Málaga',  '35' =>'Melilla',  '36' =>'Murcia',  '37' =>'Navarra',  '38' =>'Ourense',  '39' =>'Palencia',  '40' =>'Pontevedra',  '41' =>'Salamanca',  '42' =>'Segovia',  '43' =>'Sevilla',  '44' =>'Soria',  '45' =>'Tarragona',  '46' =>'Santa Cruz de Tenerife',  '47' =>'Teruel',  '48' =>'Toledo',  '49' =>'Valencia/Valéncia',  '50' =>'Valladolid',  '51' =>'Vizcaya',  '52' =>'Zamora',  '53' =>'Zaragoza'  );
                        foreach($Provincia as $key=>$value):
                        $selected=($object->get_field('provincia')==$value)?'selected="selected"':'';
                        echo "<option $selected value=\"$value\">$value</option>";
                        endforeach;     
                        ?> 
                        </select>    
                </div>

                <!-- Concello-->
                <div class="field-div">
                        <label id="" for="piso"><?php _e('Ayuntamiento','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Ayuntamiento','candepalleiro-management'));?>" name="concello" size="30" value="<?php echo esc_attr($object->get_field('concello'));?>" id="concello" autocomplete="off">
                </div>

                <!-- Lugar-->
                <div class="field-div">
                        <label id="" for="lugar"><?php _e('Lugar','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Lugar','candepalleiro-management'));?>" name="lugar" size="30" value="<?php echo esc_attr($object->get_field('lugar'));?>" id="lugar" autocomplete="off">
                </div>

                <!-- Parroquia-->
                <div class="field-div">
                        <label id="" for="parroquia"><?php _e('Parroquia','candepalleiro-management');?>&nbsp;<i class="fa fa-map-marker"></i></label>
                        <input class="input"  type="text" placeholder="<?php echo sprintf(__('Escribe %s aquí','candepalleiro-management'),__('Parroquia','candepalleiro-management'));?>" name="parroquia" size="30" value="<?php echo esc_attr($object->get_field('parroquia'));?>" id="parroquia" autocomplete="off">
                </div>             
                
                
                <!-- Notas -->
                <div class="field-div">
                        <label id="" for="notas"><?php _e('Notas','candepalleiro-management');?>&nbsp;<i class="fa fa-commenting"></i></label>
                        <?php        
                        $argumentos=array('textarea_name'=>'notas','media_buttons'=>'false','teeny'=>'true','textarea_rows'=>6);
                        wp_editor($content=$object->get_field('notas'), $id = 'notas', $argumentos);
                        ?>
                </div>               

                 
                
                <fieldset class="field-fieldset">
                <legend><?php _e('Otros datos','candepalleiro-management');?></legend>

                

                <!-- De baja -->
                <div class="field-div">
                        <label id="" for="bajapropietario"><i  title="<?php _e("De baja",'candepalleiro-management'); ?>" class="border negro"><?php _e("B",'candepalleiro-management');?></i>&nbsp;<?php _e('De baja','candepalleiro-management');?></label>
                        <select class="select" name="bajapropietario" id="bajapropietario">
                        <?php
                        $estado=array("0"=>__('No','candepalleiro-management'),"1"=>__('Sí','candepalleiro-management'));
                        $pendiente_de_facturar=$object->get_field('bajapropietario');
                        foreach($estado as $key=>$value):
                        $selected=($pendiente_de_facturar==$key)?'selected="selected"':'';
                        echo "<option $selected value=\"$key\">$value</option>";
                        endforeach;
                        ?>          
                        </select>  
                </div>
                        
                </fieldset>
                
                
                
            </div><!-- end #post-body-content -->
             
            <div id="postbox-container-1" class="postbox-container">
                <!--postbox-container-1-->
                <div id="accordion_edit">
                    <h3 class="clear"><?php _e("Update");?></h3>                    
                    <div class="content_accordion">

                        <div><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-file-pdf-o fa-stack-1x"></i></span>&nbsp;<a target="_blank" href="?page=PDF&action=pdf-owner&owner=<?php echo $_GET['codex'];?>" id="set-pdf-propietario" class=""><?php _e("PDF del Propietario",'candepalleiro-management');?></a></div>
                      

                        <hr>
                        
                        <div class="text-align-center">
                        <input type="hidden" name="action" value="edit" />
                        <input type="hidden" name="codex" value="<?php echo $_REQUEST['codex'];?>" />
                        <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php _e("Update");?>">
                        </div>
                    </div>                  
                    
                    <h3 class="clear">&nbsp;<?php _e("Perros en propiedad",'candepalleiro-management'); ?></h3>
                    <div class="content_accordion">   
                        
                    <?php if(count($dogs)>0):?>
                            <span class="fa-stack fa-lg"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-list-alt fa-stack-1x fa-inverse"></i></span>&nbsp;<span><?php _e('Listado de Perros','can-de-palleiro');?></span></span>
                            <?php foreach($dogs as $value):?>
                            <p class="hide-if-no-js"><?php _e('Nombre','candepalleiro-management');?>:&nbsp;<strong><a href="?page=perros&action=edit&codex=<?php echo $value->id_can.'&_wpnonce='.wp_create_nonce( 'wf_edit_perro'.$value->id_can);?>"><?php echo $value->nome;?></a></strong></p>
                            <p class="hide-if-no-js inside"><?php _e('RLX','candepalleiro-management');?>:&nbsp;<?php echo $value->rlx;?></p>
                            <?php endforeach;?>                         
                        <?php endif;?> 

                    </div>
                    
                                   
                 </div><!-- end accordion_edit -->                
            </div><!-- end postbox-container-1 -->
             
            <div id="postbox-container-2" class="postbox-container">
            <!--container 2-->
            </div>
        </div><!-- /#post-body -->        
    </div><!-- /poststuff -->
 
    </form><!-- /form -->
    
</div><!-- /wrap -->