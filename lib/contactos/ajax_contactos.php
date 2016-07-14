<?php
//FUNCIONES DE EDICIÓN DE AJAX 

	include '../admin/db.php';
	
	if(isset($_GET['load_subcategorias'])) {
		load_subcategorias($_GET['id_sector']);
	}
	
	
	function load_subcategorias($sector){
		$resultado = "";
		$subcategorias = run_select_query("Select id, nombre from directorio_subcategorias where id_padre = '$sector'");
		//echo "Select nombre from directorio_subcategorias where id_padre = '$sector'";
		foreach($subcategorias as $key => $value){
		$resultado.=$subcategorias[$key]['id'].",".$subcategorias[$key]['nombre']."|BK|";
		}
		$resultado = substr($resultado, 0, -4);
		//return $resultado; 
		echo $resultado;
	}	
	
?>