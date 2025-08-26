<?php

namespace Drupal\sync_simulation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for New Module routes.
 */
class SyncSimulationController extends ControllerBase {

  /**
   * Page callback for /new_module.
   *
   * @return array
   *   A render array.
   */
  public function content(): array {
    $sync_simulation = \Drupal::service('sync_simulation.sync_simulation');
    // $employees = $sync_simulation->getEmployees();
    // dump($employees);
    // $family = $sync_simulation->getFamily();
    // dump($family);
    $education = $sync_simulation->getEducation();
    dump($education);

    return [
      '#type' => 'markup',
      '#markup' => $this->t('привет друпал'),
    ];
  }

}
