

# Live Server

- create `live-server.php`

```php
# LiveServer.php
require __DIR__ . '/vendor/autoload.php';

use Yukanoe\Tag\LiveServer;

(new LiveServer)->create();
```

- run cmd

```bash

php -S localhost:8080 live-server.php

```
