<div class="wrap">
    <?php
    printf('<h2><span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-info-circle fa-stack-1x"></i>
</span> %s %s</h2>',__('Información de ','candepalleiro-management'),__('Propietario','candepalleiro-management'));
    ?>  
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
                    <div>
                        <label id="" for="microchip"><?php _e('Nombre de pila','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div>
                        <?php echo esc_attr($object->get_field('p_nome'));?>
                        </div>
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="field-div">
                        <label id="" for="microchip"><?php _e('Apellidos','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div>
                        <?php
                         echo esc_attr($object->get_field('p_apelido'));
                        ?>
                        </div>
                </div>


                <!-- Tipo de documento -->
                <div class="field-div">
                        <label id="" for="tipo_documento"><?php _e("Tipo de Documento",'candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div>
                        <?php
                        $tipo_documento=array("nif"=>__('NIF','candepalleiro-management'),"cif"=>__('CIF','candepalleiro-management'),"otros"=>__('Otros','candepalleiro-management'));
                        $valor=$object->get_field('tipo_documento');
                        echo $tipo_documento[$valor];

                        ?>
                        </div>                        
                </div> 


                <!-- Documento -->
                <div class="field-div">
                        <label id="" for="nif"><?php _e('Documento','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('nif'));?></div>
                </div>  

                <!-- Email -->
                <div class="field-div">
                        <label id="" for="mail"><?php _e('Correo electrónico','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('mail'));?></div>
                </div>          


                <!-- Teléfono -->
                <div class="field-div">
                        <label id="" for="telefono"><?php _e('Teléfono','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('telefono'));?></div>
                </div>

                <!-- Teléfono 2-->
                <div class="field-div">
                        <label id="" for="telefono2"><?php _e('Segundo teléfono','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('telefono2'));?></div>
                </div>  

               
                <!-- Código postal-->
                <div class="field-div">
                        <label id="" for="cp"><?php _e('Código postal','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('cp'));?></div>
                </div> 

                <!-- Número de vivienda-->
                <div class="field-div">
                        <label id="" for="numero_vivienda"><?php _e('Número de vivienda','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('numero_vivienda'));?></div>
                </div>


                <!-- Piso-->
                <div class="field-div">
                        <label id="" for="piso"><?php _e('Piso','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('piso'));?></div>
                </div>
                  


                <!-- País -->
                <div class="field-div">        
                        <label id="" for="pais"><?php _e('País','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('pais'));?></div>       
                </div>

                <!-- Provincia -->
                <div class="field-div">        
                        <label id="" for="pais"><?php _e('Provincia','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('provincia'));?></div>    
                </div>

                <!-- Concello-->
                <div class="field-div">
                        <label id="" for="piso"><?php _e('Ayuntamiento','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('concello'));?></div>
                </div>

                <!-- Lugar-->
                <div class="field-div">
                        <label id="" for="lugar"><?php _e('Lugar','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('lugar'));?></div>
                </div>

                <!-- Parroquia-->
                <div class="field-div">
                        <label id="" for="parroquia"><?php _e('Parroquia','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                </div>             
                
                
                <!-- Notas -->
                <div class="field-div">
                        <label id="" for="notas"><?php _e('Notas','candepalleiro-management');?>&nbsp;<i class="fa fa-check"></i></label>
                        <div><?php echo esc_attr($object->get_field('notas'));?></div>
                </div>               

                 
                
                <fieldset class="field-fieldset">
                <legend><?php _e('Otros datos','candepalleiro-management');?></legend>

                

                <!-- De baja -->
                <div class="field-div">
                        <label id="" for="bajapropietario"><i  title="<?php _e("De baja",'candepalleiro-management'); ?>" class="border negro"><?php _e("B",'candepalleiro-management');?></i>&nbsp;<?php _e('De baja','candepalleiro-management');?></label>
                        <select disabled="disabled" class="select" name="bajapropietario" id="bajapropietario">
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
                    <h3 class="clear"><?php _e("Opciones","candepalleiro-management");?></h3>                    
                    <div class="content_accordion">

                        <div><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-file-pdf-o fa-stack-1x"></i></span>&nbsp;<a target="_blank" href="?page=PDF&action=pdf-owner&owner=<?php echo $_GET['codex'];?>" id="set-pdf-propietario" class=""><?php _e("PDF del Propietario",'candepalleiro-management');?></a></div>
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
    
</div><!-- /wrap -->