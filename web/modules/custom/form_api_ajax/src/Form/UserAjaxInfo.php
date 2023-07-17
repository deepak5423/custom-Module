<?php

namespace Drupal\form_api_ajax\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Create a config ajax form for collecting user data.
 */
class UserAjaxInfo extends ConfigFormBase {
    
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'user_data_ajax_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'customajaxform.admin_settings',
        ];
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
            '#options' => [
                $this->t('Male'),
                $this->t('Female'),
                $this->t('Others'),
            ],
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
     * Using ajax showing the errors.
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
        $values = $this->validate($formField);
        if ($values['flag']) {
            $response->addCommand(new MessageCommand('Form submit successfully', NULL));
            $response->addCommand(new InvokeCommand('#edit-full-name','val',['']));
            $response->addCommand(new InvokeCommand('#edit-phone-number','val',['']));
            $response->addCommand(new InvokeCommand('#edit-email-id','val',['']));
        }
        else {
            $response->addCommand(new MessageCommand($values['error_message'], NULL, ['type' => 'error']));
        }
        return $response;
    }


    /**
     * To validate user data submitted by the form.
     *
     * @param array $formField
     *    It stores all fields of the form.
     * @return array
     *    It returns error messages and flag values.
     */
    public function validate(array $formField) {
        $error_message = '';
        $fullName = trim($formField['full_name']);
        $phoneNumber = trim($formField['phone_number']);
        $emailId = trim($formField['email_id']);
        $flag = TRUE;

        // Checks if name is in correct format or not.
        if (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {
            $error_message = $error_message . 'Enter valid Name<br>';
            $flag = FALSE;
        }

        // Checks if phoneNumber is in correct format or not and Indian number.
        if (!preg_match("/^[9|8|7|6][0-9]{9}+$/", $phoneNumber)) {
            $error_message = $error_message . 'Enter Valid Phone Number<br>';
            $flag = FALSE;
        }

        // Checks if emailId is in correct format or not.
        if (!preg_match("/^[a-zA-Z0-9.]+@[a-z]+.[a-z]{2,4}$/", $emailId)) {
            $error_message = $error_message . 'Enter Valid Email<br>';
            $flag = FALSE;
        }
        
        // Checks if emailId domain is @gmail.com or @outlook.com or not.
        else if (!preg_match("/^[a-zA-z0-9.]+@gmail.com|@outlook.com$/", $emailId)) {
            $error_message = $error_message . 'Only gmail and outlook domail is allowed<br>';
            $flag = FALSE;
        }
        return ['flag' => $flag, 'error_message' => $error_message];
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
