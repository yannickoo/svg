<?php

/**
 * @file
 * Contains \Drupal\svg\SwigTwigExtension.
 */

namespace Drupal\svg;

use Symfony\Component\DomCrawler\Crawler;
use Drupal\svg\SvgImageRenderer;

class SvgTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    $options = ['is_safe' => ['html']];

    return [
      new \Twig_SimpleFilter('svg', [
        new SvgImageRenderer(),
        'generate'
      ], $options),
      new \Twig_SimpleFilter('inline_svg', [
        new SvgInlineImageRenderer(),
        'generate'
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
//
//
//  public function generateResponsiveSourceSvg($uri)
//  {
//    $pathResolved = $this->resolvePath($uri);
//    $svgContent = $this->loadContent($pathResolved);
//
//    $svgContent = str_replace('<?xml version="1.0" encoding="utf-8"? >', '', $svgContent);
//    $svgContent = str_replace('<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">', '', $svgContent);
//    $svgContent = str_replace('<svg ', '<svg style="display:none;" ', $svgContent);
//
//    return $svgContent;
//  }
//
//  public function generateResponsiveSvg($uri, $config = [])
//  {
//
//
//    $config = array_merge($default, (array) $config);
//
//    // Parse svg file and read viewBox attribute.
//    $svg = $this->loadContent($pathResolved);
//    if ($svg === false) {
//      drupal_set_message('Cannot find SVG ' . $uri);
//      return '';
//    }
//
//
//
//    // Build markup
//    list($x, $y, $width, $height) = explode(' ', $viewBox);
//
//    $width += $config['offsetX'];
//    $height += $config['offsetY'];
//
//    if (isset($config['width'])) {
//      $width = $config['width'];
//    }
//
//    if (isset($config['height'])) {
//      $height = $config['height'];
//    }
//
//    $padding = round($height / $width * 100, 5);
//
//    $classes = array_merge(['responsive-svg'], explode(' ', $config['class']));
//
//    $dom = new \DOMDocument();
//
//    $wrapper = $dom->createElement('div');
//    $wrapper->setAttribute('class', implode(' ', $classes));
//    $wrapper->setAttribute('style', 'position: relative;');
//
//    $filler = $dom->createElement('div');
//    $filler->setAttribute('style', 'width: 100%; height: 0; overflow-hidden; padding-bottom: ' . $padding . '%');
//    $wrapper->appendChild($filler);
//
//    if (strlen($identifier) > 0) {
//      $svg = $dom->createElement('svg');
//
//      if (isset($mappings[$path]['method']) && $mappings[$path]['method'] == 'inline') {
//        // Inline SVG.
//        $svg->setAttribute('viewBox', $viewBox);
//        $content = $dom->createDocumentFragment();
//        $content->appendXML($item->html());
//        $svg->appendChild($content);
//      } else {
//        // Linked SVG.
//        $svg->setAttribute('viewBox', implode(' ', [0, 0, $width, $height]));
//        $use = $dom->createElement('use');
//        $use->setAttribute('xlink:href', $href);
//        $svg->appendChild($use);
//      }
//    } else {
//      $svg = $dom->createElement('object');
//      $svg->setAttribute('type', 'image/svg+xml');
//      $svg->setAttribute('data', $href);
//    }
//
//    $svg->setAttribute('style', 'position: absolute; top: 0; bottom: 0; left: 0; right: 0;');
//
//    $wrapper->appendChild($svg);
//    $dom->appendChild($wrapper);
//
//    return $dom->saveHTML();
//  }
//}
