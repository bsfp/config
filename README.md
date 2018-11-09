# \BSFP\C

## API

Configuration

```php
new \BSFP\C(__DIR__ . '/path/to/configFolder');
```

Retrieve your config

```php
\BSFP\C::get('filename');
```

Work with one level of folder and Yaml and Json files.

## Demo


> config/hello.json

```json

{
  "what": "world"
}

```

> index.php

```php

<?php

new \BSFP\C(__DIR__ . '/config');

echo 'Hello ' . \BSFP\C::get('hello')->get('what');

```

result:

> php index.php

```bash

Hello world

```


## Run tests

```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```
