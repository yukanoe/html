
# Change Log
1.0.0=>2.0.0


## [1.0.8 Unreleased] - 2024-??-??

### Added

### Changed
- data-yukanoe-hidden
 + Old: data-yukanoe-hidden="true/false"
 + New: data-yukanoe-hidden="any" =>  data-yukanoe-hidden="hidden"
 + (work similar as hidden/selected/checked/..)

- compileRealTime()
 + Old: return array: root=>$tagRoot,name=>$tagName
 + New: return $tagRoot

### Fixed

### Deprecated



## [1.0.7] - 2024-04-29
  
exportYD, flushByResponse
 
### Added
- Tag::exportYD()

### Changed
- flushSwoole(Response) => flushByResponse(Response)

### Fixed
 
- include html

### Deprecated


## [1.0.6] - 2017-03-14

readRealTime

### Added
- readRealTime()

### Changed
- AV => tagRoot
- AVN => tagName

### Fixed

### Deprecated
