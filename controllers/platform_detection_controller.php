<?php

class PlatformDetectionController extends PlatformDetectionAppController {
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
  
}
