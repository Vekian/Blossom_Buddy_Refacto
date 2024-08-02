# Blossom Buddy

## How to use

* Run "composer install".
* Copy and paste .env.example, then rename it ".env".
* Add your local database informations.
* Add your [https://perenual.com/](https://perenual.com/) API Key under the name "API_PERENUAL_KEY" in .env file.
* Add your [https://www.weatherapi.com/](https://www.weatherapi.com/) API Key under the name "API_WEATHERAPI_KEY" in .env file.
* Run "php artisan migrate".

## You'll need a certificate to be able to run https fetches

* Visit [https://curl.se/docs/caextract.html](https://curl.se/docs/caextract.html).
* Download the cacert.pem file and put it somewhere in your system.
* Find your php.ini and edit it (to find it, you can create a new php file and execute the code <?php phpinfo(); ?> ).
* Find the directive "curl.cainfo" in your php.ini, if it doesn't exist create it. If it already exist, uncomment it by removing the ";".
* Write the path to your cacert.pem next to the curl.cainfo directive (example : "c:/wamp64/bin/php/php8.3.4/extras/ssl/cacert.pem").
* If you use openssl, you can also define the openssl.cafile directive the same way.
* Save and restart your web server.

## To access swagger docs : http://127.0.0.1:8000/api/documentation