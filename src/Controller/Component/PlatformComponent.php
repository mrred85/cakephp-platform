<?php
/**
 * Platform Component
 *
 * @link https://github.com/mrred85/cakephp-platform
 * @copyright 2016 - present Victor Rosu. All rights reserved.
 * @license Licensed under the MIT License.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * @package App\Controller\Component
 * @method PlatformComponent isWindows()
 * @method PlatformComponent isMac()
 * @method PlatformComponent isMacOS()
 * @method PlatformComponent isLinux()
 * @method PlatformComponent isUbuntu()
 * @method PlatformComponent isiOS()
 * @method PlatformComponent isiPod()
 * @method PlatformComponent isiPad()
 * @method PlatformComponent isiPhone()
 * @method PlatformComponent isAndroid()
 * @method PlatformComponent isBlackBerry()
 * @method PlatformComponent isWebOS()
 * @method PlatformComponent isTizen()
 *
 * @method PlatformComponent isIE(mixed $version = null)
 * @method PlatformComponent isInternetExplorer(mixed $version = null)
 * @method PlatformComponent isEdge(mixed $version = null)
 * @method PlatformComponent isFirefox(mixed $version = null)
 * @method PlatformComponent isChrome(mixed $version = null)
 * @method PlatformComponent isSafari(mixed $version = null)
 * @method PlatformComponent isOpera(mixed $version = null)
 * @method PlatformComponent isOperaMini(mixed $version = null)
 * @method PlatformComponent isOperaMobile(mixed $version = null)
 * @method PlatformComponent isNetscape(mixed $version = null)
 * @method PlatformComponent isMaxthon(mixed $version = null)
 * @method PlatformComponent isKonqueror(mixed $version = null)
 * @method PlatformComponent isCamino(mixed $version = null)
 * @method PlatformComponent isFennec(mixed $version = null)
 * @method PlatformComponent isSeaMonkey(mixed $version = null)
 */
class PlatformComponent extends Component
{
    /**
     * User Agent
     *
     * @var string $userAgent
     */
    private $userAgent;

    /**
     * Operating System raw information
     *
     * @var string $os
     */
    private $os;

    /**
     * Browser raw information
     *
     * @var string $browser
     */
    private $browser;

    /**
     * @param mixed $version Version number
     * @return null|string
     */
    private function version($version)
    {
        $result = null;
        $parts = explode('.', $version);
        if ($parts) {
            $result = $parts[0];
            if (isset($parts[1])) {
                $result .= '.' . $parts[1];
            } else {
                $result .= '.0';
            }
        }

        return $result;
    }

    /**
     * Constructor hook method.
     *
     * @param array $config Platform config
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->userAgent = $this->getController()->request->getEnv('HTTP_USER_AGENT');
        $this->os = 'Unknown';
        $this->browser = 'Unknown';

        $osArray = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/macintosh/i' => 'Mac OS',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/bb10/i' => 'BlackBerry',
            '/webos/i' => 'WebOS',
            '/hpwos/i' => 'WebOS',
            '/tizen/i' => 'Tizen'
        ];
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $this->userAgent)) {
                $this->os = $value;
            }
        }

        $browserArray = [
            '/msie/i' => 'Internet Explorer',
            '/trident/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/crios/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/opr/i' => 'Opera',
            '/edge/i' => 'Edge',
            '/edgios/i' => 'Edge',
            '/edga/i' => 'Edge',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/camino/i' => 'Camino',
            '/opera mobi/i' => 'Opera Mobile',
            '/opera mini/i' => 'Opera Mini',
            '/firefox.*fennec/i' => 'Fennec',
            '/seamonkey/i' => 'Sea Monkey'
        ];
        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $this->userAgent)) {
                $this->browser = $value;
            }
        }
    }

    /**
     * Get User Agent value
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Get Operating System information
     *
     * @return object
     */
    public function getOS()
    {
        $name = $this->os;
        $version = null;

        if (substr($name, 0, 7) == 'Windows') {
            $name = strstr($this->os, 'Windows Server') ? 'Windows Server' : 'Windows';
            $version = trim(str_replace($name, '', $this->os));
        }
        if ($name == 'Mac OS') {
            $pm = preg_match('/intel mac os x ([0-9_.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[1]) && !empty($matches[1])) {
                $matches[1] = str_replace('_', '.', $matches[1]);
                $versionArray = explode('.', $matches[1]);
                $name = 'macOS';
                if ($versionArray[1] <= 7) {
                    $name = 'Mac OS X';
                } elseif ($versionArray[1] > 7 && $versionArray[1] <= 11) {
                    $name = 'OS X';
                }
                $version = implode('.', array_slice($versionArray, 0, 2));
            }
        }
        if ($name == 'Mac OS 9') {
            $version = '9.2';
        }
        if ($name == 'iPhone' || $name == 'iPod' || $name == 'iPad') {
            $pm = preg_match('/\;.*os ([0-9_.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[1]) && !empty($matches[1])) {
                $matches[1] = str_replace('_', '.', $matches[1]);
                $version = implode('.', array_slice(explode('.', $matches[1]), 0, 2));
            }
        }
        if ($name == 'Android') {
            $pm = preg_match('/\; Android ([0-9.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[1]) && !empty($matches[1])) {
                $version = implode('.', array_slice(explode('.', $matches[1]), 0, 2));
            }
        }
        if ($name == 'BlackBerry') {
            $pm = preg_match('/Version\/([0-9.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[1]) && !empty($matches[1])) {
                $version = implode('.', array_slice(explode('.', $matches[1]), 0, 2));
            }
        }
        if ($name == 'WebOS') {
            $pm = preg_match('/(webOS|hpwOS)\/([0-9.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[2]) && !empty($matches[2])) {
                $version = implode('.', array_slice(explode('.', $matches[2]), 0, 2));
            }
        }
        if ($name == 'Tizen') {
            $pm = preg_match('/(Tizen\ |Version\/)([0-9.]+)/i', $this->userAgent, $matches);
            if ($pm && isset($matches[2]) && !empty($matches[2])) {
                $version = implode('.', array_slice(explode('.', $matches[2]), 0, 2));
            }
        }

        return (object)[
            'name' => $name,
            'version' => $version
        ];
    }

