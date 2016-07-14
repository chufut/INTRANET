<?php
	// Libreria de globales
	
	
	// Muestra errores
	function error($titulo,$error) {
		?>
		
		<p class='highlight bold right big'>
			<?php echo $titulo; ?>
		</p>
		<p>
			<?php echo $error; ?>
		</p>
		
		<?php
	}
	
	// Para el resize de las imagenes con 2 thumbs
	function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth) {
		$extension = substr($imageName, strlen($imageName) - 3, strlen($imageName));
		if($extension == 'jpg' || $extension == 'jpe' || $extension == 'JPG' || $extension == 'JPE')
			$srcImg = imagecreatefromjpeg("$imageDirectory/$imageName") or error("Error: Imagen","Error al crear la imagen temporal.");
		else if($extension == 'gif' || $extension == 'GIF')
			$srcImg = imagecreatefromgif ("$imageDirectory/$imageName") or error("Error: Imagen","Error al crear la imagen temporal.");
		else if($extension == 'png' || $extension == 'PNG')
			$srcImg = imagecreatefrompng ("$imageDirectory/$imageName") or error("Error: Imagen","Error al crear la imagen temporal.");
		
		
		

		$origWidth = imagesx($srcImg);
		$origHeight = imagesy($srcImg);

		
		if($origWidth >= $origHeight) {
			$ratio = $origWidth / $thumbWidth;
			$thumbHeight = $origHeight / $ratio;
		} else {
			$thumbHeight = $thumbWidth;
			$ratio = $origHeight / $thumbHeight;
			$thumbWidth = $origWidth / $ratio;
		}


		//PARCHE PARA TAMAÑO DE IMAGEN PREDETERIMANDO EN NOTICIAS INICIO
		//$thumbWidth="208";
		//$thumbHeight="284";

		$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight) or error("Error: Imagen","Error al crear la imagen temporal.");
		$tiny_thumbImg = imagecreatetruecolor($thumbWidth/2, $thumbHeight/2) or error("Error: Imagen","Error al crear la imagen temporal.");
		
		imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($srcImg), imagesy($srcImg)) or error("Error: Imagen","Error al hacer el resize. (1)");
		imagecopyresampled($tiny_thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth/2, $thumbHeight/2, imagesx($srcImg), imagesy($srcImg)) or error("Error: Imagen","Error al hacer el resize. (2)");

		imagejpeg($thumbImg, "$thumbDirectory/$imageName") or error("Error: Imagen","Error al hacer el resize. (3)");
		imagejpeg($tiny_thumbImg, "$thumbDirectory/tiny_".$imageName) or error("Error: Imagen","Error al hacer el resize. (4)");
	}
	
	// Para el resize de las imagenes con 1 solo thumb
	function createThumbnailSingle($imageDirectory, $imageName, $thumbDirectory, $thumbWidth) {
		$extension = substr($imageName, strlen($imageName) - 3, strlen($imageName));
		
		
		

		if($extension == 'jpg' || $extension == 'jpe' || $extension == 'JPG' || $extension == 'JPE')
			$srcImg = imagecreatefromjpeg("$imageDirectory/$imageName") or error("Error: Imagen","No se pudo crear la imagen temporal para crear el thumbnail.");
		else if($extension == 'gif' || $extension == 'GIF')
			$srcImg = imagecreatefromgif ("$imageDirectory/$imageName") or error("Error: Imagen","No se pudo crear la imagen temporal para crear el thumbnail.");
		else if($extension == 'png' || $extension == 'PNG')
			$srcImg = imagecreatefrompng ("$imageDirectory/$imageName") or error("Error: Imagen","No se pudo crear la imagen temporal para crear el thumbnail.");
		
		echo $extension;
		echo $imageDirectory;
		echo $imageName;
		
		$origWidth = imagesx($srcImg);
		$origHeight = imagesy($srcImg);
		
	
		if($origWidth >= $origHeight) {
			$ratio = $origWidth / $thumbWidth;
			$thumbHeight = $origHeight / $ratio;
		} else {
			$thumbHeight = $thumbWidth;
			$ratio = $origHeight / $thumbHeight;
			$thumbWidth = $origWidth / $ratio;
		}


		$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight) or error("Error: Imagen","No se pudo crear la imagen temporal para crear el thumbnail. (2)");
		
		imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($srcImg), imagesy($srcImg)) or error("Error: Imagen","No se pudo hacer el resize a la imagen para crear el thumbnail.");

		imagejpeg($thumbImg, "$thumbDirectory/$imageName") or error("Error: Imagen","No se pudo crear el thumbnail.");
	}
	
	/* Se encarga del envio de correos
	function send_mail($from,$to,$subject,$content) {
		if($from == "") $from = "web@delegamexoi.ch";
		$headers = "From: $from\r\n" .
		"Content-Type: text/html;\n";
		
		//$content = utf8_decode($content);
		
		$host = "owa.delegamexoi.ch";
		$username = "achu";
 		$password = "Fht_jacks001";
 
		 $headers = array ('From' => $from,
		   'To' => $to,
		   'Subject' => $subject);
		 $smtp = Mail::factory('smtp',
		   array ('host' => $host,
			 'auth' => true,
			 'username' => $username,
			 'password' => $password));
		 
		 $mail = $smtp->send($to, $headers, $content);
				//if(mail($to,$subject,$content,$headers)) return true;
		//else return false;
	}*/
	
	
	function send_mail($from,$to,$subject,$content){
		$from = "achu@sre.gob.mx";
	
		$headers = "From: achu@sre.gob.mx\r\n" .
		"Content-Type: text/html;\n";
		
		$content = utf8_decode($content);
		
		if(mail($to,$subject,$content,$headers)) echo "";
		else echo "fallo al enviar el correo";
	
	}
	
	
	function bienvenida(){
	echo "
	<table border='0' background=' cellpadding='0' cellspacing='0'>
        <tr>
        
         <td></td>
        </tr>
        
        </table>
	
	";
	}
	
	function sube_archivo($archivo, $ruta){
 	                    $target_path = $ruta;
                        $file_name = date('s')."_".basename($archivo['name']);
                       $target_path .= $file_name;
					  // exit;
					
					echo $target_path;
					
			  if(move_uploaded_file($archivo['tmp_name'], $target_path)) {
							echo "Done ";//.date('s')."_".basename($archivo['name'])."<br/>";
						} else {
							echo "Error: No se pudo subir archivo";
                        }
	}
	
	function sube_archivo_protocolo($archivo, $ruta, $id_funcionario, $nombre){
		                $target_path = $ruta;
						$extension = end(explode(".", basename($archivo['name'])));
					    $file_name = $id_funcionario."_".$nombre.".".$extension;
                        $target_path .= $file_name;
					  // exit;
					
					//echo $target_path;
					
			  if(move_uploaded_file($archivo['tmp_name'], $target_path)) {
							echo "Archivo agregado correctamente";
						} else {
							echo "Error: No se pudo subir archivo";
                        }
	}
	
	function sube_archivo_notices($archivo, $ruta, $ref){
 	                    $target_path = $ruta;
                        $anio = date('Y');
						$extension = end(explode(".", basename($archivo['name'])));
	
						$file_name = "OGE".$ref."_$anio.".$extension;
                       $target_path .= $file_name;
					  // exit;
					
			  if(move_uploaded_file($archivo['tmp_name'], $target_path)) {
							//echo "Done ";//.date('s')."_".basename($archivo['name'])."<br/>";
						} else {
							echo "Error:","No se pudo subir archivo";
                        }
	}
	
	function sube_comunicacion($archivo, $ruta, $ref){
 	                    $target_path = $ruta;
                        $anio = date('Y');
						$extension = end(explode(".", basename($archivo['name'])));
	
						$file_name = $ref.".".$extension;
                       $target_path .= $file_name;
					  // exit;
					echo $target_path;
			  if(move_uploaded_file($archivo['tmp_name'], $target_path)) {
							//echo "Done ";//.date('s')."_".basename($archivo['name'])."<br/>";
						} else {
							echo "Error:","No se pudo subir archivo";
                        }
	}
	
	
	function get_month($mes, $idioma){
	
	if($idioma == "_en"){
	$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");#n
	}
	
	else if($idioma == "_fr"){
	$month = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Julliet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");#n
	}
	
	
	else $month = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");#n
 
     return $month[$mes-1];
    
}



