<?php

/**
 * @file
 * Contains \Drupal\Tests\svg\Unit\MailHandlerTest.
 */

namespace Drupal\Tests\svg\Unit;

use Drupal\contact\MailHandler;
use Drupal\contact\MessageInterface;
use Drupal\Core\Language\Language;
use Drupal\Core\Session\AccountInterface;
use Drupal\Tests\UnitTestCase;

/**
 * @group svg
 */
class SvgTest extends UnitTestCase {

  /**
   * Logger service.
   *
   * @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->logger = $this->getMock('\Psr\Log\LoggerInterface');
  }

}
