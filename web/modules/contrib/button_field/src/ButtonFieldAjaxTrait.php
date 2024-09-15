<?php

namespace Drupal\button_field;

use Drupal\button_field\Event\ButtonFieldClickedEvent;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides helper methods for handling AJAX.
 */
trait ButtonFieldAjaxTrait {

  /**
   * Handles button field click.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public static function handleClick(array $form, FormStateInterface $form_state) {
    // Gets the field and entity from the triggering element.
    $field_name = $form_state->getTriggeringElement()['#field_name'];
    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $form_state->getTriggeringElement()['#entity'];
    $field = $entity->getFieldDefinition($field_name);

    // Dispatches the rules event.
    $event = new ButtonFieldClickedEvent($entity, [
      'field' => $field,
      'entity' => $entity,
    ]);
    \Drupal::service('event_dispatcher')->dispatch($event, ButtonFieldClickedEvent::EVENT_NAME);

    // Determines the response.
    $response = new AjaxResponse();
    $request = \Drupal::requestStack()->getCurrentRequest();
    $rules_path = $request->attributes->get('_rules_redirect_action_url');
    if (!empty($rules_path)) {
      // Remove the redirect so that we don't return an HTML response, and add
      // an ajax redirect command.
      $request->attributes->remove('_rules_redirect_action_url');
      $response->addCommand(new RedirectCommand($rules_path));
    }

    return $response;
  }

}
