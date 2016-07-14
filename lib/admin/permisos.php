<?php
function asigna_permisos($id_usuario, $busca, $page, $orde, $lista){

$div_permisos = "";
$titulos = run_select_query("Select id, nombre from permisos where id_padre = '0'");

//echo "Select id, nombre from permisos where id_padre = '0'<br/>";
foreach ($titulos as $key => $value){
	$id_padre = $titulos[$key]['id'];
	$nombre = $titulos[$key]['nombre'];
	
	$asignado_titulo = run_select_query("Select id from usuarios_permisos where id_usuario = '$id_usuario' AND id_permiso = '$id_padre'");
			if($asignado_titulo != "") $check_titulo = "checked";
			else $check_titulo = "";
		
	$div_permisos.= "<div id='$id_padre'>
<h2><input type=\"checkbox\"  name=\"electo\" onclick=\"javascript:actualiza_permisos($id_usuario,$id_padre);\" $check_titulo />$nombre</h2>";
	
	$permisos = run_select_query("select distinct id, nombre from permisos where id_padre = '$id_padre'");
	foreach ($permisos as $key2 => $value2){
			$id_permiso = $permisos[$key2]['id'];
			$nombre_permiso = $permisos[$key2]['nombre'];
			
			$asignado = run_select_query("Select id from usuarios_permisos where id_usuario = '$id_usuario' AND id_permiso = '$id_permiso'");
			if($asignado != "") $check = "checked";
			else $check = "";
			
		
			$div_permisos.= "<div>
<input type=\"checkbox\"  name=\"electo\" onclick=\"javascript:actualiza_permisos($id_usuario,$id_permiso);\" $check />  $nombre_permiso                  
                        </div>	";
		}
		$div_permisos.="</div>";
}
?>
<form name="asigna_permisos" method="post" action="index.php">
<?php echo $div_permisos; ?>
</form>
<?php
}


function do_add_perimsos($id_usuario, $permisos, $busca, $page, $orden, $lista){

	foreach($permisos as $key => $value){
		$id_permiso = $permisos[$key];
		run_non_query("INSERT into permisos values ('','$id_usuario','$id_permiso')");
	}
	list_usuarios($busca, $page, $orde, $lista);

}
?>