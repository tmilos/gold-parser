# Gold Parser

Gold parser PHP runtime LALR engine and compiled grammar loader. For more information check 
[Gold Parser website](http://www.goldparser.org/). Library is written based on 
the [Calitha C# GOLD Parser Engine](http://www.calitha.com/goldparser.html).

[![Author](http://img.shields.io/badge/author-@tmilos-blue.svg?style=flat-square)](https://twitter.com/tmilos77)
[![Build Status](https://travis-ci.org/tmilos/gold-parser.svg?branch=master)](https://travis-ci.org/tmilos/gold-parser)
[![Coverage Status](https://coveralls.io/repos/github/tmilos/gold-parser/badge.svg?branch=master)](https://coveralls.io/github/tmilos/gold-parser?branch=master)
[![License](https://img.shields.io/packagist/l/tmilos/gold-parser.svg)](https://packagist.org/packages/tmilos/gold-parser)

# Installation

You can use Composer to install

```bash
$ composer require tmilos/gold-parser
```


# Usage

Use ``Loader`` class to load compiled grammar file, and it's ``createNewParser()`` to get the LALR parser for that grammar.

```php
<?php
$parser = Loader::fromFile('grammar.cgt')->createNewParser();
$nonTerminal = $parser->parse($inputString);
$parser->isAccepted(); // true
```


# Events

The ``Parser`` instance has a default event listener, which you could replace. During parsing it dispatches various events.
In the ``Events`` class are enumerated all events that are dispatched.

| Event name constant | Event name      | Event class           | Description
|---------------------|-----------------|-----------------------|---------------------------------------
| Events::PARSE_ERROR | gp.parse_error  | ``ParseErrorEvent``   | Parsing error
| Events::TOKEN_ERROR | gp.token_error  | ``TokenErrorEvent``   | Unexpected token encountered error
| Events::TOKEN_READ  | gp.token_read   | ``TokenReadEvent``    | Next token has been read
| Events::SHIFT       | gp.shift        | ``ShiftEvent``        | Parser shifted
| Events::REDUCE      | gp.reduce       | ``ReduceEvent``       | Parser reduced
| Events::ACCEPT      | gp.accept       | ``AcceptEvent``       | Parser got into accept state
| Events::GOTO_EVENT  | gp.goto         | ``GotoEvent``         | Parser state changed with goto


# Errors

By default the parser adds error listeners that will throw exceptions when error events are dispatched. You can add your own
listeners for the error events and disable those default listeners with ``Parser::setThrowExceptionsOnErrors(false)``

Default error handlers will throw ``ParseException`` on ``PARSE_ERROR`` event, and ``TokenException`` on ``TOKEN_ERROR`` event.


# Performance

On my modest laptop with PHP 7.0 it takes around 0.2 seconds to load grammar and create parser, and around 0.04 seconds
to parse ~700 chars json. Feel free to contribute and improve performance. Think the loading is critical.
