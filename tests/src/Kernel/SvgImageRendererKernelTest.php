<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgImageRenderKernelTest.
 */

namespace Drupal\Tests\svg\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\svg\SvgImageRenderer;

/**
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

    $assert_shortcut = $svg_image->generate('logo');
    $assert_token = $svg_image->generate('@svg_test/assets/icons/drupal-8.svg');
    $assert_path = $svg_image->generate($svg_test_path . '/assets/icons/drupal-8.svg');

    $this->assertEquals($expected_result, $assert_shortcut, 'mapping');
    $this->assertEquals($expected_result, $assert_token, 'token');
    $this->assertEquals($expected_result, $assert_path, 'path');
  }

}
