<?php

namespace Drupal\Svg;

class SvgBaseRenderer
{
  protected $config;
  protected $mappings;

  protected $href = '';
  protected $path = '';
  protected $identifier = '';

  protected $dom;

  function __construct() {
    $this->config =  \Drupal::config('svg.config');
    $this->mappings = $this->config->get('mappings');
    $this->dom = new \DOMDocument();
  }

  protected function parseSvg($uri) {
    list($path, $identifier) = explode('#', $uri);

    // Support for the @themeName token in the path.
    preg_match('/@([^\/]+)/', $path, $matches);
    if (count($matches)) {
      $theme_name = $matches[1];
      $theme_path = drupal_get_path('theme', $theme_name);
      if (strlen($theme_path )> 0) {
        $path = str_replace('@' . $theme_name, $theme_path , $path);
      }
    }

    // Resolve Path
    $href = $path;
    if (isset($this->mappings[$path])) {
      $path = $this->mappings[$path]['path'];

      // Replace url replacements.
      if (!empty($this->mappings[$path]['replacement'])) {
        $href = $this->mappings[$path]['replacement'];
      } else {
        $href = '/' . $path;
      }
    }
    if (strlen($identifier) > 0) {
      $href .= '#' . $identifier;
    }

    $this->href = $href;
    $this->path = $path;
    $this->identifier = $identifier;
  }

  /**
   * Loads the svg file and returns the content.
   *
   * @return string or false.
   */
  private function loadSvgFile() {
    return file_get_contents(DRUPAL_ROOT . '/' . $this->path);
  }
}
