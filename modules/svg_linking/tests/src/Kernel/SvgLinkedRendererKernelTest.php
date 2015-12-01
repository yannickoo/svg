<?php

/**
 * @file
 * Contains \Drupal\Tests\svg_linking\Kernel\SvgLinkedRendererKernelTest.
 */

namespace Drupal\Tests\svg_linking\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\DomCrawler\Crawler;
use Drupal\svg_linking\SvgLinkedRenderer;

/**
 * Kernel tests for SVG linked renderer.
 *
 * @coversDefaultClass \Drupal\svg_linking\SvgLinkedRenderer
 * @group svg
 */
class SvgLinkedRendererKernelTest extends KernelTestBase {

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
    $svg_image = new SvgLinkedRenderer();
    $svg_test_path = drupal_get_path('module', 'svg_test') . '/assets/stacks/sprite-stack.svg';

    // Read svg file and get its viewBox for comparison.
    $svg = file_get_contents(DRUPAL_ROOT . '/' . $svg_test_path);
    $crawler = new Crawler();
    $crawler->addXmlContent($svg);
    $item = $crawler->filter('#drupal-8');
    $view_box = $item->attr('viewBox');

    $expected_result = '<svg viewBox="' . $view_box . '"><use xlink:href="' . base_path() . $svg_test_path . '#drupal-8"></use></svg>';

    $this->assertEquals($expected_result, $svg_image->generate('stack#drupal-8'), 'link drupal-8 icon from svg stack');
  }

}
