<?php

/**
 * @file
 * Hello Module's front controller.
 */

namespace Drupal\hello_user\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloUser extends ControllerBase {

    /**
     * @method view()
     *  Fetches current user and displays greet message.
     */
    public function view() {
        // Storing the current user username.
        $current_user_name = \Drupal::currentUser()->getAccountName();
        return [
            '#type' => 'markup',
            '#title' => 'Welcome User',
            '#markup' => $this->t('Hello @user', ['@user' => $current_user_name]),
        ];
    }
}