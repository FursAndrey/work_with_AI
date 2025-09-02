<?php

namespace Drupal\sync_employees\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\profile\ProfileStorageInterface;
use Drupal\user\UserInterface;
use Drupal\sync_simulation\Service\SyncSimulation;

/**
 * Сервис синхронизации данных сотрудников с распределением по структуре БД.
 */
class SyncService {

  /**
   * DI: менеджер сущностей и источник данных-симулятор.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $etm,
    private readonly SyncSimulation $sim,
  ) {}

  /**
   * Полная синхронизация всех данных с распределением по профилям.
   *
   * Анализирует исходные данные из SyncSimulation и распределяет их
   * по соответствующим профилям пользователей согласно структуре БД.
   */
  public function syncAllData(): array {
    $result = [
      'users_processed' => 0,
      'profiles_created' => 0,
      'profiles_updated' => 0,
      'errors' => [],
    ];

    // Получаем все исходные данные
    $employees = $this->sim->getEmployees();
    $family = $this->sim->getFamily();
    $education = $this->sim->getEducation();
    $medicine = $this->sim->getMedicine();
    $violations = $this->sim->getViolation();

    // Группируем данные по fiz_code для эффективной обработки
    $familyByFiz = $this->groupByFizCode($family);
    $educationByFiz = $this->groupByFizCode($education);
    $medicineByFiz = $this->groupByFizCode($medicine);
    $violationsByFiz = $this->groupByFizCode($violations);

    // Обрабатываем каждого сотрудника
    foreach ($employees as $employee) {
      try {
        $fizCode = (string) $employee['fiz_code'];
        
        // Создаем/находим пользователя
        $user = $this->findOrCreateUser($fizCode);
        if (!$user) {
          $result['errors'][] = "Не удалось создать пользователя для fiz_code: {$fizCode}";
          continue;
        }

        // Синхронизируем основные профили
        $this->syncPersonalProfile($user, $employee, $result);
        $this->syncContactProfile($user, $employee, $result);
        $this->syncWorkProfile($user, $employee, $result);

        // Синхронизируем множественные профили
        if (isset($familyByFiz[$fizCode])) {
          $this->syncFamilyProfiles($user, $familyByFiz[$fizCode], $result);
        }

        if (isset($educationByFiz[$fizCode])) {
          $this->syncEducationProfiles($user, $educationByFiz[$fizCode], $result);
        }

        if (isset($medicineByFiz[$fizCode])) {
          $this->syncMedicineProfiles($user, $medicineByFiz[$fizCode], $result);
        }

        if (isset($violationsByFiz[$fizCode])) {
          $this->syncViolationProfiles($user, $violationsByFiz[$fizCode], $result);
        }

        $result['users_processed']++;

      } catch (\Exception $e) {
        $result['errors'][] = "Ошибка обработки сотрудника {$fizCode}: " . $e->getMessage();
      }
    }

    return $result;
  }

  /**
   * Синхронизация профиля личной информации.
   */
  private function syncPersonalProfile(UserInterface $user, array $employee, array &$result): void {
    $this->upsertSingleProfile($user, 'personal_information', [
      'field_personal_fio' => $employee['FIO'],
      'field_personal_birthdate' => $this->formatDate($employee['birth_date']),
      'field_personal_fiz_code' => $employee['fiz_code'],
      'field_personal_phone' => $employee['phone'],
    ], $result);
  }

  /**
   * Синхронизация профиля контактной информации.
   */
  private function syncContactProfile(UserInterface $user, array $employee, array &$result): void {
    $this->upsertSingleProfile($user, 'contact_information', [
      'field_contact_email' => $employee['email'],
      'field_contact_address' => $employee['address'],
      'field_contact_inner_phone' => $employee['inner_phone'],
    ], $result);
  }

  /**
   * Синхронизация профиля рабочей информации.
   */
  private function syncWorkProfile(UserInterface $user, array $employee, array &$result): void {
    $this->upsertSingleProfile($user, 'work_information', [
      'field_work_unit' => $employee['unit'],
      'field_work_position' => $employee['position'],
      'field_work_position_type' => $employee['position_type'],
      'field_work_enterdate' => $this->formatDate($employee['enter_date']),
      'field_work_fireddate' => $this->formatDate($employee['fired_date']),
      'field_work_number' => (int) ($employee['number']),
    ], $result);
  }

