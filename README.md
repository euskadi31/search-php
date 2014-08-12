Search Engine for PHP [![Build Status](https://secure.travis-ci.org/euskadi31/search-php.png)](http://travis-ci.org/euskadi31/search-php)
=====================

Wrapper for search engine.

Engine supported
----------------

* Sphinx
* SphinxQL

Example
-------

~~~php
require 'path/to/vendor/autoload.php';

$search = new Search\Engine\SphinxQL("127.0.0.1", 9306);

// index document
$search->insert("tweets", [
    "id"        => 4951957226,
    "text"      => "For a bit of light weekend reading how about https://github.com/php/php-langspec/blob/master/spec/php-spec-draft.md … ? Still very much work in progress, but let us know if you spot typos",
    "timestamp" => 1407857815
]);

$search->insert("contacts", [
    "id"        => 1234,
    "firstname" => "Kiefer",
    "lastname"  => "Sutherland",
    "email"     => "kiefer.sutherland@example.com"
    "timestamp" => 1407857945
]);

$search->insert("contacts", [
    "id"        => 1235,
    "firstname" => "Chloe",
    "lastname"  => "O'Brian",
    "email"     => "chloe.obrian@example.com"
    "timestamp" => 1407857948
]);

// search
$response = $search->search("chloe", "contacts");
~~~

License
-------

search-php is licensed under [the MIT license](LICENSE.md).
