# Pok&eacute;ROM

[comment]: <> (https://github.com/bag33188/new-pokerom)

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Important Notes

## NodeJS and PHP Reverse Proxy

allow node and php to run together (reverse proxy)

> _assumes nodejs is the primary runtime serving on apache_

```apacheconf
# For Windows, XAMPP

# C:/Windows/System32/drivers/etc/hosts (as admin)
127.0.0.1 localhost:8080

# C:/xampp/apache/conf/httpd.conf
Listen 5000

# C:/xampp/apache/conf/extra/httpd-vhosts.conf
<VirtualHost *:5000>
    DocumentRoot "C:\Users\Brock\Projects\PokeROM\www"
    ServerName localhost:8080
    <Directory "C:\Users\Brock\Projects\PokeROM\www">
        Allow from all
        Require all granted
    </Directory>
    ProxyPreserveHost on
    ProxyPass / http://localhost:8080/
    ProxyPassReverse / http://localhost:8080/
</VirtualHost>
```

## PHPStorm debug with Postman

add this cookie to the current request: 
> XDEBUG_SESSION=phpstorm; Path=/; Domain=pokerom.test; Expires=Tue, 16 Jan 2069 13:32:00 GMT;

## Documentation Links

* [MariaDB Server Docs][]
* [MongoDB Docs][]
* [PHP Manual][]
* [Laravel Docs][]
* [MongoDB GridFS Docs][]
* [PHP GridFS Tutorial][]
* [Vite Docs][]
* [Tailwind CSS Docs][]

[MariaDB Server Docs]: https://mariadb.com/kb/en/documentation/ "v10.9.3"
[MongoDB Docs]: https://www.mongodb.com/docs/manual/ "v6.0"
[PHP Manual]: https://www.php.net/manual/en/ "v8.1.6"
[Laravel Docs]: https://laravel.com/docs/9.x/ "v9.x"
[MongoDB GridFS Docs]: https://www.mongodb.com/docs/manual/core/gridfs/ "v6.0"
[PHP GridFS Tutorial]: https://www.mongodb.com/docs/php-library/v1.13/tutorial/gridfs/ "v1.13"
[Vite Docs]: https://vitejs.dev/guide "v3.1.3"
[Tailwind CSS Docs]: https://tailwindcss.com/docs "v3.1.8"


[//]: # ([mongodb php driver page]: https://www.php.net/manual/en/mongodb.installation.pecl.php)
[//]: # ([mongodb pecl extension]: https://pecl.php.net/package/mongodb "v1.13.0 - ts-x64, windows")
[//]: # ([php mongodb vendor manual]: https://www.php.net/manual/en/set.mongodb.php)
[//]: # ([php mongodb driver tutorial]: https://www.php.net/manual/en/mongodb.tutorial.library.php)
[//]: # ([mongodb php driver docs]: https://www.mongodb.com/docs/drivers/php/)

[comment]: <> "> Note: the Proper count of the `rom.chunks` collection should be: _**`88628`**_ documents"
