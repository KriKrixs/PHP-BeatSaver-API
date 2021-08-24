# Changelog

All notable changes to `php-beatsaver-api` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [2.2.1] - 2021-08-24

### Fixed

- getMapsByHashes returning multidimensionnal array.
- If a map is deleted (not found), it returns null which was causing the library to crash

## [2.2.0] - 2021-08-23

### Added

- Request limiter
  - For functions that retrieve multiple maps (with page or by hashes array), the library will wait 1 second every 10 call.
- Missing PHP Doc parameters for the constructor
- Default value for startPage and numberOfPage parameters (0) for the functions getMapsByUploaderID and getMapsSortedByPlays

### Fixed

- Function getMapsByHashes is now using the new call dedicated to that.
- ResponseMaps object can now add multiple beatmaps based on a json object

### Deprecated

- Functions getMapsByIds and getMapsByKeys have a high chance to hit the BeatSaver API Rate limit. Please use getMapsByHashes instead.

## [2.1.4] - 2021-08-21

### Fixed
toArray not working (fml)

## [2.1.3] - 2021-08-21

### Added
toArray / toJson function for any object (except Response)

## [2.1.2] - 2021-08-21

### Added
Argument needAutoloader which is false by default

### Fixed
Autoloader causing silent crash if using this library with composer. 

## [2.1.1] - 2021-08-21

### Added
- Object
  - ResponseDownload
    - getDownloadedMaps
    - getFailedMaps

### Removed
- Object
  - ResponseDownload
    - getDownloadStatus

### Fixed
Changed downloadMaps response according to the modification above

## [2.1.0] - 2021-08-21

### Added
- Functions
  - getMapsByIds (powered by MultiQuery)
  - getMapsByKeys (powered by MultiQuery)
  - getMapsByHashes (powered by MultiQuery)
  - downloadMapByKeys (powered by MultiQuery)

Also added some PHP Doc comments for all the download functions

## [2.0.1] - 2021-08-21

### Fixed
- Setting the error message into the error status in some functions
- getSearchMap was still using limiter instead of paging

## [2.0.0] - 2021-08-21

### Added
- Object 
  - BeatMap
    - BeatMapMetadata
    - BeatMapStats
    - BeatMapUploader
    - BeatMapVersion
      - BeatMapVersionDifficulty
        - BeatMapVersionDifficultyParitySummary
  - Response
    - ResponseDownload (extends)
    - ResponseMap (extends)
    - ResponseMaps (extends)
    - ResponseUser (extends)
  - User
    - UserStats
      - UserStatsDifficulties


- Functions
  - downloadMapByIds (powered by MultiQuery)
  - downloadMapByHashes (powered by MultiQuery)

### Fixed / Removed
  
All responses are changed from Array to Response(Map|Maps|User|Download) Object

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