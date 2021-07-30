# PHP BeatSaver API

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A Simple PHP library to interact with BeatSaver's API

## Install

Via Composer

``` bash
$ composer require krikrixs/php-beatsaver-api
```

## Usage

``` php
// Create the object
$BeatSaverAPI = new BeatSaverAPI("ApplicationName");

// Functions
$BeatSaverAPI->getMapByKey("bsr key of the map");                               // Get map by BSR Key
$BeatSaverAPI->getMapByHash("hash of the map");                                 // Get map by Hash
$BeatSaverAPI->getMapsByUploaderID("uploader id (not uploader name)");          // Get maps by Uploader ID! Not the uploader name!
$BeatSaverAPI->getMapsSortedByDownloads("limit of map you want to retrieve");   // Get maps sorted by downloads numbers
$BeatSaverAPI->getMapsSortedByHot("limit of map you want to retrieve");         // Get maps sorted by Hot
$BeatSaverAPI->getMapsSortedByLatest("limit of map you want to retrieve");      // Get latest maps
$BeatSaverAPI->getMapsSortedByPlays("limit of map you want to retrieve");       // Get maps sorted by plays numbers
$BeatSaverAPI->getMapsSortedByRating("limit of map you want to retrieve");      // Get maps sorted by their rating
$BeatSaverAPI->getMapsByName("map name", "limit of map you want to retrieve");  // Get maps by the name
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please contact me on discord [OMDN | Krixs#1106](https://discordapp.com/users/220151545486901248) or by email [kylian.barusseau@omedan.com](mailto:kylian.barusseau@omedan.com) instead of using the issue tracker.

## Credits

- [Kylian "Krixs" BARUSSEAU][link-author]
- [rui2015][link-rui] for the inspiration

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/krikrixs/php-beatsaver-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/krikrixs/php-beatsaver-api/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/krikrixs/php-beatsaver-api.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/krikrixs/php-beatsaver-api.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/krikrixs/php-beatsaver-api.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/krikrixs/php-beatsaver-api
[link-travis]: https://travis-ci.org/krikrixs/php-beatsaver-api
[link-scrutinizer]: https://scrutinizer-ci.com/g/krikrixs/php-beatsaver-api/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/krikrixs/php-beatsaver-api
[link-downloads]: https://packagist.org/packages/krikrixs/php-beatsaver-api
[link-author]: https://github.com/KriKrixs
[link-rui]: https://github.com/rui2015
