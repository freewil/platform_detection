<?php

class PlatformDetectionComponent extends Object {
  
  /**
   * The current theme being used by the controller
   * @var array
   */
  protected $_currentTheme;
  
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
    $theme = $this->_filterThemeNames($theme);
    $controller->theme = $this->_currentTheme = $theme;
    
    // allow theme to be read from view helper
    $controller->params['PlatformDetection'] = array('theme' => $theme);
    
    header('X-Mobile-Theme: ' . in_array('mobile', $theme) ? 'true' : 'false');
  }
  
  /**
   * Set a cookie on whether the platform is mobile or not.
   * 
   * @param boolean $isMobile
   * @retun void
   */
  public function setIsMobileCookie($isMobile) {
    // use boolean string in cookie
    $isMobile = $isMobile ? 'true' : 'false';
    setcookie(
    	'PlatformDetection_isMobile', 
      $isMobile,
      time() + 365 * 24 * 60 * 60,
      '/'
    );
  }
  
  /**
   * The current theme being used by the controller.
   * 
   * @return array
   */
  public function getCurrentTheme() {
    return $this->_currentTheme;
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
   * Get the forced theme (if any) in this order:
   *   configuration setting 
   *   PlatformDetection_forceTheme cookie
   *   X-Varnish-Theme request header
   * 
   * @return array
   */
  protected function _getForceTheme() {
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
