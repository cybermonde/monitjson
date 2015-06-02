<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Refresh" content="600">
	<title>Monitoring : httpd</title>
	<link href="base.css" rel="stylesheet" type="text/css">
	<script language="javascript" type="text/javascript" src="lib/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="lib/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="lib/jquery.flot.categories.js"></script>
	<script type="text/javascript">

	$(function() {

		var httpd = [ 
			<?php
				// spécifie user_agent pour toutes les requêtes
				ini_set('user_agent', 'monitjson_powered_by_cybermonde');
				// si pas de date en input, date du jour
				if(isset($_GET['date_trait'])) {
					$date_jour = $_GET['date_trait'];
				} else {
					$date_jour = date("Ymd");
				}
				// création du jour avant/après et d'une date "lisible"
				$date_plus = date_create($date_jour);
				date_modify($date_plus, '+1 day');
				$date_moins = date_create($date_jour);
				date_modify($date_moins, '-1 day');
				$date_jolie = date_create($date_jour);
				// test sur existence fichier
				$fic_distant = 'http://www.your.website/json/'.$date_jour.'.result';
				$fic_headers = @get_headers($fic_distant);
				if($fic_headers[0] == 'HTTP/1.1 404 Not Found') {
					// si pas de fichier, utilise un fichier bidon
					$fic_source = "0.result";
					require_once($fic_source);
					// ligne du graphe (= dans ce cas axe X) en noir
					$color_ligne = "\"#000000\"";
					// message d'erreur sur la zone de graphe
					$msg_erreur = "$(\"#placeholder\").append(\"<div style='position:absolute;left:300px;top:150px;color:#FF0000;font-size:large'>Pas de données pour le ".date_format($date_jolie, 'd/m/Y')."</div>\");";
				}
				else {
					$fic_source = $fic_distant;
					include $fic_distant;
					// ligne du graphe en rouge
					$color_ligne = "\"#FF0000\"";
					$msg_erreur = "";
				}
			?>
			];

		$.plot("#placeholder", [ httpd ], {
			series: {
				color: <?php echo $color_ligne; ?>,
				lines: {
					show: true
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0,
				// bornes à afficher
				ticks: [[0, "00:00"], [8, "02:00"], [16, "04:00"], [24, "06:00"], [32, "08:00"], [40, "10:00"], [48, "12:00"], [56, "14:00"], [64, "16:00"], [72, "18:00"], [80, "20:00"], [88, "22:00"], [95, "23:45"]]
			},
			yaxis: {
				min: 0,
				tickDecimals: 0
			}
		});

		 <?php echo $msg_erreur; ?>

		// ajout de la version dans le pied de page

		$("#footer").append("<a href=\"http://www.flotcharts.org\">Flot</a> " + $.plot.version);
	});

	</script>
</head>
<body>

	<div id="header">
		<a href="./" alt="index"><img src="logo.png"></a><h2>Monitoring<br/>httpd</h2>
	</div>

	<div id="content">

		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>
		
		<p><a class="flechebleue" href="<?php echo "?date_trait=".(date_format($date_moins, 'Ymd')); ?>"><<</a> Evolution du nombre de process durant la journée du <?php echo date_format($date_jolie, 'd/m/Y'); ?> <a class="flechebleue" href="<?php echo "?date_trait=".(date_format($date_plus, 'Ymd')); ?>">>></a></p>
	</div>

	<div id="footer">
		<a href="<?php echo $fic_source; ?>">JSON</a> - <a href="http://www.cybermonde.org">cybermonde.org</a> - Logo <a href="https://thenounproject.com">NounProject</a> (Sylvain Amatoury) - Version 0.2 - Basé sur 
	</div>

</body>
</html>