    /**
     * Get browser information
     *
     * @return object
     */
    public function getBrowser()
    {
        $name = $this->browser;
        $version = null;

        switch ($name) {
            case 'Internet Explorer':
                $regex = '/(MSIE\ |rv\:)([0-9.]+)/i';
                $nrMatch = 2;
                break;
            case 'Edge':
                $regex = '/(Edge|EdgiOS|EdgA)\/([0-9.]+)/i';
                $nrMatch = 2;
                break;
            case 'Safari':
                $regex = '/Version\/([0-9.]+)/i';
                $nrMatch = 1;
                break;
            case 'Opera':
                $regex = '/(Opera\ |Version\/|OPR\/)([0-9.]+)/i';
                $nrMatch = 2;
                break;
            case 'Opera Mobile':
                $regex = '/Version\/([0-9.]+)/i';
                $nrMatch = 1;
                break;
            case 'Sea Monkey':
                $regex = '/SeaMonkey\/([0-9.]+)/i';
                $nrMatch = 1;
                break;
            default:
                $regex = '/' . trim($name) . '\/([0-9.]+)/i';
                $nrMatch = 1;
                break;
        }
        $pm = preg_match($regex, $this->userAgent, $matches);
        if ($pm && isset($matches[$nrMatch]) && !empty($matches[$nrMatch])) {
            $version = $matches[$nrMatch];
            $version = implode('.', array_slice(explode('.', $version), 0, 2));
        }

        return (object)[
            'name' => $name,
            'version' => $version
        ];
    }

    /**
     * Is Operating System
     *
     * @param string $os Operating System name
     * @return bool
     */
    public function isOS($os)
    {
        $name = $this->os;
        switch (strtolower($os)) {
            case 'windows':
                $result = substr($name, 0, 7) === 'Windows';
                break;
            case 'mac':
            case 'macos':
            case 'osx':
                $result = substr($name, 0, 6) === 'Mac OS';
                break;
            case 'linux':
            case 'ubuntu':
                $result = ($name === 'Linux' || $name === 'Ubuntu');
                break;
            case 'ios':
                $result = ($name === 'iPhone' || $name === 'iPod' || $name === 'iPad');
                break;
            default:
                $result = strtolower($name) === strtolower($os);
                break;
        }

        return $result;
    }

    /**
     * Is browser
     *
     * @param string $browser Browser name
     * @param mixed $version Browser version
     * @return bool
     */
    public function isBrowser($browser, $version = null)
    {
        $info = $this->getBrowser();
        $name = $info->name;
        switch (strtolower($browser)) {
            case 'ie':
            case 'internetexplorer':
                $browser = 'Internet Explorer';
                break;
            case 'operamini':
                $browser = 'Opera Mini';
                break;
            case 'operamobile':
                $browser = 'Opera Mobile';
                break;
            case 'seamonkey':
                $browser = 'Sea Monkey';
                break;
            case 'opera':
                $name = substr($name, 0, strlen($browser));
                break;
        }
        if ($version) {
            return strtolower($browser) === strtolower($name) && version_compare($info->version, $version, '<=');
        }

        return strtolower($browser) === strtolower($name);
    }

    /**
     * Method types
     *
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 2);
        if ($prefix && $prefix == 'is') {
            $name = str_replace($prefix, '', $name);
            if ($this->isOS($name)) {
                return true;
            }
            $version = isset($arguments[0]) && $arguments[0] ? $this->version($arguments[0]) : null;
            if ($this->isBrowser($name, $version)) {
                return true;
            }
        }

        return false;
    }
}
