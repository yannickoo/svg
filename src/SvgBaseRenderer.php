<?php

namespace Drupal\svg;

use Symfony\Component\DomCrawler\Crawler;

class SvgBaseRenderer {
  protected $mappings;
  protected $uri = '';
  protected $href = '';
  protected $path = '';
  protected $identifier = '';
  protected $viewBox = [];
  protected $svgContent;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $config = \Drupal::config('svg.config');
    $this->mappings = $config->get('mappings');
  }

  protected function resolveUri($uri) {
    $path = $uri;
    $identifier = '';
    if (strpos($uri, '#') !== false) {
      list($path, $identifier) = explode('#', $uri);
    }

    // Resolve Path
    if (isset($this->mappings[$path])) {
      $path = $this->mappings[$path]['path'];
    }

    // Support for the @project token in the path.
    preg_match('/@([^\/]+)/', $path, $matches);
    if (count($matches)) {
      $project_name = $matches[1];

      try {
        $module_path = drupal_get_path('module', $project_name);
      } catch (\Exception $e) {
        $module_path = '';
      }

      try {
        $theme_path = drupal_get_path('theme', $project_name);
      } catch (\Exception $e) {
        $theme_path = '';
      }

      $project_path = $theme_path ? $theme_path : $module_path;

      if ($project_path) {
        $path = str_replace('@' . $project_name, $project_path, $path);
      }
    }

    $href = base_path() . $path;
    if (strlen($identifier) > 0) {
      $href .= '#' . $identifier;
    }

    $this->uri = $uri;
    $this->href = $href;
    $this->path = $path;
    $this->identifier = $identifier;
  }

  protected function parse() {
    $svg = file_get_contents(DRUPAL_ROOT . '/' . $this->path);

    $crawler = new Crawler();
    $crawler->addXmlContent($svg);
    $item = $crawler;

    // Filter SVG for child element when identifier is given.
    if ($this->identifier) {
      $item = $crawler->filter('#' . $this->identifier);
    }

    if (!$item->count()) {
      drupal_set_message('Cannot find SVG element for ' . $this->uri, 'warning');
      return FALSE;
    }

    // Get SVG viewBox attribute.
    $view_box = $item->attr('viewBox');

    if (!$view_box) {
      $this->viewBox = [];
    }
    else {
      list($x, $y, $width, $height) = explode(' ', $view_box);

      $this->viewBox = compact('x', 'y', 'width', 'height');
    }

    $this->svgContent = $item;

    return TRUE;
  }

}
