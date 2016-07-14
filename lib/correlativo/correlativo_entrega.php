<?php
	// ABC de correlativo
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_entrega($busca, $page, $orden, $lista, $anio) {
    //echo $busca."-".$page."-".$orden."-".$lista."-".$anio;
		$paginacion = 50; 
		$anio_actual = date("Y");
		if($page == '' || $page == 0) $page = '0';
		else {
			$page = $page - 1; 
			$page = $page * $paginacion;
		} 
		
		//SORTEAR ASCENDENTE O DESCENDENTE
		if($lista == "") $lista_query = "DESC";
		else if ($lista == "ASC") $lista_query = "DESC"; 
		else if ($lista == "DESC") $lista_query = "ASC";
		
		//SORTEAR POR CAMPO
		if($orden == ""){ 
		$orden="correlativo";
		}
		
		$id_usuario = get_cookie_id_user();
        $id_representacion = get_cookie_representacion();
		
		//Filtro para buscar en años anteriores, si no hay variable busca en año actual
		if($anio == ""){$filtro_anio = "AND YEAR(correlativo.fecha) = YEAR(CURDATE())"; $anio = date('Y');}
		else{$filtro_anio = "AND YEAR(correlativo.fecha) = '$anio'";}
		
		//Filtro para mostrar solamente los del usuario si es user y todos si es admin
		if(is_admin() || is_comunicaciones() || is_protocolo()) $filtro_usuario = "";
		else if(is_user()) $filtro_usuario = "AND usuario = '$id_usuario'";
		
        
        //EN CASO DE QUE HAYA BÚSQUEDA, SE ELIMINA LA PAGINACIÓN Y EL QUERY DE MAX PAG PARA MOSTRAR LOS RESULTADOS EN UNA SÓLA PÁGINA
		if($busca != '' && $busca != 'Buscar'){ $where .= "AND ( correlativo RLIKE '$busca' OR destino Rlike '$busca' OR referencia Rlike '$busca' OR expediente Rlike '$busca' OR asunto Rlike '$busca' OR texto_sicar Rlike '$busca')"; $limit = "LIMIT 0,$paginacion"; 
        $page = 0;}
        else {$limit = "LIMIT $page, $paginacion";
        $max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo  where 1=1 $where $filtro_usuario  $filtro_anio AND representacion = '$id_representacion'");
       	$max_pg = $max_pg[0]['id']; 
        $max_pg /= $paginacion;
        $max_pg = ceil($max_pg);
		}
        
        
        $correlativo_entrega = run_select_query("SELECT
correlativo.id,
correlativo.correlativo,
correlativo.asunto,
correlativo.referencia,
correlativo.expediente,
DATE_FORMAT(correlativo.fecha,'%d/%m/%Y') as fecha,
correlativo.texto_sicar,
correlativo.archivo,
correlativo.destino,
tipodocumento.TipoDocumento as tipo_documento,
usuarios.nombre as funcionario,
correlativo.mensajero,
correlativo.fecha_entrega,
correlativo.estado
FROM
correlativo
INNER JOIN tipodocumento ON correlativo.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN usuarios ON correlativo.usuario = usuarios.id
WHERE
correlativo.tipo_documento = '4' $where $filtro_usuario $filtro_anio
AND correlativo.representacion = '$id_representacion' order by $orden $lista_query $limit");



		?>
        <style>
        span.highlighted {
   background-color: yellow;
	}
        </style>
       <div id="toolbar-box">
             	<div class="m">
           			<div class="toolbar" id="toolbar">
					  	<table class="toolbar">
							<tr>
                                 <td class="button" id="toolbar-new"> 
								<a href="index.php?add_correlativo_entrega&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span><img src="images/agrega_archivo.png" alt="Agregar" title="Agregar"> 
								Agregar Envíos
								</a> 
								</td> 
     						</tr>
						</table>
					 </div>
               
                      <div class="header galeria">
                     	Administrador de Correlativo <?php echo $anio_actual;?>
                      </div>
				      
                      <div class="clr"></div>
				</div>
  		</div>
        
           <div id="element-box">
                <div class="m">
        <form name="form_list_correlativo_entrega" action="index.php" method="get">
		<table >
			<tr>
			  <td colspan='4'>
              Páginas: - 
				<?PHP 
                 for($i=1; $i <= $max_pg; $i++){
					//$pagina = $page/$max_pg + 1;
					$pagina = $page/$paginacion + 1;
					
                    if($pagina == $i){ 
					$estilo = "style='font-weight:bold; font-size:18px; color:#A21636;'";
					}
					
					else $estilo = "style='color:black;'";
					echo "  <a href='index.php?admin_correlativo_entrega&page=$i&anio=$anio&busca=$busca' $estilo >$i</a> -";
				}?>
                </td>
                </tr>
                <tr>
                	<td> Años: 
					<?php 
						$anios = run_select_query("Select distinct YEAR(fecha) as anios from correlativo  where representacion = '$id_representacion' order by fecha DESC ");
						
						foreach($anios as $key => $value){
							$res_anio = $anios[$key]['anios'];
							if($anio == $res_anio){ $estilo_anio = "style='font-weight:bold; font-size:18px; color:#A21636;'";}
							else $estilo_anio = "style='color:black'";
							
							
							echo "<a href='index.php?admin_correlativo_entrega&busca=$busca&page=1&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
                <tr>
                <td>
					 Búsqueda palabra: <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_entrega" value="Go" id="admin_correlativo_entrega" class="button super_tiny"/>
                     <input type="hidden" name="page" value="<?php echo $page ?>"/>
					 </span> </td>
				  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
			  <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=correlativo&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Correlativo</a></th>
			  <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=fecha&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Fecha</a></th>
              <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=tipo_documento&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Tipo de documento</a></th>
              <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=destino&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Destino</a></th>                           
              <th class="title">Referencia</th>              
			 <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=expediente&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Expediente</a></th>              
			 <th class="title">Asunto</th>
              <th class="title"><a href="index.php?admin_correlativo_entrega&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuario&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Usuario</a></th>
               <th class="title"><p>Quien Entrega</p></th>
                <th class="title"><p>Fecha de Entrega</p></th>
               <th class="title">Estado</th>
               	</thead>
		  </tr>
			
		<?php
		if($correlativo_entrega)
		foreach($correlativo_entrega as $key => $value) {
		$id_correlativo = $correlativo_entrega[$key]['id'];	
		$correlativo = $correlativo_entrega[$key]['correlativo'];
		$correlativo = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$correlativo);
        
        $archivo = $correlativo_entrega[$key]['archivo'];
		$destino = $correlativo_entrega[$key]['destino'];	
		$destino = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$destino);
        
        $asunto = $correlativo_entrega[$key]['asunto'];	
        $asunto = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$asunto);
        
        $referencia = $correlativo_entrega[$key]['referencia'];
		$referencia = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$referencia);
        
        $tipo_documento = $correlativo_entrega[$key]['tipo_documento'];	
		$funcionario = $correlativo_entrega[$key]['funcionario'];		
		$expediente = $correlativo_entrega[$key]['expediente'];	
        $expediente = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$expediente);
        
		$fecha = $correlativo_entrega[$key]['fecha'];
      	
		$longitud = strlen($correlativo);
        $mensajero = $correlativo_entrega [$key]['mensajero'];
		$fecha_entrega = $correlativo_entrega [$key]['fecha_entrega'];
		$estado = $correlativo_entrega [$key]['estado'];
        $id_representacion = get_cookie_representacion();
        $determinante = run_select_query("Select siglas from representaciones where id = '$id_representacion'");
        $determinante = strtoupper($determinante[0]['siglas']);
        
        
			if ($longitud == "1")$gua=$determinante."0000".$correlativo;
			else if ($longitud == "2") $gua=$determinante."000".$correlativo;
			else if ($longitud == "3") $gua=$determinante."00".$correlativo;
			else if ($longitud == "4") $gua=$determinante."0".$correlativo; 
            
            //Si no trae correlativo de DB el correlativo viene en la búsqueda
            else {
            $longitud2 = strlen($busca);
            if ($longitud2 == "1")$gua=$determinante."0000".$correlativo;
			else if ($longitud2 == "2") $gua=$determinante."000".$correlativo;
			else if ($longitud2 == "3") $gua=$determinante."00".$correlativo;
			else if ($longitud2 == "4") $gua=$determinante."0".$correlativo; 
         	}
			
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
			?>
			
			<tr class="row<?php echo $renglon; ?>" >
				<td class="center" nowrap="nowrap">	
					<a href='index.php?edit_correlativo_entrega&id=<?php echo $id_correlativo; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <?php if (is_admin()) { ?><a href='index.php?del_correlativo_entrega&id=<?php echo $id_correlativo ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a> <?php } ?>				</td>
				
					<td ><strong>
					<?php echo $gua;  ?>
                        </strong>
                    </td>
					<td class="center">
					<strong>
					<div id='fecha_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_fecha("<?php echo $id_correlativo ?>");'><?php echo $fecha; ?></a></div>
                    </strong>
					
					</td>		
					<td>
					<strong>
					<div id='tipo_documento_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_tipo_documento("<?php echo $id_correlativo ?>");'>
					<?php 
					if($tipo_documento == "") echo "agregar";
					else echo $tipo_documento; 
					?></a></div>
                    </strong>
					</td>		
                    <td>
					<strong>
					<div id='destino_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_destino("<?php echo $id_correlativo ?>");'>
					<?php 
					if($destino == "") echo "agregar";
					else echo $destino; 
					?>
					</a></div>
                    </strong>
					</td>
                    <td>
					<strong>
					<div id='referencia_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_referencia("<?php echo $id_correlativo ?>");'>
					<?php 
					if($referencia == "") echo "agregar";
					else echo $referencia; 
					?>
					</a></div>
                    </strong>
					</td>		
                    <td>
					<strong>
					<div id='expediente_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_expediente("<?php echo $id_correlativo ?>");'>
					<?php 
					if($expediente == "") echo "agregar";
					else echo $expediente; 
					?>
					</a></div>
                    </strong>
					</td>		
                    <td>
					<strong>
					<div id='asunto_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_asunto("<?php echo $id_correlativo ?>");'>
					<?php 
					if($asunto == "") echo "agregar";
					else echo $asunto; 
					?>
					</a></div>
                    </strong>
					</td>	
                    <td><?php echo $funcionario; ?></td>
                    <td>
					<strong>
					<div id='mensajero_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_mensajero("<?php echo $id_correlativo ?>");'>
					<?php 
					if($mensajero == "") echo "agregar";
					else echo $mensajero; 
					?>
					</a></div>
                    </strong>
					</td>	
                    <td>
					<strong>
					<div id='fecha_entrega_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_fecha_entrega("<?php echo $id_correlativo ?>");'>
					<?php 
                    if($fecha_entrega == "") echo "agregar";
					else echo $fecha_entrega; 
					
					?></td>	
                    <td><?php 
                    $p = $page/$paginacion+1;
					if($estado == "") echo "agregar";
					else echo $estado; 
					
					?></td>					
					
			</tr>
			
			
			<?php
		} // -foreach usuarios
		?>
		</table>
		</form>
	</div>
    </div>
      
		<?php
	}
	
	
	
	// Muestra la forma para agregar un usuario
	function add_correlativo_entrega_form($busca, $page, $orden, $lista, $anio) {
	 $id_representacion = get_cookie_representacion();
    $sicar = run_select_query("Select sicar from representaciones where id = '$id_representacion'");
    if($sicar[0]['sicar'] != "") {
    $archivo_sicar = $sicar[0]['sicar'];
    $display_sicar = "";
    }
    else{
    $display_sicar = "style='display:none'";
    }
    
    
		?>
 		 
		<fieldset class="adminform">
        
		<legend>Agregar Correlativo</legend>
    
				
<form action="index.php" name="form_add_periodo" method="post">
		<table class="adminform">
			<tr>
				<th class='title'>
					Tipo de Documento: </th>
				<td colspan="5">

                <?php 
					$documento = run_select_query("Select * from tipodocumento order by TipoDocumento");
					foreach($documento as $key => $value){
					$id_documento = $documento[$key]['idTipoDocumento'];
					$tipo_documento = $documento[$key]['TipoDocumento'];
				
					$select_tipo_documento.= "<option value='$id_documento'>$tipo_documento</option>";	
						
						}
					
					
					?>
                    <select name="tipo_documento" id="tipo_documento">
                    <?php echo $select_tipo_documento; ?>                    
                    </select>	
                
       </td>
			</tr>
             <tr>
				<th class='title'>Destino:</th>
				<td >	
					<input type="text" name="destino" value="" id="destino" />				</td>
			</tr>
             <tr>
				<th class='title'>Asunto:</th>
				<td ><textarea name="asunto" id="asunto"></textarea></td>
			</tr>
             
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="" id="referencia" />				</td>
				
			</tr>
          
          
            <tr <?php echo $display_sicar;?>>
				<th class='title' >
				  Expediente: </th>
				<td colspan="5"><input type="text" name="expediente" value="" id="expediente" /> 
				<a href="/gua/archivos/sicar/<?php echo $archivo_sicar;?>" target="_blank">Consultar catálogo SICAR</a> </td>
			</tr>
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
				<input class="button" type="submit" name="do_add_correlativo_entrega" value="Agregar" id="btn_add_correlativo_entrega" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}