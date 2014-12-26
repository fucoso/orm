============
Installation
============

Prerequisites
-------------

Fucoso\ORM requires PHP 5.3 or greater with the PDO_ extension loaded, as well as
any PDO drivers for databases to wich you wish to connect.

.. _PDO: http://php.net/manual/en/book.pdo.php

Via Composer
------------

The most flexible installation method is using Composer.

Create a `composer.json` file in the root of your project:

.. code-block:: javascript

    {
        "require": {
            "fucoso/ORM": "*"
        }
    }

Install composer:

.. code-block:: bash

    curl -s http://getcomposer.org/installer | php

Run Composer to install Fucoso\ORM:

.. code-block:: bash

    php composer.phar install

To upgrade Fucoso\ORM to the latest version, run:

.. code-block:: bash

    php composer.phar update

Once installed, include `vendor/autoload.php` in your script to autoload
Fucoso\ORM.

.. code-block:: bash

    require 'vendor/autoload.php';

From GitHub
-----------

The alternative is to checkout the code directly from GitHub:

.. code-block:: bash

    git clone https://github.com/fucoso/ORM.git

In your code, include and register the Fucoso\ORM autoloader:

.. code-block:: bash

    require '.../Fucoso/ORM/Autoloader.php';
    \Fucoso\ORM\Autoloader::register();

Once you have installed Fucoso\ORM, the next step is to :doc:`set it up <setup>`.