<?php
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_contactos($sector, $subcategoria, $busca, $page, $orden, $lista) {
		//echo "sector:$sector<br>subcategoria:$subcategoria<br>busca:$busca<br>page:$page<br>orden:$orden<br>lista:$lista<br>";
		$paginacion = 50; 
		if($page == "" || $page == 0) $page = '0';
		else {
			
			$page = $page - 1; 
			$page = $page * $paginacion;
		} 
		
		if($busca != '' && $busca != 'Buscar'){ 
		$where .= "AND directorio.nombre RLIKE '$busca' OR directorio.institucion RLIKE '$busca'"; $limit = "LIMIT 0,$paginacion"; 
        $page = 0;}
        else {$limit = "LIMIT $page, $paginacion";
        $max_pg = run_select_query("SELECT COUNT(directorio.id) AS id FROM 
directorio
INNER JOIN directorio_categorias ON directorio_categorias.id = directorio.sector
INNER JOIN directorio_subcategorias ON directorio_subcategorias.id = directorio.sub_categoria
INNER JOIN usuarios ON directorio.usuario = usuarios.id where (1 = 1) $where $filtro_sector");
       	$max_pg = $max_pg[0]['id']; 
        $max_pg /= $paginacion;
        $max_pg = ceil($max_pg);
		}
		
		//Filtros
		if($sector != ""){
			if($sector != "todos"){
			$filtro_sector = "AND sector = '$sector'";
			} else $filtro_sector = "";
			$q_subcategorias = run_select_query("SELECT id, nombre FROM directorio_subcategorias  where id_padre = '$sector' order by nombre");
			//echo "SELECT id, nombre FROM directorio_subcategorias  where id_padre = '$sector' order by nombre<br>";
			if($q_subcategorias)
			$lista_subcategoria = "";
			foreach ($q_subcategorias as $key => $value){
				$id = $q_subcategorias[$key]['id'];
				if($subcategoria == $id) $selected_subcategoria = "selected='selected'"; else $selected_subcategoria = "";
				$nombre = $q_subcategorias[$key]['nombre'];
				$lista_subcategoria.="<option value='$id' $selected_subcategoria>$nombre</option>";
				} 
		}
			else $filtro_sector = "";
		if($subcategoria != "" && $sector != "todos") $filtro_subcategoria = "AND sub_categoria = '$subcategoria'"; else $filtro_subcategoria = "";
		
		//SORTEAR ASCENDENTE O DESCENDENTE
		if($lista == "") $lista_query = "ASC";
		else if ($lista == "ASC") $lista_query = "DESC"; 
		else if ($lista == "DESC") $lista_query = "ASC"; 
		
		//SORTEAR POR CAMPO
		if($orden == "") $orden = "directorio.nombre";
		
		//if($busca != '' && $busca != 'Buscar contacto'){ $where .= " AND nombre Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
		//$max_pg = run_select_query("SELECT COUNT(id) AS id FROM directorio where (1 = 1) $where $filtro_sector $filtro_subcategoria");
		//echo "SELECT COUNT(id) AS id FROM directorio where (1 = 1) $where $filtro_sector $filtro_subcategoria<br>";
		//$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
		$contacto = run_select_query("SELECT directorio.id,
directorio.nombre as nombre_contacto,
directorio.titulo as titulo,
directorio.cargo as cargo,
directorio.institucion as institucion,
directorio.direccion as direccion,
directorio.telefono as telefono,
directorio.correo as correo,
directorio.fecha_actualizacion as fecha_actualizacion,
directorio_categorias.nombre as sector,
directorio_subcategorias.nombre as subcategoria,
directorio.fiesta_nacional as fiesta_nacional,
usuarios.nombre as usuario
FROM
directorio
INNER JOIN directorio_categorias ON directorio_categorias.id = directorio.sector
INNER JOIN directorio_subcategorias ON directorio_subcategorias.id = directorio.sub_categoria
INNER JOIN usuarios ON directorio.usuario = usuarios.id where (1 = 1) $where $filtro_sector $filtro_subcategoria order by $orden $lista_query $limit");


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
								<a href="index.php?add_contacto&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span>
								<img src="images/agregar_contacto.png" alt="Agregar contacto" title="Agregar contacto"> 
								Agregar - contacto
								</a> 
								</td> 
                              
							</tr>
						</table>
				  </div>
               
                      <div class="header galeria">
                        Administrador de contactos</div>
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
        <form name="form_list_contacto" action="index.php?admin_contactos" method="get">
		<table width="100%">
			<tr>
			  <td colspan='8'>
              Pages -
				<?PHP  for($i=1; $i <= $max_pg; $i++){
					$pagina = $page/$paginacion + 1;
					if($pagina == $i){ 
					$estilo = "style='font-weight:bold; font-size:18px; color:#A21636;'";
					}
					
					else $estilo = "style='color:black;'";
					echo "  <a href='index.php?admin_contactos&sector=$sector&subcateogira=$subcategoria&busca=$busca&page=$i&orden=$orden&lista=$lista' $estilo >$i</a> -";
				}?>
					 
					 </td> 
                     <td colspan="4"><a href="lib/contactos/exportar_contactos.php?sector=<?php echo $sector ?>&subcategoria=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio_categorias.nombre&lista=<?php echo $lista_query ?>" target="_blank"><img src="images/xls.png" border="0" width="30"/>EXPORTAR A EXCEL</a></td>
                     <td colspan="4"><a href="lib/contactos/exportar_contactos.php?sector=<?php echo $sector ?>&subcategoria=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio_categorias.nombre&lista=<?php echo $lista_query ?>&fiesta=SI" target="_blank"><img src="images/xls.png" border="0" width="30"/>EXPORTAR A EXCEL FIESTA NACIONAL</a></td>
                    
			  </tr>
              <tr>
              	<td>
                <? 
				$sectores = run_select_query("SELECT id, nombre FROM directorio_categorias order by nombre");
		if($sectores)
		$lista_sectores = "";
		foreach ($sectores as $key => $value){
			$id = $sectores[$key]['id'];
			if($sector == $id) $selected = "selected='selected'"; else $selected = "";
			$nombre = $sectores[$key]['nombre'];
			$lista_sectores.="<option value='$id' $selected>$nombre</option>";
		}
		
	
				?>
                    <b>Filtros de búsqueda:</b><br />
                    <b>Sector:</b><select  name="sector" id="sector" onChange="form_list_contacto.submit();" >
                    <option value="todos">TODOS</option>
                     <?php echo $lista_sectores ?>
                    </select><br/>
                	<b>Subcategoría:</b>
                    <select name="subcategoria" id="subcategoria" onChange="form_list_contacto.submit();" >
				<option value=""></option>
				<?php echo $lista_subcategoria ?>
			    </select></td>
              </tr><tr>
                <td>
					 Búsqueda: 
			           <input type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar') echo $busca;  ?>"  />
					 <span>
					 <input type="submit" value="Buscar"/>
                     <a href='index.php?sector=todos&subcategoria=&admin_contactos=admin_contactos'>Limpiar búsqueda</a>
                     <input type="hidden" name="page" value="<?php echo $page ?>"/>
					 </span> </td>
				  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
		      <thead>
              <th  class="title">Opciones</th>
              <th  class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcateogira=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio_categorias.nombre&lista=<?php echo $lista_query ?>">Sector</a></th>
              <th  class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcategoria=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio_subcategorias.nombre&lista=<?php echo $lista_query ?>">Subcategoría</a></th>
              
              <th class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcateogira=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio.titulo&lista=<?php echo $lista_query ?>"><span class="highlight">Título</span></a></th>
              
			  <th class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcateogira=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio.nombre&lista=<?php echo $lista_query ?>"><span class="highlight">Nombre</span></span></a></th>
			  
              <th class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcateogira=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio.cargo&lista=<?php echo $lista_query ?>"><span class="highlight">Cargo</span></a></th>
              <th class="title"><a href="index.php?admin_contactos&sector=<?php echo $sector ?>&subcateogira=<?php echo $subcategoria ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=directorio.institucion&lista=<?php echo $lista_query ?>"><span class="highlight">Institución</span></a></th>
              <th class="title">Teléfono</th>
              <th class="title">Correo</th>
              <th class="title"><span class="highlight">Dirección</span></th>
              <th class="title">Fiesta Nacional</th>
               
                	</thead>
		  </tr>
			
		<?php
		    	
				
		
		if($contacto)
		foreach($contacto as $key => $value) {
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
				$id = $contacto[$key]['id'];
				$sector = $contacto[$key]['sector'];
				$nombre_contacto = $contacto[$key]['nombre_contacto'];
				$nombre_contacto = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$nombre_contacto);
				$titulo = $contacto[$key]['titulo'];
				$cargo = $contacto[$key]['cargo'];
				$institucion = $contacto[$key]['institucion'];
				$institucion = str_ireplace($busca,"<span class='highlighted'>$busca</span>",$institucion);
				$direccion = $contacto[$key]['direccion'];
				$telefono = $contacto[$key]['telefono'];
				$correo = $contacto[$key]['correo'];
				$subcategoria = $contacto[$key]['subcategoria'];
				$fecha_actualizacion = $contacto[$key]['fecha_actualizacion'];
				$usuario = $contacto[$key]['usuario'];
				$fiesta_nacional = $contacto[$key]['fiesta_nacional'];
				
				if($fiesta_nacional == "SI"){
					$value_fiesta = "SI";
					$check = "checked";
				} 
				else{
					$value_fiesta = "NO";
					$check = "";
					}
				
		    	
			?>
			
			<tr class="row<?php echo $renglon; ?>">
            <td >
               <?php echo $fecha_actualizacion ?> / <?php echo $usuario ?><br> 
               	<a href='index.php?edit_contacto&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Edit'><img src="images/editar.png" alt="Editar" title="Editar"></a> | 
                   <a href='index.php?del_contacto&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Delete' onClick="return confirm('Está seguro de eliminar a: <?php echo $nombre?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a></td>
				
                    <td><?php echo $sector ?></td>
                    <td><?php echo $subcategoria ?></td>
                   <td><?php echo $titulo ?></td>
                    <td><?php echo $nombre_contacto ?></td>
                    <td><?php echo $cargo ?></td>
                    <td><?php echo $institucion ?></td>
                    <td><?php echo $telefono ?></td>
                    <td><?php echo $correo ?></td>
                    <td><?php echo $direccion ?></td>
                    <td>
                    <div>
	                <input type="checkbox"  onclick="javascript:actualiza_fiesta(<?php echo $id; ?>);" <?php echo $check; ?>/>                    
                        </div>	
                    </td>
			</tr>
			
			
			<?php
		} // -foreach usuarios
		?>
		</table>
        <input type="hidden" name="admin_contactos" value="admin_contactos"  />
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
	function add_contacto_form($busca, $page, $orden, $lista, $idioma) {
	
	
		$sectores = run_select_query("SELECT id, nombre FROM directorio_categorias order by nombre");
		//echo "SELECT id, nombre FROM directorio_categorias order by nombre";
		
		if($sectores)
		$lista_sectores = "";
		foreach ($sectores as $key => $value){
			$id = $sectores[$key]['id'];
			$nombre = $sectores[$key]['nombre'];
			$lista_sectores.="<option value='$id'>$nombre</option>";
		}
		
		$titulos = run_select_query("SELECT titulo FROM directorio_titulo order by titulo");
		
		if($titulos)
		$lista_titulos = "";
		foreach ($titulos as $key => $value){
			$titulo = $titulos[$key]['titulo'];
			$lista_titulos.="<option value='$titulo'>$titulo</option>";
		}
		
		
		?>
     <script>
     $().ready(function() {
		// validate the comment form when it is submitted
		$("#FormaContacto").validate();
	 });
     </script>
		<fieldset class="adminform">
         
		<legend><a href='index.php?admin_contactos&busca=&page=<?php echo $page ?>' title='Back'> <img src="images/agregar_contacto.png" border="0" /></a></legend>
		<legend>Agregar contacto  </legend>
		<form action="index.php" name="form_add_contacto" method="post" id="FormaContacto">
		<table class="adminform">
			
			<tr>
				<th class='title'>
					Sector: </th>
				<td>
                <select onChange="subcategorias()" name="sector" id="sector" required>
                   <option value="" selected="selected" ></option>
				  <?php echo $lista_sectores ?>
                </select>
                </td>
			</tr>
            <tr>
				<th class='title'>Subcategoría: </th>
				<td><select name="subcategoria" id="subcategoria" required>
				
			    </select></td>
                   
			</tr>     
            <tr>
				<th class='title'>
					<span class="highlight">Nombre:</span> </th>
				<td>	
                	<input type="text" name="nombre" value="" id="nombre" required/>				</td>
			</tr>
			<tr>
				<th class='title '>
					<span class="highlight">Título:</span> </th>
				<td>
                <select name="titulo" id="titulo" required>
                   <option value="" selected="selected"><?php echo $lista_titulos ?></option>   			  
                </select>
              </td>
			</tr>
            <tr>
				<th class='title '><span class="highlight">Cargo:</span></th>
				<td><input type="text" name="cargo" value="" id="cargo" required/></td>
			</tr>
			<tr>
				<th class='title '><span class="highlight">Institución:</span></th>
				<td><input type="text" name="institucion" value="" id="institucion" required/></td>
			</tr>
             <tr>
				<th class='title '><span class="highlight">Dirección:</span></th>
				<td><textarea name="direccion" id="direccion" required></textarea></td>
			</tr>
			<tr>
				<th class='title'>Teléfono: </th>
				<td><input type="text" name="telefono" value="" id="telefono" required/>
				
             </td>
			</tr>
              <tr>
				<th class='title'>
					Correo: </th>
				<td><input type="text" name="correo" value="" id="correo" required/></td>
			</tr>     
			  <tr>
				<th class='title'>Fiesta nacional: </th>
				<td><select name="fiesta_nacional" id="fiesta_nacional" required>
				  <option name="NO">NO</option>
                  <option name="SI">SI</option>
			    </select></td>
                   
			</tr>     
			<tr>
				<td colspan='2' class='center'>
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
     
           	<input class="button" type="submit" name="do_add_contacto" value="Agregar" id="btn_add_contacto" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para editar un usuario
	function edit_contacto_form($id_contacto, $busca, $page, $orden, $lista) {
		$contacto = run_select_query("SELECT * FROM directorio WHERE id = '$id_contacto'");
	//	echo "SELECT * FROM contactos WHERE id = '$id'";
		$sector = $contacto[0]['sector'];
		$nombre1 = $contacto[0]['nombre'];
		$titulo = $contacto[0]['titulo'];
		$cargo = $contacto[0]['cargo'];
		$institucion = $contacto[0]['institucion'];
		$direccion = $contacto[0]['direccion'];
		$telefono = $contacto[0]['telefono'];
		$correo = $contacto[0]['correo'];
		$subcategoria = $contacto[0]['sub_categoria'];
		$fiesta_nacional = $contacto[0]['fiesta_nacional'];
		
		if($fiesta_nacional == "SI") $selected_si = "selected='selected'";
		else $selected_si = "";
		if($fiesta_nacional == "NO") $selected_no = "selected='selected'";
		else $selected_no = "";
		
		
		$sectores = run_select_query("SELECT id, nombre FROM directorio_categorias order by nombre");
		
		$n_subcategoria = run_select_query("Select nombre from directorio_subcategorias where id = '$subcategoria'");
		$nombre_subcategoria  = $n_subcategoria[0]['nombre'];
		
		
		if($sectores)
		$lista_sectores = $selected_sector = "";
		foreach ($sectores as $key => $value){
			
			$id = $sectores[$key]['id'];
			if($id == $sector) $selected_sector = "selected='selected'";
			else $selected_sector = "";
			$nombre = $sectores[$key]['nombre'];
			$lista_sectores.="<option value='$id' $selected_sector>$nombre</option>";
		}
		
		$titulos = run_select_query("SELECT titulo FROM directorio_titulo order by titulo");
		
		if($titulos)
		$lista_titulos = "";
		foreach ($titulos as $key => $value){
			$titulo = $titulos[$key]['titulo'];
			$lista_titulos.="<option value='$titulo'>$titulo</option>";
		}
		
		?>

		<script>
     $().ready(function() {
		// validate the comment form when it is submitted
		$("#FormaContactoEditar").validate();
	 });
     </script>
<fieldset class="adminform">
        
		<legend>Editar contacto  <a href='index.php?admin_contactos&busca=&page=<?php echo $page ?>' title='Back'><img src="images/j_arrow_left.png" border="0" /></a></legend>
  
				
		<form action="index.php" id="FormaContactoEditar" name="form_edit_contacto" method="post" enctype="multipart/form-data">
		<table class="adminform">
			
			<tr>
				<th class='title'>
					Sector: </th>
				<td>
                <select name="sector" id="sector" onChange="subcategorias()" required>
                   <option name="<?php echo $sector ?>" selected="selected"></option>
				  <?php echo $lista_sectores ?>
                </select>
                </td>
			</tr>
            <tr>
				<th class='title'>Subcategoría: </th>
				<td><select name="subcategoria" id="subcategoria" required>
				<option value="<?php echo $subcategoria ?>" selected="selected"><?php echo $nombre_subcategoria ?></option>
			    </select> </td>
                   
			</tr>     
            <tr>
				<th class='title highlight'>
					Nombre: </th>
				<td>	
					<input type="text" name="nombre" value="<?php echo $nombre1 ?>" id="nombre" required/>				</td>
			</tr>
			<tr>
				<th class='title highlight'>
					Título: </th>
				<td><select name="titulo" id="titulo" required>
				  <option value="<?php echo $titulo ?>" selected="selected"></option>
                  <?php echo $lista_titulos ?>
                  </select></td>
			</tr>
            <tr>
				<th class='title highlight'>Cargo:</th>
				<td><input type="text" name="cargo" value="<?php echo $cargo ?>" id="cargo" required/></td>
			</tr>
			<tr>
				<th class='title highlight'>Institución:</th>
				<td><input type="text" name="institucion" value="<?php echo $institucion ?>" id="institucion" required/></td>
			</tr>
             <tr>
				<th class='title highlight'>Dirección:</th>
				<td><textarea name="direccion" id="direccion" required><?php echo $direccion ?></textarea></td>
			</tr>
			<tr>
				<th class='title'>Teléfono: </th>
				<td><input type="text" name="telefono" value="<?php echo $telefono ?>" id="telefono" required/>
				
             </td>
			</tr>
              <tr>
				<th class='title'>
					Correo: </th>
				<td><input type="text" name="correo" value="<?php echo $correo ?>" id="correo" required/></td>
			</tr>     
            <tr>
				<th class='title'>Fiesta nacional: </th>
				<td><select name="fiesta_nacional" id="fiesta_nacional" required>
				 <option name="SI" <?php echo $selected_si; ?>>SI</option>
                  <option name="NO" <?php echo $selected_no; ?>>NO</option>
			    </select></td>
                   
			</tr>     
			 
			<tr>
				<td colspan='2' class='center'>
					<input type="hidden" name="id" value="<?php echo $id_contacto; ?>" id="id" />
                    <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
     
           	<input class="button" type="submit" name="do_edit_contacto" value="Editar" id="btn_add_contacto" /></td>
			</tr>
		</table>
    
</form>
</fieldset>
	
		<?php
	}
	
	
	// Agrega el usuario a la BD
		function do_add_contacto($sector, $nombre, $titulo, $cargo, $institucion, $direccion, $telefono, $correo, $subcategoria, $fiesta_nacional, $busca, $page, $orden, $lista) {
		
		$mensaje = "";
		$fecha_actualizacion = date('Y-m-d');	
		$usuario = get_cookie_id_user();
		if($nombre != "") $mensaje = revisa_contacto_duplicado($nombre, $correo, $institucion);
		
		if($mensaje == ""){
			run_non_query("INSERT INTO directorio VALUES (null, '$sector','$nombre','$titulo', '$cargo', '$institucion', '$direccion', '$telefono', '$correo', '$subcategoria','$fecha_actualizacion','$usuario','$fiesta_nacional')");
		}
		else echo $mensaje;	
		?>
			
			<p class='highlight bold right'>
				Contacto agregado
			</p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM directorio"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_contactos($sector,$subcategoria,$busca, $page, $orden, $lista);
	}
	
	
	
	// Edita el usuario en BD
	function do_edit_contacto($sector, $nombre, $titulo, $cargo, $institucion, $direccion, $telefono, $correo, $subcategoria, $fiesta_nacional, $id, $busca, $page, $orden, $lista) {
		
		
	
	$fecha_actualizacion = date('Y-m-d');	
	$usuario = get_cookie_id_user();
		
	run_non_query("UPDATE directorio SET 
	sector = '$sector',
	nombre = '$nombre', 
	titulo = '$titulo', 
	cargo = '$cargo', 
	institucion = '$institucion',
	direccion = '$direccion', 
	telefono = '$telefono', 
	correo = '$correo', 
	sub_categoria = '$subcategoria',
	fecha_actualizacion = '$fecha_actualizacion',
	usuario = '$usuario',
	fiesta_nacional = '$fiesta_nacional'
	WHERE id = '$id'");
		
	/*echo "UPDATE directorio SET 
	sector = '$sector', 
	nombre = '$nombre', 
	titulo = '$titulo', 
	cargo = '$cargo', 
	institucion = '$institucion',
	direccion = '$direccion', 
	telefono = '$telefono', 
	correo = '$correo', 
	sub_categoria = '$subcategoria',
	fecha_actualizacion = '$fecha_actualizacion',
	usuario = '$usuario'
	WHERE id = '$id'";*/
	
	?>
		<p class='highlight bold right'>
			Contacto editado
		</p>
	<?php
		
		list_contactos($sector,$subcategoria, $busca, $page, $orden, $lista);
	}
	
	// Borra el contacto de BD
	function del_contacto($id, $busca, $page, $orden, $lista) {
		$usuario = get_cookie_id_user();
		$nivel = get_cookie_nivel();
		$usuario_registrado = run_select_query("Select directorio.usuario as id_usuario, usuarios.nombre as usuario from directorio INNER JOIN usuarios ON directorio.usuario = usuarios.id where directorio.id = '$id'");
		$usuario_registrado_id = $usuario_registrado[0]['id_usuario'];
		$usuario_registrado_nombre = $usuario_registrado[0]['usuario'];
		if($usuario_registrado_id == $usuario || $nivel == "Administrador"){
			run_non_query("DELETE FROM directorio WHERE id = '$id'");	
			echo "<p class='highlight bold right big'>
					Contacto eliminado
					</p>";
		}
		else echo "<h3>No cuenta con privilegios para eliminar un contacto de $usuario_registrado_nombre</h3>";
		
		?>
		
		
		<?php
		list_contactos($sector,$subcategoria, $busca, $page, $orden, $lista);
	}
	
	function revisa_contacto_duplicado($nombre, $correo, $institucion){
		$contacto = run_select_query("SELECT directorio.id,
directorio.nombre as nombre_contacto,
directorio.titulo as titulo,
directorio.cargo as cargo,
directorio.institucion as institucion,
directorio.direccion as direccion,
directorio.telefono as telefono,
directorio.correo as correo,
directorio.fecha_actualizacion as fecha_actualizacion,
directorio_categorias.nombre as sector,
directorio_subcategorias.nombre as subcategoria,
usuarios.nombre as usuario
FROM
directorio
INNER JOIN directorio_categorias ON directorio_categorias.id = directorio.sector
INNER JOIN directorio_subcategorias ON directorio_subcategorias.id = directorio.sub_categoria
INNER JOIN usuarios ON directorio.usuario = usuarios.id where (directorio.nombre LIKE '%$nombre%' AND directorio.institucion = '$institucion') OR directorio.correo = '$correo'");

		
		if($contacto){
		$mensaje = "<h1>El contacto no fue ingresado por posible(s) registro duplicado</h1><ul>";
		foreach($contacto as $key => $value){
		$nombre_contacto = $contacto[$key]['nombre_contacto']; 
		$usuario = $contacto[$key]['usuario']; 
		$fecha_actualizacion = $contacto[$key]['fecha_actualizacion']; 
		$sector = $contacto[$key]['sector']; 
		$subcategoria = $contacto[$key]['subcategoria']; 
		$titulo = $contacto[$key]['titulo']; 
		$cargo = $contacto[$key]['cargo']; 
		$institucion = $contacto[$key]['institucion']; 
		$correo = $contacto[$key]['correo']; 
		
		$mensaje.= "
		<li>
		<h4><b>Contacto previamente registrado por <u><span style='background-color: yellow;'>$usuario</span></u> el $fecha_actualizacion, bajo el nombre de <span style='background-color: yellow;'>$nombre_contacto</span> con los siguientes datos:<br/>
		Sector: $sector<br/>
		Subcategoría: $subcategoria<br/>
		Título: $titulo<br/>
		Cargo: $cargo<br/>
		Institucion: <span style='background-color: yellow;'>$institucion</span><br/>
		Correo: <span style='background-color: yellow;'>$correo</span><br/>
		</b>
		</h4>
		</li><br/>
		";
		}
		$mensaje.="</ul><h2>En caso de que se tratara de otra persona, para ingresar el registro comunicate a la extensión 3600 de TI.</h2>";
		} else $mensaje = "";
		
		return $mensaje; 

	}
	
	
?>
