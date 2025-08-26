<?php

namespace Drupal\sync_employees\Service;

use Drupal\profile\Entity\ProfileInterface;
use Drupal\profile\ProfileStorage;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Сервис установки модуля (создание/удаление профилей/конфигураций).
 */
class InstallFamilyInformationService {
    private $fields = [
        'field_fam_fio',
        'field_fam_birthdate',
        'field_fam_relation',
        'field_fam_relat_code',
    ];

    public function createProfile(ProfileStorage $profile_storage, int $accountId): ProfileInterface {
        $profile = $profile_storage->create([
            'type' => 'family_information',
            'uid' => $accountId,
            'status' => 1,
        ]);
        return $profile;
    }

    public function loadProfileById(int $accountId): array {
        $storage = \Drupal::entityTypeManager()->getStorage('profile');
        $profiles = $storage->loadByProperties([
            'type' => 'family_information',
            'uid' => $accountId,
        ]);
        return $profiles;
    }

    public function deleteProfiles(): void {
        $storage = \Drupal::entityTypeManager()->getStorage('profile');
        $ids = $storage->getQuery()
            ->condition('type', 'family_information')
            ->accessCheck(FALSE)
            ->execute();
        if (!empty($ids)) {
            $profiles = $storage->loadMultiple($ids);
            $storage->delete($profiles);
        }
    }

    public function deleteFieldConfig(): void {
        foreach ($this->fields as $field_name) {
            $instance = FieldConfig::loadByName('profile', 'family_information', $field_name);
            if ($instance) {
                $instance->delete();
            }
        }
    }

    public function deleteFieldStorageConfig(): void {
        foreach ($this->fields as $field_name) {
            $storage_def = FieldStorageConfig::loadByName('profile', $field_name);
            if ($storage_def) {
                $storage_def->delete();
            }
        }
    }

    public function deleteEntityFormDisplay(): void {
        $form_display_id = 'profile.family_information.default';
        $form_display_storage = \Drupal::entityTypeManager()->getStorage('entity_form_display');
        $form_display = $form_display_storage->load($form_display_id);
        if ($form_display) {
            $form_display->delete();
        }
    }

    public function deleteEntityViewDisplay(): void {
        $view_display_id = 'profile.family_information.default';
        $view_display_storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
        $view_display = $view_display_storage->load($view_display_id);
        if ($view_display) {
            $view_display->delete();
        }
    }

    public function deleteProfileType(): void {
        $profile_type_storage = \Drupal::entityTypeManager()->getStorage('profile_type');
        $profile_type = $profile_type_storage->load('family_information');
        if ($profile_type) {
            $profile_type->delete();
        }
    }
}
