<?php
	function panel_administracion(){
		$id_usuario = get_cookie_id_user();
		$div_permisos = "<div class='col width-45'>
			";
		$titulos = run_select_query("Select permisos.id, permisos.nombre from permisos INNER JOIN usuarios_permisos ON permisos.id = usuarios_permisos.id_permiso where id_usuario = '$id_usuario' AND id_padre = '0' order by permisos.nombre");
		foreach($titulos as $key => $value){
			$id = $titulos[$key]['id'];
			$titulo = $titulos[$key]['nombre'];
			$div_permisos.="
			<legend><h2>$titulo</h2></legend>
			<fieldset class='adminform'>
			<table clas='admintable'>
				<tr>
					<td>
						<table width='100%' class='paramlist admintable' cellspacing='1'>";
			$permisos = run_select_query("Select permisos.nombre, permisos.liga from permisos INNER JOIN usuarios_permisos ON permisos.id = usuarios_permisos.id_permiso where id_usuario = '$id_usuario' AND id_padre = '$id' order by permisos.nombre");
			foreach($permisos as $key2 => $value2){
				$nombre_permiso = $permisos[$key2]['nombre'];
				$liga = $permisos[$key2]['liga'];
				$div_permisos.="<tr>
								<td width='20%' class='paramlist_key_center'><a href='index.php?$liga'>$nombre_permiso </a></td>
								</tr>";
				
				}		
				$div_permisos.="</table></td></tr></table></fieldset>";
				if($key > 2){
				$div_permisos.="</div><div class='col width-45'>";	
				}	
		}
		$div_permisos.="</div>";
		echo $div_permisos;
	}
?>