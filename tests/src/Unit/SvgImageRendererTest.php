<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Unit\SvgImageRenderTest.
 */

namespace Drupal\Tests\svg\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\svg\SvgImageRenderer;

/**
 * @coversDefaultClass \Drupal\svg\SvgImageRenderer
 * @group svg
 */
class SvgImageRendererTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['svg_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

  /**
   * @covers ::generate
   */
  public function testGenerate() {
    $extension = new SvgImageRenderer();
    $output = $extension->generate('logo');

    $this->assertEquals('foo', $output);
  }

}
