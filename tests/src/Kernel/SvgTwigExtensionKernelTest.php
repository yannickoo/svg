<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgBaseRenderKernelTest.
 */

namespace Drupal\Tests\svg\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\svg\SvgImageRenderer;
use Drupal\svg\SvgInlineRenderer;
use Drupal\svg\SvgTwigExtension;

/**
 * Unit tests for SVG Twig extensions.
 *
 * @coversDefaultClass \Drupal\svg\SvgTwigExtension
 *
 * @group svg
 */
class SvgTwigExtensionKernelTest extends KernelTestBase {

  /**
   * Tests filters definition.
   *
   * @covers ::getFilters
   */
  public function testFilters() {
    $extension = new SvgTwigExtension();
    $options = ['is_safe' => ['html']];

    $filters = [
      new \Twig_SimpleFilter('svg', [
        new SvgImageRenderer(),
        'generate',
      ], $options),
      new \Twig_SimpleFilter('inline_svg', [
        new SvgInlineRenderer(),
        'generate',
      ], $options),
    ];

    $this->assertEquals($filters, $extension->getFilters(), 'Test Twig filters');
  }

  /**
   * Tests Twig extension name.
   *
   * @covers ::getName
   */
  public function testName() {
    $extension = new SvgTwigExtension();

    $this->assertEquals('svg.twig.svg_extension', $extension->getName(), 'Test Twig extension name');
  }

}
