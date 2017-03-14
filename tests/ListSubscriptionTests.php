<?php

class ListSubscriptionTests extends \PHPUnit\Framework\TestCase {
  public function testCanCreateNewSubscription() {
    $s = new \Skel\ListSubscription();
    $this->assertTrue($s instanceof \Skel\ListSubscription);
  }

  public function testBlankSubscriptionIsInvalid() {
    $s = new \Skel\ListSubscription();
    $this->assertTrue($s->numErrors() > 0);
  }

  public function testDefaultsToOptedOut() {
    $s = new \Skel\ListSubscription();
    $this->assertEquals(false, $s['optIn']);
  }

  public function testAcceptsNormalSubscriptionData() {
    $s = new \Skel\ListSubscription();
    $s->updateFromUserInput(array(
      'firstName' => 'John',
      'lastName' => 'Doe',
      'userEmail' => 'john.doe@example.com',
      'optIn' => 1,
      'subscriptionKey' => 'example-list',
    ));
    $this->assertEquals('John', $s['firstName']);
    $this->assertEquals('Doe', $s['lastName']);
    $this->assertEquals('john.doe@example.com', $s['userEmail']);
    $this->assertEquals(true, $s['optIn']);
    $this->assertEquals(0, $s->numErrors());
  }

  public function testAcceptsMinimumSubscription() {
    $s = new \Skel\ListSubscription();
    $s->updateFromUserInput(array(
      'userEmail' => 'john.doe@example.com',
      'subscriptionKey' => 'example-list',
    ));
    $this->assertEquals(0, $s->numErrors());
  }

  public function testValidatesEmailAddress() {
    $s = new \Skel\ListSubscription();
    $s->updateFromUserInput(array(
      'userEmail' => 'john.doe@examplecom',
      'subscriptionKey' => 'example-list',
    ));
    $this->assertEquals(1, $s->numErrors());

    $s['userEmail'] = 'example';
    $this->assertEquals(1, $s->numErrors());

    $s['userEmail'] = 'example.com';
    $this->assertEquals(1, $s->numErrors());

    $s['userEmail'] = '@example.com';
    $this->assertEquals(1, $s->numErrors());

    $s['userEmail'] = 'john.doe@example.com';
    $this->assertEquals(0, $s->numErrors());
  }

  public function testCanSetNewId() {
    $s = new \Skel\ListSubscription();
    $s->updateFromUserInput(array(
      'userEmail' => 'john.doe@example.com',
      'subscriptionKey' => 'example-list',
    ));
    $this->assertNull($s['id']);
    $s->set('id',  1);
    $this->assertEquals(1, $s['id']);
  }

  public function testThrowsErrorOnResetingId() {
    $s = new \Skel\ListSubscription();
    $s->updateFromUserInput(array(
      'userEmail' => 'john.doe@example.com',
      'subscriptionKey' => 'example-list',
    ));
    $this->assertNull($s['id']);
    $s->set('id',  1);
    $this->assertEquals(1, $s['id']);

    try {
      $s['id'] = 2;
      $this->fail('Should have thrown error on reset id');
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true, 'This is the expected behavior');
    }
  }
}

