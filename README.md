lsredis
========

[Lemonstand](http://lemonstand.com/) Redis module. Uses the [Predis](https://github.com/nrk/predis "Predis") library ( Currently v0.8.3 ) to allow Redis as an option for caching in Lemonstand.


## Installation ##
- Clone/download lsredis into the modules directory of your Lemonstand installation
- Add the following to your config.php file, which is located in the config folder at the base of your installation. Please change `<host>` and `<port>` to match your needs. For a default Redis install, '127.0.0.1:6379' should work.

```php
$CONFIG['CACHING'] = array(
  'CLASS_NAME'          => 'LsRedis_RedisCache',  
  'DISABLED'            => false, 
  'PARAMS'              => array(
    'SERVERS' => array( '<host>:<port>'),
    'TTL'     => 3600
  )
);
```
- For more information about caching in Lemonstand read their [caching documentation](http://lemonstand.com/docs/caching_api/)

## Note ##
[Daniele Alessandri](mailto:suppakilla@gmail.com) ([twitter](http://twitter.com/JoL1hAHN)) is the author of [Predis](https://github.com/nrk/predis "Predis"). I am merely using it as the library to make the connection.

## License ##
The code for lsredis is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).
