<?php

/**
 * @file
 * Contains \Drupal\svg\SvgBaseRenderer.
 */

namespace Drupal\svg;

use Symfony\Component\DomCrawler\Crawler;

class SvgBaseRenderer {

  /**
   * An array containing mapping configuration.
   *
   * @var array
   */
  protected $mappings;

  /**
   * The URI of the image.
   *
   * @var string
   */
  protected $uri = '';

  /**
   * The path of the SVG (with base path included).
   *
   * @var string
   */
  protected $href = '';

  /**
   * The path of the image.
   *
   * @var string
   */
  protected $path = '';

  /**
   * The requested identifier of the target element.
   *
   * @var string
   */
  protected $identifier = '';

  /**
   * The viewBox of the SVG.
   *
   * @var array
   */
  protected $viewBox = [];

  /**
   * A Crawler object containing target element.
   *
   * @var \Symfony\Component\DomCrawler\Crawler
   */
  protected $svgContent;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $config = \Drupal::config('svg.config');
    $this->mappings = $config->get('mappings');
  }

  /**
   * Resolves URI and sets object properties.
   *
   * @param string $uri
   *   The URI of the image.
   */
  protected function resolveUri($uri) {
    $path = $uri;
    $identifier = '';

    // Split URI into path and identifier if it contains "#".
    if (strpos($uri, '#') !== FALSE) {
      list($path, $identifier) = explode('#', $uri);
    }

    // Get mapping entry path.
    if (isset($this->mappings[$path])) {
      $path = $this->mappings[$path]['path'];
    }

    // Support for the @project token in the path.
    preg_match('/@([^\/]+)/', $path, $matches);
    if (count($matches)) {
      $project_name = $matches[1];
      $module_path = @drupal_get_path('module', $project_name);
      $theme_path = @drupal_get_path('theme', $project_name);
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

  /**
   * Parses SVG file for target element.
   *
   * @return bool
   *   Whether parsing was successful or not.
   */
  protected function parse() {
    $path = DRUPAL_ROOT . '/' . $this->path;

    if (!file_exists($path)) {
      drupal_set_message('File ' . $this->path . ' does not exist', 'warning');
      return FALSE;
    }

    $svg = file_get_contents($path);

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
