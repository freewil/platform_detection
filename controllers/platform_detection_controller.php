<?php

class PlatformDetectionController extends AppController {
  var $name = 'PlatformDetection';
  var $uses = array();
  var $autoRender = false;
  var $components = array('PlatformDetection');
  
  public function mobileoverride() {
    $this->PlatformDetection->writeThemeCookie('mobileoverride');
    $this->PlatformDetection->setIsMobileCookie(false);
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
    $this->PlatformDetection->setIsMobileCookie(in_array('mobile', $theme));
    header('Content-Type: application/json');
    echo json_encode(array('isMobile' => $isMobile));
  }
}
