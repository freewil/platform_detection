<?php

// class is nearly identical to ThemeView, except modifed to handle an array
// for $controller->theme

class ThemeArrayView extends View {
  
	function __construct (&$controller) {
		parent::__construct($controller);
		$this->theme =& $controller->theme;

		if (!empty($this->theme)) {
			foreach ($this->theme as $theme) {
			  if (is_dir(WWW_ROOT . 'themed' . DS . $theme)) {
			    $this->themeWeb = 'themed/' . $theme . '/';
			    break;
			  }
			}
			
			/* deprecated: as of 6128 the following properties are no longer needed */
			$this->themeElement = 'themed'. DS . $this->theme . DS .'elements'. DS;
			$this->themeLayout =  'themed'. DS . $this->theme . DS .'layouts'. DS;
			$this->themePath = 'themed'. DS . $this->theme . DS;
		}
	}
  
/**
 * Return all possible paths to find view files in order
 *
 * @param string $plugin
 * @return array paths
 * @access private
 */
	function _paths($plugin = null, $cached = true) {
		$paths = parent::_paths($plugin, $cached);

		if (!empty($this->theme)) {
			$count = count($paths);
			for ($i = 0; $i < $count; $i++) {
			  $themes = (array)$this->theme;
			  $countThemes = count($themes);
			  for ($j = 0; $j < $countThemes; $j++) {
			    $themePaths[] = $paths[$i] . 'themed'. DS . $themes[$j] . DS;
			  }
			}
			$paths = array_merge($themePaths, $paths);
		}

		if (empty($this->__paths)) {
			$this->__paths = $paths;
		}
		
		return $paths;
	}
  
}
