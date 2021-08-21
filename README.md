SearchMap function# PHP BeatSaver API

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
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
// [Composer] Create the object
$BeatSaverAPI = new BeatSaverAPI("ApplicationName");

// [Not Composer] Create the object
$BeatSaverAPI = new BeatSaverAPI("ApplicationName", true);

// Functions
$BeatSaverAPI->getMapByID((int) $mapId);
$BeatSaverAPI->getMapByKey((string) $bsrKey);
$BeatSaverAPI->getMapByHash((string) $mapHash);
$BeatSaverAPI->getMapsByIDs((array) $mapsIds);
$BeatSaverAPI->getMapsByKeys((array) $bsrKeys);
$BeatSaverAPI->getMapsByHashes((array) $mapsHashes);
$BeatSaverAPI->getMapsByUploaderID((int) $uploaderID, (int) $limit);
$BeatSaverAPI->getMapsSortedByLatest((bool) $autoMapper);
$BeatSaverAPI->getMapsSortedByPlays((int) $limit);
$BeatSaverAPI->downloadMapByIds((array) $ids, (string) $targetDir);
$BeatSaverAPI->downloadMapByKeys((array) $keys, (string) $targetDir);
$BeatSaverAPI->downloadMapByHashes((array) $hashes, (string) $targetDir);
// $sortOrder possible values: 1 = Latest | 2 = Relevance | 3 = Rating
$BeatSaverAPI->searchMap((int) $limit, (int) $sortOrder = 1, (string) $mapName = null, (DateTime) $startDate = null, (DateTime) $endDate = null, (bool) $ranked = false, (bool) $automapper = false, (bool) $chroma = false, (bool) $noodle = false, (bool) $cinema = false, (bool) $fullSpread = false, (float) $minBpm = null, (float) $maxBpm = null, (float) $minNps = null, (float) $maxNps = null, (float) $minRating = null, (float) $maxRating = null, (int) $minDuration = null, (int) $maxDuration = null): ResponseMaps; 
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please contact me on discord [OMDN | Krixs#1106](https://discordapp.com/users/220151545486901248) or by email [kylian.barusseau@omedan.com](mailto:kylian.barusseau@omedan.com) instead of using the issue tracker.

## Credits

- [Kylian "Krixs" BARUSSEAU][link-author]
- [rui2015][link-rui] - For the inspiration
- [Top-Cat](https://github.com/Top-Cat) - BeatSaver Owner
- [Curze](https://github.com/charliemo25) - Contributor

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
