	<?php
		// ABC de usuarios
		$determinante = "";
		$tabla_usuarios = "usuarios";
		$tabla_funcionarios = "funcionarios";
		
		//Muestra el perfil del usuario
		function show_perfil(){
		global $tabla_usuarios;
		global $tabla_funcionarios;
		
		$id_usuario = get_cookie_id_user();	
		$usuario = run_select_query("Select *, DATE_FORMAT(cumple,'%d/%M/%Y') as cumple from $tabla_usuarios where id = '$id_usuario'");
       
		$nombre_usuario = $usuario[0]['nombre'];	
		$iniciales = $usuario[0]['iniciales'];
		$correo = $usuario[0]['correo'];
		$foto = $usuario[0]['foto'];
		$password = $usuario[0]['password'];
		$extension = $usuario[0]['extension'];
		$telefono = $usuario[0]['telefono'];
		$celular = $usuario[0]['celular'];
		$direccion = $usuario[0]['direccion'];
		$cumple = $usuario[0]['cumple'];
		$id_funcionario = $usuario[0]['id_funcionario'];
		
		$direccion_google = str_replace(" ","+",$direccion);
		
       if($id_funcionario != ""){
		$documentos = run_select_query("Select * from documentos_funcionario where id_funcionario = '$id_funcionario' AND valor != ''");
		$nombre_documento_titular = $valor_titular = $archivo_dependiente = $lista_documentos = "";
		foreach($documentos as $key => $value){
			$nombre_documento_titular = $documentos[$key]['nombre'];
			$valor_titular = $documentos[$key]['valor'];	
			$archivo_dependiente = $documentos[$key]['archivo'];	
			$lista_documentos.="<li><a href='index.php?ver_documento&archivo=$archivo_dependiente&carpeta=protocolo' target='_blank'>$nombre_documento_titular - $valor_titular</a></li>";
		}
		
		$dependientes = run_select_query("Select * from funcionarios where id_padre = '$id_funcionario'");
         if($dependientes)
		foreach($dependientes as $key2 => $value2){
		
		$id_dependiente = $dependientes[$key2]['id'];
		$nombre_dependiente = $dependientes[$key2]['nombre'];	
		$lista_documentos_dependientes.= "<h3>".$nombre_dependiente."</h3>";	
		$documentos_dependientes = run_select_query("Select * from documentos_funcionario where id_funcionario = '$id_dependiente' AND valor != ''");
		$nombre_documento_dependiente = $valor_dependiente = $archivo_dependiente = "";
		foreach($documentos_dependientes as $key3 => $value3){
			$nombre_documento_dependiente = $documentos_dependientes[$key3]['nombre'];
			$valor_dependiente = $documentos_dependientes[$key3]['valor'];	
			$archivo_dependiente = $documentos_dependientes[$key3]['archivo'];	
			$lista_documentos_dependientes.="<li><a href='index.php?ver_documento&archivo=$archivo_dependiente&carpeta=protocolo' target='_blank'>$nombre_documento_dependiente - $valor_dependiente</a></li>";
		}
			
		}
	}
		?>
		<table><tr><td>
<div id="perfil_foto"><img src="archivos/fotos/<?php echo $foto?>" border="0" width="200px"/></div>
		<div id="perfil_datos">
		  <h2>Datos Personales:</h2>
			<p><b>Nombre: </b><? echo $nombre_usuario ?><br />
			  <b>Iniciales:</b> <? echo $iniciales ?><br />
			  <b>Correo: </b><? echo $correo ?><br />
			  <b>Extensión: </b><? echo $extension ?><br />
			  <b>Teléfono: </b><? echo $telefono ?><br />
			  <b>Celular: </b><? echo $celular ?><br />
			  <b>Cumpleaños: </b><? echo $cumple ?><br />
              <b><a href="index.php?edit_perfil">Actualizar mis datos</a></b><br />
		  </p>
        </div>
				        
                        
                        <script src="https://www.bootstrapskins.com/google-maps-authorization.js?id=32197d10-2aa8-6698-b10c-776102633962&c=code-for-google-map&u=1450134687" defer="defer" async="async"></script>
                        
                        
       
       	<div id="perfil_mapa">
		<h2>Dirección:</h2>
		<div style="height:400px;width:600px;max-width:100%;list-style:none; transition: none;"><div id="google-maps-canvas" style="height:100%; width:100%;max-width:100%; "><iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/search?q=<?php echo $direccion_google ?>&key=AIzaSyAN0om9mFmy1QN6Wf54tXAowK4eT0ZUPrU"></iframe></div><a class="code-for-google-map" href="https://www.bootstrapskins.com/themes/gifts" id="grab-map-data">bootstrap gifts themes</a><style>#google-maps-canvas img{max-width:none!important;background:none!important;font-size: inherit;}</style></div><script src="https://www.bootstrapskins.com/google-maps-authorization.js?id=32197d10-2aa8-6698-b10c-776102633962&c=code-for-google-map&u=1450134687" defer="defer" async="async"></script>
		</div>
       
       <div id="perfil_documentos">
		<h2>Documentos:</h2>
		<p><?php echo $lista_documentos; ?></p>
        <p><?php echo $lista_documentos_dependientes;?></p>
		</div>
</td></tr></table>
		
		<?php
		}
		
		
		// Lista los s con opciones para editar, borrar y agregar
		function list_usuarios($busca, $page, $orden, $lista) {
			global $tabla_usuarios;
			
            
			$paginacion = 50; 
			if($page == '') $page = '0';
			else {
				$page = $page - 1; 
				$page = $page * $paginacion;
			} 
			$limit = "LIMIT $page, $paginacion";
			
			//SORTEAR ASCENDENTE O DESCENDENTE
         	if($lista == "") $lista_query = "ASC";
			else if ($lista == "ASC") $lista_query = "DESC"; 
			else if ($lista == "DESC") $lista_query = "ASC"; 
			
			//SORTEAR POR CAMPO
			if($orden == "") $orden = "representaciones.nombre";	
	
			if($busca != '' && $busca != 'Buscar usuario'){ $where .= " AND nombre Rlike '$busca' OR iniciales Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM $tabla_usuarios  $where");
			//echo "SELECT COUNT(id) AS id FROM $tabla_usuarios  $where";
			
			$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
			
            $usuario = run_select_query("SELECT usuarios.id,
usuarios.representacion,
usuarios.departamento,
usuarios.nombre,
usuarios.correo,
usuarios.cumple,
representaciones.nombre as representacion
FROM
$tabla_usuarios
INNER JOIN representaciones ON representaciones.id = usuarios.representacion where (1 = 1) $where  order by $orden $lista_query $limit");      
			
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
									<a href="index.php?add_usuario&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
									<span class="icon-32-new" title="Nuevo"> 
									</span>
									<img src="images/agregar_usuario.png" alt="Agregar usuario" title="Agregar usuario"> 
									Agregar - usuario
									</a> 
									</td> 
								  
								</tr>
							</table>
					  </div>
				   
						  <div class="header galeria">
							Administrador de usuarios</div>
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
			<form name="form_list_usuario" action="index.php" method="get">
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
						echo "  <a href='index.php?admin_usuarios&page=$i' $estilo >$i</a> -";
					}?>
						 <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar usuarios') echo $busca; else echo "Buscar usuarios"; ?>" id="id_busca" tabindex='2' />
						 <span class="highlight small center">
						 <input type="submit" name="admin_usuarios" value="Go" id="admin_usuarios" class="button super_tiny"/>
						 </span> </td> 
						
			  </tr>
			  </table>
			
				<table class="adminlist">
				<tr>
				  <thead>
				  <th  class="title">Opciones</th>
				  <th class="title"><a href="index.php?admin_usuarios&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=representaciones.nombre&lista=<?php echo $lista_query ?>">Representación</a></th>
				  <th class="title"><a href="index.php?admin_usuarios&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuarios.departamento&lista=<?php echo $lista_query ?>">Departamento</a></th>
				  <th class="title"><a href="index.php?admin_usuarios&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuarios.cargo&lista=<?php echo $lista_query ?>">Cargo</a></th>
                  <th class="title"><a href="index.php?admin_usuarios&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=usuarios.nombre&lista=<?php echo $lista_query ?>">Nombre</a></th> 
                  <th class="title">Iniciales</th>
				  <th class="title">Correo </th>
				  <th class="title">Cumpleaños </th>
				 </thead>
			  </tr>
				
			<?php
					
					
			
			if($usuario)
			foreach($usuario as $key => $value) {
			if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
			
					$id = $usuario[$key]['id'];
					$representacion = $usuario[$key]['representacion'];
					$departamento = $usuario[$key]['departamento'];
					$cargo = $usuario[$key]['cargo'];
					$nombre = $usuario[$key]['nombre'];
					$iniciales = $usuario[$key]['iniciales'];
					$correo = $usuario[$key]['correo'];
					$cumple = $usuario[$key]['cumple'];
					
				
					
					
				?>
				
				<tr class="row<?php echo $renglon; ?>">
					<td class="center">
						<a href='index.php?edit_usuarios&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Edit'><img src="images/editar.png" alt="Editar" title="Editar"></a> | 
		    <a href='index.php?del_usuario&id=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Delete' onclick="return confirm('Está seguro de eliminar a: <?php echo $nombre?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a> | <a href="index.php?asigna_permisos&id_usuario=<?php echo $id ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>">Permisos</a>				</td>
						<td><?php echo $representacion ?></td>
                        <td><?php echo $departamento ?></td>
                        <td><?php echo $cargo ?></td>
                        <td><?php echo $nombre ?></td>
						<td><?php echo $iniciales ?></td>
						<td><?php echo $correo ?></td>
						<td><?php echo $cumple ?></td>
					   
				</tr>
				
				
				<?php
			} // -foreach s
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
		
		
		// Muestra la forma para agregar un 
		function add_usuario_form($busca, $page, $orden, $lista, $idioma) {
			
            
            $representaciones = run_select_query("Select id, nombre from representaciones where tipo = 'Embajadas' OR tipo = 'Misiones' order by nombre ASC");
            if($representaciones)
            $renglon_representaciones = "";
            foreach($representaciones as $key => $value){
            
            	$id_representacion = $representaciones[$key]['id'];
            	$nombre_representacion = $representaciones[$key]['nombre'];
                $renglon_representaciones.="<option value='$id_representacion'>$nombre_representacion</option>";
            }
			?>
		 
<fieldset class="adminform">
			 
			<legend><a href='index.php?admin_usuarios&busca=&page=<?php echo $page ?>' title='Back'> <img src="images/flecha_atras.png" border="0" /></a></legend>
			<legend>Agregar usuario  </legend>
			<form action="index.php" name="form_add_usuario" method="post" enctype="multipart/form-data">
			<table class="adminform">
				
				<tr>
				  <th class='title'>
						Representación: </th>
					<td><select name="representacion">
					  <?php echo $renglon_representaciones; ?>
				    </select></td>
				</tr>
                <tr>
				  <th class='title'>
						Departamento: </th>
					<td><select name="departamento">
					  <option selected="selected"></option>
					  <option>Oficina Embajadora</option>
					  <option>Oficina Jefe de Cancillería</option>
					  <option>Asuntos culturales</option>
					  <option>Asuntos jurídicos y derechos humanos</option>
					  <option>Cooperación técnica y científica</option>
					  <option>Asuntos políticos</option>
					  <option>Asuntos económicos</option>
					  <option>Prensa</option>
                      <option>Comunicaciones y archivo</option>
                      <option>Administración</option>
                      <option>Protocolo y TI</option>
				    </select></td>
				</tr>
                <tr>
				  <th class='title'>
						Tipo: </th>
					<td><select name="tipo">
					  <option selected="selected"></option>
					  <option>SEM</option>
					  <option>LOCAL</option>
				    </select></td>
				</tr>
				<tr>
					<th class='title'>
						Nombre: </th>
					<td>	
						<input type="text" name="nombre" value="" id="nombre" />				</td>
				</tr>
                <tr>
					<th class='title'>
						Cargo: </th>
					<td>	
						<input type="text" name="cargo" value="" id="cargo" />				</td>
				</tr>
				<tr>
					<th class='title'>
						Iniciales: </th>
					<td>	
						<input type="text" name="iniciales" value="" id="iniciales" />				</td>
				</tr>
				<tr>
					<th class='title'>Correo:</th>
					<td>	
						<input type="text" name="correo" value="" id="correo" />				</td>
				</tr>
				<tr>
					<th class='title'>Foto:</th>
					<td>	
						<input type="file" name="foto" value="" id="foto" />				</td>
				</tr>
				<tr>
					<th class='title'>Contraseña:</th>
					<td><input type="text" name="password" value="" id="password" /></td>
				</tr>
				 
				<tr>
					<th class='title'>Nivel:</th>
					<td><select name="nivel">
					  <option selected="selected">Usuario</option>
					  <option>Comunicaciones</option>
                      <option>Protocolo</option>
					  <option>Administrador</option>
					  </select>
					  
					</td>
				</tr>
				<tr>
					<th class='title'>Oficina:</th>
					<td><input type="oficina" name="oficina" value="" id="oficina" /></td>
				</tr>
				<tr>
					<th class='title'>Extensión:</th>
					<td><input type="extension" name="extension" value="" id="extension" /></td>
				</tr>
				<tr>
					<th class='title'>Teléfono:</th>
					<td><input type="telefono" name="telefono" value="" id="telefono" /></td>
				</tr>
				<tr>
					<th class='title'>Celular:</th>
					<td><input type="celular" name="celular" value="" id="celular" /></td>
				</tr>
				<tr>
					<th class='title'>Dirección:</th>
					<td><textarea name="direccion"></textarea></td>
				</tr>
				<tr>
					<th class='title'>Cumpleaños:</th>
					<td><input type="text" name="cumple" value="" id="cumple" />				 <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_add_usuario',
		// input name
		'controlname': 'cumple'
	});
	</script>
   
    
    </td>
				</tr>
				  <tr>
					<th class='title'>Id funcionario: </th>
					<td><input type="text" size="40" value="" id="id_funcionario" name="id_funcionario"/>
			   
									</script>	
					</td>
				</tr>  
                <tr>
					<th class='title'>Estado:</th>
					<td><select name="estado">
					  <option selected="selected">ACTIVO</option>
					  <option>INACTIVO</option>
					  </select>
					  
					</td>
				</tr>   
				<tr>
					<td colspan='2' class='center'>
						<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
						<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
						<input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
						<input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
		 
				<input class="button" type="submit" name="do_add_usuario" value="Agregar" id="btn_add_usuario" /></td>
				</tr>
			</table>
	
	</form>
	
			</fieldset>
			
			<?php
		}
		
		
		// Muestra la forma para editar un 
		function edit_usuario_form($id, $busca, $page, $orden, $lista) {
			global $tabla_usuarios;
			
			
			$usuario = run_select_query("SELECT * FROM $tabla_usuarios WHERE id = '$id'");
			//echo "SELECT * FROM $tabla_usuarios WHERE id = '$id'";
		//	echo "SELECT * FROM usuarios WHERE id = '$id'";
			$representacion = $usuario[0]['representacion'];
			$departamento = $usuario[0]['departamento'];
			$tipo = $usuario[0]['tipo'];
			$nombre = $usuario[0]['nombre'];
			$cargo = $usuario[0]['cargo'];
			$iniciales = $usuario[0]['iniciales'];
			$correo = $usuario[0]['correo'];
			$foto = $usuario[0]['foto'];
			$password = $usuario[0]['password'];
			$nivel = $usuario[0]['nivel'];
			$oficina = $usuario[0]['oficina'];
			$extension = $usuario[0]['extension'];
			$telefono = $usuario[0]['telefono'];
			$celular = $usuario[0]['celular'];
			$direccion = $usuario[0]['direccion'];
			$cumple = $usuario[0]['cumple'];
			$correo = $usuario[0]['correo'];
			$id_funcionario = $usuario[0]['id_funcionario'];
			$estado = $usuario[0]['estado'];
			
			$query_nombre = run_select_query("SELECT nombre FROM usuarios WHERE id = '$id_usuario'");
			$nombre_titular = $query_nombre [0]['nombre'];
			$resultado_titular = "<option value = '$id_usuario' selected='selected'>$nombre_titular</option>";
			
			
			$query_titulares = run_select_query("SELECT id, nombre FROM usuarios WHERE id_padre = 0");
			foreach ($query_titulares as $key=> $value) {
				
				$id = $query_titulares[$key]['id'];
				$nombres =$query_titulares[$key]['nombre'];
				$select_usuarios.="<option value = '$id'>$nombres</option>";
				} 
			
			
			
			?>
	
			
	<fieldset class="adminform">
			
			<legend>Editar usuario  <a href='index.php?admin_usuarios&busca=&page=<?php echo $page ?>' title='Back'><img src="images/j_arrow_left.png" border="0" /></a></legend>
	  
					
			<form action="index.php" name="form_edit_usuario" method="post" enctype="multipart/form-data">
			<table class="adminform">
				<tr>
				  <th class='title'>
						Representación: </th>
					<td><select name="representacion">
					  <option selected="selected"><?php echo $representacion ?></option>
					  <option>AGREGADURÍA MILITAR Y AÉREA</option>
                      <option>SECCIÓN CONSULAR</option>
                      <option>EMBAJADA</option>
					  <option>AGREGADURÍA NAVAL</option>
                      <option>OFICINA DE ASUNTOS REGIONALES (CISEN)</option>
                      <option>AGREGADURÍA DE ASUNTOS MIGRATORIOS</option>
                      <option>AGREGADURÍA LEGAL</option>
                      <option>AGREGADURÍA DE SEGURIDAD PÚBLICA</option>
                      <option>CONSEJERÍA AGROPECUARIA</option>
                      <option>CONSEJERÍA COMERCIAL PROMEXICO</option>
				    </select></td>
				</tr>
                <tr>
				  <th class='title'>
						Departamento: </th>
					<td><select name="departamento">
					   <option selected="selected"><?php echo $departamento ?></option>
                      <option selected="selected"></option>
					  <option>Oficina Embajadora</option>
					  <option>Oficina Jefe de Cancillería</option>
					  <option>Asuntos culturales</option>
					  <option>Asuntos jurídicos y derechos humanos</option>
					  <option>Cooperación técnica y científica</option>
					  <option>Asuntos políticos</option>
					  <option>Asuntos económicos</option>
					  <option>Prensa</option>
                      <option>Comunicaciones y archivo</option>
                      <option>Administración</option>
                      <option>Protocolo y TI</option>
				    </select></td>
				</tr>
                <tr>
				  <th class='title'>
						Tipo: </th>
					<td><select name="tipo">
					  <option selected="selected"><?php echo $tipo ?></option>
					  <option>SEM</option>
					  <option>LOCAL</option>
				    </select></td>
				</tr>
				<tr>
					<th width="108" class='title'>
						Nombre: </th>
					<td width="170">	
						<input type="text" name="nombre" value="<?php echo $nombre ?>" id="nombre" />				</td>
				</tr>
                 <tr>
					<th class='title'>
						Cargo: </th>
					<td>	
						<input type="text" name="cargo" value="<?php echo $cargo ?>" id="cargo" />				</td>
				</tr>
				<tr>
					<th class='title'>
						Iniciales: </th>
					<td>	
						<input type="text" name="iniciales" value="<?php echo $iniciales?>" id="iniciales" />				</td>
				</tr>
                
				<tr>
					<th class='title'>Correo: </th>
					<td>	
						<input type="text" name="correo" value="<?php echo $correo?>" id="correo" />				</td>
				</tr>
				<tr>
					<th class='title'>Foto: </th>
					<td><input type="file" name="foto" value="" id="foto" /><?php echo $foto?></td>
				</tr>
				<tr>
					<th class='title'>Contraseña: </th>
					<td><input type="text" name="password" value="" id="password" /></td>
				</tr>
				<tr>
					<th class='title'>Nivel: </th>
					<td><select name= "nivel">
					<option name="<?php echo $nivel ?>" selected="selected"><?php echo $nivel ?></option> 
					  <option>Usuario</option>
					  <option>Comunicaciones</option>
                      <option>Protocolo</option>
					  <option>Administrador</option>
					</select>
							
				  </td>
				</tr>
				<tr>
					<th class='title'>Oficina: </th>
					<td><input type="text" name="oficina" value="<?php echo $oficina?>" id="oficina" /></td>
				</tr>
				<tr>
					<th class='title'>Extensión: </th>
					<td><input type="text" name="extension" value="<?php echo $extension?>" id="extension" /></td>
				</tr>
				<tr>
					<th class='title'>Teléfono: </th>
					<td><input type="text" name="telefono" value="<?php echo $telefono?>" id="telefono" /></td>
				</tr>
				<tr>
					<th class='title'>Celular: </th>
					<td><input type="text" name="celular" value="<?php echo $celular?>" id="celular" /></td>
				</tr>
				 <tr>
					<th class='title'>Dirección:</th>
					<td><textarea name="direccion"><?php echo $direccion?></textarea></td>
				</tr>
				 <tr>
					<th class='title'>Cumpleaños:</th>
					<td>
                    <input type="text" name="cumple" value="<?php echo $cumple?>" id="cumple" />				 <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_edit_usuario',
		// input name
		'controlname': 'cumple'
	});
	</script>
              </tr>
				<tr>
					<th class='title'>
						Id funcionario: </th>
					<td><input type="text" name="id_funcionario" value="<?php echo $id_funcionario?>" id="id_funcionario" /></td>
				</tr>  
                <tr>
					<th class='title'>Estado:</th>
					<td><select name="estado">
					  <option selected="selected"><?php echo $estado?></option>
					  <option>ACTIVO</option>
                      <option>INACTIVO</option>
					  </select>
					  
					</td>
				</tr>
				<tr>
					<td colspan='2' class='center'>
					<input type="hidden" name="id" value="<?php echo $usuario[0]['id']; ?>" id="id" />
						<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
						<input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
						<input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
						<input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
					   
															
						<input class="button" type="submit" name="do_edit_usuario" value="Editar" id="btn_edit_usuario" />	</td>
				</tr>
			</table>
			
			
			
			
			
	</form>
	</fieldset>
		
			<?php
		}
		
		// Muestra la forma para editar un 
		function edit_perfil_form($id) {
			global $tabla_usuarios;
			$id_usuario = get_cookie_id_user();
			$usuario = run_select_query("SELECT * FROM $tabla_usuarios WHERE id = '$id_usuario'");
			//echo "SELECT * FROM $tabla_usuarios WHERE id = '$id'";
		//	echo "SELECT * FROM usuarios WHERE id = '$id'";
			$nombre = $usuario[0]['nombre'];
			$iniciales = $usuario[0]['iniciales'];
			$correo = $usuario[0]['correo'];
			$foto = $usuario[0]['foto'];
			$password = $usuario[0]['password'];
			$nivel = $usuario[0]['nivel'];
			$oficina = $usuario[0]['oficina'];
			$extension = $usuario[0]['extension'];
			$telefono = $usuario[0]['telefono'];
			$celular = $usuario[0]['celular'];
			$direccion = $usuario[0]['direccion'];
			$cumple = $usuario[0]['cumple'];
			$correo = $usuario[0]['correo'];
			$id_funcionario = $usuario[0]['id_funcionario'];
			
			$query_nombre = run_select_query("SELECT nombre FROM usuarios WHERE id = '$id_usuario'");
			$nombre_titular = $query_nombre [0]['nombre'];
			$resultado_titular = "<option value = '$id_usuario' selected='selected'>$nombre_titular</option>";
			
			
			$query_titulares = run_select_query("SELECT id, nombre FROM usuarios WHERE id_padre = 0");
			foreach ($query_titulares as $key=> $value) {
				
				$id = $query_titulares[$key]['id'];
				$nombres =$query_titulares[$key]['nombre'];
				$select_usuarios.="<option value = '$id'>$nombres</option>";
				} 
			
			
			
			?>
	
			
	<fieldset class="adminform">
			
			<legend>Editar perfil<a href='index.php?mi_perfil&busca=&page=<?php echo $page ?>' title='Back'><img src="images/j_arrow_left.png" border="0" /></a></legend>
	  
					
			<form action="index.php" name="form_edit_perfil" method="post" enctype="multipart/form-data">
			<table class="adminform">
				
				<tr>
					<th width="108" class='title'>
						Nombre: </th>
					<td width="263">	
						<input type="text" name="nombre" value="<?php echo $nombre ?>" id="nombre" />				</td>
				</tr>
				<tr>
					<th class='title'>
						Iniciales: </th>
					<td>	
						<input type="text" name="iniciales" value="<?php echo $iniciales?>" id="iniciales" />				</td>
				</tr>
				<tr>
					<th class='title'>Correo: </th>
					<td>	
						<input type="text" name="correo" value="<?php echo $correo?>" id="correo" />				</td>
				</tr>
				<tr>
					<th class='title'>Foto: </th>
					<td><input type="file" name="archivo" value="" id="archivo" /><?php echo $foto?></td>
				</tr>
				<tr>
					<th class='title'>Contraseña: </th>
					<td><input type="text" name="password" value="" id="password" /></td>
				</tr>
				<tr>
					<th class='title'>Oficina: </th>
					<td><input type="text" name="oficina" value="<?php echo $oficina?>" id="oficina" /></td>
				</tr>
				<tr>
					<th class='title'>Extensión: </th>
					<td><input type="text" name="extension" value="<?php echo $extension?>" id="extension" /></td>
				</tr>
				<tr>
					<th class='title'>Teléfono: </th>
					<td><input type="text" name="telefono" value="<?php echo $telefono?>" id="telefono" /></td>
				</tr>
				<tr>
					<th class='title'>Celular: </th>
					<td><input type="text" name="celular" value="<?php echo $celular?>" id="celular" /></td>
				</tr>
				 <tr>
					<th class='title'>Dirección:</th>
					<td><textarea name="direccion"><?php echo $direccion?></textarea></td>
				</tr>
				 <tr>
					<th class='title'>Cumpleaños:</th>
					<td>
                    <input type="text" name="cumple" value="<?php echo $cumple?>" id="cumple" />				 <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_edit_perfil',
		// input name
		'controlname': 'cumple'
	});
	</script>
             	</tr>  
				<tr>
					<td colspan='2' class='center'>
					<input type="hidden" name="id" value="<?php echo $id_usuario; ?>" id="id" />
						<input class="button" type="submit" name="do_edit_perfil" value="Editar" id="btn_edit_usuario" />	</td>
				</tr>
			</table>
			
			
			
			
			
	</form>
	</fieldset>
		
