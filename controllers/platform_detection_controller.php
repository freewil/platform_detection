<?php

class PlatformDetectionController extends AppController {
  var $name = 'PlatformDetection';
  var $uses = array();
  var $autoRender = false;
  var $components = array('PlatformDetection');
  
  public function mobileoverride() {
    $this->PlatformDetection->writeThemeCookie('mobileoverride');
    $this->redirect($this->referer('/', true));
  }
  
  public function mobileoverride_reset() {
    $this->PlatformDetection->delThemeCookie();
    $this->redirect($this->referer('/', true));
  }
  
  public function ismobile_json() {
    Configure::write('debug', 0);
    $theme = $this->PlatformDetection->getCurrentTheme();
    $isMobile = in_array('mobile', $theme) ? 'true' : 'false';
    header('Content-Type: application/json');
    setcookie(
    	'PlatformDetection_isMobile', 
      $isMobile,
      time() + 365 * 24 * 60 * 60,
      '/'
    );
    echo json_encode(array('isMobile' => $isMobile));
  }
  
}
