<?php

namespace Drupal\sync_employees\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\sync_employees\Service\SyncService;
use Drupal\sync_simulation\Service\SyncSimulation;

/**
 * Контроллер для страницы /sync_employees.
 */
class SyncEmployeesController extends ControllerBase {

  /**
   * Выводит простой текст.
   *
   * @return array
   *   Render array.
   */
  public function content(): array {
    $sync_sim = new SyncSimulation();
    $syncService = new SyncService($this->entityTypeManager(), $sync_sim);
    $syncService->syncAllData();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Синхронизация завершена'),
    ];
  }

}
