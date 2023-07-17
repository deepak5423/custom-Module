<?php

namespace Drupal\hello_user\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

class HelloUser extends ControllerBase {
    public function view() {
        $current_user_name = \Drupal::currentUser()->getAccountName();
        return [
            '#type' => 'markup',
            '#title' => 'Welcome User',
            '#markup' => $this->t('Hello @user', ['@user' => $current_user_name]),
        ];
    }
}