# platform_detection

Dynamic layout/view [theme](http://book.cakephp.org/1.2/view/488/Themes) 
selection in CakePHP based on user's platform thanks to 
[browscap.ini](https://browsers.garykeith.com/downloads.asp).

## Tested with:
* PHP 5.3.6
* CakePHP 1.2

## Setup
Check that your install of PHP5 has the 
[browscap config setting](http://php.net/manual/misc.configuration.php#ini.browscap) 
pointing to a recent version of 
[browscap.ini](https://browsers.garykeith.com/downloads.asp).

You can easily submodule the plugin into your existing CakePHP project:
`git submodule add https://github.com/freewil/platform_detection app/plugins/platform_detection`

Add the plugin's component and view helper to your AppController:

```php
<?php
class AppController extends Controller {
  var $components = array('PlatformDetection.PlatformDetection');
  var $helpers = array('PlatformDetection.PlatformDetection');
}

```

## Component 
The component will dynamically set the theme in the controller to match the 
user's platform upon each request. An additional fallback theme named `mobile` 
will be used for mobile platforms.

For example, if the user is using an Android phone then the theme will be set to
`android` and `mobile` with `android` having the higher priority. If there
aren't any view scripts for the `android` theme, then the `mobile` theme
will be used as a second priority, and finally falling back to your non-themed 
view scripts as a last resort.

## View Helper
You may add this to the bottom of your `mobile` layout for a `View Full Site` 
link: 

```php
<footer>
  <?php echo $platformDetection->mobileOverrideLink(); ?>
</footer>
```

You may also add this to your normal non-themed layout for a 
`View Mobile Site`:

```php
<footer>
  <?php if ($platformDetection->isMobileOverride()):
    echo $platformDetection->mobileOverrideResetLink();
  endif; ?>
</footer>
```
