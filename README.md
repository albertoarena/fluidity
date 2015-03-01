# Fluidity

Fluidity is a PHP library that implements the concept of ["Fluid methods"][fluid-method], in addition to the
possibility of injecting methods and properties against instantiated objects.

What is a fluid method?
-----------------------

The idea behind a **fluid method** is simple:

- I want to define it **outside of a class (generic)**
- I want it to work as a **normal method**
- I want it to work not only on the object, but also on the object **properties** themselves
- I want it to work **on data types (obliquely)**
- I want the business logic encapsulated in the fluid method itself

How does it work
----------------

In PHP, it's not possible to inject a method against instantiated objects of different classes, or against the properties
of that object.

The way I found to implement the concept of fluid method is to create a class `\Fluidity\Fluid\Base` that handles the
logic behind it, and to define fluid methods using a final class named `\Fluidity\Fluidizer`.

E.g. I define a fluid method named `json` that should return the JSON version of the object/property to which it is
applied:


    // Define a fluid method "json"
    Fluidizer::define('json', function ($object, $arguments) {
        $properties = $object->properties();
        if (empty($properties)) {
            return json_encode($object->get());
        }
        else {
            return json_encode(array_map(function ($v) {
                return $v->toFlat();
            }, $properties));
        }
    });

After, I define a class Person

    class Person extends \Fluidity\Fluid\Base
    {
        protected $name, $address;

        public function __construct()
        {
            parent::__construct(new \Fluidity\Fluid\Settings());
        }
    }

I should be able to assign a value to the properties:

    $person = new Person();
    $person->name = 'Alberto Arena';
    $person->address = array('number' => '1', 'street' => 'Waterloo', 'city' => 'London');

And to recover the value:

    echo 'person.name: ' . $person->name . "\n";
    # ==> Alberto Arena

    echo 'person.address: ' . $person->address . "\n";
    # ==> 1,Waterloo,London

    echo 'person: ' . $person . "\n";
    # ==> Alberto Arena,1,Waterloo,London

Now, I want to apply the fluid method `json` to all properties and the object itself:

    echo 'person.name.json(): ' . $person->name->json() . "\n";
    # ==> "Alberto Arena"

    echo 'person.address.json(): ' . $person->address->json() . "\n";
    # ==> {"number":"1","street":"Waterloo","city":"London"}

    echo 'person.json(): ' . $person->json() . "\n";
    # ==> {"name":"Alberto Arena","address":{"number":"1","street":"Waterloo","city":"London"}}

Injecting methods and properties
--------------------------------

The library allows to inject methods and properties on-the-fly in objects already instantiated.

E.g. I can inject a method:

    $person->sayHello = function($obj) {
        return 'Hello, ' . $obj->name;
    };

    echo $person->sayHello() . "\n";
    # ==> Hello, Alberto Arena

And I can inject a new property:

    $person->phone = '07771234567';

    echo 'person.phone: ' . $person->phone . "\n";
    # ==> 07771234567


Documentation
--------------

I am currently still writing a complete documentation of the library.

[fluid-method]: [http://albertoarena.co.uk/fluid-methods-a-new-approach-to-generic-and-oblique-methods-in-oop/]