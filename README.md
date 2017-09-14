# Internet Archive API

[![Build Status](https://travis-ci.org/votemike/internet-archive-api.svg?branch=master)](https://travis-ci.org/votemike/internet-archive-api)
[![Latest Stable Version](https://poser.pugx.org/votemike/internet-archive-api/v/stable)](https://packagist.org/packages/votemike/internet-archive-api)
[![Total Downloads](https://poser.pugx.org/votemike/internet-archive-api/downloads)](https://packagist.org/packages/votemike/internet-archive-api)
[![Latest Unstable Version](https://poser.pugx.org/votemike/internet-archive-api/v/unstable)](https://packagist.org/packages/votemike/internet-archive-api)
[![License](https://poser.pugx.org/votemike/internet-archive-api/license)](https://packagist.org/packages/votemike/internet-archive-api)
[![composer.lock](https://poser.pugx.org/votemike/internet-archive-api/composerlock)](https://packagist.org/packages/votemike/internet-archive-api)
[![StyleCI](https://styleci.io/repos/102968140/shield?branch=master)](https://styleci.io/repos/102968140)

A library to call archive.org's search API. 
Please use GitHub to raise any issues and suggest any improvements.

## Install

composer require votemike/internetarchiveapi

## Usage

Create a new instance of `Api` passing in a Guzzle Client with the base_uri set as 'https://archive.org/'.
```
$client = new Client(['base_uri' => 'https://archive.org/']);
$api = new Api($client);
```

For a list of items, call `$api->search()` with your query. For information about the valid query strings, see the [advancedsearch page](https://archive.org/advancedsearch.php)
Optional you can page in extra arguments:
* Page - By default this library paginates 50 items at a time. Get additional pages by changing this page.
* Rows - The number of items you would like returned as part of each call
* Fields - By default, all fields are returned. Limit them by passing through an array of fields as mentioned on [advancedsearch page](https://archive.org/advancedsearch.php)
* Sort - Send through an array of sort parameters

If you would like a specific item populated with extra metadata, pass an `Item` object in to `$api->getMetaDataForItem()`.  
A second parameter can be added to only return [sub-items](http://blog.archive.org/2013/07/04/metadata-api/) of the metadata

## Credits

- [Michael Gwynne](http://www.votemike.co.uk)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/votemike
