<?php

namespace Drupal\bs_tour\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure BsTour settings for this site.
 */
class BsTourAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bs_tour_form_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bs_tour.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('bs_tour.settings');

    $form['bs_tour_form_intro'] = array(
      '#markup' => '<p>Quick and easy way to build your <b>Tours</b> with <b>Bootstrap</b> Popovers.</p>',
    );

    $form['general'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('General Configurations'),
      '#open' => TRUE,
    );

    $form['general']['bs_tour_form_keyboard'] = array(
      '#type' => 'select',
      '#title' => $this->t('Keyboard'),
      '#options' => array(
        TRUE => $this->t('True'),
        FALSE => $this->t('False'),
      ),
      '#multiple' => FALSE,
      '#default_value' => $config->get('keyboard'),
      '#description' => $this->t('This option set the left and right arrow navigation.'),
    );

    $form['general']['bs_tour_form_debug'] = array(
      '#type' => 'select',
      '#title' => $this->t('Debug'),
      '#options' => array(
        TRUE => $this->t('True'),
        FALSE => $this->t('False'),
      ),
      '#multiple' => FALSE,
      '#default_value' => $config->get('debug'),
      '#description' => $this->t('Set this option to true to have some useful information printed in the console.'),
    );

    $form['steps'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Tour Tips'),
      '#open' => TRUE,
    );

    $form['steps']['bs_tour_number_of_tips'] = array(
      '#type' => 'number',
      '#title' => $this->t('Number of tips'),
      '#min' => 0,
      '#step' => 1,
      '#description' => $this->t('Number of tips to be used in <b>Bootstrap tour.</b>'
        . '<br />Please save the form after changing this number to update the tips settings.'),
      '#required' => TRUE,
      '#default_value' => $config->get('number_of_tips'),
    );

    $number_of_tips = $config->get('number_of_tips');
    if ($number_of_tips) {
      $form['bs_tour_groups_settings'] = array(
        '#type' => 'vertical_tabs'
      );

      for ($step = 1; $step <= $number_of_tips; $step++) {

        $form['bs_tour_steps_' . $step . '_wrapper'] = array(
          '#type' => 'details',
          '#title' => t('Tip @num settings', array('@num' => $step)),
          '#group' => 'bs_tour_groups_settings'
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_title'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Title'),
          '#default_value' => $config->get('steps')[$step - 1]['title'],
          '#description' => $this->t('Tip title.')
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_content'] = array(
          '#type' => 'text_format',
          '#title' => $this->t('Content'),
          '#default_value' => $config->get('steps')[$step - 1]['content'],
          '#rows' => 5,
          '#description' => $this->t('Tip content.')
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_element'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('jQuery selector'),
          '#default_value' => $config->get('steps')[$step - 1]['element'],
          '#description' => $this->t('Enter jQuery selector for an HTML element on which the tip should be shown.')
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_placement'] = array(
          '#type' => 'select',
          '#title' => $this->t('Placement'),
          '#options' => array(
            'auto' => $this->t('Auto'),
            'top' => $this->t('Top'),
            'bottom' => $this->t('Bottom'),
            'left' => $this->t('Left'),
            'right' => $this->t('Right'),
          ),
          '#multiple' => FALSE,
          '#default_value' => $config->get('steps')[$step - 1]['placement'],
          '#description' => $this->t('How to position the tip.'
            . 'Possible choices: <b>top</b>, <b>bottom</b>, <b>left</b>, <b>right</b> and <b>auto</b>.'
            . '<br />When <b>auto</b> is specified, it will dynamically reorient the tip.'),
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_backdrop'] = array(
          '#type' => 'select',
          '#title' => $this->t('Highlight'),
          '#options' => array(
            TRUE => $this->t('True'),
            FALSE => $this->t('False'),
          ),
          '#multiple' => FALSE,
          '#default_value' => $config->get('steps')[$step - 1]['backdrop'],
          '#description' => $this->t('Show a dark background behind the popover and its element, highlighting the current tip.'),
        );
        $form['bs_tour_steps_' . $step . '_wrapper']['bs_tour_step_' . $step . '_backdrop_padding'] = array(
          '#type' => 'number',
          '#title' => $this->t('Highlight padding'),
          '#min' => 0,
          '#step' => 1,
          '#default_value' => $config->get('steps')[$step - 1]['backdropPadding'],
          '#states' => array(
            'visible' => array(
              'select[name="bs_tour_step_' . $step . '_backdrop"]' => array('value' => 1),
            ),
          ),
          '#description' => $this->t('Add padding to the dark background that highlights the tip element.'),
        );
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bs_tour.settings');
    $config
      ->set('debug', $form_state->getValue('bs_tour_form_debug'))
      ->set('keyboard', $form_state->getValue('bs_tour_form_keyboard'))
      ->set('number_of_tips', $form_state->getValue('bs_tour_number_of_tips'))
      ->save();

    $number_of_tips = $form_state->getValue('bs_tour_number_of_tips');
    if ($number_of_tips) {
      $tips = array();

      for ($step = 1; $step <= $number_of_tips; $step++) {
        $tips[] = array(
          'title' => $form_state->getValue('bs_tour_step_' . $step . '_title'),
          'content' => $form_state->getValue('bs_tour_step_' . $step . '_content')['value'],
          'element' => $form_state->getValue('bs_tour_step_' . $step . '_element'),
          'placement' => $form_state->getValue('bs_tour_step_' . $step . '_placement'),
          'backdrop' => $form_state->getValue('bs_tour_step_' . $step . '_backdrop'),
          'backdropPadding' => $form_state->getValue('bs_tour_step_' . $step . '_backdrop_padding'),
        );
      }

      $config
        ->set('steps', $tips)
        ->save();
    }

    parent::submitForm($form, $form_state);
  }

}
