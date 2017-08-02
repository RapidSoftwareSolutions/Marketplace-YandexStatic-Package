[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Yandex/functions?utm_source=RapidAPIGitHub_YandexFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# YandexStatic Package
Yandex is a Russian multinational technology company specializing in Internet-related services and products. Yandex operates the largest search engine in Russia with about 65% market share in that country.Yandex operates the largest search engine in Russia with about 65% market share in that country.The Static API generates a map image based on the parameter values passed to the service.
* Domain: [yandex.com](https://yandex.com)

## How to get credentials:
1. Navigate to [Developers Console](https://developer.tech.yandex.com/keys).
2. Create API app.


## Custom datatypes:
  |Datatype|Description|Example
  |--------|-----------|----------
  |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
  |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
  |List|Simple array|```["123", "sample"]```
  |Select|String with predefined values|```sample```
  |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```



## YandexStatic.getStaticMap
The Static API generates a map image in accordance with the values ​​of the parameters passed to the service.

| Field| Type       | Description
|------|------------|----------
| mapType    | List       | The list of layers that determine the type of map: map and sat, skl (hybrid),trf.
| mapCenter   | Map        | Longitude and latitude of the center of the map in degrees.
| mapExtent  | String     | The length of the map display area by longitude and latitude (in degrees).
| mapCaliber    | Number     | The zoom level of the map (0-17).
| size | String     | The width and height of the requested map image (in pixels), see Map size. The default value is 450x450.
| scale| String     | The coefficient of magnification of objects on the map. Can take a fractional value from 1.0 to 4.0.
| descLabel   | String     | Contains descriptions of one or more labels that you want to display on the map.
| descFigure   | String     | Contains a set of descriptions of geometric shapes (polygons and polygons) that you want to display on the map.
| lang | String     | API allows you to display maps, localized in different languages, taking into account the specifics of individual countries.
| key  | String| API Yandex key.
