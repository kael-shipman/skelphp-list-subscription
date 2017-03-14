<?php
namespace Skel;

class ListSubscription extends \Skel\DataClass {
  public function __construct(array $elements=array(), Interfaces\Template $t=null) {
    $this->addDefinedFields(array('firstName','lastName','optIn','subscriptionKey','userEmail'));
    $this->set('optIn',false,true);
  }

  protected function validateField(string $field) {
    $val = $this[$field];
    $error = null;
    $required = array(
      'userEmail' => "You must provide a valid email address so we can send you your subscription!",
      'subscriptionKey' => "Hm... Something seems to be up on our end. We lost the subscription key! Please check back later to see if this is fixed.",
    );

    if ($field == 'userEmail') {
      if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i', $val)) $error = $required['userEmail'];
    } else {
      if (!$val === '' || $val === null) $error = $required[$field];
    }

    // should figure out how to validate subscription key

    if ($error) {
      $this->setError($field, $error);
      return false;
    } else {
      $this->clearError($field);
      return true;
    }
  }

  public function validateObject(\Skel\Interfaces\Db $db) {
    return true;
  }




  protected function convertDataToField(string $field, $dataVal) {
    if ($field == 'optIn') return (bool)$dataVal;
    else return parent::convertDataToField($field, $dataVal);
  }

  protected function typecheckAndConvertInput(string $field, $val) {
    if ($val === null) return $val;
    if ($field == 'optIn') {
      if (!is_bool($val)) throw new \InvalidArgumentException("Field `$field` must be a boolean value!");
      return (int)$val;
    } elseif (array_search($val, array('firstName','lastName','subscriptionKey','userEmail')) === false) {
      if (!is_string($val)) throw new \InvalidArgumentException("Field `$field` must be a string value!");
      return (string)$val;
    }
    return parent::typecheckAndConvertInput($field, $val);
  }
}



