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
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['svg_test']);
  }

  /**
   * @covers ::generate
   */
  public function testGenerate() {
    $svg_image = new SvgImageRenderer();
    $svg_test_path = drupal_get_path('module', 'svg_test');
    $expected_result = '<img src="' . base_path() . $svg_test_path . '/assets/icons/drupal-8.svg">';

    $assertions = [
      [
        'message' => 'Use image from mapping configuration',
        'output' => $svg_image->generate('logo'),
      ],
      [
        'message' => 'Use placeholder in URI',
        'output' => $svg_image->generate('@svg_test/assets/icons/drupal-8.svg'),
      ],
      [
        'message' => 'Use path as URI',
        'output' => $svg_image->generate($svg_test_path . '/assets/icons/drupal-8.svg'),
      ],
    ];

    foreach ($assertions as $assertion) {
      $this->assertEquals($expected_result, $assertion['output'], $assertion['message']);
    }

    // Test if attributes can be set.
    $result = $svg_image->generate('logo', ['attributes' => ['data-test' => 'test-value']]);
    $expected_result = '<img data-test="test-value" src="' . base_path() . $svg_test_path . '/assets/icons/drupal-8.svg">';
    $this->assertEquals($expected_result, $result, 'Add custom attributes to the image element');
  }

}
