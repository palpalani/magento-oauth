# Magento oauth

A service class for Magento OAuth using the [Lusitanian PHP OAuth](https://github.com/Lusitanian/PHPoAuthLib) library.

[![Total Downloads](https://poser.pugx.org/jonnyw/magento-oauth/downloads.png)](https://packagist.org/packages/jonnyw/magento-oauth) 
[![Latest Version on Packagist](https://img.shields.io/packagist/v/palpalani/magento-oauth.svg?style=flat-square)](https://packagist.org/packages/palpalani/magento-oauth)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/palpalani/magento-oauth/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/palpalani/magento-oauth/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/palpalani/magento-oauth/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)]

## Examples
------------

A working example can be found in the examples/ directory of the repo.

You can create your own instance of the Magento service or use the service factory in the Lusitanian OAuth library, which ensures all dependencies are injected into the service:

```php
<?php

use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\ServiceFactory;

$applicationUrl     = 'http://magento.local';
$consumerKey        = 'd19e5e1ce0a8298a32fafc2d1d50227b';
$consumerSecret     = '7c230aba0da67e2ab462f88e6e83ee39';

$storage        = new Session();
$uriFactory     = new UriFactory();

$serviceFactory = new ServiceFactory();
$serviceFactory->registerService('magento', 'JonnyW\MagentoOAuth\OAuth1\Service\Magento');

$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

$baseUri = $uriFactory->createFromAbsolute($applicationUrl);

$credentials = new Credentials(
    $consumerKey,
    $consumerSecret,
    $currentUri->getAbsoluteUri()
);

$magentoService = $serviceFactory->createService('magento', $credentials, $storage, array(), $baseUri);
```

By default the service class authorizes users in the admin scope. To authorize customers simply set the authorization endpoint on the Magento service after instantiating it:

```php
<?php

use JonnyW\MagentoOAuth\OAuth1\Service\Magento;

$magentoService->setAuthorizationEndpoint(Magento::AUTHORIZATION_ENDPOINT_CUSTOMER);
```


## Troubleshooting

If you receive a 'Server can not understand Accept HTTP header media type' error message when making API requests through the service then you may need to add an 'Accept' header to the request:

```php
$result = $magentoService->request('/api/rest/customers', 'GET', null, array('Accept' => '*/*'));
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/palpalani/magento-oauth/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [palpalani](https://github.com/palpalani)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
