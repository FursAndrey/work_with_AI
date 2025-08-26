<?php

namespace Drupal\sync_employees\Controller;

use Drupal\Core\Controller\ControllerBase;

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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('привет'),
    ];
  }

}
