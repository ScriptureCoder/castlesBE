- Clone the project and run.

<pre>composer update</pre>
in your terminal to install required packages

- Rename the .env.example file to .env and set the needed configurations

- Run the followings to migrate your database.

<pre>php artisan migrate</pre>
<pre>php artisan db:seed</pre>

- Finally, to start the application run

<pre>php artisan serve</pre>
