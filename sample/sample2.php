<?php
chdir(dirname(__DIR__));
require './vendor/autoload.php';

use \Fluidity\FluidizerDefault;

// Create default methods
FluidizerDefault::create();

class Person extends \Fluidity\Fluid\Base
{
    protected $name, $address;

    public function __construct()
    {
        parent::__construct(new \Fluidity\Fluid\Settings());
    }
}

// ============================================================
// I assign values to the protected properties
// The class \Fluidity\Fluid\Base handles access
// ============================================================
$person = new Person();
$person->name = 'Alberto Arena';
$person->address = array('number' => '1', 'street' => 'Waterloo', 'city' => 'London');

echo 'person.name: ' . $person->name . "\n";
# ==> Alberto Arena

echo 'person.address: ' . $person->address . "\n";
# ==> 1,Waterloo,London

echo 'person: ' . $person . "\n";
# ==> Alberto Arena,1,Waterloo,London

// ============================================================
// I apply the fluid method json() to the properties
// ============================================================

echo 'person.name.json(): ' . $person->name->json() . "\n";
# ==> "Alberto Arena"

echo 'person.address.json(): ' . $person->address->json() . "\n";
# ==> {"number":"1","street":"Waterloo","city":"London"}

// ============================================================
// I apply the fluid method json() to the class itself
// ============================================================

echo 'person.json(): ' . $person->json() . "\n";
# ==> {"name":"Alberto Arena","address":{"number":"1","street":"Waterloo","city":"London"}}

// ============================================================
// I inject a method sayHello()
// ============================================================
$person->sayHello = function($obj) {
    return 'Hello, ' . $obj->name;
};

echo 'person.sayHello(): ' . $person->sayHello() . "\n";
# ==> Hello, Alberto Arena


// ============================================================
// I inject a property phone
// ============================================================
$person->phone = '07771234567';

echo 'person.phone: ' . $person->phone . "\n";
# ==> 07771234567