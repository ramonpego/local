# A lightweight package to execute commands on your local machine.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/ssh.svg?style=flat-square)](https://packagist.org/packages/spatie/ssh)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/ssh/run-tests?label=tests)](https://github.com/spatie/ssh/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/ssh.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/ssh)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/ssh.svg?style=flat-square)](https://packagist.org/packages/spatie/ssh)

You can execute a command on your local machine like this:

```php
CLI::create('path', 'user')->execute('your favorite command');
```

It will return an instance of [Symfony's `Process`](https://symfony.com/doc/current/components/process.html).

This package is based on the spatie/ssh package
please consider support them.

## Support them

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/ssh.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/ssh)

## Installation

You can install the package via composer:

```bash
composer require spatie/ssh
```

## Usage

You can execute an SSH command like this:

```php
$process = CLI::create('path', 'user')->execute('your favorite command');
```

It will return an instance of [Symfony's `Process`](https://symfony.com/doc/current/components/process.html).

If you don't want to wait until the execute commands complete, you can call `executeAsync`

```php
$process = CLI::create('path', 'user')->executeAsync('your favorite command');
```

### Getting the result of a command

To check if your command ran ok

```php
$process->isSuccessful();
```


This is how you can get the output

```php
$process->getOutput();
```

### Running multiple commands

To run multiple commands pass an array to the execute method.

```php
$process = CLI::create('path', 'user')->execute([
   'first command',
   'second command',
]);
```


### Modifying the Symfony process

Behind the scenes all commands will be performed using [Symfonys `Process`](https://symfony.com/doc/current/components/process.html).

You can configure to the `Process` by using the `configureProcess` method. Here's an example where we disable the timeout.

```php
CLI::create('user', 'host')->configureProcess(fn (Process $process) => $process->setTimeout(null));
```

### Immediately responding to output

You can get notified whenever your command produces output by passing a closure to `onOutput`. 

```php
CLI::create('user', 'host')->onOutput(fn($type, $line) => echo $line)->execute('whoami');
```

Whenever there is output that closure will get called with two parameters:
- `type`: this can be `Symfony\Component\Process\Process::OUT` for regular output and `Symfony\Component\Process\Process::ERR` for error output
- `line`: the output itself

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ramon.pego.stl@gmail.com instead of using the issue tracker.

## Credits

- [Ramon Pêgo](https://github.com/ramonpego)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The `CLI` class contains code taken from [laravel/envoy](https://laravel.com/docs/6.x/envoy)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
