<?php
	// ABC de correlativo
	//NUEVO COMENTARIO SIN PRESIONAR EL BOTON SYNC
	//SEGUNDO COMENTARIO 
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_salida($busca, $page, $orden, $lista, $anio) {
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
        
        
        $correlativo_salida = run_select_query("SELECT
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
usuarios.nombre as funcionario
FROM
correlativo
INNER JOIN tipodocumento ON correlativo.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN usuarios ON correlativo.usuario = usuarios.id where 1=1 $where $filtro_usuario $filtro_anio
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
								<a href="index.php?add_correlativo_salida&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span><img src="images/agrega_archivo.png" alt="Agregar" title="Agregar"> 
								Agregar - Correlativo
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
        <form name="form_list_correlativo_salida" action="index.php" method="get">
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
					echo "  <a href='index.php?admin_correlativo_salida&page=$i&anio=$anio&busca=$busca' $estilo >$i</a> -";
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
							
							
							echo "<a href='index.php?admin_correlativo_salida&busca=$busca&page=1&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
                <tr>
                <td>
					 Búsqueda palabra: <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_salida" value="Go" id="admin_correlativo_salida" class="button super_tiny"/>
                     <input type="hidden" name="page" value="<?php echo $page ?>"/>
					 </span> </td>
				  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
			  <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=correlativo&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Correlativo</a></th>
			  <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=fecha&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Fecha</a></th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=tipo_documento&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Tipo de documento</a></th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=destino&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Destino</a></th>                           
              <th class="title">Referencia</th>              
			 <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=expediente&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Expediente</a></th>              
			 <th class="title">Asunto</th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuario&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Usuario</a></th>
               <th class="title">Texto SICAR</th>
               <th class="title">Archivo</th>
               	</thead>
		  </tr>
			
		<?php
		if($correlativo_salida)
		foreach($correlativo_salida as $key => $value) {
		$id_correlativo = $correlativo_salida[$key]['id'];	
		$correlativo = $correlativo_salida[$key]['correlativo'];
		$correlativo = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$correlativo);
        
        $archivo = $correlativo_salida[$key]['archivo'];
		$destino = $correlativo_salida[$key]['destino'];	
		$destino = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$destino);
        
        $asunto = $correlativo_salida[$key]['asunto'];	
        $asunto = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$asunto);
        
        $referencia = $correlativo_salida[$key]['referencia'];
		$referencia = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$referencia);
        
        $tipo_documento = $correlativo_salida[$key]['tipo_documento'];	
		$funcionario = $correlativo_salida[$key]['funcionario'];
		$expediente = $correlativo_salida[$key]['expediente'];	
        $expediente = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$expediente);
        
		$fecha = $correlativo_salida[$key]['fecha'];
      	
		$longitud = strlen($correlativo);
        
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
					<a href='index.php?edit_correlativo_salida&id=<?php echo $id_correlativo; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <?php if (is_admin()) { ?><a href='index.php?del_correlativo_salida&id=<?php echo $id_correlativo ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a> <?php } ?>				</td>
				
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
					<div id='texto_sicar_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_texto_sicar("<?php echo $id_correlativo ?>");'>
					<?php 
					if($texto_sicar == "") echo "agregar";
					else echo $texto_sicar; 
					?>
					</a></div>
                    </strong>
					</td>	
                    <td><?php 
                    $p = $page/$paginacion+1;
					if($archivo == "") echo "<a href='index.php?add_correlativo_archivo&id=$id_correlativo&busca=$busca&page=$p&orden=$orden&lista=$lista&anio=$anio'>agregar</a>";
					else echo "<a href='index.php?ver_documento&archivo=$archivo&carpeta=correlativo' target='_blank'>$archivo</a>"; 
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
	function add_correlativo_salida_form($busca, $page, $orden, $lista, $anio) {
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
				<input class="button" type="submit" name="do_add_correlativo_salida" value="Agregar" id="btn_add_correlativo_salida" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para editar un usuario
	function edit_correlativo_salida_form($id,$busca, $page, $orden, $lista, $anio) {
		$correlativo_salida = run_select_query("SELECT * FROM correlativo WHERE id = '$id'");
		$id_tipo_documento = $correlativo_salida[0]['tipo_documento'];
        $nombre_documento = run_select_query("Select TipoDocumento from tipodocumento where idTipoDocumento = '$id_tipo_documento'");
		$nombre_documento = $nombre_documento[0][0];
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
		<legend>Editar Correlativo </legend>
  
				
		<form action="index.php" name="form_edit_correlativo_salida" method="post" enctype="multipart/form-data">
  <table class="adminform">
			
			<tr>
				<th class='title'>Tipo de Documento:</th>
				<td><?php 
					$documento = run_select_query("Select * from tipodocumento order by TipoDocumento");
					foreach($documento as $key => $value){
					$id_documento = $documento[$key]['idTipoDocumento'];
					$tipo_documento = $documento[$key]['TipoDocumento'];
				
					$select_tipo_documento.= "<option value='$id_documento'>$tipo_documento</option>";
                    
                    						
						}
					
					
					?>
                    <select name="tipo_documento" id="tipo_documento">
                    <option value="<?php echo $id_tipo_documento ?>" selected="selected"><?php echo $nombre_documento ?></option>
					<?php echo $select_tipo_documento; ?>                    
                    </select>	
                
					
   			  </td>
			</tr>
             <tr>
				<th class='title'>Destino: </th>
				<td colspan="5">	
					<input type="text" name="destino" value="<?php echo $correlativo_salida[0]['destino'] ?>" id="periodo" size="40"/>				</td>
		</tr>
        <tr>
				<th class='title'>Asunto: </th>
				<td colspan="5"><textarea name="asunto" id="asunto"><?php echo $correlativo_salida[0]['asunto'] ?></textarea></td>
			</tr>         
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="<?php echo $correlativo_salida[0]['referencia'] ?>"  size="40"/>				</td>
				
			</tr>
           <tr <?php echo $display_sicar;?>>
				<th class='title' >
				  Expediente: </th>
				<td colspan="5"><input type="text" name="expediente" value="" id="expediente" /> 
				<a href="/gua/archivos/sicar/<?php echo $archivo_sicar;?>" ?>
				Consultar catálogo SICAR</a>		    </td>
		</tr> 
        <tr>
				<th class='title'>Archivo: </th>
				<td><input type="file" name="archivo" value="" id="archivo" />	<?php echo $correlativo_salida[0]['archivo']; ?>			</td>
				
			</tr>    
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $correlativo_salida[0]['id']; ?>" id="id" />
                
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                    <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
                    
					<input class="button" type="submit" name="do_edit_correlativo_salida" value="Editar" id="btn_edit_correlativo_salida" />	</td>
			</tr>
		</table>
		
		
		
		
		
</form>
		   </fieldset>
	
		<?php
	}
	
	
	// Agrega el usuario a la BD
		function do_add_correlativo_salida($tipo_documento, $destino, $asunto, $referencia, $expediente, $id_funcionario, $fecha, $texto_sicar, $archivo, $fecha_entrega, $mensajero, $fecha_acuse, $funcionario_acuse, $estado, $busca, $page, $orden, $lista, $anio) {
	
			$destino = addslashes($destino);
			$asunto = addslashes($asunto);
			$referencia = addslashes($referencia);
			$expediente = addslashes($expediente);
			$texto_sicar = addslashes($texto_sicar);
			$id_funcionario = get_cookie_id_user();
            $id_representacion = get_cookie_representacion();
			$fecha = date('Y-m-d');
			$correlativo = dame_correlativo_siguiente();
			$estado = 'PENDIENTE';
			
			
          	run_non_query("INSERT INTO correlativo  VALUES (null, $correlativo,'$tipo_documento','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$id_representacion', '$fecha', '$texto_sicar', '$archivo', '$fecha_entrega', '$mensajero', '$fecha_acuse', '$funcionario_acuse', '$estado')");
          
          	//echo "INSERT INTO correlativo  VALUES (null, $correlativo,  '$gua','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$fecha', '$texto_sicar', '$archivo')";
			
			?>
			<p class='highlight'>
			Correlativo Agregado </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_salida($busca, $page);
	}
	
	
	// Edita el usuario en BD
	function do_edit_correlativo_salida($tipo_documento, $destino, $asunto, $referencia, $expediente, $texto_sicar, $archivo, $id, $busca, $page, $orden, $lista, $anio) {
		
        //Subimos archivo a carpeta de protocolo
			 $ruta = '/var/www/html/gua/archivos/protocolo/';
           
				if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
						sube_archivo($archivo, $ruta);
						$n_archivo = date('s')."_".basename($archivo['name']);
						$texto_archivo = "foto='$n_archivo',";
				}
				else $texto_archivo="";
                echo $n_archivo."aqui<br>";
		
        
	run_non_query("UPDATE correlativo SET tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', $texto_archivo expediente = '$expediente' WHERE id = '$id'");
    
	//echo "UPDATE correlativo SET tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', $texto_archivo expediente = '$expediente' WHERE id = '$id'";

		?>
		<p class='highlight'>
		Correlativo Editado </p>
		<?php
		
		list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	// Borra el usuario de BD
	function del_correlativo_salida($id,$busca, $page, $orden, $lista, $anio) {
		run_non_query("DELETE FROM correlativo WHERE id = $id");
        //echo "DELETE FROM correlativo WHERE id = $id";
		?>
	  <p class='highlight'>
		Correlativo Eliminado </p>
		<?php
		list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	function dame_correlativo_siguiente(){
		$anio = date("Y");
         $id_representacion = get_cookie_representacion();
         
	$correlativo = run_select_query("Select correlativo from correlativo where id=(select MAX(id) from correlativo where representacion = '$id_representacion' AND YEAR(fecha) = YEAR(CURDATE()))");
	
	return $correlativo[0][0]+1;	
	}
    
  
    
    function dame_recibidos_siguiente(){
    $id_representacion = get_cookie_representacion();
	$correlativo = run_select_query("Select correlativo from correlativo_recibidos where id=(select MAX(id) from correlativo_recibidos where representacion = '$id_representacion')");
	return $correlativo[0][0]+1;	
	}
	
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_recibidos($busca, $page, $orden, $lista, $anio) {
		$paginacion = 50; 
		if($page == '') $page = '0';
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
		$orden="correlativo_recibidos.id";
		}
		
		$id_usuario = get_cookie_id_user();
         $id_representacion = get_cookie_representacion();
		
		
        //Filtro para buscar en años anteriores, si no hay variable busca en año actual
		if($anio == ""){$filtro_anio = "AND YEAR(correlativo_recibidos.fecha) = YEAR(CURDATE())"; $anio = date('Y');}
		else{$filtro_anio = "AND YEAR(correlativo_recibidos.fecha) = '$anio'";}
		
        
		if(is_admin()) $filtro_usuario = "";
		else if(is_user()) $filtro_usuario = "correlativo_recibidos.turnado = '$id_usuario'";
		
        //EN CASO DE QUE HAYA BÚSQUEDA, SE ELIMINA LA PAGINACIÓN Y EL QUERY DE MAX PAG PARA MOSTRAR LOS RESULTADOS EN UNA SÓLA PÁGINA
		if($busca != '' && $busca != 'Buscar'){ $where .= "AND documento Rlike '$busca' OR procedencia Rlike '$busca' OR asunto Rlike '$busca' OR referencia Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
        else{
        $limit = "LIMIT $page, $paginacion";
     	$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_recibidos  
        where 1=1 $where $filtro_usuario  $filtro_anio AND representacion = '$id_representacion'");
    	$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
        }
        
		$correlativo_salida = run_select_query("SELECT
correlativo_recibidos.id as id,		
tipodocumento.TipoDocumento as tipo_documento,
correlativo_recibidos.correlativo,
correlativo_recibidos.documento,
correlativo_recibidos.procedencia,
correlativo_recibidos.turnado,
correlativo_recibidos.asunto,
correlativo_recibidos.referencia,
DATE_FORMAT(correlativo_recibidos.fecha,'%d/%m/%Y') as fecha,
correlativo_recibidos.expediente
FROM
correlativo_recibidos
INNER JOIN tipodocumento ON correlativo_recibidos.tipo_documento = tipodocumento.idTipoDocumento 
where 1=1 $where $filtro_anio
AND correlativo_recibidos.representacion = '$id_representacion' order by $orden $lista_query $limit");
	?>
     <style>
        span.highlighted {
   background-color: yellow;
	}
        </style>
        <table>
        <tr>
        <td>
		<div id="toolbar-box">
        	<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
   			
			
            	<div class="m">
                <div class="clr"></div>
				<div class="toolbar" id="toolbar">
					  	<table class="toolbar">
							<tr>
                                 <td class="button" id="toolbar-new"> 
								<a href="index.php?add_correlativo_recibidos&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span> 
								Agregar - Recibidos
								</a> 
								</td> 
                                
								
                                
                               
							</tr>
						</table>
					 </div>
               
                      <div class="header galeria">
                        Administrador de correspondencia recibida</div>
				      <div class="clr"></div>
				</div>
                
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
  		</div>
        
           <div id="element-box">
                    <div class="t">
                        <div class="t">
        
                            <div class="t"></div>
                        </div>
                    </div>
                <div class="m">
        <form name="form_list_correlativo_recibidos" action="index.php" method="get">
		<table >
       
			<tr>
			  <td colspan='4'>
              Pages -
				<?PHP  for($i=1; $i <= $max_pg; $i++){
					$pagina = $page/$paginacion + 1;
					if($pagina == $i){ 
					$estilo = "style='font-weight:bold; font-size:18px; color:#A21636;'";
					}
					
					else $estilo = "style='color:black;'";
					echo "  <a href='index.php?admin_correlativo_recibidos&busca=$busca&page=$i&orden=$orden&lista=$lista&anio=$res_anio' $estilo >$i</a> -";
				}?>
                </td>
                </tr>
                <tr>
                	<td> Años: 
					<?php 
					
						$anios = run_select_query("Select distinct YEAR(fecha) as anios from correlativo_recibidos order by fecha DESC");
						
						foreach($anios as $key => $value){
							$res_anio = $anios[$key]['anios'];
							if($anio == $res_anio){ $estilo_anio = "style='font-weight:bold; font-size:18px; color:#A21636;'";}
							else $estilo_anio = "style='color:black'";
							
							
							echo "<a href='index.php?admin_correlativo_recibidos&busca=$busca&page=1&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
                <tr>
                <td>
					 <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_recibidos" value="Go" id="admin_correlativo_recibidos" class="button super_tiny"/>
					 </span> </td>
				  </tr>
                   
                   
          </table>
          
          
          
        
          
          
          
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
              <th class="title">Correlativo</a></th>
              <th class="title">Fecha</th>
              <th class="title">Tipo de documento</th>
              <th class="title">Documento</th>                           
              <th class="title">Procedencia</th>              
			 <th class="title">Turnado</th>              
			 <th class="title">Asunto</th>
              <th class="title">Referencia</th>
               <th class="title">Expediente</th>
               	</thead>
		  </tr>
			
		<?php
		if($correlativo_salida)
		foreach($correlativo_salida as $key => $value) {
		$id = $correlativo_salida[$key]['id'];
		$consecutivo = $correlativo_salida[$key]['correlativo'];	
		$fecha = $correlativo_salida[$key]['fecha'];	
		$tipo_documento = $correlativo_salida[$key]['tipo_documento'];	
		$documento = $correlativo_salida[$key]['documento'];	
		$documento = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$documento);
        $procedencia = $correlativo_salida[$key]['procedencia'];
		$procedencia = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$procedencia);
        $turnado = $correlativo_salida[$key]['turnado'];
		$asunto = $correlativo_salida[$key]['asunto'];	
		$asunto = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$asunto);
        $referencia = $correlativo_salida[$key]['referencia'];
		$referencia = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$referencia);
        $expediente = $correlativo_salida[$key]['expediente'];	
		
		
			
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
			?>
			
			<tr class="row<?php echo $renglon; ?>" >
				<td class="center" nowrap="nowrap">	
					<a href='index.php?edit_correlativo_recibidos&id=<?php echo $id; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <?php if(is_admin()) { ?><a href='index.php?del_correlativo_recibidos&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/del.png" alt="Eliminar" title="Eliminar"></a>
                    <?php } ?>				</td>
				
					<td class="center">
					<strong>
					<div id='correlativo_<?php echo $id?>'><a href='javascript:guarda_recibidos_consecutivo("<?php echo $id ?>");'><?php echo $consecutivo; ?></a></div>
                    </strong>
					
					</td>		
					
					<td class="center">
					<strong>
					<div id='fecha_<?php echo $id?>'><a href='javascript:guarda_recibidos_fecha("<?php echo $id ?>");'><?php echo $fecha; ?></a></div>
                    </strong>
					
					</td>		
					<td>
                    <?php echo $tipo_documento; ?>
					</td>		
                    <td>
					<strong>
					<div id='documento_<?php echo $id?>'><a href='javascript:guarda_recibidos_documento("<?php echo $id ?>");'>
					<?php 
					if($documento == "") echo "agregar";
					else echo $documento; 
					?>
					
					</a></div>
                    </strong>
					</td>
                    <td>
					<strong>
					<div id='procedencia_<?php echo $id?>'><a href='javascript:guarda_recibidos_procedencia("<?php echo $id ?>");'>
					<?php 
					if($procedencia == "") echo "agregar";
					else echo $procedencia; 
					?>
					
					</a></div>
                    </strong>
					</td>		
                    <td>
                    <?php echo $turnado; ?>
					
					</td>		
                    <td>
					<strong>
					<div id='asunto_<?php echo $id?>'><a href='javascript:guarda_recibidos_asunto("<?php echo $id ?>");'>
					<?php 
					if($asunto == "") echo "agregar";
					else echo $asunto; 
					?>
					</a></div>
                    </strong>
					</td>	
                    <td>
					<strong>
					<div id='referencia_<?php echo $id?>'><a href='javascript:guarda_recibidos_referencia("<?php echo $id ?>");'>
					<?php 
					if($referencia == "") echo "agregar";
					else echo $referencia; 
					?>
					</a></div>
                    </strong></td>
                    <td>
					<strong>
					<div id='expediente_<?php echo $id?>'><a href='javascript:guarda_recibidos_expediente("<?php echo $id ?>");'>
					<?php 
					if($expediente == "") echo "agregar";
					else echo $expediente; 
					?>
					</a></div>
                    </strong>
					</td>						
					
			</tr>
			
			
			<?php
		} // -foreach usuarios
		?>
		</table>
		</form>
		   <div class="clr"></div>
			</div>
			<div class="b">
				<div class="b">

					<div class="b"></div>
				</div>
			</div>
		</div>
        
        </div>
        </td></tr></table>
		<?php
	}
	
	// Muestra la forma para agregar un usuario
	function add_correlativo_recibidos_form($busca, $page) {
	
		?>
 		 
		<fieldset class="adminform">
        
		<legend>Agregar Recibidos</legend>
    
				
<form action="index.php" name="form_add_recibidos" method="post">
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
				<th class='title'>Fecha:</th>
				<td >	
					<input type="text" name="fecha" value="" id="fecha" />	
                    <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_add_recibidos',
		// input name
		'controlname': 'fecha'
	});
	</script>			</td>
			</tr>
             <tr>
				<th class='title'>Documento:</th>
				<td >	
					<input type="text" name="documento" value="" id="documento" />				</td>
			</tr>
             <tr>
				<th class='title'>Procedencia:</th>
				<td ><input type="text" name="procedencia" value="" id="procedencia" /></td>
			</tr>
             
			<tr>
				<th class='title'>Turnado: </th>
				<td>
                <input type="text"  value="" id="turnado" name="turnado" autocomplete="off"/>
           
                </td>
				
			</tr>
          
          
            <tr>
				<th class='title'>
				  Asunto: </th>
				<td colspan="5"><input type="text" name="asunto" value="" id="asunto" /></td>
			</tr>
            <tr>
				<th class='title'>
				  Referencia: </th>
				<td colspan="5"><input type="text" name="referencia" value="" id="referencia" /></td>
			</tr>
            <tr>
				<th class='title'>
				  Expediente: </th>
				<td colspan="5"><input type="text" name="expediente" value="" id="expediente" /></td>
			</tr>
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
				<input class="button" type="submit" name="do_add_correlativo_recibidos" value="Agregar" id="btn_add_correlativo_salida" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	
	// Muestra la forma para editar un usuario
	function edit_correlativo_recibidos_form($id, $busca, $page, $orden, $lista) {
		$correlativo_salida = run_select_query("SELECT *,
		correlativo_recibidos.tipo_documento, 
		correlativo_recibidos.documento,
		correlativo_recibidos.procedencia,
        correlativo_recibidos.fecha,
		correlativo_recibidos.turnado as turnado,
		correlativo_recibidos.turnado as id_turnado,
		correlativo_recibidos.asunto,
		correlativo_recibidos.referencia,
		correlativo_recibidos.expediente
		 FROM correlativo_recibidos 
		WHERE correlativo_recibidos.id = '$id'");
        
      
		
		$id_tipo_documento = $correlativo_salida[0]['tipo_documento'];
		$nombre_documento = run_select_query("Select TipoDocumento from tipodocumento where idTipoDocumento = '$id_tipo_documento'");
		$nombre_documento = $nombre_documento[0][0];
		?>

		
<fieldset class="adminform">
		<legend>Editar Recibidos </legend>
  
				
		<form action="index.php" name="form_edit_recibidos" method="post" enctype="multipart/form-data">
  <table class="adminform">
			
			<tr>
				<th class='title'>Tipo de Documento:</th>
				<td><?php 
					$documento = run_select_query("Select * from tipodocumento order by TipoDocumento");
					foreach($documento as $key => $value){
					$id_documento = $documento[$key]['idTipoDocumento'];
					$tipo_documento = $documento[$key]['TipoDocumento'];
				
					$select_tipo_documento.= "<option value='$id_documento'>$tipo_documento</option>";	
						
						}
					
					
					?>
                    <select name="tipo_documento" id="tipo_documento">
                    <option value="<?php echo $id_tipo_documento ?>" selected="selected"><?php echo $nombre_documento ?></option>
					<?php echo $select_tipo_documento; ?>                    
                    </select>	
                
					
   			  </td>
			</tr>
             <tr>
				<th class='title'>Fecha:</th>
				<td >	
					<input type="text" name="fecha" value="<?php echo $correlativo_salida[0]['fecha'] ?>" id="fecha" size="40"/>		
                    <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_edit_recibidos',
		// input name
		'controlname': 'fecha'
	});
	</script>			</td>
			</tr>
             <tr>
				<th class='title'>Documento: </th>
				<td colspan="5">	
					<input type="text" name="documento" value="<?php echo $correlativo_salida[0]['documento'] ?>" id="documento" size="40"/>				</td>
		</tr>
        <tr>
				<th class='title'>Procedencia: </th>
				<td colspan="5"><input type="text" name="procedencia" value="<?php echo $correlativo_salida[0]['procedencia'] ?>" id="procedencia" size="40"/></td>
	  </tr>         
			<tr>
				<th class='title'>Turnado: </th>
				<td>
            <input type="text" size="40" value="<?php echo $correlativo_salida[0]['turnado'] ?>" id="turnado" name="turnado" />
           
                </td>
				
			</tr>
           <tr>
				<th class='title'>Asunto: </th>
				<td colspan="5">	
					<input type="text" name="asunto" value="<?php echo $correlativo_salida[0]['asunto'] ?>" id="asunto" size="40"/>				    </td>
		</tr>     
        <tr>
				<th class='title'>Referencia: </th>
				<td colspan="5">	
					<input type="text" name="referencia" value="<?php echo $correlativo_salida[0]['referencia'] ?>" id="referencia" size="40"/>				    </td>
		</tr>     
         <tr>
				<th class='title'>Expediente: </th>
				<td colspan="5">	
					<input type="text" name="expediente" value="<?php echo $correlativo_salida[0]['expediente'] ?>" id="expediente" size="40"/>				    </td>
		</tr>     
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $id ?>" id="id" />
                
					<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
					<input class="button" type="submit" name="do_edit_correlativo_recibidos" value="Editar" id="btn_edit_correlativo_salida" />	</td>
			</tr>
		</table>
		
		
		
		
		
