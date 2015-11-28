<?php

namespace Drupal\svg;

use Symfony\Component\DomCrawler\Crawler;

class SvgBaseRenderer {
  protected $config;
  protected $mappings;

  protected $uri = '';
  protected $href = '';
  protected $path = '';
  protected $identifier = '';
  protected $viewBox = [];
  protected $svgContent;

  protected $dom;

  function __construct() {
    $this->config = \Drupal::config('svg.config');
    $this->mappings = $this->config->get('mappings');
    $this->dom = new \DOMDocument();
  }

  protected function resolveUri($uri) {
    list($path, $identifier) = explode('#', $uri);

    // Resolve Path
    if (isset($this->mappings[$path])) {
      $path = $this->mappings[$path]['path'];
    }

    // Support for the @themeName token in the path.
    preg_match('/@([^\/]+)/', $path, $matches);
    if (count($matches)) {
      $theme_name = $matches[1];
      $theme_path = drupal_get_path('theme', $theme_name);
      if (strlen($theme_path) > 0) {
        $path = str_replace('@' . $theme_name, $theme_path, $path);
      }
    }

    // Replace URL replacements.
    if (!empty($this->mappings[$path]['replacement'])) {
      $href = $this->mappings[$path]['replacement'];
    }
    else {
      $href = base_path() . $path;
    }
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

    // Read viewbox
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
