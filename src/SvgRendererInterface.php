<?php

/**
 * @file
 * Contains \Drupal\svg\SvgRendererInterface.
 */

namespace Drupal\svg;

/**
 * Provides an interface for assembly and dispatch of contact mail messages.
 */
interface SvgRendererInterface {

  /**
   * Resolves URI and sets object properties.
   *
   * @param string $uri
   *   The URI of the image.
   *
   * @return string
   *   The resolved path.
   */
  public function resolveUri($uri);

  /**
   * Parses SVG file for target element.
   *
   * @return bool
   *   Whether parsing was successful or not.
   */
  public function parse();

}
