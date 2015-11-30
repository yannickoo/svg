<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Kernel\SvgImageRenderKernelTest.
 */

namespace Drupal\Tests\svg\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\DomCrawler\Crawler;
use Drupal\svg\SvgInlineImageRenderer;

/**
 * Kernel tests for SVG inline image renderer.
 *
 * @coversDefaultClass \Drupal\svg\SvgInlineImageRenderer
 * @group svg
 */
class SvgInlineImageRendererKernelTest extends KernelTestBase {

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
   * Tests inline SVG rendering.
   *
   * @covers ::generate
   */
  public function testGenerate() {
    $svg_inline_image = new SvgInlineImageRenderer();

    // Read svg file and get its content for comparison.
    $svg = file_get_contents(DRUPAL_ROOT . '/' . drupal_get_path('module', 'svg_test') . '/assets/icons/drupal-8.svg');
    $crawler = new Crawler();
    $crawler->addXmlContent($svg);
    $view_box = $crawler->attr('viewBox');

    // Test plain inline svg rendering.
    $expected_result = '<svg viewBox="' . $view_box . '">' . $crawler->html() .  '</svg>';
    $result = $svg_inline_image->generate('@svg_test/assets/icons/drupal-8.svg');
    $this->assertEquals($expected_result, $result, 'inline svg rendering');

    // Test inline svg rendering with custom viewBox.
    $expected_result = '<svg viewBox="1 2 3 4">' . $crawler->html() .  '</svg>';
    $result = $svg_inline_image->generate(
      '@svg_test/assets/icons/drupal-8.svg',
      [
        'viewBox' => [
          'x' => 1,
          'y' => 2,
          'width' => 3,
          'height' => 4
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'inline svg rendering with custom viewBox');

    // Test inline svg rendering with custom attributes.
    $expected_result = '<svg viewBox="' . $view_box . '" class="test-class" data-test="test-value">' . $crawler->html() .  '</svg>';
    $result = $svg_inline_image->generate(
      '@svg_test/assets/icons/drupal-8.svg',
      [
        'attributes' => [
          'class' => 'test-class',
          'data-test' => 'test-value'
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'inline svg rendering with custom attributes');

    // Test inline svg rendering with adjusted viewBox.
    $view_box_array = explode(' ', $view_box);
    $view_box_array[2] += 50;
    $view_box_array[3] += 100;

    $expected_result = '<svg viewBox="' . implode(' ', $view_box_array) . '">' . $crawler->html() .  '</svg>';
    $result = $svg_inline_image->generate(
      '@svg_test/assets/icons/drupal-8.svg',
      [
        'adjustWidth' => 50,
        'adjustHeight' => 100,
      ]
    );
    $this->assertEquals($expected_result, $result, 'inline svg rendering with adjusted viewBox');

    // Combined test of all techniques to ensure the combination is working.
    $expected_result = '<svg viewBox="1 2 53 104" data-test="test-value">' . $crawler->html() .  '</svg>';
    $result = $svg_inline_image->generate(
      '@svg_test/assets/icons/drupal-8.svg',
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
          'height' => 4
        ]
      ]
    );
    $this->assertEquals($expected_result, $result, 'inline svg rendering with all manipulation techniques');

    // Test if generator returns an empty string if svg file can not be found.
    $expected_result = '';
    $result = $svg_inline_image->generate('@svg_test/assets/icons/not-existing-icon.svg');
    $this->assertEquals($expected_result, $result, 'test behaviour for not existing files');
  }

}
