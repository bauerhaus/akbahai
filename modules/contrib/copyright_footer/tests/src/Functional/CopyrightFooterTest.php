<?php

namespace Drupal\Tests\copyright_footer\Functional;

use Drupal\block\Entity\Block;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Test case for a Copyright Footer block.
 *
 * @group Copyright Footer
 */
class CopyrightFooterTest extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * The profile to install as a basis for testing.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'copyright_footer',
  ];

  /**
   * Permissions.
   *
   * @var array
   */
  protected $perms = [
    'access content',
    'administer blocks',
  ];

  /**
   * A node.
   *
   * @var \Drupal\node\Entity\Node
   */
  protected $node;

  /**
   * Set up test.
   */
  protected function setUp(): void {
    parent::setUp();

    $user = $this->drupalCreateUser($this->perms);
    $this->drupalLogin($user);

    // Create a dummy node.
    $this->createContentType(['type' => 'page']);
    $this->node = $this->drupalCreateNode();
  }

  /**
   * Test the copyright footer black.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   * @throws \Behat\Mink\Exception\ExpectationException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function testCopyrightFooter(): void {

    $year_origin = '2018';
    $year_to_date = '2062';
    $year = (new \DateTime())->format('Y');
    $organization_name = $this->randomString();
    $organization_url = 'https://www.drupal.org/';
    $version = '8.x-1.x';
    $version_url = 'https://www.drupal.org/project/copyright_footer';
    $node_url = "/node/{$this->node->id()}";

    // Place a Copyright footer block - All blank.
    $block = $this->placeCopyrightFooterBlock();
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Copyright © @year', [
      '@year' => $year,
    ]));
    $block->delete();

    // Place a Copyright footer block - Organization name only.
    $block = $this->placeCopyrightFooterBlock([
      'organization_name' => $organization_name,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains("© $year $organization_name");
    $block->delete();

    // Place a Copyright footer block - Organization w/ URL.
    $block = $this->placeCopyrightFooterBlock([
      'organization_name' => $organization_name,
      'organization_url' => $organization_url,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains("© $year $organization_name");

    $this->clickLink($organization_name);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Why Drupal?');
    $block->delete();

    // Place a Copyright footer block - Year origin only.
    $block = $this->placeCopyrightFooterBlock([
      'year_origin' => $year_origin,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Copyright © @year_origin-@year', [
      '@year_origin' => $year_origin,
      '@year' => $year,
    ]));
    $block->delete();

    // Place a Copyright footer block - Year origin and year to date.
    $block = $this->placeCopyrightFooterBlock([
      'year_origin' => $year_origin,
      'year_to_date' => $year_to_date,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Copyright © @year_origin-@year_to_date', [
      '@year_origin' => $year_origin,
      '@year_to_date' => $year_to_date,
    ]));
    $block->delete();

    // Place a Copyright footer block - Version only.
    $block = $this->placeCopyrightFooterBlock([
      'version' => $version,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Copyright © @year ver.@version', [
      '@year' => $year,
      '@version' => $version,
    ]));
    $block->delete();

    // Place a Copyright footer block - Version w/ URL.
    $block = $this->placeCopyrightFooterBlock([
      'version' => $version,
      'version_url' => $version_url,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Copyright © @year ver.@version', [
      '@year' => $year,
      '@version' => $version,
    ]));
    $this->clickLink($version);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Issues for Copyright Footer');
    $block->delete();

    // Place a Copyright footer block - Full parameters.
    $block = $this->placeCopyrightFooterBlock([
      'organization_name' => $organization_name,
      'organization_url' => $organization_url,
      'year_origin' => $year_origin,
      '@year_to_date' => $year_to_date,
      'version' => $version,
      'version_url' => $version_url,
    ]);
    $this->drupalGet($node_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains("© $year_origin-$year $organization_name ver.$version");

    $this->clickLink($organization_name);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Why Drupal?');

    $this->drupalGet($node_url);
    $this->clickLink($version);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Issues for Copyright Footer');
    $block->delete();
  }

  /**
   * Create a Copyright Footer block.
   *
   * @return \Drupal\block\Entity\Block
   *   The Copyright Footer block object.
   */
  private function placeCopyrightFooterBlock($settings = []): Block {
    return $this->drupalPlaceBlock('copyright_footer', [
      'organization_name' => $settings['organization_name'] ?? '',
      'organization_url' => $settings['organization_url'] ?? '',
      'year_origin' => $settings['year_origin'] ?? '',
      'year_to_date' => $settings['year_to_date'] ?? '',
      'version' => $settings['version'] ?? '',
      'version_url' => $settings['version_url'] ?? '',
    ]);
  }

}
