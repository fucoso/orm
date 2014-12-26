ORM
========

ORM for those project where Doctrine can't be used.


Warning: This is a work in progress.

Intended Features
--------

* CRUD operations made simple
* batch update and delete
* filtering
* ordering
* limiting
* transactions
* custom queries
* events

Quick Overview
--------

```php
// Create a new person record
$person = new Person();
$person->name = "Frank Zappa";
$person->birthday = "1940-12-20";
$person->save();

// Get record by primary key
Person::get(10);   // Throws exception if the model doesn't exist
Person::find(10);  // Returns null if the model doesn't exist

// Check record exists by primary key
Person::exists(10);

// Also works for composite primary keys
Trade::get('2013-01-01', 100);
Trade::find('2013-01-01', 100);
Trade::exists('2013-01-01', 100);

// Primary keys can also be given as arrays
$tradePK = array('2013-01-01', 100);
Trade::get($tradePK);
Trade::find($tradePK);
Trade::exists($tradePK);

// Fetch, update, save
$person = Person::get(10);
$person->salary += 5000; // give the man a raise!
$person->save();

// Fetch, delete
Person::get(37)->delete();

// Intuitive filtering, ordering and limiting
$persons = Person::objects()
    ->filter('salary', '>', 10000)
    ->filter('birthday', 'between', ['2000-01-01', '2001-01-01'])
    ->orderBy('name', 'desc')
    ->limit(100)
    ->fetch();

// Count records
$count = Person::objects()
    ->filter('salary', '>', 10000)
    ->count();

// Distinct values
$count = Person::objects()
    ->distinct('name', 'email');

// Complex composite filters
$persons = Person::objects()->filter(
    Filter::_or(
        Filter::_and(
            array('id', '>=', 10),
            array('id', '<=', 20)
        ),
        Filter::_and(
            array('id', '>=', 50),
            array('id', '<=', 60)
        ),
        array('id', '>=', 100),
    )
)->fetch();

// Fetch a single record (otherwise throws an exeption)
$person = Person::objects()
    ->filter('email', '=', 'ivan@example.com')
    ->single();

// Batch update
Person::objects()
    ->filter('salary', '>', 10000)
    ->update(['salary' => 5000]);

// Batch delete
Person::objects()
    ->filter('salary', '>', 10000)
    ->delete();

// Aggregates
Person::objects()->filter('name', 'like', 'Ivan%')->avg('salary');
Person::objects()->filter('name', 'like', 'Marko%')->min('birthday');

// Custom queries
$conn = DB::getConnection('myconn');
$data1 = $conn->query("SELECT * FROM mytable;");
$data2 = $conn->preparedQuery("SELECT * FROM mytable WHERE mycol > :value", array("value" => 10))
```

Inspirations:

* [Django ORM](https://docs.djangoproject.com/en/dev/topics/db/)
* Laravel's [Eloquent ORM](http://laravel.com/docs/database/eloquent)
* [Paris](http://j4mie.github.io/idiormandparis/)
* [Phormium](https://github.com/ihabunek/phormium)
