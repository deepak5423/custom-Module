<?php

namespace Drupal\form_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Create a config form for collecting user data.
 */
class UserInfo extends ConfigFormBase {
    
    const FORM_VALUES = 'form_values:values';

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'user_data_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return 'customform.admin_settings';
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = [];
        $form['full_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Full Name'),
            '#placeholder' => $this->t('Full Name'),
            '#required' => TRUE,
        ];

        $form['phone_number'] = [
            '#type' => 'number',
            '#title' => $this->t('Phone Number'),
            '#placeholder' => $this->t('Phone Number'),
            '#maxlength' => 10,
            '#pattern' => '^+91(9|8|7|6|5][0-9){9}+$',
            '#required' => TRUE,
        ];

        $form['email_id'] = [
            '#type' => 'email',
            '#title' => $this->t('Email Id'),
            '#placeholder' => $this->t('Email Id'),
            '#required' => TRUE,
        ];

        $form['gender'] = [
            '#type' => 'radios',
            '#title' => $this->t('Gender'),
            '#placeholder' => $this->t('Gender'),
            '#options' => array(t('Male'), t('Female'), t('others')),
            '#required' => TRUE,
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(&$form, FormStateInterface $form_state) {
        $formField = $form_state->getValues();
        $fullName = trim($formField['full_name']);
        $phoneNumber = trim($formField['phone_number']);
        $emailId = trim($formField['email_id']);

        // Checks if name is in correct format or not.
        if (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {
            $form_state->setErrorByName('full_name', $this->t('Enter valid Name'));
        }

        // Checks if phoneNumber is in correct format or not and Indian number.
        if (!preg_match("/^[+91][9|8|7|6][0-9]{9}+$/", $phoneNumber)) {
            $form_state->setErrorByName('phone_number', $this->t('Enter valid Phone Number'));
        }

        // Checks if emailId is in correct format or not.
        if (!preg_match("/^[a-zA-Z0-9.]+@[a-z]+.[a-z]{2,4}$/", $emailId)) {
            $form_state->setErrorByName('email_id', $this->t('Enter Valid Email'));

        }

        // Checks if emailId domain is @gmail.com or @outlook.com or not.
        else if (!preg_match("/^[a-zA-z0-9.]+@gmail.com|@outlook.com$/", $emailId)) {
            $form_state->setErrorByName('email_id', $this->t('Only gmail and outlook domail is allowed'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(&$form, FormStateInterface $form_state) {
        $submitted_value = $form_state->cleanValues()->getValues();
        \Drupal::state()->set(self::FORM_VALUES, $submitted_value);

        $messenger = \Drupal::service('messenger');
        // After successful submission printing thankyou sms.
        $messenger->addMessage($this->t('Thankyou for filling this form'));
    }
}