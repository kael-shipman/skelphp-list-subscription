<?php
namespace Skel;

class ListSubscription extends \Skel\DataClass {
  public function __construct(array $elements=array(), Interfaces\Template $t=null) {
    $this->addDefinedFields(array('firstName','lastName','optIn','subscriptionKey','userEmail'));
    $this->set('optIn',false,true);
  }

  protected function validateField(string $field) {
    $val = $this[$field];
    $errors = false;
    $required = array(
      'userEmail' => "You must provide a valid email address so we can send you your subscription!",
      'subscriptionKey' => "Hm... Something seems to be up on our end. We lost the subscription key! Please check back later to see if this is fixed.",
    );

    if ($field == 'userEmail') {
      if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i', $val)) {
        $this->setError($field, $required[$field], 'format');
        $errors = true;
      } else {
        $this->clearError($field, 'format');
      }
    } else {
      if (array_key_exists($field, $required) && (!$val === '' || $val === null)) {
        $this->setError($field, $required[$field], 'presence');
        $errors = true;
      } else {
        $this->clearError($field, 'presence');
      }
    }

    // should figure out how to validate subscription key

    return !$errors;
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