  /**
   * Синхронизация семейных профилей.
   * Все родственники сохраняются в одном профиле с множественными значениями полей.
   */
  private function syncFamilyProfiles(UserInterface $user, array $familyData, array &$result): void {
    $storage = $this->profileStorage();
    
    // Загружаем существующие профили (должен быть только один)
    $existing = $storage->loadByUser($user, 'family_information');
    if (is_null($existing)) {
        $existing = [];
    }
    $profile = $existing ?? null;
    
    // Если профиля нет, создаем новый
    if (!$profile) {
      $profile = $storage->create(['type' => 'family_information', 'uid' => $user->id()]);
      $isNew = true;
    } else {
      $isNew = false;
    }

    // Подготавливаем массивы значений для множественных полей
    $fioValues = [];
    $relationValues = [];
    $relationCodeValues = [];
    $birthdateValues = [];

    foreach ($familyData as $item) {
      $fioValues[] = ['value' => (string) ($item['fio'])];
      $relationValues[] = ['value' => (string) ($item['relation'])];
      $relationCodeValues[] = ['value' => (int) ($item['relation_code'])];
      $birthdateValues[] = ['value' => trim((string) ($item['birth_date']))];
    }

    // Устанавливаем множественные значения для всех полей
    $profile->set('field_fam_fio', $fioValues);
    $profile->set('field_fam_relation', $relationValues);
    $profile->set('field_fam_relat_code', $relationCodeValues);
    $profile->set('field_fam_birthdate', $birthdateValues);
    
    $profile->save();

    if ($isNew) {
      $result['profiles_created']++;
    } else {
      $result['profiles_updated']++;
    }
  }

  /**
   * Синхронизация профилей образования.
   */
  private function syncEducationProfiles(UserInterface $user, array $educationData, array &$result): void {
    $storage = $this->profileStorage();
    
    // Загружаем существующие профили (должен быть только один)
    $existing = $storage->loadByUser($user, 'education_information');
    if (is_null($existing)) {
        $existing = [];
    }
    $profile = $existing ?? null;
    
    // Если профиля нет, создаем новый
    if (!$profile) {
      $profile = $storage->create(['type' => 'education_information', 'uid' => $user->id()]);
      $isNew = true;
    } else {
      $isNew = false;
    }

    // Подготавливаем массивы значений для множественных полей
    $type = [];
    $specialty = [];
    $institution = [];
    $startDate = [];
    $endDate = [];
    
    foreach ($educationData as $item) {
      $type[] = ['value' => (string) ($item['education_type'])];
      $specialty[] = ['value' => (string) ($item['specialty'])];
      $institution[] = ['value' => (string) ($item['institution'])];
      $startDate[] = ['value' => trim((string) ($item['start_date']))];
      $endDate[] = ['value' => trim((string) ($item['end_date']))];
    }
    
    // Устанавливаем множественные значения для всех полей
    $profile->set('field_edu_education_type', $type);
    $profile->set('field_edu_specialty', $specialty);
    $profile->set('field_edu_institution', $institution);
    $profile->set('field_edu_start_date', $startDate);
    $profile->set('field_edu_end_date', $endDate);
    
    $profile->save();

    if ($isNew) {
      $result['profiles_created']++;
    } else {
      $result['profiles_updated']++;
    }
  }

  /**
   * Синхронизация медицинских профилей (wipe-стратегия).
   */
  private function syncMedicineProfiles(UserInterface $user, array $medicineData, array &$result): void {
    $storage = $this->profileStorage();
    
    // Загружаем существующие профили
    $existing = $storage->loadByUser($user, 'med_information');
    if (is_null($existing)) {
      $existing = [];
    }
    $profile = $existing ?? null;
    
    // Если профиля нет, создаем новый
    if (!$profile) {
      $profile = $storage->create(['type' => 'med_information', 'uid' => $user->id()]);
      $isNew = true;
    } else {
      $isNew = false;
    }

    // Подготавливаем массивы значений для множественных полей
    $visitDate = [];
    $amount = [];
    
    foreach ($medicineData as $item) {
      $visitDate[] = ['value' => $item['visit_date']];
      $amount[] = ['value' => $item['amount']];
    }

    // Устанавливаем множественные значения для всех полей
    $profile->set('field_med_visit_date', $visitDate);
    $profile->set('field_med_amount', $amount);
    
    $profile->save();

    if ($isNew) {
      $result['profiles_created']++;
    } else {
      $result['profiles_updated']++;
    }
  }

