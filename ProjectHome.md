# ZendX - The Zend Framework Extentions Library #

## Purpose ##

ZendX was created with the intention of improving and building upon the already solid Zend Framework. Whilst the Zend Framework contains many great features, I found the need to extend upon it's functionality whilst working on a recent project. Rather than mix and match frameworks I decided to create a repository of useful classes that built upon existing Zend Framework functionality.

The most notable additions to the library at present are the porting of the Java collections framework across to PHP and a RESTful style PHP web services implementation  reminiscent of .NET web services.

## Features ##

Currently the ZendX library contains the following features:

  * Partial port of the Java collections framwork to PHP
  * .NET style RESTful web services API with JSON support
  * Additional experimental classes

## Conventions ##

ZendX follows all the same coding conventions as outlined in the [Zend Framework programmers    reference](http://framework.zend.com/manual/en/coding-standard.html). As such it is intended to integrate seamlessly with existing framework  features, however resides in it's own psuedo-namespace 'ZendX' in order not to cause conflict with future changes.

## Acknowledgements ##

The ZendX library borrows its ideas heavily from the following sources, and subsequently wishes to acknowledge:

  * The Zend Framework team
  * The Java collections framework
  * The ASP.NET AJAX web services architecture