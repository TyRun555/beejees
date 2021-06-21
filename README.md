# test app

### instalation
 - git clone
 - ``composer install``
 - create local config in ./config/app-local.php and move db-connection info there
 - run ``./vendor/bin/doctrine-migrations migrate``

### config
Merged together ./config/app.php and ./config/app-local.php. ./config/app-local.php has higher priority

#### sections:
 - 'dbParams' - Array of database connection settings
 - 'doctrineDevMode' - used in ``core\App::initEntityManager()``
 - 'routes' - array where keys represent url wildcard or exact url, and values are route within the app
 - 'cookieSalt' - used for token generation

### routing
Routing can be set as strict url:

[url] => [controller]/[action]
```
'/' => 'site/index',
  
'/login' => 'site/login',
  
'/logout' => 'site/logout',

'/admin' => 'site/admin',
```  
Or a wildcard for dynamic determination of controller and action
other tags (in this example ``<id>``) goes as parameters of controller action
```
'/<controller>/<action>/<id>' => '<controller>/<action>',
'/<controller>/<action>' => '<controller>/<action>'
```
### usage
You can add a tasks with corresponding button and form.
If you login into system you can mark task as complete and change the text of the task.
List of tasks are sortable.