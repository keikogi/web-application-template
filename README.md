Web Application Template based on Silex
=======================================

Requirements
------------
PHP 5.3+

Installation
------------
Add this to a composer.json file:
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/keikogi/web-application-template"
    }
],
"require": {
    "keikogi/web-application-template": ">=1.0.0"
}
```

Usage
-----
```php
define('WEB_PATH', __DIR__);

define('ROOT_PATH', __DIR__ . '/..');

require_once __DIR__ . '/../vendor/autoload.php';

use Keikogi\Application\Application;

Application::run(array(), false);
```
