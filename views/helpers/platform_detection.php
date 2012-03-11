<?php

class PlatformDetectionHelper extends AppHelper {
  var $helpers = array('Html');

  /**
   * Returns a link to view the full site, overriding the mobile detection.
   * 
   * @param string $title (OPTIONAL)
   * @return string
   */
  public function mobileOverrideLink($title = 'View Full Site') {
    return $this->Html->link($title, array(
      'controller' => 'platform_detection', 
      'action' => 'mobileoverride'
    ));
  }
  
  /**
   * Returns a link to view the mobile theme which resets the user's previous
   * choice to override the mobile detection.
   *
   * @return string
   */
  public function mobileOverrideResetLink($title = 'View Mobile Site') {
    return $this->Html->link($title, array(
    	'controller' => 'platform_detection',
      'action' => 'mobileoverride_reset'
    ));
  }
  
  /**
   * Has the user chosen to override the mobile detection?
   *
   * @return boolean
   */
  public function isMobileOverride() {
    if (!isset($_COOKIE['PlatformDetection_forceTheme']))
      return false;
      
    $forceTheme = json_decode($_COOKIE['PlatformDetection_forceTheme'], false, 2);
    return is_array($forceTheme) && in_array('mobileoverride', $forceTheme);
  }

}
