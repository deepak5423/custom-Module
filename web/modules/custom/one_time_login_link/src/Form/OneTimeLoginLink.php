<?php

namespace Drupal\one_time_login_link\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Generates one time login link.
 */ 
class OneTimeLoginLink extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'user_otll_form';
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = [];
        $form['user_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('User Name'),
            '#placeholder' => $this->t('User Name'),
            '#required' => TRUE,
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
            '#ajax' => [
                'callback' => '::ajaxFormSubmitHandler'
              ]
        ];

        return $form;
    }

    /**
     * Using ajax generating one time login link.
     *
     * @param array $form
     *   Takes the form render array.
     * @param FormStateInterface $form_state
     *   Takes the FormState object.
     * 
     * @return object
     *   Returns ajax response.
     */
    public function ajaxFormSubmitHandler(&$form, FormStateInterface $form_state){
        $response = new AjaxResponse();
        $formField = $form_state->getValues();
        $userName = trim($formField['user_name']);

        $account = user_load_by_name($userName);
        $otll = user_pass_reset_url($account);
        $response->addCommand(new MessageCommand('One Time Login Link : ' . $otll, NULL));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(&$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(&$form, FormStateInterface $form_state) {

    }
}
