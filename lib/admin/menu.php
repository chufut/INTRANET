<?php
	// Libreria para menu
	// regresa el menu checando seguridad
	function get_menu() {
		
		//CHECAMOS SI ES MENU DE REPRESENTACION
		if(is_representacion()){
				?>
				 <a href='index.php?panel_administracion' title='Home' class="blanco bold">Home</a> | <a href='index.php?logout=' title='Salir' class="blanco bold">Logout</a> |
				<?php	
			}
		
		
		
		// Checamos si esta loggeado
		else if(is_logged()) {
		   ?>
		     <a href='index.php?panel_administracion' title='Login' class="blanco bold">Home</a>   	|
			 <a href='index.php?directorio' title='Directorio' class="blanco bold">Directorio</a>   	|
			 <a href='index.php?mi_perfil' title='Mis Datos' class="blanco bold">Mis Datos</a>   	|
			  
			 <?PHP
			// Checamos si es admin
			if(is_admin()) {
			//<a href='index.php?admin_agents&page=1&busca=' title='Agents' class="blanco bold">Agents</a>|
				
				?>
                <a href='index.php?admin_funcionarios&page=1&busca=' title='Funcionarios' class="blanco bold">Admin Funcionarios</a> |
				<a href='index.php?admin_temas&page=1&busca=' title='Temas' class="blanco bold">Admin Temas</a> | 
               	<a href='index.php?admin_asignacion&page=1&busca=' title='Temas / Funcionarios' class="blanco bold">Temas / Funcionarios</a> | 
				
				<?php
					}
			else if(is_recepcion()) {
			//<a href='index.php?admin_agents&page=1&busca=' title='Agents' class="blanco bold">Agents</a>|
				
				?>
                <a href='index.php?admin_funcionarios&page=1&busca=' title='Funcionarios' class="blanco bold">Admin Funcionarios</a> |
				<?php
				}
				?>
				 <a href='index.php?logout=' title='Salir' class="blanco bold">Logout</a> |
				<?php	
			}
			
			
	} // - get_menu
?>