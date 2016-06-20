<?php

namespace Drupal\Tests\datetime_tweaks\Functional;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the datetime_tweaks functionality.
 *
 * @group datetime_tweaks
 */
class DateTimeItemTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['datetime', 'datetime_tweaks', 'entity_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create a field with settings to validate.
    $field_storage = FieldStorageConfig::create(array(
      'field_name' => 'field_datetime',
      'type' => 'datetime',
      'entity_type' => 'entity_test',
      'settings' => ['datetime_type' => 'date'],
    ));
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'entity_test',
      'settings' => [
        'default_value' => 'blank',
      ],
    ]);
    // Setup widget.
    // Try loading the entity from configuration.
    if (!$entity_form_display = EntityFormDisplay::load('entity_test.entity_test.default')) {
      $entity_form_display = EntityFormDisplay::create([
        'targetEntityType' => 'entity_test',
        'bundle' => 'entity_test',
        'mode' => 'default',
        'status' => TRUE,
      ]);
    }
    $entity_form_display
      ->setComponent('field_datetime', array(
        'type' => 'datetime_default',
        'weight' => 20,
      ))
      ->save();
    $field->save();
    $this->drupalLogin($this->drupalCreateUser(['administer entity_test content']));
  }

  /**
   * Tests submitting dates in human format.
   */
  public function testSubmittingHumanFormat() {
    $this->drupalGet('entity_test/add');
    $date = new \DateTime();
    $this->submitForm([
      'name[0][value]' => 'Test human date',
      'user_id[0][target_id]' => $this->loggedInUser->label() . ' (' . $this->loggedInUser->id() . ')',
      'field_datetime[0][value][date]' => $date->format('d/m/Y'),
    ], t('Save'));
    $entities = \Drupal::entityTypeManager()
      ->getStorage('entity_test')
      ->loadByProperties(array(
        'name' => 'Test human date',
      ));
    $this->assertEquals(1, count($entities), 'Entity was saved');
    $this->assertEquals($date->format('d/m/Y'), reset($entities)->field_datetime->date->format('d/m/Y'));
  }

}
