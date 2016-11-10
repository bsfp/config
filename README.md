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

> index.php

```php

<?php

new \BSFP\C(__DIR__ . '/config');

echo 'Hello ' . \BSFP\C::get('hello')->get('what');

```

> config/hello.json

```json

{
  "what": "world"
}

```

result:

> php index.php

```bash

Hello world

```
