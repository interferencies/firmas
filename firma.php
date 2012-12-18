<?php
   /*
      Plugin Name: Recogida de Firmas
      Plugin URI: https://htn.laotracarboneria.net
      Description: Recoge firmas individuales y colectivas para paralizar desalojos
      Version: 0.1
      Author: hackthenight
      Author URI: https://htn.laotracarboneria.net
   */
   
   /*
   	Escribimos en la tabla wp_recogida_firmas las firmas
	las firmas tendran:
		identificador ID
		tipo A (associacio) / P (persona)
		correu
		nom
		NIF
		Adreca
		Codi Postal
		Ciutat
		Rebre_dades
		
	*/ 


	function show_firmas() {
		// muestra el numero de firmas
		global $wpdb;
		$table_name = $wpdb->prefix."firmas";
		$num_firmas = $wpdb->get_var("SELECT COUNT(*) AS NUM_FIRMAS FROM ".$wpdb->prefix."firmas;"); // TODO: wp_firmas no vale para otros prefijos
		$num_firmas = $num_firmas+2074;
		include('template/show_firmas.php');
	}

	function instala_firma() {
		global $wpdb;
		$table_name= $wpdb->prefix . "firmas";

		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table_name."'"))==1) return;

		$sql = " CREATE TABLE $table_name(
      		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
	      	tipo varchar(1) NOT NULL ,
		correo varchar(100) NOT NULL,
		nom varchar(200) NOT NULL,
		nif varchar(20) NOT NULL,
		adreca varchar(200),
		codi_postal int,
		ciutat varchar(200),
		rebre_dades boolean not null,
      		PRIMARY KEY ( `id` )
   		) ;";
		$wpdb->query($sql);
	}

	function add_firmas(){

		global $wpdb; 
		$table_name = $wpdb->prefix . "firmas";

		if (!isset($_POST['firmas'])) {
			include('template/panel.html');			
		} else {
			if ($_POST['nom']=="") { echo "<div style='background-color:#eee;text-align:center;'>Has d'afegir el teu nom</div>"; include("template/panel.html"); return;} 
			if ($_POST['tipo']=="") { echo "<div style='background-color:#eee;text-align:center;'>Has d'afegir el tipus</div>"; include("template/panel.html"); return;} 
			if ($_POST['correu']=="") { echo "<div style='background-color:#eee;text-align:center;'>Has d'afegir el correu</div>"; include("template/panel.html"); return;} 
			if ($_POST['info']=="") { echo "<div style='background-color:#eee;text-align:center;'>Has de dir si vols rebre informació.</div>"; include("template/panel.html"); return;} 
			if ($_POST['dni']=="") { echo "<div style='background-color:#eee;text-align:center;'>Iep! inventa't encara que sigui un DNI.</div>"; include("template/panel.html"); return;}
			if ($_POST["info"]==1) { wp_mail('luca@interferencies.net', '[Alta] Nova usuaria per la llista d\'informació', $_POST["nom"]." amb el correu: ".$_POST["correu"]); }
			
			// enviamos datos a la tabla
		
			$nom = $_POST['nom'];		
			$tipo = $_POST['tipo'];		
			$correo = $_POST['correu'];		
			$nif = $_POST['dni'];		
			$adreca = $_POST['adreca'];		
			$codi_postal = $_POST['cp'];		
			$ciutat = $_POST['poblacio'];		
			$rebre_dades = $_POST['info'];	

			$wpdb->query( $wpdb->prepare("INSERT INTO $table_name (nom,tipo,correo,nif,adreca,codi_postal,ciutat,rebre_dades) VALUES (%s,%s,%s,%s,%s,%s,%s,%s);",$nom,$tipo,$correo,$nif,$adreca,$codi_postal,$ciutat,$rebre_dades));
	

 
			echo "S'ha afegit la teva firma. Gràcies ".$_POST['nom'].".";
		}
	}

	add_action('activate_firma/firma.php','instala_firma');
	add_shortcode('show_firmas','show_firmas');
	add_shortcode('form_firmas','add_firmas');

?>
