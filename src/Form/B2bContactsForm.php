<?php

namespace Drupal\b2b_contacts\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the b2b_contacts entity edit forms.
 */
class B2bContactsForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['uid']['#access'] = FALSE;
    $form['gid']['#access'] = FALSE;
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New b2b_contacts %label has been created.', $message_arguments));
      $this->logger('b2b_contacts')->notice('Created new b2b_contacts %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The b2b_contacts %label has been updated.', $message_arguments));
      $this->logger('b2b_contacts')->notice('Updated new b2b_contacts %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.b2b_contacts.canonical', ['b2b_contacts' => $entity->id()]);
  }

}
