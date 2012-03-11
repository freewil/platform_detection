<?php

class PlatformDetectionComponent extends Object {
  
  /**
   * Set theme of controller based on user's platform. If the user is using
   * a mobile device, a generic 'mobile' theme will be used as a second 
   * priority to the platform-specific theme.
   * 
   * The theme may be overridden in config file for development purposes:
   * Configure::write('PlatformDetection.forceTheme, array('android', 'mobile'));
   * 
   * @param AppController $controller
   * @return void
   */
  public function startup(AppController $controller) {
    $controller->view = 'PlatformDetection.ThemeArray';
    
    $theme = $this->_getForceTheme();
    if (empty($theme)) {
      $theme = $this->_getUnforceTheme();
    }
    
    $controller->theme = $this->_filterThemeNames($theme);
  }
  
  /**
   * Set a PlatformDetection_forceTheme cookie to override the theme via 
   * user-agent detection.
   * 
   * NOTE: The cookie does NOT override 
   * Configure::write('PlatformDetection.forceTheme') if that has been set.
   *  
   * @param mixed $theme
   * @return void
   */
  public function writeThemeCookie($theme) {
    $theme = $this->_filterThemeNames($theme);
    setcookie('PlatformDetection_forceTheme', json_encode($theme), strtotime('3 months'), '/');
  }
  
  /**
   * Delete a theme cookie previously set with WriteThemeCookie().
   * 
   * @return void
   */
  public function delThemeCookie() {
    setcookie('PlatformDetection_forceTheme', null, strtotime('-1 year'), '/');
  }
  
  /**
   * Get the forced theme via a configuration setting or cookie if one exists.
   * 
   * @return array
   */
  protected function _getForceTheme() {
    Configure::load('platform_detection');
    $forceTheme = Configure::read('PlatformDetection.forceTheme');
    if (empty($forceTheme)) {
      $forceTheme = isset($_COOKIE['PlatformDetection_forceTheme'])
        ? json_decode($_COOKIE['PlatformDetection_forceTheme'], false, 2)
        : array();
    }
    
    return (array)$forceTheme;
  }
  
  /**
   * Get the normal unforced theme via user agent string.
   * 
   * @return array
   */
  protected function _getUnforceTheme() {
    $browser = get_browser();
    if (!$browser || !isset($browser->platform))
      return array();
      
    $theme = (array)$browser->platform;
    if (isset($browser->ismobiledevice) && $browser->ismobiledevice)
      $theme[] = 'mobile';
      
    return $theme;
  }
  
  /**
   * Replaces any potentially dangerous characters in the theme names.
   * 
   * @param string|array $theme
   * @return array
   */
  protected function _filterThemeNames($theme) {
    $theme = (array)$theme;
    $filtered = array();
    foreach ($theme as $t) {
      $filtered[] = preg_replace('/[^a-z]/', '_', strtolower((string)$t));
    }
    return $filtered;
  }
  
}
