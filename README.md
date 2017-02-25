# IPLocate Plugin

The **IPLocate** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It attempts to geolocate your visitor using various services. 

> Remember that it is very easy to spoof your IP address! All this plugin does is return the data from the service.

For a demo, [visit my blog](https://perlkonig.com/demos/iplocate).

## Installation

Installing the IPLocate plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install iplocate

This will install the IPLocate plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/iplocate`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `iplocate`. You can find these files on [GitHub](https://github.com/Perlkonig/grav-plugin-iplocate) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/iplocate
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Services

The plugin currently supports the following geolocation services (in alphabetical order):

| Name | Code | Key Required? | 
| --- | --- | --- |
| [DB-IP](https://www.db-ip.com) | `dbip` | Y (free) |
| [FreeGeoIP](https://freegeoip.net) | `freegeoip` | N |
| [geoPlugin](http://www.geoplugin.com/) | `geoplugin` | N |
| [IPInfo](http://ipinfo.io) | `ipinfo` | N |

>> NOTE: Some services have licensing terms that involve linking back to the service. This plugin does **not** do this! It is the user's responsibility to ensure that all licensing conditions are met.

Visit the individual websites for more information on what exact data they provide and what limitations exist. The inclusion of a service should in no way be taken as an endorsement of any kind. These are simply services I came across doing research. Caveat emptor!

If you wish me to add new services, please submit an issue containing a link or description of the API and I am happy to add it.

## Configuration

Here's the default configuration. To override, first copy `iplocate.yaml` from the `plugins/iplocate` folder to your `config/plugins` folder.

```
enabled: true
sequence:
  - geoplugin
  - freegeoip
  - ipinfo
  - dbip # API key required

keys:
  dbip: "FAKEKEY"

test_ip: null
```

  - `enabled` is used to enable/disable the plugin. There is no way to selectively enable this plugin. Either it is on or off.

  - `sequence` tells the system in what order to try the various services. You do not need to include them all in this list. If you only wish to rely on one or two, then only list those. The system will stop going through the list once it receives a valid response.

  - `keys` is where you'll list your API keys.

  - `test_ip` can be set if you want to to manually test a specific IP address.

## Usage

All you have to do is make sure the plugin is `enabled`. The system will then use the `sequence` list and the cache to get what data it can. Any data found will be injected into the system config in the `plugins.iplocate` namespace. Below is the list of available field names:

- areaCode
- city
- continentCode
- continentName
- countryCode
- countryName
- currencyCode
- district
- dmaCode
- geonameID
- gmtOffset
- isp
- languages
- latitude
- longitude
- metroCode
- organization
- regionCode
- regionName
- stateProv
- timezone
- zipcode

The plugin does not guarantee that any one of these fields will be populated. That depends on the service provider. I have, though, attempted to normalize the data as much as is reasonable. The `countryCode`, for example, is always upper case

## Performance

The system stops processing the `sequence` list once any one of the services returns a valid response. Also, this plugin uses Grav's built-in caching API, so only one external API call should ever be made for a given service + IP address combination unless you clear the cache. 

Note, though, that these are still external calls! If one is slow to respond, it could slow down rendering your site as well.