function get_month_en($mes){
	
	$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");#n
	
     return $month[$mes-1];
    
}


function convert_number_to_words($number) {
	
	   
    $hyphen      = ' y ';
    $conjunction = 'to ';
    $separator   = ', ';
    $negative    = 'negativo ';
    $decimal     = ' punto ';
    $dictionary  = array(
        0                   => 'cero',
        1                   => 'uno',
        2                   => 'dos',
        3                   => 'tres',
        4                   => 'cuatro',
        5                   => 'cinco',
        6                   => 'seis',
        7                   => 'siete',
        8                   => 'ocho',
        9                   => 'nueve',
        10                  => 'diez',
        11                  => 'once',
        12                  => 'doce',
        13                  => 'trece',
        14                  => 'catorce',
        15                  => 'quince',
        16                  => 'dieciseis',
        17                  => 'diecisiete',
        18                  => 'dieciocho',
        19                  => 'diecinueve',
        20                  => 'veinte',
        30                  => 'treinta',
        40                  => 'cuarenta',
        50                  => 'cincuenta',
        60                  => 'sesenta',
        70                  => 'setenta',
        80                  => 'ochenta',
        90                  => 'noventa',
        100                 => 'cien',
        1000                => 'mil',
        1000000             => 'millón',
        1000000000          => 'billón',
        1000000000000       => 'trillón',
        1000000000000000    => 'cuadrillón',
        1000000000000000000 => 'quintillón'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}


function convert_moneda_to_words($moneda) {
$texto_moneda = "";
if($moneda == "CHF") $texto_moneda = "francos suizos";
else if($moneda == "USD") $texto_moneda = "dólares americanos";
else if($moneda == "MXN") $texto_moneda = "pesos mexicanos";

return $texto_moneda;

}



function fullUpper2($string){ 
  return strtr(strtoupper($string), array( 
      "à" => "À", 
      "è" => "È", 
      "ì" => "Ì", 
      "ò" => "Ò", 
      "ù" => "Ù", 
      "á" => "Á", 
      "é" => "É", 
      "í" => "Í", 
      "ó" => "Ó", 
      "ú" => "Ú", 
      "â" => "Â", 
      "ê" => "Ê", 
      "î" => "Î", 
      "ô" => "Ô", 
      "û" => "Û", 
      "ç" => "Ç",
	  "ñ" => "Ñ", 
    )); 
} 


function formatMoney($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
} 




?>