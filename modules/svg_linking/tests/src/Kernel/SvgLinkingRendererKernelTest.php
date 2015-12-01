<?php

/**
 * @file
 * Contains \Drupal\Tests\svg_linking\Kernel\SvgLinkingRendererKernelTest.
 */

namespace Drupal\Tests\svg_linking\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\DomCrawler\Crawler;
use Drupal\svg_linking\SvgLinkingRenderer;

/**
 * Kernel tests for SVG linked renderer.
 *
 * @coversDefaultClass \Drupal\svg_linking\SvgLinkingRenderer
 * @group svg
 */
class SvgLinkingRendererKernelTest extends KernelTestBase {

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
   * Tests linked SVG image rendering.
   *
   * @covers ::generate
   */
  public function testGenerate() {
    $svg_renderer = new SvgLinkingRenderer();
    $svg_test_path = drupal_get_path('module', 'svg_test') . '/assets/stacks/sprite-stack.svg';

    // Read SVG file and get its viewBox for comparison.
    $svg = file_get_contents(DRUPAL_ROOT . '/' . $svg_test_path);
    $crawler = new Crawler();
    $crawler->addXmlContent($svg);
    $item = $crawler->filter('#drupal-8');
    $view_box = $item->attr('viewBox');

    // Test plain rendering of linked svgs.
    $expected_result = '<svg viewBox="' . $view_box . '"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';
    $this->assertEquals($expected_result, $svg_renderer->generate('stack#drupal-8'), 'linked svg rendering');

    // Test inline svg rendering with custom viewBox.
    $expected_result = '<svg viewBox="1 2 3 4"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';
    $result = $svg_renderer->generate(
      'stack#drupal-8',
      [
        'viewBox' => [
          'x' => 1,
          'y' => 2,
          'width' => 3,
          'height' => 4,
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'linked svg rendering with custom viewBox');

    // Test inline svg rendering with custom attributes.
    $expected_result = '<svg viewBox="' . $view_box . '" class="test-class" data-test="test-value"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';
    $result = $svg_renderer->generate(
      'stack#drupal-8',
      [
        'attributes' => [
          'class' => 'test-class',
          'data-test' => 'test-value',
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'linked svg rendering with custom attributes');

    // Test inline svg rendering with adjusted viewBox.
    $view_box_array = explode(' ', $view_box);
    $view_box_array[2] += 50;
    $view_box_array[3] += 100;

    $expected_result = '<svg viewBox="' . implode(' ', $view_box_array) . '"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';
    $result = $svg_renderer->generate(
      'stack#drupal-8',
      [
        'adjustWidth' => 50,
        'adjustHeight' => 100,
      ]
    );
    $this->assertEquals($expected_result, $result, 'linked svg rendering with adjusted viewBox');

    // Combined test of all techniques to ensure the combination is working.
    $expected_result = '<svg viewBox="1 2 53 104" data-test="test-value"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';
    $result = $svg_renderer->generate(
      'stack#drupal-8',
      [
        'adjustWidth' => 50,
        'adjustHeight' => 100,
        'attributes' => [
          'data-test' => 'test-value',
        ],
        'viewBox' => [
          'x' => 1,
          'y' => 2,
          'width' => 3,
          'height' => 4,
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'linked svg rendering with all manipulation techniques');
  }

}
