<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class GeopluginPlugin
 * @package Grav\Plugin
 */
class GeopluginPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        //Load the custom PHP for generating the lists
        require_once __DIR__ . '/classes/geoplugin.class.php';
        require_once __DIR__ . '/classes/freegeoip.class.php';
        require_once __DIR__ . '/classes/dbip.class.php';
        require_once __DIR__ . '/classes/ipinfo.class.php';

        global $_SERVER;
        if ( is_null( $ip ) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        /* Standardized field codes:
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
        */

        foreach ($this->config->get('plugins.geoplugin.sequence') as $service) {
            try {
                if ($service === 'geoplugin') {
                    //dump("In geoplugin");
                    $geoplugin = new geoPlugin();
                    $geoplugin->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.geoplugin.areaCode', $geoplugin->areaCode);
                    $this->config->set('plugins.geoplugin.city', $geoplugin->city);
                    $this->config->set('plugins.geoplugin.countryCode', $geoplugin->countryCode);
                    $this->config->set('plugins.geoplugin.countryName', $geoplugin->countryName);
                    $this->config->set('plugins.geoplugin.dmaCode', $geoplugin->dmaCode);
                    $this->config->set('plugins.geoplugin.latitude', $geoplugin->latitude);
                    $this->config->set('plugins.geoplugin.longitude', $geoplugin->longitude);
                    $this->config->set('plugins.geoplugin.regionCode', $geoplugin->regionCode);
                    $this->config->set('plugins.geoplugin.currencyCode', $geoplugin->currencyCode);
                    break;
                } elseif ($service === 'freegeoip') {
                    //dump("In freegeoip");
                    $freegeoip = new freeGeoIP();
                    $freegeoip->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.geoplugin.city', $freegeoip->city);
                    $this->config->set('plugins.geoplugin.countryCode', $freegeoip->countryCode);
                    $this->config->set('plugins.geoplugin.countryName', $freegeoip->countryName);
                    $this->config->set('plugins.geoplugin.latitude', $freegeoip->latitude);
                    $this->config->set('plugins.geoplugin.longitude', $freegeoip->longitude);
                    $this->config->set('plugins.geoplugin.metroCode', $freegeoip->metroCode);
                    $this->config->set('plugins.geoplugin.regionCode', $freegeoip->regionCode);
                    $this->config->set('plugins.geoplugin.regionName', $freegeoip->regionName);
                    $this->config->set('plugins.geoplugin.timezone', $freegeoip->timezone);
                    $this->config->set('plugins.geoplugin.zipcode', $freegeoip->zipcode);
                    break;
                } elseif ($service === 'dbip') {
                    //dump("In dbip");
                    $dbip = new DBIP();
                    $dbip->locate($this->grav['cache'], $this->config->get('plugins.geoplugin.keys.dbip'), $ip);
                    $this->config->set('plugins.geoplugin.continentCode', $dbip->continentCode);
                    $this->config->set('plugins.geoplugin.continentName', $dbip->continentName);
                    $this->config->set('plugins.geoplugin.countryCode', $dbip->countryCode);
                    $this->config->set('plugins.geoplugin.countryName', $dbip->countryName);
                    $this->config->set('plugins.geoplugin.currencyCode', $dbip->currencyCode);
                    $this->config->set('plugins.geoplugin.areaCode', $dbip->phonePrefix);
                    $this->config->set('plugins.geoplugin.languages', $dbip->languages);
                    $this->config->set('plugins.geoplugin.stateProv', $dbip->stateProv);
                    $this->config->set('plugins.geoplugin.district', $dbip->district);
                    $this->config->set('plugins.geoplugin.city', $dbip->city);
                    $this->config->set('plugins.geoplugin.geonameID', $dbip->geonameID);
                    $this->config->set('plugins.geoplugin.zipcode', $dbip->zipCode);
                    $this->config->set('plugins.geoplugin.latitude', $dbip->latitude);
                    $this->config->set('plugins.geoplugin.longitude', $dbip->longitude);
                    $this->config->set('plugins.geoplugin.gmtOffset', $dbip->gmtOffset);
                    $this->config->set('plugins.geoplugin.timezone', $dbip->tz);
                    $this->config->set('plugins.geoplugin.isp', $dbip->isp);
                    $this->config->set('plugins.geoplugin.organization', $dbip->organization);
                    break;
                } elseif ($service == 'ipinfo') {
                    //dump("In ipinfo");
                    $ipinfo = new IPInfo();
                    $ipinfo->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.geoplugin.city', $ipinfo->city);
                    $this->config->set('plugins.geoplugin.regionCode', $ipinfo->regionCode);
                    $this->config->set('plugins.geoplugin.countryCode', $ipinfo->countryCode);
                    $this->config->set('plugins.geoplugin.latitude', $ipinfo->latitude);
                    $this->config->set('plugins.geoplugin.longitude', $ipinfo->longitude);
                    $this->config->set('plugins.geoplugin.organization', $ipinfo->org);
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
