<?php
chdir(dirname(__DIR__));
require './vendor/autoload.php';

use \Fluidity\FluidizerDefault;

// Create default methods
FluidizerDefault::create();

// Test class
class Name extends \Fluidity\Fluid\Base
{
    protected $name, $surname, $address;

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

$name = new Name();
echo 'name.json: ' . $name->json() . "\n";

// Set properties
$name->name = 'Alberto';
$name->surname = 'Alberto';
$name->age = 41;
$name->address = array('street' => '55 Farringdon Road', 'city' => 'London', 'zip' => 'EC1 000');
$name->dateBirth = new DateTime();

echo 'name.name: ' . $name->name . "\n";
echo 'name.surname: ' . $name->surname . "\n";
echo 'name.age: ' . $name->age . "\n";
echo 'name.address: ' . $name->address . "\n";
echo 'name.address[street]: ' . $name->address['street'] . "\n";

// Apply fluid method
echo 'name.json: ' . $name->json() . "\n";

// Add a property at runtime
$name->phone = '020312345678';
echo 'name.json: ' . $name->json() . "\n";

// Apply fluid method to a property
echo 'name.address.json: ' . $name->address->json() . "\n";

foreach ($name as $property => $value) {
    echo '[loop] name.' . $property . ': ' . $value . ' [' . $value->type() . ':' . $value->length() . ']' . "\n";
}

// Length
echo 'name.length: ' . $name->length() . "\n";
echo 'name.name.length: ' . $name->name->length() . "\n";
echo 'name.surname.length: ' . $name->surname->length() . "\n";
echo 'name.age.length: ' . $name->age->length() . "\n";
echo 'name.phone.length: ' . $name->phone->length() . "\n";
echo 'name.address.length: ' . $name->address->length() . "\n";
echo 'name.address[street]: ' . $name->address['street'] . "\n";
echo 'name.address[street].length: ' . $name->address['street']->length() . "\n";

// Call native method
$name->surname = 'Smith';
echo 'name.label1: ' . $name->label1() . "\n";
echo 'name.label2: ' . $name->label2() . "\n";

// Call injected methods
$name->name = 'Jim';
$name['surname'] = 'Jones';
$name->sayHello = function ($obj) {
    echo 'Hello, I am ' . $obj['name'] . ' ' . $obj['surname'] . "\n";
};
$name->sayHello();
echo "name.sayHello.type: " . $name->sayHello->type() . "\n";
