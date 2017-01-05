Digits
======

[ ![Codeship Status for sportlobster/digits-php](https://codeship.com/projects/7b84b480-b4a8-0134-0817-665c05a5a8f8/status?branch=master)](https://codeship.com/projects/193797)

PHP library for [Digits by Twitter](https://get.digits.com/).

Usage
-----

Simple example of server side verification:

```
use Sportlobster\Digits\Client;
use Sportlobster\Digits\Exception\AuthenticationException;

$client = Client('YOUR_DIGITS_CONSUMER_KEY');

// Verify a user via OAuth Echo
try {
    $user = $client->verify($echoUrl, $echoAuth);
} catch (AuthenticationException $e) {
    // User could not be authenticated
}

// Get details about the user
$user->getId();
$user->getPhoneNumber();

// Get details about the user's OAuth token
$accessToken = $user->getAccessToken();
$accessToken->getToken();
$accessToken->getSecret();
```

The underlying HTTP client (Guzzle) can be configured.  This allows the ability
to set request timeout options, proxies etc.

```
use Sportlobster\Digits\Client;

// See http://docs.guzzlephp.org/en/latest/request-options.html
$options = [
    'connect_timeout' => 3.14,
    'proxy' => 'tcp://localhost:8125',
];

$client = Client('YOUR_DIGITS_CONSUMER_KEY', $options);
```

Contributing
------------

Classes are all specified using PHPSpec.  To run:

```
vendor/bin/phpspec run
```

Integration tests are done via PHPUnit.  To run:

```
vendor/bin/phpunit
```

Code must be to standards.  To run:

```
php vendor/bin/php-cs-fixer fix
```
