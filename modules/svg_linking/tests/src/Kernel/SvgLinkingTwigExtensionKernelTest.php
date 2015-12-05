<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgBaseRenderKernelTest.
 */

namespace Drupal\Tests\svg_linking\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\svg_linking\SvgLinkingRenderer;

/**
 * Unit tests for SVG Twig extensions.
 *
 * @coversDefaultClass \Drupal\svg_linking\SvgLinkingTwigExtension
 *
 * @group svg
 */
class SvgLinkingTwigExtensionKernelTest extends KernelTestBase {

  /**
   * Tests filters definition.
   *
   * @covers ::getFilters
   */
  public function testFilters() {
    $extension = new SvgLinkingRenderer();
    $options = ['is_safe' => ['html']];

    $filters = [
      new \Twig_SimpleFilter('linked_svg', [
        new SvgLinkingRenderer(),
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
    $extension = new SvgLinkingRenderer();

    $this->assertEquals('svg_linking.twig.svg_linking_extension', $extension->getName(), 'Test Twig extension name');
  }

}
