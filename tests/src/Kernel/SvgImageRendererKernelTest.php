<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgImageRenderKernelTest.
 */

namespace Drupal\Tests\svg\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\svg\SvgImageRenderer;

/**
 * Kernel tests for SVG image renderer.
 *
 * @coversDefaultClass \Drupal\svg\SvgImageRenderer
 *
 * @group svg
 */
class SvgImageRendererKernelTest extends KernelTestBase {

  /**
   * Exempt from strict schema checking.
   *
   * @see \Drupal\Core\Config\Testing\ConfigSchemaChecker
   *
   * @var bool
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['svg_test'];

  /**
   * SVG base renderer.
   *
   * @var \Drupal\svg\SvgRendererInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $svgRenderer;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->svgRenderer = $this->getMock('\Drupal\svg\SvgRendererInterface');

    $this->installConfig(['svg_test']);
  }

  /**
   * Tests SVG image rendering.
   *
   * @covers ::generate
   */
  public function testGenerate() {
    $svg_renderer = new SvgImageRenderer();
    $svg_test_path = drupal_get_path('module', 'svg_test');
    $expected_result = '<img src="' . base_path() . $svg_test_path . '/assets/icons/drupal-8.svg">';

    $this->assertEquals($expected_result, $svg_renderer->generate('logo'), 'Use image from mapping configuration');

    // Test if attributes can be set.
    $result = $svg_renderer->generate('logo', ['attributes' => ['data-test' => 'test-value']]);
    $expected_result = '<img data-test="test-value" src="' . base_path() . $svg_test_path . '/assets/icons/drupal-8.svg">';
    $this->assertEquals($expected_result, $result, 'Add custom attributes to the image element');
  }

}
