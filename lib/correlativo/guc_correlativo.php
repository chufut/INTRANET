<?php
	$determinante = "guc_";
	
	$tabla_correlativo = "guc_correlativo";
	$tabla_tipodocumento = "guc_tipodocumento";
	$tabla_correlativo_recibidos = "guc_correlativo_recibidos";
	$tabla_usuarios = "guc_usuarios";

	// ABC de usuarios
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_salida($busca, $page, $orden, $lista, $anio) {
	global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
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
		if($anio == ""){$filtro_anio = "AND YEAR($tabla_correlativo.fecha) = YEAR(CURDATE())"; 
		$anio = date('Y');}
		else{$filtro_anio = "AND YEAR($tabla_correlativo.fecha) = '$anio'";}
		
		if(is_admin()) $filtro_usuario = "(1=1)";
		else if(is_user()) $filtro_usuario = "usuario = '$id_usuario'";
		
		if($busca != '' && $busca != 'Buscar'){ $where .= " AND correlativo Rlike '$busca' OR tipo_documento Rlike '$busca' OR destino Rlike '$busca' OR referencia Rlike '$busca' OR expediente Rlike '$busca' OR asunto Rlike '$busca' OR texto_sicar Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
		$max_pg = run_select_query("SELECT COUNT(id) AS id FROM $tabla_correlativo  where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit");
		echo "SELECT COUNT(id) AS id FROM $tabla_correlativo  where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit";
		
		
		$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
		$correlativo_salida = run_select_query("SELECT
$tabla_correlativo.id,
$tabla_correlativo.correlativo,
$tabla_correlativo.asunto,
$tabla_correlativo.referencia,
$tabla_correlativo.expediente,
DATE_FORMAT($tabla_correlativo.fecha,'%d/%m/%Y') as fecha,
$tabla_correlativo.texto_sicar,
$tabla_correlativo.archivo,
$tabla_correlativo.destino,
$tabla_tipodocumento.TipoDocumento as tipo_documento,
$tabla_usuarios.nombre as funcionario
FROM
$tabla_correlativo
INNER JOIN $tabla_tipodocumento ON $tabla_correlativo.tipo_documento = $tabla_tipodocumento.idTipoDocumento
INNER JOIN $tabla_usuarios ON $tabla_correlativo.usuario = $tabla_usuarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit");

echo "SELECT
$tabla_correlativo.id,
$tabla_correlativo.correlativo,
$tabla_correlativo.asunto,
$tabla_correlativo.referencia,
$tabla_correlativo.expediente,
DATE_FORMAT($tabla_correlativo.fecha,'%d/%m/%Y') as fecha,
$tabla_correlativo.texto_sicar,
$tabla_correlativo.archivo,
$tabla_correlativo.destino,
$tabla_tipodocumento.TipoDocumento as tipo_documento,
$tabla_usuarios.nombre as funcionario
FROM
$tabla_correlativo
INNER JOIN $tabla_tipodocumento ON $tabla_correlativo.tipo_documento = $tabla_tipodocumento.idTipoDocumento
INNER JOIN $tabla_usuarios ON $tabla_correlativo.usuario = $tabla_usuarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit";




		?>
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
								<a href="index.php?add_correlativo_salida&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span> 
								Agregar - Correlativo
								</a> 
								</td> 
                                
								
                                
                               
							</tr>
						</table>
					 </div>
               
                      <div class="header galeria">
                        Administrador de Correlativo</div>
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
        <form name="form_list_correlativo_salida" action="index.php" method="get">
		<table >
			<tr>
			  <td colspan='4'>
              Páginas: - 
				<?PHP  for($i=1; $i <= $max_pg; $i++){
					$pagina = $page/$max_pg + 1;
					if($pagina == $i){ 
					$estilo = "style='font-weight:bold; font-size:18px; color:#0066CC'";
					}
					
					else $estilo = "style='color:#0000'";
					echo "  <a href='index.php?admin_correlativo_salida&page=$i' $estilo >$i</a> -";
				}?>
                </td>
                </tr>
                 <tr>
                	<td> Años: 
					<?php 
					
						$anios = run_select_query("Select distinct YEAR(fecha) as anios from $tabla_correlativo order by fecha DESC");
						
						foreach($anios as $key => $value){
							$res_anio = $anios[$key]['anios'];
							if($anio == $res_anio){ $estilo_anio = "style='font-weight:bold; font-size:18px; color:#A21636;'";}
							else $estilo_anio = "style='color:black'";
							
							
							echo "<a href='index.php?admin_correlativo_salida&busca=$busca&page=$page&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
                <tr>
                <td>
					 Búsqueda palabra: <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_salida" value="Go" id="admin_correlativo_salida" class="button super_tiny"/>
					 </span> </td>
				  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
			  <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=correlativo&lista=<?php echo $lista_query ?>&">Correlativo</a></th>
			  <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=fecha&lista=<?php echo $lista_query ?>&">Fecha</a></th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=tipo_documento&lista=<?php echo $lista_query ?>&">Tipo de documento</a></th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=destino&lista=<?php echo $lista_query ?>&">Destino</a></th>                           
              <th class="title">Referencia</th>              
			 <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=expediente&lista=<?php echo $lista_query ?>&">Expediente</a></th>              
			 <th class="title">Asunto</th>
              <th class="title"><a href="index.php?admin_correlativo_salida&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuario&lista=<?php echo $lista_query ?>&">Usuario</a></th>
               <th class="title">Texto SICAR</th>
               	</thead>
		  </tr>
			
		<?php
		if($correlativo_salida)
		foreach($correlativo_salida as $key => $value) {
		$id_correlativo = $correlativo_salida[$key]['id'];	
		$correlativo = $correlativo_salida[$key]['correlativo'];
		$destino = $correlativo_salida[$key]['destino'];	
		$asunto = $correlativo_salida[$key]['asunto'];	
		$referencia = $correlativo_salida[$key]['referencia'];
		$tipo_documento = $correlativo_salida[$key]['tipo_documento'];	
		$funcionario = $correlativo_salida[$key]['funcionario'];
		$expediente = $correlativo_salida[$key]['expediente'];	
		$fecha = $correlativo_salida[$key]['fecha'];		
		
		$longitud = strlen($correlativo);
			if ($longitud == "1")$gua="GUC0000".$correlativo;
			else if ($longitud == "2") $gua="GUC000".$correlativo;
			else if ($longitud == "3") $gua="GUC00".$correlativo;
			else if ($longitud == "4") $gua="GUC0".$correlativo; 
		
			
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
			?>
			
			<tr class="row<?php echo $renglon; ?>" >
				<td class="center" nowrap="nowrap">	
					<a href='index.php?edit_correlativo_salida&id=<?php echo $id_correlativo; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <a href='index.php?del_correlativo_salida&id=<?php echo $id_correlativo ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/del.png" alt="Eliminar" title="Eliminar"></a>				</td>
				
					<td ><strong>
					<?php echo $gua; ?>
                        </strong>
                    </td>
					<td>
					<?php 
					if( $fecha == " ") $texto_fecha = "fecha";
					else $texto_fecha = $fecha;
					?>
					<div id='fecha_<?php echo $id_correlativo?>'>
                    <a href='javascript:guarda_correlativo_fecha("<?php echo $id_correlativo ?>");'><?php echo $texto_fecha; ?></a></div>
                  
					
					</td>		
					<td>
					<strong>
					<div id='tipo_documento_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_tipo_documento("<?php echo $id_correlativo ?>");'><?php 
					if($tipo_documento == "") echo "agregar";
					else echo $tipo_documento; 
					?></a></div>
                    </strong>
					</td>		
                    <td>
					<strong>
					<div id='destino_<?php echo $id_correlativo?>'><a href='javascript:guarda_correlativo_destino("<?php echo $id_correlativo ?>");'><?php 
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
        </td></tr></table>
		<?php
	}
	
	
	
	// Muestra la forma para agregar un usuario
	function add_correlativo_salida_form($busca, $page, $orden, $lista, $anio) {
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	
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
					$documento = run_select_query("Select * from $tabla_tipodocumento order by TipoDocumento");
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
					<input type="text" name="destino" value="" id="destino" /></td>
			</tr>
             <tr>
				<th class='title'>Asunto:</th>
				<td ><input type="text" name="asunto" value="" id="asunto" /></td>
			</tr>
             
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="" id="referencia" /></td>
				
			</tr>
          
          
            <tr>
				<th class='title'>
				  Expediente: </th>
				<td colspan="5"><input type="text" name="expediente" value="" id="expediente" /></td>
			</tr>
			<tr>
				<td colspan='2' class='center'>
				
                <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                <input type="hidden" name="orden" value="<?php echo $page; ?>" id="orden" />
                <input type="hidden" name="lista" value="<?php echo $page; ?>" id="lista" />
                <input type="hidden" name="anio" value="<?php echo $page; ?>" id="anio" />
                
				<input class="button" type="submit" name="do_add_correlativo_salida" value="Agregar" id="btn_add_correlativo_salida" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para editar un usuario
	function edit_correlativo_salida_form($id,$busca, $page, $orden, $lista, $anio) {
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
    
		$correlativo_salida = run_select_query("SELECT * from $tabla_correlativo WHERE id = '$id'");
		$id_tipo_documento = $correlativo_salida[0]['tipo_documento'];
		$nombre_documento = run_select_query("Select TipoDocumento from $tabla_tipodocumento where idTipoDocumento = '$id_tipo_documento'");
		$nombre_documento = $nombre_documento[0][0];
		?>

		
<fieldset class="adminform">
		<legend>Editar Correlativo </legend>
  
				
		<form action="index.php" name="form_edit_correlativo_salida" method="post" enctype="multipart/form-data">
  <table class="adminform">
			
			<tr>
				<th class='title'>Tipo de Documento:</th>
				<td><?php 
					$documento = run_select_query("Select * from $tabla_tipodocumento order by TipoDocumento");
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
				<td colspan="5"><input type="text" name="asunto" id="asunto" value="<?php echo $correlativo_salida[0]['asunto'] ?>" size="40"></td>
			</tr>         
			<tr>
				<th class='title'>Referencia: </th>
				<td><input type="text" name="referencia" value="<?php echo $correlativo_salida[0]['referencia'] ?>"  size="40"/>				</td>
				
			</tr>
           <tr>
				<th class='title'>Expediente: </th>
				<td colspan="5">	
					<input type="text" name="expediente" value="<?php echo $correlativo_salida[0]['expediente'] ?>" id="periodo" size="40"/>				    </td>
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
		function do_add_correlativo_salida($tipo_documento, $destino, $asunto, $referencia, $expediente, $id_funcionario, $fecha, $texto_sicar, $archivo, $busca, $page, $orden, $lista, $anio) {
        
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	
			$destino = addslashes($destino);
			$asunto = addslashes($asunto);
			$referencia = addslashes($referencia);
			$expediente = addslashes($expediente);
			$texto_sicar = addslashes($texto_sicar);
			$id_funcionario = get_cookie_id_user();
            echo $id_funcionario; 
            
			$fecha = date('Y-m-d');
			$correlativo = dame_correlativo_siguiente();
			
			
			
			
			
			run_non_query("INSERT INTO $tabla_correlativo VALUES (null, $correlativo,'$tipo_documento','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$fecha', '$texto_sicar', '$archivo')");
			echo "INSERT INTO $tabla_correlativo VALUES (null, $correlativo,  '$gua','$destino','$asunto','$referencia','$expediente', '$id_funcionario', '$fecha', '$texto_sicar', '$archivo')";
			
			?>
			<p class='highlight'>
			Correlativo Agregado </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM $tabla_correlativo "); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	
	// Edita el usuario en BD
	function do_edit_correlativo_salida($tipo_documento, $destino, $asunto, $referencia, $expediente, $texto_sicar, $archivo,$id, $busca, $page, $orden, $lista, $anio) {
    
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
		
	run_non_query("UPDATE $tabla_correlativo SET tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente', archivo = '$archivo' WHERE id = '$id'");
	echo "UPDATE $tabla_correlativo SET tipo_documento = '$tipo_documento', destino = '$destino', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente', archivo = '$archivo' WHERE id = '$id'";

		?>
		<p class='highlight'>
		Correlativo Editado </p>
		<?php
		
		list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	// Borra el usuario de BD
	function del_correlativo_salida($id,$busca, $page, $orden, $lista, $anio) {
    
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
		run_non_query("DELETE from $tabla_correlativo WHERE id = $id");
		?>
	  <p class='highlight'>
		Correlativo Eliminado </p>
		<?php
		list_correlativo_salida($busca, $page, $orden, $lista, $anio);
	}
	
	
	function dame_correlativo_siguiente(){
    
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	$correlativo = run_select_query("Select correlativo from $tabla_correlativo where id=(select MAX(id) from $tabla_correlativo)");
	return $correlativo[0][0]+1;	

	}
	
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_correlativo_recibidos($busca, $page, $orden, $lista, $anio) {
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
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
		$orden="correlativo_recibidos.id";
		}
		
		$id_usuario = get_cookie_id_user();
		//Filtro para buscar en años anteriores, si no hay variable busca en año actual
		if($anio == ""){$filtro_anio = "AND YEAR(tabla_correlativo_recibidos.fecha) = YEAR(CURDATE())"; $anio = date('Y');}
		else{$filtro_anio = "AND YEAR(correlativo.fecha) = '$anio'";}
		
		if(is_admin()) $filtro_usuario = "(1=1)";
		else if(is_user()) $filtro_usuario = "correlativo_recibidos.turnado = '$id_usuario'";
		
		if($busca != '' && $busca != 'Buscar'){ $where .= " AND tipo_documento Rlike '$busca' OR documento Rlike '$busca' OR procedencia Rlike '$busca' OR asunto Rlike '$busca' OR referencia Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
		$max_pg = run_select_query("SELECT COUNT(id) AS id from $tabla_correlativo_recibidos  where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit");
		echo "SELECT COUNT(id) AS id FROM correlativo_recibidos  where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit";
		$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
		$correlativo_salida = run_select_query("SELECT
correlativo_recibidos.id as id,		
tipodocumento.TipoDocumento as tipo_documento,
correlativo_recibidos.documento,
correlativo_recibidos.procedencia,
funcionarios.nombre as turnado,
correlativo_recibidos.asunto,
correlativo_recibidos.referencia,
DATE_FORMAT(correlativo_recibidos.fecha,'%d/%m/%Y') as fecha,
correlativo_recibidos.expediente
FROM
guc_correlativo_recibidos
INNER JOIN tipodocumento ON correlativo_recibidos.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN funcionarios ON correlativo_recibidos.turnado = funcionarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit");

echo "SELECT
tipodocumento.TipoDocumento as tipo_documento,
correlativo_recibidos.documento,
correlativo_recibidos.procedencia,
usuarios.nombre as turnado,
correlativo_recibidos.asunto,
correlativo_recibidos.referencia,
DATE_FORMAT(correlativo_recibidos.fecha,'%d/%m/%Y') as fecha,
correlativo_recibidos.expediente
FROM
guc_correlativo_recibidos
INNER JOIN tipodocumento ON correlativo_recibidos.tipo_documento = tipodocumento.idTipoDocumento
INNER JOIN usuarios ON correlativo_recibidos.turnado = usuarios.id where $filtro_usuario $filtro_anio $where order by $orden $lista_query $limit";
	?>
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
        <form name="form_list_correlativo_salida" action="index.php" method="get">
		<table >
			<tr>
			  <td colspan='4'>
              Pages -
				<?PHP  for($i=1; $i <= $max_pg; $i++){
					$pagina = $page/$paginacion + 1;
					if($pagina == $i){ 
					$estilo = "style='color:#0066CC'";
					}
					
					else $estilo = "style='color:#0000'";
					echo "  <a href='index.php?admin_correlativo_salida&page=$i' $estilo >$i</a> -";
				}?>
					 <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca; else echo "Buscar"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_correlativo_salida" value="Go" id="admin_funcionarios" class="button super_tiny"/>
					 </span> </td>
				  </tr>
                   <tr>
                	<td> Años: 
					<?php 
					
						$anios = run_select_query("Select distinct YEAR(fecha) as anios from $tabla_correlativo_recibidos order by fecha DESC");
						
						foreach($anios as $key => $value){
							$res_anio = $anios[$key]['anios'];
							if($anio == $res_anio){ $estilo_anio = "style='font-weight:bold; font-size:18px; color:#A21636;'";}
							else $estilo_anio = "style='color:black'";
							
							
							echo "<a href='index.php?admin_correlativo_salida&busca=$busca&page=$page&orden=$orden&lista=$lista&anio=$res_anio' $estilo_anio>$res_anio</a> -";
							}
					?>
                    
                    </td>
                </tr>
          </table>
		
            <table class="adminlist">
			<tr>
			   <thead>
              <th  class="title">Opciones&nbsp;</th>
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
		$fecha = $correlativo_salida[$key]['fecha'];	
		$tipo_documento = $correlativo_salida[$key]['tipo_documento'];	
		$documento = $correlativo_salida[$key]['documento'];	
		$procedencia = $correlativo_salida[$key]['procedencia'];
		$turnado = $correlativo_salida[$key]['turnado'];
		$asunto = $correlativo_salida[$key]['asunto'];	
		$referencia = $correlativo_salida[$key]['referencia'];
		$expediente = $correlativo_salida[$key]['expediente'];	
		
		
			
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
			?>
			
			<tr class="row<?php echo $renglon; ?>" >
				<td class="center" nowrap="nowrap">	
					<a href='index.php?edit_correlativo_recibidos&id=<?php echo $id; ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Edit'><img src="images/tool.png" alt="Editar" title="Editar"></a> | <a href='index.php?del_correlativo_recibidos&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>' title='Delete' onClick="return confirm('Está seguro de eliminar <?php echo $correlativo?>?')"><img src="images/del.png" alt="Eliminar" title="Eliminar"></a>				</td>
				
					
					<td class="center">
					<strong>
					<div id='fecha2_<?php echo $id?>'><a href='javascript:guarda_recibidos_fecha("<?php echo $id ?>");'><?php echo $fecha; ?></a></div>
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
					<div id='referencia2_<?php echo $id?>'><a href='javascript:guarda_recibidos_referencia("<?php echo $id ?>");'>
					<?php 
					if($referencia == "") echo "agregar";
					else echo $referencia; 
					?>
					</a></div>
                    </strong></td>
                    <td>
					<strong>
					<div id='expediente2_<?php echo $id?>'><a href='javascript:guarda_recibidos_expediente("<?php echo $id ?>");'>
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
	function add_correlativo_recibidos_form($busca, $page, $orden, $lista, $anio) {
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	
		?>
 		 
		<fieldset class="adminform">
        
		<legend>Agregar Recibidos</legend>
    
				
<form action="index.php" name="form_add_periodo" method="post">
		<table class="adminform">
			
            <tr>
				<th class='title'>
					Tipo de Documento: </th>
				<td colspan="5">

                <?php 
					$documento = run_select_query("Select * from $tabla_tipodocumento order by TipoDocumento");
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
                <input type="text" size="60" value="" id="funcionario" name="funcionario" autocomplete="off"/>
            <div id="resultado_turnado" class="autocomplete"></div>
                                <input type="hidden" id="turnado" name="turnado" />
                                <script type="text/javascript">
                                new Ajax.Autocompleter("funcionario","resultado_turnado","lib/protocolo/protocolo_ajax.php?dame_funcionario",{afterUpdateElement : getFuncionarioId});
                                function getFuncionarioId(text, li) {
                                var datos=new Array();
                                var valor=li.id;
                                datos=valor.split(",");
                                $('turnado').value=datos[0];
                                
                                }
                                </script>
            </div>
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
				 <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />	
                 <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
               <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
               <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
               <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
               
				<input class="button" type="submit" name="do_add_correlativo_recibidos" value="Agregar" id="btn_add_correlativo_salida" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	
	// Muestra la forma para editar un usuario
	function edit_correlativo_recibidos_form($id, $busca, $page, $orden, $lista, $anio) {
    
    global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
		$correlativo_salida = run_select_query("SELECT *,
		correlativo_recibidos.tipo_documento, 
		correlativo_recibidos.documento,
		correlativo_recibidos.procedencia,
		funcionarios.nombre as turnado,
		correlativo_recibidos.turnado as id_turnado,
		correlativo_recibidos.asunto,
		correlativo_recibidos.referencia,
		correlativo_recibidos.expediente
		 from $tabla_correlativo_recibidos 
		INNER JOIN funcionarios ON correlativo_recibidos.turnado = funcionarios.id WHERE correlativo_recibidos.id = '$id'");
		
		$id_tipo_documento = $correlativo_salida[0]['tipo_documento'];
		$nombre_documento = run_select_query("Select TipoDocumento from guc_tipodocumento where idTipoDocumento = '$id_tipo_documento'");
		$nombre_documento = $nombre_documento[0][0];
		?>

		
<fieldset class="adminform">
		<legend>Editar Correlativo </legend>
  
				
		<form action="index.php" name="form_edit_correlativo_salida" method="post" enctype="multipart/form-data">
  <table class="adminform">
			
			<tr>
				<th class='title'>Tipo de Documento:</th>
				<td><?php 
					$documento = run_select_query("Select * from guc_tipodocumento order by TipoDocumento");
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
            <input type="text" size="60" value="<?php echo $correlativo_salida[0]['turnado'] ?>" id="funcionario" name="funcionario" autocomplete="off"/>
           
            <div id="resultado_turnado2" class="autocomplete"></div>
                                <input type="hidden" id="turnado" name="turnado" value
                                ="<?php echo $correlativo_salida[0]['id_turnado'] ?>"/>
                                <script type="text/javascript">
                                new Ajax.Autocompleter("funcionario","resultado_turnado2","lib/protocolo/protocolo_ajax.php?dame_funcionario",{afterUpdateElement : getFuncionarioId});
                                function getFuncionarioId(text, li) {
                                var datos=new Array();
                                var valor=li.id;
                                datos=valor.split(",");
                                $('turnado').value=datos[0];
                                
                                }
                                </script>
            </div>
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
					<input type="text" name="expediente" value="<?php echo $correlativo_salida[0]['expediente'] ?>" id="periodo" size="40"/>				    </td>
		</tr>     
			<tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $id ?>" id="id" />
                
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                    <input type="hidden" name="anio" value="<?php echo $anio; ?>" id="anio" />
                    
					<input class="button" type="submit" name="do_edit_correlativo_recibidos" value="Editar" id="btn_edit_correlativo_salida" />	</td>
			</tr>
		</table>
		
		
		
		
		
</form>
		   </fieldset>
	
		<?php
	}




// Agrega el usuario a la BD
		function do_add_correlativo_recibidos($tipo_documento, $documento, $procedencia, $turnado, $asunto, $referencia, $expediente, $busca, $page, $orden, $lista, $anio) {
	
	global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
			$destino = addslashes($destino);
			$asunto = addslashes($asunto);
			$referencia = addslashes($referencia);
			$expediente = addslashes($expediente);
			$texto_sicar = addslashes($texto_sicar);
			$id_funcionario = get_cookie_id_user();
			$fecha = date('Y-m-d');
			
			
			
			
			
			run_non_query("INSERT INTO $tabla_correlativo_recibidos VALUES (null, '$tipo_documento','$documento','$procedencia','$turnado','$asunto','$referencia','$fecha','$expediente')");
			echo "INSERT INTO $tabla_correlativo_recibidos VALUES (null, '$tipo_documento','$documento','$procedencia','$turnado','$asunto','$referencia','$fecha','$expediente')";
			
			?>
			<p class='highlight'>
			Comunicación recibida agregada </p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM correlativo_recibidos"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_correlativo_recibidos($busca, $page, $orden, $lista, $anio);
	}
	
	
	// Edita el usuario en BD
	function do_edit_correlativo_recibidos($tipo_documento, $documento, $procedencia, $turnado, $asunto, $referencia, $expediente, $id, $busca, $page, $orden, $lista, $anio) {
	global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	run_non_query("UPDATE $tabla_correlativo_recibidos SET tipo_documento = '$tipo_documento', documento = '$documento', procedencia = '$procedencia', turnado = '$turnado', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente' WHERE id = '$id'");
	
	echo "UPDATE $tabla_correlativo_recibidos SET tipo_documento = '$tipo_documento', documento = '$documento', procedencia = '$procedencia', turnado = '$turnado', asunto = '$asunto', referencia = '$referencia', expediente = '$expediente' WHERE id = '$id'";

		?>
		<p class='highlight'>
		Comunicación recibida editada </p>
		<?php
		
		list_correlativo_recibidos($busca,$page,$orden, $lista, $anio);
	}
	
	// Borra el usuario de BD
	function del_correlativo_recibidos($id,$busca, $page, $orden, $lista, $anio) {
	global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
		run_non_query("DELETE from $tabla_correlativo_recibidos WHERE id = $id");
		?>
	  <p class='highlight'>
		Comunicación recibida eliminada </p>
		<?php
		list_correlativo_recibidos($busca,$page,$orden, $lista, $anio);
	}
	
	
	
	// Setea la numeración a 0 para un nuevo año. 
	function reiniciar_correlativo() {
	global $tabla_correlativo;
	global $tabla_tipodocumento;
	global $tabla_correlativo_recibidos;
	global $tabla_usuarios;
	
	$fecha = date('Y-m-d');
		
		run_non_query("INSERT INTO $tabla_correlativo VALUES (null, 0,0,'','','','', '', '000-00-00', '', '')");
		
		echo "INSERT INTO $tabla_correlativo VALUES (null, 0,0,'','','','', 0, '$fecha', '', '')";
		?>
	  <p class='highlight'>
		Numeración reiniciada correctamente </p>
		<?php
		list_correlativo($busca,$page,$orden, $lista, $anio);
	}
?>