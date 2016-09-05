# Geoplugin Plugin

The **Geoplugin** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It attempts to geolocate your visitor using [the geoPlugin service](http://www.geoplugin.com).

> Remember that it is very easy to spoof your IP address! All this plugin does is return the data from [the geoPlugin service](http://www.geoplugin.com).

## Installation

Installing the Geoplugin plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install geoplugin

This will install the Geoplugin plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/geoplugin`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `geoplugin`. You can find these files on [GitHub](https://github.com/Perlkonig/grav-plugin-geoplugin) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/geoplugin
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Usage

As long as the plugin is `enabled`, it will inject the following settings:

```
plugins.geoplugin.city
plugins.geoplugin.region
plugins.geoplugin.areaCode
plugins.geoplugin.dmaCode
plugins.geoplugin.countryName
plugins.geoplugin.countryCode
plugins.geoplugin.longitude
plugins.geoplugin.latitude
```

[Please visit the geoPlugin site for more information.](http://www.geoplugin.com)

## Performance

This plugin uses Grav's built-in caching API, so only one external API call should ever be made for a given IP address unless you clear the cache.
