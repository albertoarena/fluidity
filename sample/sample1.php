<?php
chdir(dirname(__DIR__));
require './vendor/autoload.php';

use \Fluidity\FluidizerDefault;

// Create default methods
FluidizerDefault::create();

// Test class
class Person extends \Fluidity\Fluid\Base
{
    protected $person, $surname, $address;

    public function __construct()
    {
        parent::__construct(new \Fluidity\Fluid\Settings());
    }

    public function label1()
    {
        // Original properties
        return $this->name . ' ' . $this->surname . ', phone ' . $this->phone;
    }

    public function label2()
    {
        // Magic properties
        return $this['name'] . ' ' . $this['surname'] . ', phone ' . $this['phone'];
    }
}

$person = new Person();
echo 'name.json: ' . $person->json() . "\n";

// Set properties
$person->name = 'Alberto';
$person->surname = 'Alberto';
$person->age = 41;
$person->address = array('street' => '55 Farringdon Road', 'city' => 'London', 'zip' => 'EC1 000');
$person->dateBirth = new DateTime();

echo 'name.name: ' . $person->name . "\n";
echo 'name.surname: ' . $person->surname . "\n";
echo 'name.age: ' . $person->age . "\n";
echo 'name.address: ' . $person->address . "\n";
echo 'name.address[street]: ' . $person->address['street'] . "\n";

// Apply fluid method
echo 'name.json: ' . $person->json() . "\n";

// Add a property at runtime
$person->phone = '020312345678';
echo 'name.json: ' . $person->json() . "\n";

// Apply fluid method to a property
echo 'name.address.json: ' . $person->address->json() . "\n";

foreach ($person as $property => $value) {
    echo '[loop] name.' . $property . ': ' . $value . ' [' . $value->type() . ':' . $value->length() . ']' . "\n";
}

// Length
echo 'name.length: ' . $person->length() . "\n";
echo 'name.name.length: ' . $person->name->length() . "\n";
echo 'name.surname.length: ' . $person->surname->length() . "\n";
echo 'name.age.length: ' . $person->age->length() . "\n";
echo 'name.phone.length: ' . $person->phone->length() . "\n";
echo 'name.address.length: ' . $person->address->length() . "\n";
echo 'name.address[street]: ' . $person->address['street'] . "\n";
echo 'name.address[street].length: ' . $person->address['street']->length() . "\n";

// Call native method
$person->surname = 'Smith';
echo 'name.label1: ' . $person->label1() . "\n";
echo 'name.label2: ' . $person->label2() . "\n";

// Call injected methods
$person->name = 'Jim';
$person['surname'] = 'Jones';
$person->sayHello = function ($obj) {
    echo 'Hello, I am ' . $obj['name'] . ' ' . $obj['surname'] . "\n";
};
$person->sayHello();
echo "name.sayHello.type: " . $person->sayHello->type() . "\n";
