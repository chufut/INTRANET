<?php 
function controller() {
                // login
               // if(isset($_GET['login'])) show_login_form($_GET['msg']);
                //elseif(isset($_POST['do_login'])) do_login($_POST['usuario'],$_POST['password']);
                
				
		 /*if(isset($_GET['panel_administracion'])) {
                        if(is_admin())panel_administracion_admin();
						elseif(is_user() || is_protocolo() || is_comunicaciones())panel_administracion();
						
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
		*/
		
		if(isset($_GET['panel_administracion'])) {
                        if(is_admin() || is_user() || is_protocolo() || is_comunicaciones())panel_administracion();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
				
				//DIRECTORIO Y DATOS PERSONALES directorio_interno-php
				elseif(isset($_GET['mi_perfil'])) {
                        if(is_admin() || is_logged())show_perfil();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['directorio'])) {
                        if(is_admin() || is_logged())list_directorio();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_perfil'])) {
                        if(is_admin() || is_logged())edit_perfil_form();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_edit_perfil']) ) {
				      if(is_admin() || is_logged())do_edit_perfil($_POST['nombre'],$_POST['iniciales'],$_POST['correo'],$_FILES['archivo'],$_POST['password'],$_POST['oficina'],$_POST['extension'],$_POST['telefono'],$_POST['celular'],$_POST['direccion'],$_POST['cumple'],$_POST['id']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}
				
				
				
                // FUNCTIONARIOS  .php				
                elseif(isset($_GET['admin_funcionarios'])) {
                        if(is_admin() || is_protocolo() || is_user())list_funcionarios($_GET['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['ver_funcionario']) ) {
                        if(is_admin() || is_protocolo() || is_user()) ver_funcionario($_GET['id'],$_GET['busca'],$_GET['estado'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['agregar_documentos']) ) {
                        if(is_admin() || is_protocolo() || is_user()) add_documento_form($_GET['accion'],$_GET['id_gestion'],$_GET['id'],$_GET['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_funcionario']) ) {
                        if(is_admin() || is_protocolo() || is_user()) add_funcionario_form($_GET['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_funcionario'])) {
                        if(is_admin() || is_protocolo() || is_user()) do_add_funcionario($_POST['nombre'], $_POST['titulo'],$_POST['cargo'],$_POST['dependencia'],$_POST['estado'],$_POST['id_funcionario'],$_POST['tipo'],$_POST['expediente'],$_POST['correo'],$_GET['estado'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_documento'])) {
                        if(is_admin() || is_protocolo() || is_user()) do_add_documento($_POST['accion'],$_POST['id_gestion'],$_POST['id_tramite'],$_POST['id_funcionario'],$_POST['nombre'],$_POST['valor'],$_POST['fecha_expedicion'],$_POST['fecha_expiracion'],$_FILES['archivo'],$_POST['estado'],$_POST['estado_doc'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_funcionario'])) {
                        if(is_admin() || is_user()) del_funcionario($_GET['id'],$_GET['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_funcionarios'])) {
                        if(is_admin() || is_protocolo() || is_user()) edit_funcionario_form($_GET['id'],$_GET['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_funcionario'])) {
                        if(is_admin() || is_protocolo() || is_user())do_edit_funcionario($_POST['nombre'], $_POST['titulo'],$_POST['cargo'],$_POST['dependencia'],$_POST['estado'],$_POST['funcionario'],$_POST['tipo'],$_POST['expediente'],$_POST['correo'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				} elseif(isset($_GET['registra_entrada'])) {
                        if(is_admin() || is_protocolo() || is_user()) registra_entrada($_GET['id']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['ver_documento'])) {
                        if(is_admin() || is_protocolo() || is_user()) ver_documento($_GET['archivo'], $_GET['carpeta']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	elseif(isset($_GET['del_archivo_funcionario'])) {
                        if(is_admin() || is_protocolo() || is_user()) del_archivo_funcionario($_GET['id'],$_GET['id_funcionario']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
				
				 // USUARIOS usuarios.php				
                elseif(isset($_GET['admin_usuarios'])) {
                        if(is_admin() || is_user())list_usuarios($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_usuario']) ) {
                        if(is_admin() || is_user()) add_usuario_form($_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_usuario'])) {
                        if(is_admin() || is_user()) do_add_usuario($_POST['representacion'], $_POST['departamento'],$_POST['nombre'], $_POST['cargo'],$_POST['iniciales'],$_POST['tipo'],$_POST['correo'],$_FILES['foto'],$_POST['password'],$_POST['nivel'],$_POST['oficina'],$_POST['extension'],$_POST['telefono'],$_POST['celular'],$_POST['direccion'],$_POST['cumple'],$_POST['id_funcionario'],$_POST['estado'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_usuario'])) {
                        if(is_admin() || is_user()) del_usuario($_GET['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_usuarios'])) {
                        if(is_admin() || is_user()) edit_usuario_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_usuario'])) {
                        if(is_admin() || is_user())do_edit_usuario($_POST['representacion'], $_POST['departamento'],$_POST['nombre'], $_POST['cargo'],$_POST['iniciales'],$_POST['tipo'],$_POST['correo'],$_FILES['foto'],$_POST['password'],$_POST['nivel'],$_POST['oficina'],$_POST['extension'],$_POST['telefono'],$_POST['celular'],$_POST['direccion'],$_POST['cumple'],$_POST['id_funcionario'],$_POST['estado'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}
				
				
				//PERMISOS
				elseif(isset($_GET['asigna_permisos'])) {
                        if(is_admin()) asigna_permisos($_GET['id_usuario'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_permisos'])) {
                        if(is_admin())do_add_perimsos($_POST['id_usuario'], $_POST['permisos'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}
				


 // TRAMITES PROTOCOLO protocolo.php	
 				//ADMINISTRADOR
 				 elseif(isset($_GET['admin_tramites'])) {
                        if(is_admin() || is_protocolo())list_tramites($_GET['busca'],$_GET['page']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_tramite'])) {
                        if(is_admin() || is_protocolo()) add_tramite_form($_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_tramite'])) {
                        if(is_admin() || is_protocolo()) do_add_tramite($_POST['nombre'],$_POST['categoria'],$_POST['formato'],$_POST['tiempo_estimado']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_tramite'])) {
                        if(is_admin() || is_protocolo()) edit_tramite_form($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_tramite'])) {
                        if(is_admin() || is_protocolo())do_edit_tramite($_POST['nombre'],$_POST['categoria'],$_POST['formato'],$_POST['tiempo_estimado'],$_POST['id'],$_POST['page']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_GET['del_tramite'])) {
                        if(is_admin() || is_protocolo()) del_tramite($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
 
 			
				 // PROVEEDORES proveedores.php				
                elseif(isset($_GET['admin_proveedores'])) {
                        if(is_admin() || is_user())list_proveedores($_GET['busca'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_proveedor']) ) {
                        if(is_admin() || is_user()) add_proveedor_form($_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_proveedor'])) {
                        if(is_admin() || is_user()) do_add_proveedor($_POST['empresa'], $_POST['producto'],$_POST['nombre'],$_POST['correo'],$_POST['telefono'],$_POST['extension'],$_POST['celular'],$_POST['pagina'],$_POST['direccion'],$_POST['fecha'],$_FILES['imagen1'],$_POST['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_proveedor'])) {
                        if(is_admin() || is_user()) del_proveedor($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_proveedor'])) {
                        if(is_admin() || is_user()) edit_proveedor_form($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_proveedor'])) {
                        if(is_admin() || is_user())do_edit_proveedor($_POST['empresa'], $_POST['producto'],$_POST['nombre'],$_POST['correo'],$_POST['telefono'],$_POST['extension'],$_POST['celular'],$_POST['pagina'],$_POST['direccion'],$_POST['fecha'], $_FILES['imagen1'],$_POST['id'],$_POST['page']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				} 
				
				
				 // PROYECTOS proyectos.php				
                elseif(isset($_GET['admin_proyectos'])) {
                        if(is_admin() || is_user())list_proyectos($_GET['busca'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_proyecto']) ) {
                        if(is_admin() || is_user()) add_proyecto_form($_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_proyecto'])) {
                        if(is_admin() || is_user()) do_add_proyecto($_POST['nombre'], $_POST['tipo'],$_POST['secciones'],$_POST['footer'],$_POST['facebook_id'],$_POST['facebook'],$_POST['twitter'],$_POST['font']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_proyecto'])) {
                        if(is_admin() || is_user()) del_proyecto($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_proyecto'])) {
                        if(is_admin() || is_user()) edit_proyecto_form($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_proyecto'])) {
                        if(is_admin() || is_user())do_edit_proyecto($_POST['nombre'], $_POST['tipo'],$_POST['secciones'],$_POST['footer'],$_POST['facebook_id'],$_POST['facebook'],$_POST['twitter'],$_POST['font'],$_POST['id'],$_POST['page']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_GET['agregar_secciones'])) {
				       if(is_admin() || is_user()) add_secciones_form($_GET['id']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_POST['do_add_seccion'])) {
                        if(is_admin() || is_user()) do_add_seccion($_POST['titulo_es'], $_POST['subtitulo_es'],$_POST['contenido_es'],$_POST['titulo_en'], $_POST['subtitulo_en'],$_POST['contenido_en'],$_POST['titulo_fr'], $_POST['subtitulo_fr'],$_POST['contenido_fr'],$_FILES['foto_seccion'], $_POST['color_seccion'],$_POST['color_texto'],$_POST['orden'],$_POST['despliegue'], $_POST['id_proyecto']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_seccion'])) {
                        if(is_admin() || is_user()) del_seccion($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 
				
				
				
				// CORRELATIVO correlativo.php
                elseif(isset($_GET['admin_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())list_correlativo_salida($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())add_correlativo_salida_form($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())do_add_correlativo_salida($_POST['tipo_documento'],$_POST['destino'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['id_funcionario'],$_POST['fecha'],$_POST['texto_sicar'],$_POST['archivo'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['edit_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())edit_correlativo_salida_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'] ,$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_POST['do_edit_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())do_edit_correlativo_salida($_POST['tipo_documento'],$_POST['destino'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['texto_sicar'],$_POST['archivo'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista'],$_POST['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['del_correlativo_salida'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user()) del_correlativo_salida($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'] ,$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
				
				
				// CORRELATIVO DUPLICADOS correlativo_bis.php
                elseif(isset($_GET['admin_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())list_correlativo_bis($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())add_correlativo_bis_form($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())do_add_correlativo_bis($_POST['correlativo'],$_POST['tipo_documento'],$_POST['destino'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['id_funcionario'],$_POST['fecha'],$_POST['texto_sicar'],$_POST['archivo'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['edit_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())edit_correlativo_bis_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'] ,$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_POST['do_edit_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())do_edit_correlativo_bis($_POST['correlativo'],$_POST['tipo_documento'],$_POST['destino'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['texto_sicar'],$_POST['archivo'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista'],$_POST['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['del_correlativo_bis'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user()) del_correlativo_bis($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'] ,$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
				
				
				
                
                //Correlativo archivo
                elseif(isset($_GET['add_correlativo_archivo'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())add_correlativo_archivo($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_correlativo_archivo'])) {
                        if(is_admin()|| is_protocolo() || is_comunicaciones() || is_user())do_add_correlativo_archivo($_POST['id'],$_FILES['archivo'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
                //Correlativo recibidos
				elseif(isset($_GET['admin_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones() )list_correlativo_recibidos($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones())add_correlativo_recibidos_form($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones())do_add_correlativo_recibidos($_POST['tipo_documento'],$_POST['fecha'],$_POST['documento'],$_POST['procedencia'],$_POST['turnado'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['edit_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones())edit_correlativo_recibidos_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_POST['do_edit_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones())do_edit_correlativo_recibidos($_POST['tipo_documento'],$_POST['fecha'],$_POST['documento'],$_POST['procedencia'],$_POST['turnado'],$_POST['asunto'],$_POST['referencia'],$_POST['expediente'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['del_correlativo_recibidos'])) {
                        if(is_admin() || is_comunicaciones()) del_correlativo_recibidos($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['reiniciar_correlativo'])) {
                        if(is_admin()) reiniciar_correlativo();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
			
				
                // TRAMITES PROTOCOLO protocolo.php	
 				//ADMINISTRADOR
 				 elseif(isset($_GET['admin_tramites'])) {
                        if(is_admin() || is_protocolo())list_tramites($_GET['busca'],$_GET['page']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_tramite'])) {
                        if(is_admin() || is_protocolo()) add_tramite_form($_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_tramite'])) {
                        if(is_admin() || is_protocolo()) do_add_tramite($_POST['nombre'],$_POST['categoria'],$_POST['formato'],$_POST['tiempo_estimado']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_tramite'])) {
                        if(is_admin() || is_protocolo()) edit_tramite_form($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_tramite'])) {
                        if(is_admin() || is_protocolo())do_edit_tramite($_POST['nombre'],$_POST['categoria'],$_POST['formato'],$_POST['tiempo_estimado'],$_POST['id'],$_POST['page']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_GET['del_tramite'])) {
                        if(is_admin() || is_protocolo()) del_tramite($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
 
 			//GESTIONES
                elseif(isset($_GET['realizar_gestion'])) {
                        if(is_admin() || is_protocolo())realizar_gestion($_GET['id_gestion'],$_GET['id_tramite'],$_GET['id_funcionario']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }elseif(isset($_GET['admin_gestiones'])) {
                        if(is_admin() || is_protocolo())list_gestiones($_GET['busca'],$_GET['page']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['admin_gestiones_terminadas'])) {
                        if(is_admin() || is_protocolo())list_gestiones_terminadas($_GET['busca'],$_GET['page']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['add_gestion'])) {
                        if(is_admin() || is_protocolo()) add_gestion_form($_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_gestion'])) {
                        if(is_admin() || is_protocolo()) do_add_gestion($_POST['tramite'],$_POST['id_funcionario'],$_POST['funcionario'],$_POST['busca'],$_POST['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_gestiones'])) {
                        if(is_admin() || is_protocolo()) edit_gestion_form($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_gestion'])) {
                        if(is_admin() || is_protocolo())do_edit_gestion($_POST['categoria'],$_POST['subcategoria'],$_POST['titulo'],$_POST['gestion'],$_FILES['imagen1'],$_FILES['imagen2'],$_FILES['imagen3'],$_FILES['imagen4'],$_FILES['imagen5'],$_FILES['imagen6'],$_POST['id'],$_POST['page']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_GET['del_gestion'])) {
                        if(is_admin() || is_protocolo()) del_gestion($_GET['id'],$_GET['page']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['cambia_estado'])) {
                        if(is_admin() || is_protocolo()) cambia_estado($_GET['id_gestion'],$_GET['estado']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
				 elseif(isset($_GET['ver_graficas_protocolo'])) {
                        if(is_admin() || is_protocolo()) ver_graficas_protocolo($_GET['id'],$_GET['estado']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
				
				 elseif(isset($_GET['ver_documentos_vencidos'])) {
                        if(is_admin() || is_protocolo()) list_documentos_vencidos();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	
                
                elseif(isset($_GET['dame_seguimiento'])) {
                        if(is_admin() || is_protocolo()) dame_seguimiento($_GET['id']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 	elseif(isset($_GET['edit_seguimiento'])) {
                        if(is_admin() || is_protocolo()) edit_seguimiento($_GET['id']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_edit_seguimiento'])) {
                        if(is_admin() || is_protocolo()) do_edit_seguimiento($_POST['id'],$_POST['id_gestion'],$_POST['fecha_mensaje'],$_POST['mensaje']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");	}	elseif(isset($_POST['do_add_seguimiento'])) {
                        if(is_admin() || is_protocolo()) do_add_seguimiento($_POST['id_gestion'],$_POST['fecha_mensaje'],$_POST['mensaje']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_seguimiento'])) {
                        if(is_admin() || is_protocolo()) del_seguimiento($_GET['id'],$_GET['id_gestion']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
                
              

				
				//EVENTOS CULTURALES
              elseif(isset($_GET['admin_eventos'])) {
                        if(is_admin() || is_user())list_eventos($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_evento'])) {
                        if(is_admin() || is_user()) add_evento_form($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_evento'])) {
                        if(is_admin() || is_user()) do_add_evento($_POST['nombre'],$_POST['fecha'],$_POST['hora'],$_POST['lugar'],$_POST['descripcion'],$_FILES['imagen'],$_POST['estado'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_evento'])) {
                        if(is_admin()) edit_evento_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_POST['do_edit_evento'])) {
                        if(is_admin())do_edit_evento($_POST['nombre'],$_POST['fecha'],$_POST['lugar'],$_POST['hora'],$_POST['descripcion'],$_FILES['imagen'],$_POST['estado'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista'],$_POST['anio']);
         			    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				}  elseif(isset($_GET['del_evento'])) {
                        if(is_admin() || is_user()) del_evento($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista'],$_GET['anio']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 
				
				
				 //REGISTRO MEXICANOS
                elseif(isset($_GET['admin_personas'])) {
                        if(is_admin()|| is_user()) list_personas($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['ver_mapa_personas'])) {
                        if(is_admin()|| is_user()) ver_mapa_personas();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['ver_mapa_deslaves'])) {
                        if(is_admin()|| is_user()) ver_mapa_deslaves();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['ver_mapa_inundaciones'])) {
                        if(is_admin()|| is_user()) ver_mapa_inundaciones();
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  
                   elseif(isset($_GET['add_personas'])) {
                        if(is_admin() || is_user()) add_personas_form($_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
	                    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } 
                   elseif(isset($_POST['do_add_personas'])) {
                        if(is_admin() || is_user()) do_add_personas($_POST['nombre1'],$_POST['nombre2'],$_POST['apellido1'],$_POST['apellido2'],$_POST['sexo'],$_POST['fecha_nacimiento'],$_POST['pais_nacimiento'],$_POST['estado_nacimiento'],$_POST['lugar_nacimiento'],$_POST['tipo_documento'],$_POST['numero_documento'], $_POST['caracteristica_migratoria'],$_POST['otra_caracteristica'],$_POST['direccion'],$_POST['municipio'],$_POST['departamento'],$_POST['telcasa'],$_POST['telcelular'],$_POST['ocupacion'],$_POST['escolaridad'],$_POST['email'],$_POST['nombre_conyuge'],$_POST['emergencianombre'],$_POST['emergenciatelefono'],$_POST['emergencianombre2'],$_POST['emergenciatelefono2'],$_POST['coordenada_a'],$_POST['coordenada_b'],$_FILES['foto'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
	                    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['edit_personas'])) {
                        if(is_admin()|| is_user())edit_personas_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_edit_personas'])) {
                        if(is_admin()|| is_user())do_edit_personas($_POST['nombre1'],$_POST['nombre2'],$_POST['apellido1'],$_POST['apellido2'],$_POST['sexo'],$_POST['fecha_nacimiento'],$_POST['pais_nacimiento'],$_POST['estado_nacimiento'],$_POST['lugar_nacimiento'],$_POST['tipo_documento'],$_POST['numero_documento'], $_POST['caracteristica_migratoria'],$_POST['otra_caracteristica'],$_POST['direccion'],$_POST['municipio'],$_POST['departamento'],$_POST['telcasa'],$_POST['telcelular'],$_POST['ocupacion'],$_POST['escolaridad'],$_POST['email'],$_POST['nombre_conyuge'],$_POST['emergencianombre'],$_POST['emergenciatelefono'],$_POST['emergencianombre2'],$_POST['emergenciatelefono2'],$_POST['coordenada_a'],$_POST['coordenada_b'],$_FILES['foto'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['ver_persona'])) {
                        if(is_admin() || is_user()) ver_persona($_GET['id']);
	                    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['del_personas'])) {
                        if(is_admin()|| is_user()) del_personas($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
				//administracion/contactos_embajada.php
				 elseif(isset($_GET['admin_contactos'])) {
					     if(is_admin()|| is_user()) list_contactos($_GET['sector'],$_GET['subcategoria'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);

                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_GET['exportar_contactos'])) {
                        if(is_admin()|| is_user()) exportar_contactos($_GET['sector'],$_GET['subcategoria'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);

                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }  elseif(isset($_GET['add_contacto']) ) {
                        if(is_admin() || is_user()) add_contacto_form($_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
						else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_add_contacto'])) {
					    if(is_admin() || is_user()) do_add_contacto($_POST['sector'],$_POST['nombre'],$_POST['titulo'],$_POST['cargo'],$_POST['institucion'],$_POST['direccion'],$_POST['telefono'],$_POST['correo'],$_POST['subcategoria'],$_POST['fiesta_nacional'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
					    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				} elseif(isset($_GET['edit_contacto'])) {
                        if(is_admin()|| is_user())edit_contacto_form($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                } elseif(isset($_POST['do_edit_contacto'])) {
					    if(is_admin() || is_user()) do_edit_contacto($_POST['sector'],$_POST['nombre'],$_POST['titulo'],$_POST['cargo'],$_POST['institucion'],$_POST['direccion'],$_POST['telefono'],$_POST['correo'],$_POST['subcategoria'],$_POST['fiesta_nacional'],$_POST['id'],$_POST['busca'],$_POST['page'],$_POST['orden'],$_POST['lista']);
					    else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
				} elseif(isset($_GET['del_contacto'])) {
					      if(is_admin()|| is_user()) del_contacto($_GET['id'],$_GET['busca'],$_GET['page'],$_GET['orden'],$_GET['lista']);
                        else error("Error de Permisos.","No tiene los permisos suficientes para acceder esta sección.");
                }
				
	                else  show_login_form('Intranet Guatemala');
        }	

?>