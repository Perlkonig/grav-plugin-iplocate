<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class IPLocatePlugin
 * @package Grav\Plugin
 */
class IPLocatePlugin extends Plugin
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

        //It's possible to specify the ip sent to the APIs,
        //mainly for test purposes.
        if ($this->config->get('plugins.iplocate.test_ip')) {
            $ip = $this->config->get('plugins.iplocate.test_ip');
        } else {
            global $_SERVER;
            if (!isset($ip)) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
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

        foreach ($this->config->get('plugins.iplocate.sequence') as $service) {
            try {
                if ($service === 'geoplugin') {
                    //dump("In geoplugin");
                    $geoplugin = new geoPlugin();
                    $geoplugin->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.iplocate.areaCode', $geoplugin->areaCode);
                    $this->config->set('plugins.iplocate.city', $geoplugin->city);
                    $this->config->set('plugins.iplocate.countryCode', $geoplugin->countryCode);
                    $this->config->set('plugins.iplocate.countryName', $geoplugin->countryName);
                    $this->config->set('plugins.iplocate.dmaCode', $geoplugin->dmaCode);
                    $this->config->set('plugins.iplocate.latitude', $geoplugin->latitude);
                    $this->config->set('plugins.iplocate.longitude', $geoplugin->longitude);
                    $this->config->set('plugins.iplocate.regionCode', $geoplugin->regionCode);
                    $this->config->set('plugins.iplocate.currencyCode', $geoplugin->currencyCode);
                    break;
                } elseif ($service === 'freegeoip') {
                    //dump("In freegeoip");
                    $freegeoip = new freeGeoIP();
                    $freegeoip->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.iplocate.city', $freegeoip->city);
                    $this->config->set('plugins.iplocate.countryCode', $freegeoip->countryCode);
                    $this->config->set('plugins.iplocate.countryName', $freegeoip->countryName);
                    $this->config->set('plugins.iplocate.latitude', $freegeoip->latitude);
                    $this->config->set('plugins.iplocate.longitude', $freegeoip->longitude);
                    $this->config->set('plugins.iplocate.metroCode', $freegeoip->metroCode);
                    $this->config->set('plugins.iplocate.regionCode', $freegeoip->regionCode);
                    $this->config->set('plugins.iplocate.regionName', $freegeoip->regionName);
                    $this->config->set('plugins.iplocate.timezone', $freegeoip->timezone);
                    $this->config->set('plugins.iplocate.zipcode', $freegeoip->zipcode);
                    break;
                } elseif ($service === 'dbip') {
                    //dump("In dbip");
                    $dbip = new DBIP();
                    $dbip->locate($this->grav['cache'], $this->config->get('plugins.iplocate.keys.dbip'), $ip);
                    $this->config->set('plugins.iplocate.continentCode', $dbip->continentCode);
                    $this->config->set('plugins.iplocate.continentName', $dbip->continentName);
                    $this->config->set('plugins.iplocate.countryCode', $dbip->countryCode);
                    $this->config->set('plugins.iplocate.countryName', $dbip->countryName);
                    $this->config->set('plugins.iplocate.currencyCode', $dbip->currencyCode);
                    $this->config->set('plugins.iplocate.areaCode', $dbip->phonePrefix);
                    $this->config->set('plugins.iplocate.languages', $dbip->languages);
                    $this->config->set('plugins.iplocate.stateProv', $dbip->stateProv);
                    $this->config->set('plugins.iplocate.district', $dbip->district);
                    $this->config->set('plugins.iplocate.city', $dbip->city);
                    $this->config->set('plugins.iplocate.geonameID', $dbip->geonameID);
                    $this->config->set('plugins.iplocate.zipcode', $dbip->zipCode);
                    $this->config->set('plugins.iplocate.latitude', $dbip->latitude);
                    $this->config->set('plugins.iplocate.longitude', $dbip->longitude);
                    $this->config->set('plugins.iplocate.gmtOffset', $dbip->gmtOffset);
                    $this->config->set('plugins.iplocate.timezone', $dbip->tz);
                    $this->config->set('plugins.iplocate.isp', $dbip->isp);
                    $this->config->set('plugins.iplocate.organization', $dbip->organization);
                    break;
                } elseif ($service == 'ipinfo') {
                    //dump("In ipinfo");
                    $ipinfo = new IPInfo();
                    $ipinfo->locate($this->grav['cache'], $ip);
                    $this->config->set('plugins.iplocate.city', $ipinfo->city);
                    $this->config->set('plugins.iplocate.regionCode', $ipinfo->regionCode);
                    $this->config->set('plugins.iplocate.countryCode', $ipinfo->countryCode);
                    $this->config->set('plugins.iplocate.latitude', $ipinfo->latitude);
                    $this->config->set('plugins.iplocate.longitude', $ipinfo->longitude);
                    $this->config->set('plugins.iplocate.organization', $ipinfo->org);
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
