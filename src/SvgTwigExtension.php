<?php

/**
 * @file
 * Contains \Drupal\svg\SvgTwigExtension.
 */

namespace Drupal\svg;

/**
 * Class SvgTwigExtension
 *
 * @package Drupal\svg
 */
class SvgTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    $options = ['is_safe' => ['html']];

    return [
      new \Twig_SimpleFilter('svg', [
        new SvgImageRenderer(),
        'generate',
      ], $options),
      new \Twig_SimpleFilter('inline_svg', [
        new SvgInlineRenderer(),
        'generate',
      ], $options),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'svg.twig.svg_extension';
  }

}
