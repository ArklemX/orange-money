A simple implementation of Orange Money Payment API, for PHP.
=======================================================

What is this? <a name="what"></a>
-------------

A [PHP][] class, which is an implementation of the Orange Money Payment API of [Orange](https://orange.cm).

The implementation focus is to make many types of payment, using the API Credentials credentials (accessKey).
(You can get it by signing up to the [Payment API](https://wso2apim.orange.cm/store/apis/info?name=OrangeMoneyCoreAPIS&version=1.0.2&provider=admin).)

[PHP]: http://php.net/ "PHP is a popular general-purpose scripting language that is especially suited to web development."


Requirements
-----

- Your API credentials
- [PHP 7.4 or higher](http://www.php.net/downloads.php) to use it.
  .

Installation <a name="installation"></a>
------------

Installation is recommended to be done via [composer][] by running:

	composer require karbura/orange-money

Alternatively you can add the following to the `require` section in your `composer.json` manually:

```json
"karbura/orange-money"
```

Run `composer update` afterwards.

[composer]: https://getcomposer.org/ "The PHP package manager"

Usage <a name="usage"></a>
-----

### In your PHP project

To make payments , you'll need only one lines of code.

The first one is to set the accessKey and the originator
(the name of who send the message).

The next step is to call the `send()`-method to send the `message` to the `receivers`(recipients).

Here is an example:

```php
// Initialize the MessageBird
MessageBird::__construct("acessKey", "originator");

// Or create a component to use it

// send a message to a single receiver
$response = MessageBird::send(["+237653214587"], "My First SMS");

//Or

// set a list of receivers / recipients
$receivers = [
    "receiver1",
    "receiver2",
    "receiver3",
    ...
]

// and then make a single call to send
$response = MessageBird::send(["+237653214587"], "Send Many SMS");
```

Thank You for using this extension and if there is any problem, feel free to report it.
