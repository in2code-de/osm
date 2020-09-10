# OSM - Open Street Map

## Introduction

A small but modern OpenStreetMap extension for TYPO3 (10 and newer). You can simply show a map with or without markers.
If you want to show a marker, just add an address in FlexForm or geo coordinates.

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

## FAQ

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

CSS and JS is included via Layout html template. You can simply adjust the paths to your needs.

### Filter address in Pi2 to some pages

If you don't want to present all available tt_address records in your FlexForm selection for your editors, you can
filter it via Page TSconfig to one or more pages like:

```
tx_osm {
  flexform {
    pi2 {
      addressPageIdentifiers = 2,3,4
    }
  }
}
```

## Changelog

| Version    | Date       | State      | Description                                                                                                                                                                                |
| ---------- | ---------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1.2.0      | 2020-08-13 | Bugfix     | Prevent `let` in JavaScript to support old browsers. Adjust marker size.                                                                                                                   |
| 1.1.0      | 2020-08-13 | Task       | Some small improvements (marker image with outline, some adjustments of the views in backend)                                                                                              |
| 1.0.0      | 2020-08-12 | Task       | Initial release                                                                                                                                                                            |
