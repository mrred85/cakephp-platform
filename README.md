# CakePHP Platform Component

PlatformComponent is a CakePHP component created to determine your browser name, browser version, operating system name and version based on your browser user agent.

## Install Platform
- Copy the `src > Controller > Component > PlatformComponent.php` to your **Component** folder.
- Load the component in the controller with `$this->loadComponent('Platform');` command.

### Requirements
- PHP >= 7.1.x
- CakePHP >= 3.6.x

## Browsers
- IE / InternetExplorer
- Edge
- Firefox
- Chrome
- Safari
- Opera
- OperaMini
- OperaMobile
- Netscape
- Maxthon
- Konqueror
- Camino
- Fennec
- SeaMonkey

## isBrowser
```php
function isBrowser(string $browser, string $version = null): bool
```

Usage of `isBrowser` function:
```php
$result = $this->Platform->isBrowser('safari');
var_dump($result);

$result = $this->Platform->isBrowser('ie', '8');
var_dump($result);
```

## Mobile devices, OS and Mobile OS
- Windows
- Mac / MacOS
- Linux
- Ubuntu
- iOS
- Android
- iPod
- iPad
- iPhone
- BlackBerry

## isOS
```php
function isOS(string $os): bool
```

Usage of `isOS` function:
```php
$result = $this->Platform->isOS('macos');
var_dump($result);
```

## Dynamic `is` methods
You can also check the operating system and browser using dynamically constructed `is` methods. The name of the operating system or browser name is in upper camel case, like `OperatingSystemName` or `BrowserName`. All methods return boolean values.
```php
$result = $this->Platform->isMacOS();
var_dump($result);

$result = $this->Platform->isChrome();
var_dump($result);

$result = $this->Platform->isInternetExplorer('8.1');
var_dump($result);
```

**Special names**
- `isMacOS()`
- `isiOS()`
- `isiPod()`
- `isiPad()`
- `isiPhone()`
- `isWebOS()`
- `isIE($version = null)`

## Example
Load `Platform` component and get browser name and version.
```php
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * @package App\Controller
 * @property \App\Controller\Component\PlatformComponent $Platform
 */
class AppController extends Controller
{
    // Your code
    public function initialize()
    {
        $this->loadComponent('Platform');
    }

    public function index()
    {
        // Get browser User Agent
        $ua = $this->Platform->getUserAgent();
        echo $ua;

        // BROWSER
        $browser = $this->Platform->getBrowser();
        // Get browser name
        echo $browser->name;

        // Get browser version
        echo $browser->version;

        // OS
        $os = $this->Platform->getOS();
        // Get operating system name
        echo $os->name;

        // Get operating system version
        echo $os->version;

        // Check if is iPhone
        $result = $this->Platform->isiPhone();
        var_dump($result);

        // Check if is iOS device
        $result = $this->Platform->isiOS();
        var_dump($result);
    }
    // Your code
}
```

Enjoy ;)
