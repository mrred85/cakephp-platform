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
 * @method PlatformComponent isIE(string $version = null)
 * @method PlatformComponent isInternetExplorer(string $version = null)
 * @method PlatformComponent isEdge(string $version = null)
 * @method PlatformComponent isFirefox(string $version = null)
 * @method PlatformComponent isChrome(string $version = null)
 * @method PlatformComponent isSafari(string $version = null)
 * @method PlatformComponent isOpera(string $version = null)
 * @method PlatformComponent isOperaMini(string $version = null)
 * @method PlatformComponent isOperaMobile(string $version = null)
 * @method PlatformComponent isNetscape(string $version = null)
 * @method PlatformComponent isMaxthon(string $version = null)
 * @method PlatformComponent isKonqueror(string $version = null)
 * @method PlatformComponent isCamino(string $version = null)
 * @method PlatformComponent isFennec(string $version = null)
 * @method PlatformComponent isSeaMonkey(string $version = null)
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
     * @param string|null $version Version number
     * @return string
     */
    private function version(?string $version): string
    {
        if ($version) {
            $version = str_replace('_', '.', $version);
            $parts = explode('.', $version);
            $result = $parts[0];
            if (isset($parts[1])) {
                $result .= '.' . $parts[1];
            } else {
                $result .= '.0';
            }
        } else {
            $result = '0.0';
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

        $this->userAgent = $this->getController()->getRequest()->getEnv('HTTP_USER_AGENT', '');
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
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/crios/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/opr/i' => 'Opera',
            '/opera mobi/i' => 'Opera Mobile',
            '/opera mini/i' => 'Opera Mini',
            '/edge/i' => 'Edge',
            '/edgios/i' => 'Edge',
            '/edga/i' => 'Edge',
            '/firefox/i' => 'Firefox',
            '/fxios/i' => 'Firefox',
            '/klar/i' => 'Firefox',
            '/focus/i' => 'Firefox',
            '/firefox.*fennec/i' => 'Fennec',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/camino/i' => 'Camino',
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
    public function getUserAgent(): string
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
        $regex = null;
        $nrMatch = 0;

        switch ($name) {
            case (substr($name, 0, 7) == 'Windows'):
                $name = (strpos($this->os, 'Windows Server') !== false ? 'Windows Server' : 'Windows');
                $version = trim(str_replace($name, '', $this->os));
                break;
            case 'Mac OS':
                $regex = '/intel mac os x ([0-9_.]+)/i';
                $nrMatch = 1;
                break;
            case 'Mac OS 9':
                $version = '9.2';
                break;
            case 'iPhone':
            case 'iPod':
            case 'iPad':
                $regex = '/\;.*os ([0-9_]+)/i';
                $nrMatch = 1;
                break;
            case 'Android':
                $regex = '/Android ([0-9.]+)/i';
                $nrMatch = 1;
                break;
            case 'BlackBerry':
                $regex = '/Version\/([0-9.]+)/i';
                $nrMatch = 1;
                break;
            case 'WebOS':
                $regex = '/(webOS|hpwOS)\/([0-9.]+)/i';
                $nrMatch = 2;
                break;
            case 'Tizen':
                $regex = '/(Tizen\ |Version\/)([0-9.]+)/i';
                $nrMatch = 2;
                break;
        }
        if ($regex && $nrMatch) {
            $pm = preg_match($regex, $this->userAgent, $matches);
            if ($pm && !empty($matches[$nrMatch])) {
                $version = $this->version($matches[$nrMatch]);

                if ($name == 'Mac OS') {
                    if (version_compare($version, '10.7', '<=')) {
                        $name = 'Mac OS X';
                    } elseif (version_compare($version, '10.11', '<=')) {
                        $name = 'OS X';
                    } else {
                        $name = 'macOS';
                    }
                }
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
            case 'Firefox':
                $regex = '/(Firefox|FxiOS|Klar|Focus)\/([a-z0-9.]+)/i';
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
            case 'Chrome':
                $regex = '/(Chrome|CriOS)\/([0-9.]+)/i';
                $nrMatch = 2;
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
        if ($pm && !empty($matches[$nrMatch])) {
            $version = $this->version($matches[$nrMatch]);
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
    public function isOS(string $os): bool
    {
        $name = $this->os;
        switch (strtolower($os)) {
            case 'windows':
                $result = (substr($name, 0, 7) === 'Windows');
                break;
            case 'mac':
            case 'macos':
            case 'osx':
                $result = (substr($name, 0, 6) === 'Mac OS');
                break;
            case 'linux':
            case 'ubuntu':
                $result = ($name === 'Linux' || $name === 'Ubuntu');
                break;
            case 'ios':
                $result = ($name === 'iPhone' || $name === 'iPod' || $name === 'iPad');
                break;
            default:
                $result = (strtolower($name) === strtolower($os));
                break;
        }

        return $result;
    }

    /**
     * Is browser
     *
     * @param string $browser Browser name
     * @param string|null $version Browser version
     * @return bool
     */
    public function isBrowser(string $browser, ?string $version = null): bool
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
            $result = (strtolower($browser) === strtolower($name) && version_compare($this->version($info->version), $this->version($version), '<='));
        } else {
            $result = (strtolower($browser) === strtolower($name));
        }

        return $result;
    }

    /**
     * Is mobile browser
     *
     * @return bool
     */
    public function isMobile(): bool
    {
        return (strpos($this->userAgent, 'Mobile') !== false ? true : false);
    }

    /**
     * Method types
     *
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return bool
     */
    public function __call(string $name, array $arguments): bool
    {
        $prefix = substr($name, 0, 2);
        if ($prefix && $prefix == 'is') {
            $name = str_replace($prefix, '', $name);
            if ($this->isOS($name)) {
                return true;
            }
            $version = (!empty($arguments[0]) ? $arguments[0] : null);
            if ($this->isBrowser($name, $version)) {
                return true;
            }
        }

        return false;
    }
}
