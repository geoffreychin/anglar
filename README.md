# generator-anglar

Adds laravel service controller and route endpoits for a resource name. 
Adds a angular app for said resource name.


Edit your composer.json

```
"require-dev": {
		"gchin/anglar":"dev-master"
	},
"repositories": [
        {
        	"type": "git",
            "url": "https://github.com/geoffreychin/anglar"
        }
],
```

Run composer update for this single install of this package
```
sudo composer update gchin/anglar
````
Add to  your providers array in your app/config/*/app.php
```
'providers'=>array('Gchin\Anglar\AnglarServiceProvider'),
```

Run php artisan task with the name of your resourcename. 
use --env=<config folder> depending on where you edited your app.php file.(omit flag if using default)
```
php artisan Anglar:make <resourcename> --env=local --ngdashboard --ngservice
```

run a git status to see what files have been added and modified.
If everything works should go to localhost/<resourcname>/dashboard to see everything hooked up

Edit to hearts content
