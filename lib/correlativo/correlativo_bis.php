<?php
	// ABC de correlativo
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_bis($busca, $page, $orden, $lista, $anio) {
    	$paginacion = 50; 
		$anio_actual = date("Y");
		if($page == '' || $page == 0) $page = '0';
		else {
			$page = $page - 1; 
			$page = $page * $paginacion;
		} 
		$limit = "LIMIT $page, $paginacion";
		
		//SORTEAR ASCENDENTE O DESCENDENTE
		if($lista == "") $lista_query = "DESC";
		else if ($lista == "ASC") $lista_query = "DESC"; 
		else if ($lista == "DESC") $lista_query = "ASC";
		
		//SORTEAR POR CAMPO
		if($orden == ""){ 
		$orden="correlativo";
		}
		
		$id_usuario = get_cookie_id_user();
		
		//Filtro para buscar en años anteriores, si no hay variable busca en año actual
		if($anio == ""){$filtro_anio = "AND YEAR(correlativo_bis.fecha) = YEAR(CURDATE())"; $anio = date('Y');}
		else{$filtro_anio = "AND YEAR(correlativo_bis.fecha) = '$anio'";}
		
		//Filtro para mostrar solamente los del usuario si es user y todos si es admin
		if(is_admin() || is_comunicaciones() || is_protocolo()) $filtro_usuario = "1=1";
		else if(is_user()) $filtro_usuario = "usuario = '$id_usuario'";
		
		if($busca != '' && $busca != 'Buscar'){ $where .= " AND correlativo RLIKE '$busca' OR destino Rlike '$busca' OR referencia Rlike '$busca' OR expediente Rlike '$busca' OR asunto Rlike '$busca' OR texto_sicar Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
		$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_bis  where $filtro_usuario  $filtro_anio $where ");
		$max_pg = $max_pg[0]['id']; 
        $max_pg /= $paginacion;
        $max_pg = ceil($max_pg);
		
        $correlativo_bis = run_select_query("SELECT
correlativo_bis.id,
correlativo_bis.correlativo,
correlativo_bis.asunto,
correlativo_bis.referencia,
correlativo_bis.expediente,
DATE_FORMAT(correlativo_bis.fecha,'%d/%m/%Y') as fecha,
correlativo_bis.texto_sicar,
correlativo_bis.archivo,
correlativo_bis.destino,
tipodocumento.TipoDocumento as tipo_documento,
usuarios.nombre as funcionario
FROM
correlativo_bis
INNER JOIN tipodocumento ON correlativo_bis.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN usuarios ON correlativo_bis.usuario = usuarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit");
echo "SELECT
correlativo_bis.id,
correlativo_bis.correlativo,
correlativo_bis.asunto,
correlativo_bis.referencia,
correlativo_bis.expediente,
DATE_FORMAT(correlativo_bis.fecha,'%d/%m/%Y') as fecha,
correlativo_bis.texto_sicar,
correlativo_bis.archivo,
correlativo_bis.destino,
tipodocumento.TipoDocumento as tipo_documento,
usuarios.nombre as funcionario
FROM
correlativo_bis
INNER JOIN tipodocumento ON correlativo_bis.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN usuarios ON correlativo_bis.usuario = usuarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit";

		?>
       <div id="toolbar-box">
             	<div class="m">
           			<div class="toolbar" id="toolbar">
					  	<table class="toolbar">
							<tr>
                                 <td class="button" id="toolbar-new"> 
								<a href="index.php?add_correlativo_bis&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
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
        <form name="form_list_correlativo_bis" action="index.php" method="get">
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
					echo "  <a href='index.php?admin_correlativo_bis&page=$i&anio=$anio' $estilo >$i</a> -";
				}?>
                </td>
                </tr>
                <tr>
                	<td> Años: 
					<?php 
					
						$anios = run_select_query("Select distinct YEAR(fecha) as anios from correlativo order by fecha DESC");
						
						foreach($anios as $key => $value){
							$res_anio = $anios[$key]['anios'];
							if($anio == $res_anio){ $estilo_anio = "style='font-weight:bold; font-size:18px; color:#A21636;'";}
							else $estilo_anio = "style='color:black'";
							
							
							echo "<a href='index.php?admin_correlativo_bis&busca=$busca&page=1&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
                <tr>
                <td>
					 Búsqueda palabra: <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_bis" value="Go" id="admin_correlativo_bis" class="button super_tiny"/>
					 </span> </td>
				  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
			  <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=correlativo&lista=&anio=<?php echo $anio ?>">Correlativo</a></th>
			  <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=fecha&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Fecha</a></th>
              <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=tipo_documento&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Tipo de documento</a></th>
              <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=destino&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Destino</a></th>                           
              <th class="title">Referencia</th>              
			 <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=expediente&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Expediente</a></th>              
			 <th class="title">Asunto</th>
              <th class="title"><a href="index.php?admin_correlativo_bis&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuario&lista=<?php echo $lista_query ?>&anio=<?php echo $anio ?>">Usuario</a></th>
               <th class="title">Texto SICAR</th>
               <th class="title">Archivo</th>
               	</thead>
		  </tr>
			
		<?php
		if($correlativo_bis)
		foreach($correlativo_bis as $key => $value) {
		$id_correlativo = $correlativo_bis[$key]['id'];	
		$correlativo = $correlativo_bis[$key]['correlativo'];
		$archivo = $correlativo_bis[$key]['archivo'];
		$destino = $correlativo_bis[$key]['destino'];	
		$asunto = $correlativo_bis[$key]['asunto'];	
		$referencia = $correlativo_bis[$key]['referencia'];
		$tipo_documento = $correlativo_bis[$key]['tipo_documento'];	
		$funcionario = $correlativo_bis[$key]['funcionario'];
		$expediente = $correlativo_bis[$key]['expediente'];	
		$fecha = $correlativo_bis[$key]['fecha'];
      	
		$longitud = strlen($correlativo);
        
			if ($longitud == "1")$gua="GUA0000".$correlativo;
			else if ($longitud == "2") $gua="GUA000".$correlativo;
			else if ($longitud == "3") $gua="GUA00".$correlativo;
			else if ($longitud == "4") $gua="GUA0".$correlativo; 
            else $gua = "GUA-".$correlativo;
		
			
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
			?>
			
			<tr class="row<?php echo $renglon; ?>" >
				<td class="center" nowrap="nowrap">	
					<a href='index.php?edit_correlativo_bis&id=<?php echo $id_correlativo; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <?php if (is_admin()) { ?><a href='index.php?del_correlativo_bis&id=<?php echo $id_correlativo ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>&anio=<?php echo $anio ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a> <?php } ?>				</td>
				
					<td ><strong>
					<?php echo $gua;  ?>-BIS
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
					else echo "<a href='archivos/correlativo/$archivo' target='_blank'>$archivo</a>"; 
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
	function add_correlativo_bis_form($busca, $page, $orden, $lista, $anio) {
	$anio_actual = date("Y");
		?>
 		 
		<fieldset class="adminform">
        
		<legend>Agregar Correlativo</legend>
    
				
<form action="index.php" name="form_add_correlativo_bis" method="post">
		<table class="adminform">
			<tr>
            	<th class="title">Correlativo: </th>
                <td colspan="5"><input type="text" name="correlativo" id="correlativo"></td>
            </tr>
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
					<input type="text" name="fecha" value="" id="fecha" /><script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_add_correlativo_bis',
		// input name
		'controlname': 'fecha'
	});
	</script>				</td>
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
				<th class='title'>Usuario:</th>
				<td ><select name="tipo_documento" id="tipo_documento">
                    <?php echo $select_usuarios; ?>                    
                    </select>	</td>
			</tr>
             
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="" id="referencia" />				</td>
				
			</tr>
          
          
            <tr>
				<th class='title'>
				  Expediente: </th>
				<td colspan="5"><input type="text" name="expediente" value="" id="expediente" /> 
				<a href="sicar2016.htm" target="_blank">Consultar catálogo SICAR</a> <?php echo $anio; ?></td>
			</tr>
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
				<input class="button" type="submit" name="do_add_correlativo_bis" value="Agregar" id="btn_add_correlativo_bis" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para editar un usuario
	function edit_correlativo_bis_form($id,$busca, $page, $orden, $lista, $anio) {
		$correlativo_bis = run_select_query("SELECT * FROM correlativo_bis WHERE id = '$id'");
		$id_tipo_documento = $correlativo_bis[0]['tipo_documento'];
		$nombre_documento = run_select_query("Select TipoDocumento from tipodocumento where idTipoDocumento = '$id_tipo_documento'");
		$nombre_documento = $nombre_documento[0][0];
		?>

		
<fieldset class="adminform">
		<legend>Editar Correlativo </legend>
  
				
		<form action="index.php" name="form_edit_correlativo_bis" method="post" enctype="multipart/form-data">
  <table class="adminform">
			<tr>
            	<th class="title">Correlativo: </th>
                <td colspan="5"><input type="text" name="correlativo" id="correlativo" value="<?php  echo $correlativo_bis[0]['correlativo'] ?>"></td>
            </tr>
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
					<input type="text" name="destino" value="<?php echo $correlativo_bis[0]['destino'] ?>" id="periodo" size="40"/>				</td>
		</tr>
        <tr>
				<th class='title'>Asunto: </th>
				<td colspan="5"><textarea name="asunto" id="asunto"><?php echo $correlativo_bis[0]['asunto'] ?></textarea></td>
			</tr>         
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="<?php echo $correlativo_bis[0]['referencia'] ?>"  size="40"/>				</td>
				
			</tr>
           <tr>
				<th class='title'>Expediente: </th>
				<td colspan="5">	
					<input type="text" name="expediente" value="<?php echo $correlativo_bis[0]['expediente'] ?>" id="periodo" size="40"/>				    </td>
		</tr>     
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $correlativo_bis[0]['id']; ?>" id="id" />
                
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                    <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
                    
					<input class="button" type="submit" name="do_edit_correlativo_bis" value="Editar" id="btn_edit_correlativo_bis" />	</td>
			</tr>
		</table>
		
		
		
		
		
</form>
		   </fieldset>
	
		<?php
	}
	
	
	// Agrega el usuario a la BD
		function do_add_correlativo_bis($correlativo, $tipo_documento, $destino, $asunto, $referencia, $expediente, $id_funcionario, $fecha, $texto_sicar, $archivo, $busca, $page, $orden, $lista, $anio) {
	
			$destino = addslashes($destino);
			$asunto = addslashes($asunto);
			$referencia = addslashes($referencia);
			$expediente = addslashes($expediente);
			$texto_sicar = addslashes($texto_sicar);
			$id_funcionario = get_cookie_id_user();
			//$fecha = date('Y-m-d');
			//$correlativo = dame_correlativo_siguiente();
			
			run_non_query("INSERT INTO correlativo_bis  VALUES (null, $correlativo,'$tipo_documento','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$fecha', '$texto_sicar', '$archivo')");
			echo "INSERT INTO correlativo_bis  VALUES (null, $correlativo,'$tipo_documento','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$fecha', '$texto_sicar', '$archivo')";
			
			?>
			<p class='highlight'>
			Correlativo Agregado </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_bis"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_bis($busca, $page);
	}
	
	
	// Edita el usuario en BD
	function do_edit_correlativo_bis($correlativo, $tipo_documento, $destino, $asunto, $referencia, $expediente, $texto_sicar, $archivo,$id, $busca, $page, $orden, $lista, $anio) {
		
	run_non_query("UPDATE correlativo_bis SET correlativo = '$correlativo', tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente', archivo = '$archivo' WHERE id = '$id'");
	echo "UPDATE correlativo_bis SET correlativo = '$correlativo', tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente', archivo = '$archivo' WHERE id = '$id'";

		?>
		<p class='highlight'>
		Correlativo Editado </p>
		<?php
		
		list_correlativo_bis($busca, $page, $orden, $lista, $anio);
	}
	
	// Borra el usuario de BD
	function del_correlativo_bis($id,$busca, $page, $orden, $lista, $anio) {
		run_non_query("DELETE FROM correlativo_bis WHERE id = $id");
        echo "DELETE FROM correlativo_bis WHERE id = $id";
		?>
	  <p class='highlight'>
		Correlativo Eliminado </p>
		<?php
		list_correlativo_bis($busca, $page, $orden, $lista, $anio);
	}
	
	

	
?>