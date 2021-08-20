# Changelog

All notable changes to `php-beatsaver-api` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [1.1.2] - 2021-08-05

### Fixed
- Changed BeatSaver URL
- Changed endpoint for getMapByKey function

## [1.1.1] - 2021-08-03

It seems like i let a some bugs in the code x)

*The base url will change in a few days. I highly recommend to check for updates*

### Added
- Commentary for Scrutinizer

### Fixed
- if duration set to 0, it will not set the attribute.

## [1.1.0] - 2021-08-03

Since BeatSaver is moving from JellyFish to [Top-Cat](https://github.com/Top-Cat), the api will change to use the [BeatMaps API]("https://api.beatmaps.io/docs/") 

*The base url will change in a few days. I highly recommend to check for updates*

### Added
- getMapById
- getMapsSortedByLatest
- getMapsSortedByPlays
- searchMap
- getUserByID

### Fixed
- Changing base url from "https://beatsaver.com/api" to "https://api.beatmaps.io/"

### Removed
- getMapsSortedByDownloads
- getMapsSortedByHot
- getMapsSortedByRating
- getMapsByName

## [1.0.0] - 2021-07-31

Initial Release !

### Added
- getMapByKey
- getMapByHash
- getMapsByUploaderID
- getMapsSortedByDownloads
- getMapsSortedByHot
- getMapsSortedByLatest
- getMapsSortedByPlays
- getMapsSortedByRating
- getMapsByName