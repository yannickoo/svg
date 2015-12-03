<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgBaseRenderKernelTest.
 */

namespace Drupal\Tests\svg\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\svg\SvgBaseRenderer;

/**
 * Kernel tests for SVG base renderer.
 *
 * @coversDefaultClass \Drupal\svg\SvgBaseRenderer
 *
 * @group svg
 */
class SvgBaseRendererKernelTest extends KernelTestBase {

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
   * Tests constructing.
   *
   * @covers ::__construct
   */
  public function testConstruct() {
    $svg_base = new SvgBaseRenderer();
    $mappings = $svg_base->mappings;
    $config = \Drupal::config('svg.config');

    $this->assertEquals($mappings, $config->get('mappings'), 'Constructor test');
  }

  /**
   * Tests URI resolving.
   *
   * @covers ::resolveUri
   */
  public function testResolveUri() {
    $svg_base = new SvgBaseRenderer();
    $svg_test_path = drupal_get_path('module', 'svg_test');
    $expected_result = $svg_test_path . '/assets/icons/drupal-8.svg';

    $assertions = [
      [
        'message' => 'Use path from mapping configuration',
        'output' => $svg_base->resolveUri('logo'),
      ],
      [
        'message' => 'Use placeholder in URI',
        'output' => $svg_base->resolveUri('@svg_test/assets/icons/drupal-8.svg'),
      ],
      [
        'message' => 'Use path as URI',
        'output' => $svg_base->resolveUri($svg_test_path . '/assets/icons/drupal-8.svg'),
      ],
    ];

    foreach ($assertions as $assertion) {
      $this->assertEquals($expected_result, $assertion['output'], $assertion['message']);
    }

    $stack_uri_expected = $svg_test_path . '/assets/stacks/sprite-stack.svg';
    $stack_uri = $svg_base->resolveUri('stack#drupal-8');

    $this->assertEquals($stack_uri, $stack_uri_expected, 'Use stack as URI');
    $this->assertEquals($svg_base->identifier, 'drupal-8', 'Test identifier detection');
  }

  /**
   * Tests SVG parsing.
   *
   * @covers ::parse
   */
  public function testParse() {
    $svg_base = new SvgBaseRenderer();

    $svg_base->resolveUri('logo');
    $this->assertTrue($svg_base->parse(), 'Parse logo');

    $svg_base->resolveUri('logo#foo');
    $this->assertFalse($svg_base->parse(), 'Parse logo with non-existent identifier');

    $svg_base->resolveUri('stack#drupal-8');
    $this->assertTrue($svg_base->parse(), 'Parse stack with identifier');

    $svg_base->resolveUri('stack#drupal-9');
    $this->assertFalse($svg_base->parse(), 'Parse stack with non-existent identifier');

    $svg_base->resolveUri('not-found');
    $this->assertFalse($svg_base->parse(), 'Parse non-existing URI');

    $svg_base->resolveUri('@svg_test/assets/icons/druplicon-no-viewbox.svg');
    $svg_base->parse();
    $this->assertEquals($svg_base->viewBox, [], 'Test SVG without viewBox attribute');
  }

}
