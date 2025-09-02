<?php

namespace Drupal\sync_employees_users\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Контроллер для управления списком пользователей.
 */
class UsersController extends ControllerBase {

  /**
   * Отображает список пользователей.
   *
   * @return array
   *   Render array.
   */
  public function usersList(): array {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('список пользователей'),
    ];
  }

}
