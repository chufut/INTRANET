<?php 
include "../admin/db.php";

$sector = $_GET['sector'];
$subcategoria = $_GET['subcategoria'];
$busca = $_GET['busca'];
$page =$_GET['page'];
$orden = $_GET['orden'];
$lista = $_GET['lista'];
$fiesta_nacional = $_GET['fiesta'];

//Filtros
		if($fiesta_nacional == "SI"){
			$filtro_fiesta = "AND fiesta_nacional = 'SI'";
			} else $filtro_fiesta = "";
			

		if($sector != ""){
			if($sector != "todos"){
			$filtro_sector = "AND sector = '$sector'";
			} else $filtro_sector = "";
		}
		
		if($subcategoria != "" && $sector != "todos") $filtro_subcategoria = "AND sub_categoria = '$subcategoria'"; else $filtro_subcategoria = "";
		
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
INNER JOIN usuarios ON directorio.usuario = usuarios.id where (1 = 1) $where $filtro_sector $filtro_subcategoria $filtro_fiesta order by $orden $lista_query $limit");


	$contents = "";
	$contents = "<table><tr><td>CATEGORÍA</td><td>SUBCATEGORÍA</td><td>NOMBRE</td><td>TÍTULO</td><td>CARGO</td><td>INSTITUCIÓN</td><td>DIRECCIÓN</td><td>TELÉFONO</td><td>CORREO</td><td>FECHA ACTUALIZACIÓN</td><td>FUNCIONARIO</td><td>FIESTA NACIONAL</td></tr>";
	if($contacto)
		foreach($contacto as $key => $value) {
		if($key	 % 2 == 0) $renglon = 0; else $renglon = 1;
		
				$id = $contacto[$key]['id'];
				$sector = $contacto[$key]['sector'];
				$nombre_contacto = $contacto[$key]['nombre_contacto'];
				$titulo = $contacto[$key]['titulo'];
				$cargo = $contacto[$key]['cargo'];
				$institucion = $contacto[$key]['institucion'];
				$direccion = $contacto[$key]['direccion'];
				$telefono = $contacto[$key]['telefono'];
				$correo = $contacto[$key]['correo'];
				$subcategoria = $contacto[$key]['subcategoria'];
				$fecha_actualizacion = $contacto[$key]['fecha_actualizacion'];
				$usuario = $contacto[$key]['usuario'];
				$fiesta_nacional = $contacto[$key]['fiesta_nacional'];
			$contents.="<tr><td>$sector</td><td>$subcategoria</td><td>$nombre_contacto</td><td>$titulo</td><td>$cargo</td><td>$institucion</td><td>$direccion</td><td>$telefono</td><td>$correo</td><td>$fecha_actualizacion</td><td>$usuario</td><td>$fiesta_nacional</td></tr>";
				
		}
	$contents.= "</table>";
	//$contenido =  mb_convert_encoding($contents,'utf-16','utf-8');
$filename ="contactos_".date("Y-m-d_H-i",time()).".xls";
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header('Content-Disposition: attachment; filename='.$filename);
?>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<?php 
echo $contents; 
?>