# NoCSRF

Easy as fuck CSRF protection library for PHP based on [OWASP recommandations](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html).

## Installation

Using composer:

```sh
composer require madeorsk/nocsrf
```

## Getting started

Easy example code:

```php

use NoCSRF\NoCSRF;

// Creating a new NoCSRF instance, which manages anti-CSRF tokens.
$nocsrf = new NoCSRF();

// Get an anti-CSRF token (to add in a hidden input field or a request header).
$token = $nocsrf->getToken();

// Verify anti-CSRF token.
if ($nocsrf->verify($token))
	echo "Anti-CSRF token is VALID!";
else
	echo "Anti-CSRF token is INVALID.";

```

## Custom modules

NoCSRF is made of three main components:

- The `KeyGenerator`: generate a key, in the default implementation it is an OpenSSL random key generator.
- The `KeyStorage` : store the key used in token generation / verification. The key storage need to keep the key in such a way that it is available for a specific session, but cannot be retrievable by the client.
- The `TokenManager` : contain token generation / verification logic. The key is provided.

You can create custom classes for these three components. You can chose which module to use in the NoCSRF initialization:

```php
$nocsrf = new NoCSRF([
	"keyGenerator" => new OpensslKeyGenerator(16),
	"keyStorage" => new SessionKeyStorage(),
	"tokenManager" => new HMACTokenManager(),
]);
```

The components used in this example are the default values.

## Full API documentation

Full API documentation is available in GitHub wiki (WIP) or in code documentation.