</form>

		   </fieldset>
	
		<?php
	}




// Agrega el usuario a la BD
		function do_add_correlativo_recibidos($tipo_documento, $fecha, $documento, $procedencia, $turnado, $asunto, $referencia, $expediente, $busca, $page, $orden, $lista) {
	
			$destino = addslashes($destino);
			$asunto = addslashes($asunto);
			$referencia = addslashes($referencia);
			$expediente = addslashes($expediente);
			$texto_sicar = addslashes($texto_sicar);
			$id_funcionario = get_cookie_id_user();
			if($fecha == "")	$fecha = date('Y-m-d');
            $correlativo = dame_recibidos_siguiente();
			$id_representacion = get_cookie_representacion();
		
			
			
			
			
			run_non_query("INSERT INTO correlativo_recibidos  VALUES (null, '$correlativo','$tipo_documento','$documento','$procedencia','$turnado','$asunto','$referencia','$fecha','$expediente', '$id_representacion')");
		
			?>
			<p class='highlight'>
			Comunicación recibida agregada </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_recibidos"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_recibidos($busca, $page, $orden, $lista);
	}
	
	
	// Edita el usuario en BD
	function do_edit_correlativo_recibidos($tipo_documento, $fecha, $documento, $procedencia, $turnado, $asunto, $referencia, $expediente, $id, $busca, $page, $orden, $lista) {
		
	run_non_query("UPDATE correlativo_recibidos SET tipo_documento = '$tipo_documento', fecha = '$fecha', documento = '$documento', procedencia = '$procedencia', turnado = '$turnado', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente' WHERE id = '$id'");
	
	//echo "UPDATE correlativo_recibidos SET tipo_documento = '$tipo_documento', documento = '$documento', procedencia = '$procedencia', turnado = '$turnado', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente' WHERE id = '$id'";

		?>
		<p class='highlight'>
		Comunicación recibida editada </p>
		<?php
		
		list_correlativo_recibidos($busca,$page,$orden, $lista);
	}
	
	// Borra el usuario de BD
	function del_correlativo_recibidos($id,$page) {
		run_non_query("DELETE FROM correlativo_recibidos WHERE id = $id");
		?>
	  <p class='highlight'>
		Comunicación recibida eliminada </p>
		<?php
		list_correlativo_recibidos($busca,$page,$orden, $lista);
	}
	
	
	
	// Reinicia el Correlativo
	function reiniciar_correlativo() {
		$fecha = date('Y-m-d H:i:s');
        echo $fecha;
        $id_usuario = get_cookie_id_user();
        $id_representacion = get_cookie_representacion();
    
        run_non_query("INSERT INTO log_reinicio_correlativo VALUES ('','$id_usuario','$fecha')");
        //run_non_query("INSERT INTO correlativo VALUES (null, 0,0,'','','','', 0, '$fecha', '', '')");
 		run_non_query("INSERT INTO correlativo_representaciones VALUES (null, 0,0,'','','','', '$id_usuario','$id_representacion','$fecha', '', '')");
        echo "INSERT INTO correlativo_representaciones VALUES (null, 0,0,'','','','', '$id_usuario','$id_representacion','$fecha', '', '')";
 
     	?>
	  <p class='highlight'>
		Numeración reiniciada correctamente </p>
		<?php
		list_correlativo($busca,$page,$orden, $lista);
	}
    
    // Muestra la forma para agregar un usuario
	function add_correlativo_archivo($id,$busca, $page, $orden, $lista, $anio) {
		?>
 		 
		<fieldset class="adminform">
        
		<legend>Agregar Archivo</legend>
    
				
<form action="index.php" name="form_add_periodo" method="post" enctype="multipart/form-data">
		<table class="adminform">
             
			<tr>
				<th class='title'>Archivo: </th>
				<td><input type="file" name="archivo" value="" id="archivo" />				</td>
				
			</tr>

			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $id; ?>" id="id" />
                <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
				<input class="button" type="submit" name="do_add_correlativo_archivo" value="Agregar" id="btn_add_correlativo_salida" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
    
    // Agrega el usuario a la BD
		function do_add_correlativo_archivo($id,$archivo,$busca, $page, $orden, $lista, $anio) {
			//Subimos archivo a carpeta de protocolo
			$ruta = '/var/www/html/gua/archivos/correlativo/';
            
            
            $id_representacion = get_cookie_representacion();
        	$determinante = run_select_query("Select siglas from representaciones where id = '$id_representacion'");
        	$determinante = strtoupper($determinante[0]['siglas']);
        	$correlativo = run_select_query("Select correlativo from correlativo where id = '$id'");
            $correlativo = $correlativo[0]['correlativo'];
            $longitud = strlen($correlativo);
        
			if ($longitud == "1")$gua=$determinante."0000".$correlativo;
			else if ($longitud == "2") $gua=$determinante."000".$correlativo;
			else if ($longitud == "3") $gua=$determinante."00".$correlativo;
			else if ($longitud == "4") $gua=$determinante."0".$correlativo; 
            else $gua=$determinante."-";
            
				if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
						sube_archivo_correlativo($archivo, $ruta, $gua);
						//$n_archivo = date('Y')."_".basename($archivo['name']);
                        $n_archivo = $gua."_".date('Y').".pdf";
                        //echo $n_archivo2;
                        
				}
                
			run_non_query("UPDATE correlativo SET archivo = '$n_archivo' WHERE id = '$id'");
           // echo "UPDATE correlativo SET archivo = '$n_archivo' WHERE id = '$id'";
            
			?>
			<p class='highlight'>
			Archivo agregado </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_recibidos"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	
?>