  /**
   * Синхронизация профилей нарушений (wipe-стратегия).
   */
  private function syncViolationProfiles(UserInterface $user, array $violationData, array &$result): void {
    $storage = $this->profileStorage();
    
    // Загружаем существующие профили
    $existing = $storage->loadByUser($user, 'violation_information');
    if (is_null($existing)) {
        $existing = [];
    }
    $profile = $existing ?? null;
    
    // Если профиля нет, создаем новый
    if (!$profile) {
      $profile = $storage->create(['type' => 'violation_information', 'uid' => $user->id()]);
      $isNew = true;
    } else {
      $isNew = false;
    }

    // Подготавливаем массивы значений для множественных полей
    $date = [];
    $type = [];
    $code = [];
    
    foreach ($violationData as $item) {
      $date[] = ['value' => (string) ($item['violation_date'])];
      $type[] = ['value' => $item['violation_type']];
      $code[] = ['value' => $item['violation_code']];
    }
    
    // Устанавливаем множественные значения для всех полей
    $profile->set('field_violation_date', $date);
    $profile->set('field_violation_type', $type);
    $profile->set('field_violation_code', $code);
    
    $profile->save();

    if ($isNew) {
      $result['profiles_created']++;
    } else {
      $result['profiles_updated']++;
    }
  }

  // -------- Вспомогательные методы -------- //

  /**
   * Найти или создать пользователя по fiz_code.
   */
  private function findOrCreateUser(string $fizCode): ?UserInterface {
    // Поиск через профиль personal_information
    $profileIds = $this->profileStorage()->getQuery()
      ->condition('type', 'personal_information')
      ->condition('field_personal_fiz_code', $fizCode)
      ->accessCheck(FALSE)
      ->execute();

    if ($profileIds) {
      $profiles = $this->profileStorage()->loadMultiple($profileIds);
      $profile = reset($profiles);
      if ($profile) {
        return $this->userStorage()->load($profile->getOwnerId());
      }
    }

    // Создаем нового пользователя
    $username = 'fiz_' . $fizCode;
    $user = $this->userStorage()->create([
      'name' => $username,
      'mail' => NULL,
      'status' => 1,
    ]);
    $user->enforceIsNew();
    $user->save();

    return $user;
  }

  /**
   * Создать или обновить одиночный профиль.
   */
  private function upsertSingleProfile(UserInterface $user, string $bundle, array $values, array &$result): void {
    $storage = $this->profileStorage();
    $profile = $storage->loadByUser($user, $bundle);
    $isNew = !$profile;
    $profile = $profile ?? $storage->create(['type' => $bundle, 'uid' => $user->id()]);

    foreach ($values as $field => $value) {
      if ($value === null || $value === '') {
        $profile->set($field, NULL);
      } else {
        $profile->set($field, $value);
      }
    }
    
    $profile->save();
    
    if ($isNew) {
      $result['profiles_created']++;
    } else {
      $result['profiles_updated']++;
    }
  }

  /**
   * Группировка массива данных по fiz_code.
   */
  private function groupByFizCode(array $data): array {
    $grouped = [];
    foreach ($data as $item) {
      $fizCode = (string) ($item['fiz_code'] ?? '');
      if ($fizCode !== '') {
        $grouped[$fizCode][] = $item;
      }
    }
    return $grouped;
  }

  /**
   * Проверка корректности даты в формате YYYY-MM-DD.
   */
  private function isValidDate(string $date): bool {
    return !empty($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
  }

  /**
   * Форматирование даты для сохранения в БД.
   */
  private function formatDate(?string $date): ?array {
    if (!$date || !$this->isValidDate($date)) {
      return null;
    }
    return ['value' => $date];
  }

  /**
   * Получить storage пользователей.
   */
  private function userStorage() {
    return $this->etm->getStorage('user');
  }

  /**
   * Получить storage профилей.
   */
  private function profileStorage(): ProfileStorageInterface {
    return $this->etm->getStorage('profile');
  }
}