<?php
		}
		
		// Agrega el  a la BD
			function do_add_usuario($representacion, $departamento, $nombre, $cargo, $iniciales, $tipo, $correo, $archivo, $password, $nivel, $oficina,$extension, $telefono, $celular, $direccion, $cumple, $id_funcionario, $estado, $busca, $page, $orden, $lista) {
			
			
			if($id_funcionario == "") $valor_id_funcionario = "null";
			else $valor_id_funcionario = "'".$id_funcionario."'";
			
			if($cumple == "") $valor_cumple = "0000-00-00";
			else $valor_cumple = "'".$cumple."'";
		
		
			global $tabla_usuarios;
			$fecha = date('Y-m-d');	
			
			//Subimos archivo a carpeta de protocolo
		     $ruta = '/var/www/html/gua/archivos/fotos/';
           
				if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
						sube_archivo($archivo, $ruta);
						$n_archivo = date('s')."_".basename($archivo['name']);
				}
				
			$pass = hash("sha256", $password);
				run_non_query("INSERT INTO $tabla_usuarios  VALUES (null, '$representacion','$departamento', '$nombre', '$cargo',  '$iniciales', '$tipo', '$correo', '$n_archivo', '$pass', '$nivel', '$oficina','$extension','$telefono','$celular','$direccion','$valor_cumple', $valor_id_funcionario, '$estado')");
				
				echo "INSERT INTO $tabla_usuarios  VALUES (null, '$representacion','$departamento', '$nombre', '$cargo',  '$iniciales', '$tipo', '$correo', '$n_archivo', '$pass', '$nivel', '$oficina','$extension','$telefono','$celular','$direccion','$valor_cumple', $valor_id_funcionario, '$estado')";
				?>
				
				<p class='highlight bold right'>
					Usuario Agregado
				</p>
				<?php
				
				$max_pg = run_select_query("SELECT COUNT(id) AS id FROM $tabla_usuarios"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
				
				list_usuarios($busca, $page, $orden, $lista);
		}
		
		
		// Edita el usuario en BD
		function do_edit_usuario($representacion, $departamento, $nombre, $cargo, $iniciales, $tipo, $correo, $archivo, $password, $nivel, $oficina,$extension, $telefono, $celular, $direccion, $cumple, $id_funcionario, $estado, $id, $busca, $page, $orden, $lista) {
		global $tabla_usuarios;		
		$fecha = date('Y-m-d');	
		
		if($id_funcionario == "") $valor_id_funcionario = "null";
			else $valor_id_funcionario = "'".$id_funcionario."'";
			
		if($cumple == "") $valor_cumple = "0000-00-00";
		else $valor_cumple = "$cumple";
		
		
		if($password == "") $texto_password = "";
		
		else{
			$pass = hash("sha256", $password);	
			$texto_password = "password = '$pass',";
		}
		
		//Subimos archivo a carpeta de protocolo
			 $ruta = '/var/www/html/gua/archivos/fotos/';
           
				if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
						sube_archivo($archivo, $ruta);
						$n_archivo = date('s')."_".basename($archivo['name']);
						$texto_archivo = "foto='$n_archivo',";
				}
				else $texto_archivo="";
                echo $n_archivo."aqui<br>";
		
        
		run_non_query("UPDATE $tabla_usuarios SET 
		representacion = '$representacion',
		departamento = '$departamento',
		nombre = '$nombre', 
		cargo = '$cargo', 
		iniciales = '$iniciales', 
		tipo = '$tipo ', 
		correo = '$correo', 
		$texto_archivo
		$texto_password
		nivel = '$nivel',
		oficina = '$oficina',
		extension = '$extension',
		telefono = '$telefono',
		celular = '$celular',
		direccion = '$direccion',
		cumple = '$valor_cumple', 
		id_funcionario = $valor_id_funcionario,
		estado = '$estado'
		WHERE id = '$id'");
		
		
		
		?>
			<p class='highlight bold right'>
				Usuario Editado
			</p>
		<?php
			list_usuarios($busca, $page, $orden, $lista);
		}
		
		// Edita el usuario en BD
		function do_edit_perfil($nombre, $iniciales, $correo, $archivo, $password, $oficina,$extension, $telefono, $celular, $direccion, $cumple, $id) {
		
		global $tabla_usuarios;		
		$fecha = date('Y-m-d');	
		
		if($password == "") $texto_password = "";
		
		else{
			$pass = hash("sha256", $password);	
			$texto_password = "password = '$pass',";
		}
		
		//Subimos archivo a carpeta de protocolo
			 $ruta = '/var/www/html/gua/archivos/fotos/';
           
            
				if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
						sube_archivo($archivo, $ruta);
						$n_archivo = date('s')."_".basename($archivo['name']);
						$texto_archivo = "foto='$n_archivo',";
				}
				else $texto_archivo="";
		
        
		run_non_query("UPDATE $tabla_usuarios SET 
		nombre = '$nombre', 
		iniciales = '$iniciales', 
		correo = '$correo', 
		$texto_archivo
		$texto_password
		oficina = '$oficina',
		extension = '$extension',
		telefono = '$telefono',
		celular = '$celular',
		direccion = '$direccion',
		cumple = '$cumple'
		WHERE id = '$id'");
       
		
		?>
			<p class='highlight bold right'>
				Tus datos han sido modificados
			</p>
<?php
			
			show_perfil();
		}
		
		// Borra el usuario de BD
		function del_usuario($id, $busca, $page, $orden, $lista) {
			global $tabla_usuarios;
			run_non_query("DELETE FROM $tabla_usuarios WHERE id = $id");
			?>
			<p class='highlight bold right big'>
				Usuario Eliminado
			</p>
			<?php
			list_usuarios($busca, $page, $orden, $lista);
		}
		
	
	
	?>