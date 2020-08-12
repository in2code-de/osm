# OSM - Open Street Map

## Introduction

A small but modern OpenStreetMap extension for TYPO3 (10 and newer). You can simple show a map with or without markers.
If you want to show a marker, simple add an address in FlexForm or geo coordinates.

A second plugin allows you to show multiple markers from tt_address records (when tt_address.latitude and .longitude is
filled).

No jQuery, just vanilla JS. Modern asset collector used for includes of JS or CSS.

## Plugin 1

![screenshot_pi1_frontend.png](Documentation/Images/screenshot_pi1_frontend.png "Map in frontend")

![screenshot_pi1_backend.png](Documentation/Images/screenshot_pi1_backend.png "Plugin in backend")

## Plugin 2

![screenshot_pi1_frontend.png](Documentation/Images/screenshot_pi2_frontend.png "Map in frontend")

![screenshot_pi2_backend.png](Documentation/Images/screenshot_pi2_backend.png "Plugin in backend")

## Installation

Add this extension via composer (TYPO3 in classic mode could work but is not supported):

`composer require in2code/osm`

## FAQ

### TypeNum in siteconfiguration?

Don't forget to add typeNum 1597149189 for AJAX requests for the markers to your siteconfiguration like:

```
...
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    default: /
    suffix: /
    index: ''
    map:
      /: 0
      .html: 0
      'feed.xml': 9818
      'markers.json': 1597149189
...
```

### How to overwrite paths?

As always via TypoScript setup - like

```
plugin.tx_osm {
    view {
        templateRootPaths {
            0 = EXT:osm/Resources/Private/Templates/
            1 = EXT:yoursitepackage/Resources/Private/Templates/Extensions/Osm/
        }
        layoutRootPaths {
            0 = EXT:osm/Resources/Private/Layouts/
            1 = EXT:yoursitepackage/Resources/Private/Layouts/Extensions/Osm/
        }
    }
}
```

### How to define own css or js?

CSS and JS is included via Layout html template. You can simple adjust the paths to your needs.

## Changelog

| Version    | Date       | State      | Description                                                                                                                                                                                |
| ---------- | ---------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1.0.0      | 2020-08-12 | Task       | Initial release                                                                                                                                                                            |
