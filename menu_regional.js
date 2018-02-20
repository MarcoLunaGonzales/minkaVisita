//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menu_regional.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menu_regional_menu {z-index:999;border-color:#ffffff;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#ffffff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menu_regional_plain, a.menu_regional_plain:link, a.menu_regional_plain:visited{text-align:left;background-color:#ffffff;color:#000000;text-decoration:none;border-color:#ffffff;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_regional_plain:hover, a.menu_regional_plain:active{background-color:#000000;color:#ffffff;text-decoration:none;border-color:#ffffff;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_regional_l:link, a.menu_regional_l:visited{text-align:left;background:#ffffff url("+loc+"menu_regional_l.gif) no-repeat right;color:#000000;text-decoration:none;border-color:#ffffff;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("a.menu_regional_l:hover, a.menu_regional_l:active{background:#000000 url("+loc+"menu_regional_l2.gif) no-repeat right;color: #ffffff;text-decoration:none;border-color:#ffffff;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0xffffff;
var bc=0x000000;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menu_regional_b1",".gif",21,140,"javascript:;","","Medicos",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b2",".gif",21,140,loc+"../"+"navegador_grupo_especial.php","","Grupos Especiales",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b3",".gif",21,140,loc+"../"+"navegador_funcionarios_regional.php","","Visitadores",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b4",".gif",21,140,"javascript:;","","Reportes",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b5",".gif",21,140,"javascript:;","","Contactos",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b6",".gif",21,140,"javascript:;","","Personal",2,2,"menu_regional_plain");
mainMenuItem("menu_regional_b7",".gif",21,140,loc+"../"+"ingreso_lineas_regional.php","","Inicio",2,2,"menu_regional_plain");
endMainMenu("",0,0);

startSubmenu("menu_regional_b6","menu_regional_menu",163);
submenuItem("Cambiar Contraseña",loc+"../"+"cambiar_contrasena_regional.php","","menu_regional_plain");
endSubmenu("menu_regional_b6");

startSubmenu("menu_regional_b5","menu_regional_menu",140);
submenuItem("Administrador",loc+"../"+"contactos_regional_hermes.php","","menu_regional_plain");
submenuItem("Jefe de Línea",loc+"../"+"contactos_regional_jefelinea.php","","menu_regional_plain");
endSubmenu("menu_regional_b5");

startSubmenu("menu_regional_b4_4","menu_regional_menu",260);
submenuItem("Cobertura",loc+"../"+"rpt_op_regional_cobertura.php","","menu_regional_plain");
submenuItem("Contactos Rezagados",loc+"../"+"rpt_op_regional_contactos_rezagados.php","","menu_regional_plain");
submenuItem("Medicos en Rutero Maestro",loc+"../"+"rpt_op_regional_medicos_rutero_maestro.php","","menu_regional_plain");
submenuItem("Rutero Maestro vs. Ejecutado",loc+"../"+"rpt_op_regional_medicos_rutero_maestro.php","","menu_regional_plain");
submenuItem("Medicos Asignados x Especialidad",loc+"../"+"rpt_op_regional_medicos_asignados.php","","menu_regional_plain");
endSubmenu("menu_regional_b4_4");

startSubmenu("menu_regional_b4_3","menu_regional_menu",302);
submenuItem("Cantidad de Muestras y MA planificadas",loc+"../"+"rpt_op_regional_prod_visitador.php","","menu_regional_plain");
submenuItem("Cantidad de Muestras y MA entregadas",loc+"../"+"rpt_op_regional_prod_ent.php","","menu_regional_plain");
endSubmenu("menu_regional_b4_3");

startSubmenu("menu_regional_b4_2","menu_regional_menu",159);
submenuItem("Parrilla Promocional",loc+"../"+"rpt_op_parrilla_regional.php","","menu_regional_plain");
endSubmenu("menu_regional_b4_2");

startSubmenu("menu_regional_b4_1","menu_regional_menu",242);
submenuItem("Frecuencias de Visita",loc+"../"+"rpt_op_regional_frecuencia_visita.php","","menu_regional_plain");
submenuItem("Grupos Especiales",loc+"../"+"rpt_op_regional_grupos_especiales.php","","menu_regional_plain");
submenuItem("Productos Objetivos y Filtrados",loc+"../"+"rpt_op_regional_prod_med.php","","menu_regional_plain");
endSubmenu("menu_regional_b4_1");

startSubmenu("menu_regional_b4","menu_regional_menu",192);
mainMenuItem("menu_regional_b4_1","Medicos",0,0,"javascript:;","","",1,1,"menu_regional_l");
mainMenuItem("menu_regional_b4_2","Parrillas de Productos",0,0,"javascript:;","","",1,1,"menu_regional_l");
mainMenuItem("menu_regional_b4_3","Productos",0,0,"javascript:;","","",1,1,"menu_regional_l");
mainMenuItem("menu_regional_b4_4","Visitadores",0,0,"javascript:;","","",1,1,"menu_regional_l");
endSubmenu("menu_regional_b4");

startSubmenu("menu_regional_b1_2_2","menu_regional_menu",105);
submenuItem("Alfabetico",loc+"../"+"navegador_medicos_lineas_resumen.phpnavegador_medicos_lineas_resumen.php","","menu_regional_plain");
submenuItem("Categoria",loc+"../"+"navegador_medicos_categoria.php","","menu_regional_plain");
submenuItem("Especialidad",loc+"../"+"navegador_medicos_especialidad.php","","menu_regional_plain");
submenuItem("RUC",loc+"../"+"navegador_medicos_ruc.php","","menu_regional_plain");
endSubmenu("menu_regional_b1_2_2");

startSubmenu("menu_regional_b1_2","menu_regional_menu",105);
submenuItem("Detallado",loc+"../"+"navegador_medicos_lineas.php","","menu_regional_plain");
mainMenuItem("menu_regional_b1_2_2","Resumido",0,0,"javascript:;","","",1,1,"menu_regional_l");
endSubmenu("menu_regional_b1_2");

startSubmenu("menu_regional_b1_1","menu_regional_menu",127);
submenuItem("Listado General",loc+"../"+"anadir_medico_linea_general.php","","menu_regional_plain");
submenuItem("Búsqueda",loc+"../"+"anadir_medico_linea_busqueda.php","","menu_regional_plain");
endSubmenu("menu_regional_b1_1");

startSubmenu("menu_regional_b1","menu_regional_menu",217);
mainMenuItem("menu_regional_b1_1","Añadir Medicos a la Línea",0,0,"javascript:;","","",1,1,"menu_regional_l");
mainMenuItem("menu_regional_b1_2","Medicos de la Línea",0,0,loc+"../"+"navegador_bajas_medicos.php","","",1,1,"menu_regional_l");
endSubmenu("menu_regional_b1");

loc="";
