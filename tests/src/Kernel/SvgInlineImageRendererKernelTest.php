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

  protected function setUp() {
    parent::setUp();

    $this->installConfig(['svg_test']);
  }

  /**
   * @covers ::generate
   */
  public function testGenerate() {
    $svg_inline_image = new SvgInlineImageRenderer();

    $svg = file_get_contents(DRUPAL_ROOT . '/' . drupal_get_path('module', 'svg_test') . '/assets/icons/drupal-8.svg');
    $crawler = new Crawler();
    $crawler->addXmlContent($svg);

    $expected_result = '<svg viewBox="' . $crawler->attr('viewBox') . '">' . $crawler->html() .  '</svg>';

    $result = $svg_inline_image->generate('@svg_test/assets/icons/drupal-8.svg');

    $this->assertEquals($expected_result, $result, 'inline svg rendering');
  }

}
