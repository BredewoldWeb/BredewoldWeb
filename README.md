## Bredewold Web starter pack

Stap 1. 
Maak een folder aan in je localhost configuratie. open de command line in deze folder. Voer de volgende commando's uit
1. git clone https://github.com/BredewoldWeb/BredewoldWeb.git .
2. rm -rf .git  (rmdir /s .git op windows)

Stap 2. 
Installeer Bedrock [Bedrock installation documentation](https://roots.io/bedrock/docs/installation/#getting-started).

Stap 3. 
Stel de .htaccess in /web in op de gewenste foldernaam. 

Stap 4. 
Installeer WordPress. Activeer eerst alle plugins en daarna het Bredewoldweb thema


## Livegang stappenplan

Stap 1.
Upload alle bestanden naar de webserver.

Stap 2. 
Exporteer de database vanuit test/local en importeer deze op de gewenste omgeving met phpMyAdmin.

Stap 3. 
Pas de .env aan met de database/domeingegevens van de nieuwe omgeving. 
- Zet WP_ENV op **development** bij een **ontwikkelomgeving**.
- Zet WP_ENV op **staging** bij een **testomgeving**.
- Zet WP_ENV op **production** bij een **liveomgeving**. 

Stap 4. 
Pas de .htaccess in /web aan. Verwijder hier de foldernaam zodat er enkel een **/** overblijft.

Stap 5. 
Login via /wp-admin/ en installeer [Better Search Replace](https://wordpress.org/plugins/better-search-replace/). Vervang het volgende: 
- http: -> https:
- localhost/voorbeeld -> voorbeeld.nl 

Stap 6. 
Check alle WordPress settings.