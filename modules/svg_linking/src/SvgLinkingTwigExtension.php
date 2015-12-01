<?php

/**
 * @file
 * Contains \Drupal\svg_linking\SvgLinkingTwigExtension.
 */

namespace Drupal\svg_linking;

class SvgLinkingTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    $options = ['is_safe' => ['html']];

    return [
      new \Twig_SimpleFilter('linked_svg', [
        new SvgLinkingRenderer(),
        'generate'
      ], $options),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'svg_linking.twig.svg_linking_extension';
  }

}
