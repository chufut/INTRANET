<?php
	// ABC de funcionarios
	function ver_funcionario($id,$estado, $busca, $page, $orden, $lista){
		//Sacamos datos de funcionario
		$funcionario = run_select_query("SELECT * FROM funcionarios where id = '$id'");
		//echo "SELECT * FROM funcionarios where id = '$id'";
		$titulo = $funcionario[0]['titulo'];
		$nombre_titular = $funcionario[0]['nombre'];
		$cargo = $funcionario[0]['cargo'];
		$dependencia = $funcionario[0]['dependencia'];
		?>
		<h2>Información:</h2>
		<?php
		echo $titulo.$nombre_titular."<br>";
		echo $cargo."<br>";
		echo $dependencia."<br>";
		
          $documentos = run_select_query("Select *,DATE_FORMAT(fecha_expedicion,'%d/%m/%Y') as fecha_expedicion, DATE_FORMAT(fecha_expiracion,'%d/%m/%Y') as fecha_expiracion from documentos_funcionario where id_funcionario = '$id' and valor != '' order by nombre");
		
		if($documentos)
		foreach($documentos as $key => $value){
        $id_funcionario = $documentos[$key]['id_funcionario'];
		$id_documento = $documentos[$key]['id'];
		$nombre_documento = $documentos[$key]['nombre'];
		$valor = $documentos[$key]['valor'];
		$archivo = $documentos[$key]['archivo'];	
		$fecha_expedicion  = $documentos[$key]['fecha_expedicion']; 
        $fecha_vencimiento = $documentos[$key]['fecha_expiracion'];
		$estado_doc =$documentos[$key]['estado'];
       
        if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
        
		$resultado.="<tr class='row$renglon'>
        <td>
        <a href='index.php?edit_archivo_funcionario&id=$id_documento' title='Editar documento'><img src='images/editar.png' alt='Editar' title='Editar'></a>|<a href='index.php?del_archivo_funcionario&id=$id_documento&id_funcionario=$id_funcionario' title='Eliminar documento'><img src='images/eliminar.png' alt='Eliminar' title='Eliminar' onclick=\"return confirm('Está seguro de eliminar el documento')\"></a> </td><td> <a href='index.php?ver_documento&archivo=".$archivo."&carpeta=protocolo' target='_blank'>$nombre_documento</a></td><td>$valor</td><td>$fecha_expedicion</td><td>$fecha_vencimiento</td><td>$estado_doc</td></tr>";
		}
		?>
		<h2>Documentos:</h2>
        <table class="adminlist">
        <tr>
        <thead>
        	<th class="titulo">Opciones</th>
            <th class="titulo">Documento</th>
            <th class="titulo">Número</th>
            <th class="titulo">Fecha expedición</th>
            <th class="titulo">Fecha vencimiento</th>
            <th class="titulo">Estado Documento</th>
        </thead>
        </tr>
		<?php
		echo $resultado;
		?>
        </table>
        <?php
		
		//Sacamos dependientes
		$dependientes = run_select_query("SELECT * FROM funcionarios where id_padre = '$id'");
		if($dependientes) echo "<h2>Dependientes:</h2>";
		foreach($dependientes as $key => $value){
		$id_dependiente = $dependientes[$key]['id'];
		$nombre = $dependientes[$key]['nombre'];
		$tipo = $dependientes[$key]['tipo'];
		$resultado_dependientes.="<li><a href='index.php?ver_funcionario&id=$id_dependiente'>$nombre ($tipo)</a></li>";
		}
		?>
        <ul>
        <?php
		echo $resultado_dependientes;
		?>
        </ul>
        <?php
	}
	
	
	
	
	
	// Lista los usuarios con opciones para editar, borrar y agregar
	function list_funcionarios($estado, $busca, $page, $orden, $lista) {
		$paginacion = 200; 
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
		if($orden == "") $orden = "nombre";	
		
		if($estado == "BAJA") $where.="AND estado = 'BAJA'";
		else $where.= "AND estado = 'ACTIVO'";
		
		if($busca != '' && $busca != 'Buscar funcionario'){ $where .= " AND funcionario Rlike '$busca' OR siglas Rlike '$busca'"; $limit = "LIMIT 0,$paginacion"; $page = 0;}
		$max_pg = run_select_query("SELECT COUNT(id) AS id FROM funcionarios  $where");
		$max_pg = $max_pg[0]['id']; $max_pg /= $paginacion;$max_pg = ceil($max_pg);
		$funcionario = run_select_query("SELECT * FROM funcionarios where (1 = 1) $where  order by $orden $lista_query $limit");
		//echo "SELECT * FROM funcionarios where (1 = 1) $where  order by $orden $lista_query $limit";
		
		//echo "SELECT * FROM funcionarios where (1 = 1) $where  order by $orden $lista_query $limit";

		//echo "SELECT * FROM funcionarios where (1 = 1) $where  order by $orden $lista_query $limit";
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
								<a href="index.php?add_funcionario&page=<?php echo $page/$paginacion + 1 ?>" class="toolbar"> 
								<span class="icon-32-new" title="Nuevo"> 
								</span>
								<img src="images/agregar_funcionario.png" alt="Agregar Funcionario" title="Agregar Funcionario"> 
								Agregar - funcionario
								</a> 
								</td> 
                              
							</tr>
						</table>
				  </div>
               
                      <div class="header galeria">
                        Administrador de funcionarios</div>
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
        <form name="form_list_funcionario" action="index.php" method="get">
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
					echo "  <a href='index.php?admin_funcionarios&page=$i' $estilo >$i</a> -";
				}?>
					 <input class='small_width highlight small' type="text" name="busca" value="<?php if($busca != '' && $busca != 'Buscar funcionarios') echo $busca; else echo "Buscar funcionarios"; ?>" id="id_busca" tabindex='2' />
					 <span class="highlight small center">
					 <input type="submit" name="admin_funcionarios" value="Go" id="admin_funcionarios" class="button super_tiny"/>
					 </span> </td> 
                    
			  </tr>
          </table>
		
            <table class="adminlist">
			<tr>
		      <thead>
              <th  class="title">&nbsp;</th>
              <th  class="title">Opciones</th>
              <th  class="title"><a href="index.php?admin_funcionarios&estado=<?php echo $estado ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=tipo&lista=<?php echo $lista_query ?>">Tipo</a></th>
			  <th class="title"><a href="index.php?admin_funcionarios&estado=<?php echo $estado ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=nombre&lista=<?php echo $lista_query ?>">Nombre</a></th>
			  <th class="title">Título</th>
              <th class="title">Cargo</th>
              <th class="title"><a href="index.php?admin_funcionarios&estado=<?php echo $estado ?>&busca=<?php echo $busca; ?>&page=<?php echo $pagina; ?>&orden=dependencia&lista=<?php echo $lista_query ?>">Dependencia</a></th>
              
                	</thead>
		  </tr>
			
		<?php
		    	
				
		
		if($funcionario)
		foreach($funcionario as $key => $value) {
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
				$id = $funcionario[$key]['id'];
				$fecha = $funcionario[$key]['fecha_modificacion'];
				$tipo = $funcionario[$key]['tipo'];
				$nombre = $funcionario[$key]['nombre'];
				$titulo = $funcionario[$key]['titulo'];
				$cargo = $funcionario[$key]['cargo'];
				$dependencia = $funcionario[$key]['dependencia'];
				$estado = $funcionario[$key]['estado'];
		    	
			?>
			
			<tr class="row<?php echo $renglon; ?>">
            <td>
                Modificado: <?php echo $fecha ?>
               	</td>
				<td class="center">
                <a href='index.php?ver_funcionario&id=<?php echo $id ?>&estado=<?php echo $estado ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>'title='view'><img src="images/ver_funcionario.png" alt="Ver" title="Ver Funcionario"></a>
                <a href='index.php?agregar_documentos&id=<?php echo $id ?>&estado=<?php echo $estado ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Add'><img src="images/agrega_archivo.png" alt="Agregar" title="Agregar"></a>	
					<a href='index.php?edit_funcionarios&id=<?php echo $id ?>&estado=<?php echo $estado ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Edit'><img src="images/editar.png" alt="Editar" title="Editar"></a> | 
                    <a href='index.php?del_funcionario&id=<?php echo $id ?>&estado=<?php echo $estado ?>&busca=<?php echo $busca ?>&page=<?php echo $page/$paginacion + 1 ?>&orden=<?php echo $orden ?>&lista=<?php echo $lista ?>' title='Delete' onclick="return confirm('Está seguro de eliminar a: <?php echo $nombre?>?')"><img src="images/eliminar.png" alt="Eliminar" title="Eliminar"></a>				</td>
				
                    <td><?php echo $tipo ?></td>
                    <td><?php echo $nombre ?></td>
                    <td><?php echo $titulo ?></td>
                    <td><?php echo $cargo ?></td>
                    <td><?php echo $dependencia ?></td>
                   
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
	function add_documento_form($accion, $id_gestion, $id_funcionario,$tipo, $busca, $page, $orden, $lista) {
	
		$nombre_tramite = run_select_query("Select tramites.id, tramites.nombre from tramites INNER JOIN gestion_tramites_seguimiento ON tramites.id = gestion_tramites_seguimiento.id_tramite WHERE gestion_tramites_seguimiento.id = '$id_gestion'");
		//echo "Select tramites.id, tramites.nombre from tramites INNER JOIN gestion_tramites_seguimiento ON tramites.id = gestion_tramites_seguimiento.id_tramite WHERE gestion_tramites_seguimiento.id = '$id_gestion'";
		
		$id_tramite_=$nombre_tramite[0]['id'];
		$nombre_tramite_=$nombre_tramite[0]['nombre'];
		
		//Sacamos datos de funcionario
		$funcionario = run_select_query("SELECT * FROM funcionarios where id = '$id_funcionario'");
		$titulo = $funcionario[0]['titulo'];
		$nombre_titular = $funcionario[0]['nombre'];
		$cargo = $funcionario[0]['cargo'];
		$dependencia = $funcionario[0]['dependencia'];
		
		
			
		?>
     
		<fieldset class="adminform">
         
		<legend>Agregar documentos de: <?php echo $titulo." ".$nombre_titular." adscrito en ".$dependencia; ?> <a href='index.php?admin_funcionarios&busca=&page=<?php echo $page ?>' title='Back'><img src="images/j_arrow_left.png" border="0" /></a></legend>			
<form action="index.php" name="form_add_documento" method="post" enctype="multipart/form-data">
		<table class="adminform">
			
			
            <tr>
				<th class='title'>
					Trámite: </th>
				<td><input type="text" size="60" value="<?php echo $nombre_tramite_?>" id="tramite" name="tramite" autocomplete="off"/>
            <div id="resultado_tramites" class="autocomplete"></div>
             <script type="text/javascript">
                                new Ajax.Autocompleter("tramite","resultado_tramites","lib/protocolo/protocolo_ajax.php?dame_tramites",{
									afterUpdateElement : getTramiteId});
                                function getTramiteId(text, li) {
									var valor = li.id;
						            $('id_tramite').value=valor;
						        }
                                </script>
                                <input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite_?>"/></td>
			</tr>
			<tr>
				<th class='title'>
					Nombre: </th>
				<td>	asa
					<select name="nombre">
				  <option selected="selected">-------</option>
				  <option>acreditacion</option>
		                  <option>baja</option>
		                  <option>calcomanía</option>
		                  <option>carnet</option>
                          <option>credencial Embajada</option>
                          <option>devolución placas</option>
		                  <option>exeniva</option>
		                  <option>franquicia importacion</option>
		                  <option>franquicia vehiculos</option>
		                  <option>franquicia menaje</option>
		                  <option>franquicia licores</option>
		                  <option>nit</option>
		                  <option>pasaporte</option>
		                  <option>permiso sobrevuelo</option>
		                  <option>permiso aportacion de armas</option>
                          <option>placas</option>
		                  <option>tarjeta circulación</option>
		                  <option>visa</option>
                          <option></option>
		                  
		                  
			    </select>				</td>
			</tr>
            <tr>
				<th class='title'>
					Número: </th>
				<td>	
					<input type="text" name="valor" value="" id="valor" />				</td>
			</tr>
            <tr>
				<th class='title'>Fecha expedición:</th>
				<td>	
					<input type="text" name="fecha_expedicion" value="" id="fecha_expedicion" />				 <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_add_documento',
		// input name
		'controlname': 'fecha_expedicion'
	});
	</script></td>
			</tr>
			<tr>
				<th class='title'>Fecha expiración:</th>
				<td><input type="text" name="fecha_expiracion" value="" id="fecha_expiracion" /><script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form_add_documento',
		// input name
		'controlname': 'fecha_expiracion'
	});
	</script></td>
			</tr> 
            <tr>
            <th>Archivo:</th>
            	<td><input type="file" name="archivo" />
            </tr> 
              <tr>
				<th>
					Estado: </th>
				<td>	
					<select name="estado_doc">
				  <option selected="selected">-------</option>
				  <option>Activo</option>
		          <option>Inactivo</option>                            
		          </select></td>
			</tr>   
			<tr>
				<td colspan='2' class='center'>
					<input type="hidden" name="id_funcionario" value="<?php echo $id_funcionario; ?>" id="id_funcionario" />
                    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>" id="tipo" />
                    <input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                    <input type="hidden" name="accion" value="<?php echo $accion; ?>" id="accion" />
                    <input type="hidden" name="id_gestion" value="<?php echo $id_gestion; ?>" id="id_gestion" />
     
           	<input class="button" type="submit" name="do_add_documento" value="Agregar" id="btn_add_funcionario" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para agregar un usuario
	function add_funcionario_form($busca, $page, $orden, $lista, $idioma) {
	
	
		$paises = run_select_query("SELECT * FROM misiones order by grupo_regional");
		
		if($paises)
		$row_paises = "";
		foreach ($paises as $key => $value){
			if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
			$nombre_pais = $paises[$key]['nombre_espanol'];
			$grupo_regional = $paises[$key]['grupo_regional'];			
			$id_pais = 	$paises[$key]['id'];			
			
			if($grupo_regional == $temp){}
			else{$temp = $grupo_regional;
			$lista_paises.="<br/><li style='background:#c3e4e9'>$grupo_regional</li>";
			}
			
			
			
			$lista_paises.=  "
			<li class='row$renglon'>
				<input type='checkbox' name='paises[]' value='$id_pais' id='pais_$key'>
				$nombre_pais
			</li>			
			";			
				}
		
		
		?>
     
		<fieldset class="adminform">
         
		<legend><a href='index.php?admin_funcionarios&busca=&page=<?php echo $page ?>' title='Back'> <img src="images/agregar_funcionario.png" border="0" /></a></legend>
		<legend>Agregar funcionario  </legend>
		<form action="index.php" name="form_add_funcionario" method="post">
		<table class="adminform">
			
			
            <tr>
				<th class='title'>
					Nombre: </th>
				<td>	
					<input type="text" name="nombre" value="" id="nombre" />				</td>
			</tr>
			<tr>
				<th class='title'>
					Título: </th>
				<td>	
					<input type="text" name="titulo" value="" id="titulo" />				</td>
			</tr>
            <tr>
				<th class='title'>Cargo:</th>
				<td>	
					<input type="text" name="cargo" value="" id="cargo" />				</td>
			</tr>
			<tr>
				<th class='title'>Dependencia:</th>
				<td>	
					<select name="dependencia">
                  <option name="EMBAMEX GUATEMALA" selected="selected">EMBAMEX GUATEMALA</option> 				
                  <option name="SECCIÓN CONSULAR">SECCIÓN CONSULAR</option>
                  <option name="CONSULMEX QUETZALTENANGO">CONSULMEX QUETZALTENANGO</option>
                  <option name="CONSULMEX TECÚN UMÁN">CONSULMEX TECÚN UMÁN</option>
        		  <option name="CISEN">CISEN</option>
                  <option name="INM">INM</option>
                  <option name="PGR">PGR</option>
                  <option name="PROMÉXICO">PROMÉXICO</option>
                  <option name="SEDENA">SEDENA</option>
                  <option name="SEMAR">SEMAR</option>
                  <option name="SSP">SSP</option>
                  <option name="SAGARPA">SAGARPA</option>
                 </select>				</td>
			</tr>
             <tr>
				<th class='title'>Estado:</th>
				<td>	
				<select name="estado">
                  <option name="ACTIVO" selected="selected">ACTIVO</option> 				
                  <option name="BAJA">BAJA</option>
                 </select>						</td>
			</tr>
			<tr>
				<th class='title'>Titular: </th>
				<td><input type="text" size="60" value="" id="funcionario" name="funcionario" autocomplete="off"/>
            <div id="resultado_funcionarios" class="autocomplete"></div>
             <script type="text/javascript">
                                new Ajax.Autocompleter("funcionario","resultado_funcionarios","lib/protocolo/protocolo_ajax.php?dame_funcionario",{
									parameters:'tipo=titular',
									afterUpdateElement : getFuncionarioId});
                                function getFuncionarioId(text, li) {
									var datos = new Array();
							// this will return an array with strings "1", "2", etc.
									var valor = li.id;
									datos = valor.split(",");
                                    $('id_funcionario').value=datos[0];
									$('expediente').value=datos[1];
                                }
                                </script>
                                <input type="hidden" id="id_funcionario" name="id_funcionario" />
                               
            </div></td>
			</tr>
              <tr>
				<th class='title'>
					Tipo: </th>
				<td>	
					<select name="tipo">
                    	<option selected="selected">titular</option>
                        <option>esposa</option>
                        <option>esposo</option> 
                        <option>hija</option> 
                        <option>hijo</option>  
                        <option>madre</option>  
                        <option>padre</option>                      
                    </select>	</td>
			</tr>  
            <tr>
				<th class='title'>Expediente (SICAR): </th>
				<td>	
					<input type="text" name="expediente" value="" id="expediente" />				</td>
                   
			</tr>     
			 <tr>
				<th class='title'>Correo: </th>
				<td>	
					<input type="text" name="correo" value="" id="correo" />				</td>
                   
			</tr>     
			<tr>
				<td colspan='2' class='center'>
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
     
           	<input class="button" type="submit" name="do_add_funcionario" value="Agregar" id="btn_add_funcionario" /></td>
			</tr>
		</table>

</form>

        </fieldset>
		
		<?php
	}
	
	
	// Muestra la forma para editar un usuario
	function edit_funcionario_form($id, $busca, $page, $orden, $lista) {
		$funcionario = run_select_query("SELECT * FROM funcionarios WHERE id = '$id'");
	//	echo "SELECT * FROM funcionarios WHERE id = '$id'";
		$nombre = $funcionario[0]['nombre'];
		$titulo = $funcionario[0]['titulo'];
		$cargo = $funcionario[0]['cargo'];
		$dependencia = $funcionario[0]['dependencia'];
		$estado = $funcionario[0]['estado'];
		$id_funcionario = $funcionario[0]['id_padre'];
		$tipo = $funcionario[0]['tipo'];
		$expediente = $funcionario[0]['expediente'];
		$correo = $funcionario[0]['correo'];
		
		$query_nombre = run_select_query("SELECT nombre FROM funcionarios WHERE id = '$id_funcionario'");
		$nombre_titular = $query_nombre [0]['nombre'];
		$resultado_titular = "<option value = '$id_funcionario' selected='selected'>$nombre_titular</option>";
		
		
		$query_titulares = run_select_query("SELECT id, nombre FROM funcionarios WHERE id_padre = 0");
		foreach ($query_titulares as $key=> $value) {
			
			$id = $query_titulares[$key]['id'];
			$nombres =$query_titulares[$key]['nombre'];
			$select_funcionarios.="<option value = '$id'>$nombres</option>";
			} 
		
		
		
		?>

		
<fieldset class="adminform">
        
		<legend>Editar funcionario  <a href='index.php?admin_funcionarios&busca=&page=<?php echo $page ?>' title='Back'><img src="images/j_arrow_left.png" border="0" /></a></legend>
  
				
		<form action="index.php" name="form_edit_funcionario" method="post" enctype="multipart/form-data">
		<table class="adminform">
			
			<tr>
				<th class='title'>
					Nombre: </th>
				<td>	
					<input type="text" name="nombre" value="<?php echo $nombre ?>" id="nombre" />				</td>
			</tr>
			<tr>
				<th class='title'>
					Título: </th>
				<td>	
					<input type="text" name="titulo" value="<?php echo $titulo?>" id="titulo" />				</td>
			</tr>
            <tr>
				<th class='title'>Cargo: </th>
				<td>	
					<input type="text" name="cargo" value="<?php echo $cargo?>" id="cargo" />				</td>
			</tr>
			<tr>
				<th class='title'>Dependencia: </th>
				<td>	
					<select name="dependencia">
                  <option name="<?php echo $dependencia ?>" selected="selected"><?php echo $dependencia ?></option>
                  <option name="EMBAMEX GUATEMALA">EMBAMEX GUATEMALA</option> 				
                  <option name="SECCIÓN CONSULAR">SECCIÓN CONSULAR</option>
                  <option name="CONSULMEX QUETZALTENANGO">CONSULMEX QUETZALTENANGO</option>
                  <option name="CONSULMEX TECÚN UMÁN">CONSULMEX TECÚN UMÁN</option>
        		  <option name="CISEN">CISEN</option>
                  <option name="INM">INM</option>
                  <option name="PGR">PGR</option>
                  <option name="PROMÉXICO">PROMÉXICO</option>
                  <option name="SEDENA">SEDENA</option>
                  <option name="SEMAR">SEMAR</option>
                  <option name="SSP">SSP</option>
                  <option name="SAGARPA">SAGARPA</option>
                 </select>				</td>
			</tr>
               <tr>
				<th class='title'>Estado: </th>
				<td>	
					<select name="estado">
                  <option name="<?php echo $estado ?>" selected="selected"><?php echo $estado ?></option> 
                  <option name="ACTIVO" >ACTIVO</option> 				
                  <option name="BAJA">BAJA</option>
                 </select>						</td>
			</tr>
			<tr>
				<th class='title'>Titutlar: </th>
				<td><select name= "funcionario"> 
                <?php echo $resultado_titular;
				echo $select_funcionarios;
                        
                        ?>
                
                </select>
                		
			  </td>
			</tr>
            
            <tr>
				<th class='title'>
					Tipo: </th>
				<td><select name="tipo">
                    	<option selected="selected"><?php echo $tipo ?></option>
                        <option name="esposa">esposa</option>
                        <option name="esposo">esposo</option> 
                        <option name="hija">hija</option> 
                        <option name="hijo">hijo</option>  
                        <option name="madre">madre</option>  
                        <option name="padre">padre</option>                      
                    </select>	</td>
			</tr>
            <tr>
				<th class='title'>Expediente (SICAR): </th>
				<td>	
					<input type="text" name="expediente" value="<?php echo $expediente?>" id="expediente" />				</td>
			</tr>  

				 <tr>
				<th class='title'>Correo: </th>
				<td>	
					<input type="text" name="correo" value="<?php echo $correo?>" id="correo" />				</td>
                   
			</tr>  
            <tr>
				<td colspan='2' class='center'>
				<input type="hidden" name="id" value="<?php echo $funcionario[0]['id']; ?>" id="id" />
					<input type="hidden" name="busca" value="<?php echo $busca; ?>" id="busca" />
                    <input type="hidden" name="page" value="<?php echo $page; ?>" id="page" />
                    <input type="hidden" name="orden" value="<?php echo $orden; ?>" id="orden" />
                    <input type="hidden" name="lista" value="<?php echo $lista; ?>" id="lista" />
                   
                                                        
					<input class="button" type="submit" name="do_edit_funcionario" value="Editar" id="btn_edit_funcionario" />	</td>
			</tr>
		</table>
		
		
		
		
		
</form>
</fieldset>
	
		<?php
	}
	
	
	// Agrega el usuario a la BD
		function do_add_funcionario($nombre, $titulo, $cargo, $dependencia, $estado, $id_funcionario, $tipo, $expediente, $correo,$estado, $busca, $page, $orden, $lista) {
	
		$fecha = date('Y-m-d');	
		$estado = "ACTIVO";
		if($id_funcionario == "") $id_funcionario = 0;
		
			run_non_query("INSERT INTO funcionarios  VALUES (null, '$nombre',  '$titulo', '$cargo', '$dependencia', '$estado', '$id_funcionario', '$tipo', '$fecha','$expediente','$correo')");
			
			//echo "INSERT INTO funcionarios  VALUES (null, '$nombre',  '$titulo', '$cargo', '$dependencia', '$estado', '$id_funcionario', '$tipo', '$fecha','$expediente','$correo');"
			?>
			
			<p class='highlight bold right'>
				Funcionario Agregado
			</p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM funcionarios"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
			list_funcionarios($estado, $busca, $page, $orden, $lista);
	}
	
	// Agrega el usuario a la BD
		function do_add_documento($accion,$id_gestion, $id_tramite, $id_funcionario,$nombre, $valor, $fecha_expedicion, $fecha_expiracion,$archivo, $estado, $estado_doc,$busca, $page, $orden, $lista) {
	
	//Validamos fecha para meter a base de datos 
	if($fecha_expedicion == "") $fecha_expedicion = "0000-00-00";
	if($fecha_expiracion == "") $fecha_expiracion = "0000-00-00";
	
	//QUITAMOS ACENTOS Y SUSTITUIMOS ESPACIOS POR _ EN LOS NOMBRES DE ARCHIVO
	
	$nombre = normaliza($nombre);
	$nombre = str_replace(" ", "_", $nombre);
	//Subimos archivo a carpeta de protocolo
		$ruta = '/var/www/html/gua/archivos/protocolo/';
			if(basename($archivo['name']) != '' && basename($archivo['name']) != NULL){ 
					sube_archivo_protocolo($archivo, $ruta, $id_funcionario, $nombre);  
					$extension = end(explode(".", basename($archivo['name'])));
                    $fecha = date("m-d-y");
					$n_archivo = $id_funcionario."_".$nombre."_".$fecha.".".$extension;
     		}
			echo $nombre."nombre <br>";
			echo $n_archivo." n_archivo";
		//$valor = preg_quote($valor);
		//echo "escape: ".$valor."<br>";	
			

/*VALIDACION DOCUMENTO EXISTENTE
	$validacion_documento = run_select_query("Select id from documentos_funcionario where id_funcionario = '$id_funcionario' AND id_tramite = '$id_tramite' AND nombre = '$nombre'");
	if($validacion_documento) $id_documento = $validacion_documento[0]['id'];
	if($id_documento != ""){
	//echo "Entro a update:<br>";
	//echo "UPDATE documentos_funcionario set id_funcionario = '$id_funcionario', id_tramite = '$id_tramite', nombre = '$nombre', valor = '$valor', fecha_expedicion = '$fecha_expedicion', fecha_expiracion = '$fecha_expiracion', archivo = '$n_archivo' WHERE id = '$id_documento'";
	run_non_query("UPDATE documentos_funcionario set id_funcionario = '$id_funcionario', id_tramite = '$id_tramite', nombre = '$nombre', valor = '$valor', fecha_expedicion = '$fecha_expedicion', fecha_expiracion = '$fecha_expiracion', archivo = '$n_archivo' WHERE id = '$id_documento'");
	}
	else{
	//echo "Entro a insert:<br>";
	//echo "INSERT INTO documentos_funcionario  VALUES (null, '$id_funcionario','$id_tramite','$nombre','$valor', '$fecha_expedicion', '$fecha_expiracion','$n_archivo')";
	
	run_non_query("INSERT INTO documentos_funcionario  VALUES (null, '$id_funcionario','$id_tramite','$nombre','$valor', '$fecha_expedicion', '$fecha_expiracion','$n_archivo')");
	}
    */
    run_non_query("INSERT INTO documentos_funcionario  VALUES (null, '$id_funcionario','$id_tramite','$nombre - $n_archivo','$valor', '$fecha_expedicion', '$fecha_expiracion','$n_archivo','$estado_doc')");
    
	
	//Procedimiento para actualizar el estado a Finalizado cuando se concluye el proceso 
	if($accion == "actualiza_estado"){
				//echo "Update gestion_tramites_seguimiento set estado = 'Finalizado' where id = '$id_gestion'";
	run_non_query("Update gestion_tramites_seguimiento set estado = 'Finalizado' where id = '$id_gestion'");	
	

			?>

			<p class='highlight bold right'>
				Documento Agregado
			</p>
			<?php
			
			$max_pg = run_select_query("SELECT COUNT(id) AS id FROM funcionarios"); $max_pg = $max_pg[0]['id']; $max_pg /= 10;$max_pg = ceil($max_pg);
			
				
	
	}
			
			list_funcionarios($estado, $busca, $page, $orden, $lista);
	}
	
	
	// Edita el usuario en BD
	function do_edit_funcionario($nombre, $titulo, $cargo, $dependencia, $estado, $titular, $tipo, $expediente, $correo, $id, $busca, $page, $orden, $lista) {
			
	$fecha = date('Y-m-d');	
		
	run_non_query("UPDATE funcionarios SET 
	nombre = '$nombre', 
	titulo = '$titulo', 
	cargo = '$cargo', 
	dependencia = '$dependencia',
	estado = '$estado', 
	id_padre = '$titular', 
	tipo = '$tipo', 
	fecha_modificacion = '$fecha',
	expediente = '$expediente',
	correo = '$correo'
	WHERE id = '$id'");
	
	?>
		<p class='highlight bold right'>
			Funcionario Editado
		</p>
	<?php
		
		list_funcionarios($estado,$busca, $page, $orden, $lista);
	}
	
	// Borra el funcionario de BD
	function del_funcionario($id, $estado, $busca, $page, $orden, $lista) {
		
		run_non_query("DELETE FROM funcionarios WHERE id = $id");
		?>
		<p class='highlight bold right big'>
			Funcionario Eliminado
		</p>
		<p>
<span class="highlight bold right big">funcionario</span> eliminado exitosamente. </p>
		<?php
		list_funcionarios($estado, $busca, $page, $orden, $lista);
	}
    
    function del_archivo_funcionario($id, $id_funcionario){
    $nombre_archivo_q = run_select_query("Select nombre from documentos_funcionario where id = '$id'");
    $nombre_archivo= $nombre_archivo_q[0]['nombre'];
    $ruta = '/var/www/html/gua/archivos/protocolo/'.$nombre_archivo;
    echo $ruta."<br>";
    
    //Borramos archivo de servidor
   	unlink($ruta);
    
    run_non_query("DELETE FROM documentos_funcionario WHERE id = '$id'");
    ver_funcionario($id_funcionario);
    }
	
?>