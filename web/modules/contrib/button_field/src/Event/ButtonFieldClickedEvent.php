<?php

namespace Drupal\button_field\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Event that is fired when a user clicks a button field on an entity.
 *
 * @see rules_user_login()
 */
class ButtonFieldClickedEvent extends GenericEvent {

  const EVENT_NAME = 'button_field_clicked';

}
