#!/bin/sh
# monitoring minimum avec du json
# par cybermonde.org
# dernière version 27/05/2015

# date-heure du jour
datenow=$(date +"%Y%m%d")
heurenow=$(date +"%H:%M")

# process httpd
prochttpd=`ps -C httpd --no-headers | wc -l`

# fichier résultat aaaammjj.result
# si heure est la première (00:00), pas de virgule espace
if [ "$heurenow" = "00:00" ]; then
	printf "[\"$heurenow\", $prochttpd]" >> /var/www/website/json/$datenow.result
else
	printf ", [\"$heurenow\", $prochttpd]" >> /var/www/website/json/$datenow.result
fi
