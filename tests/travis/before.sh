#!/bin/bash

# Current foder
SCRIPT_DIR=$(cd "$(dirname "$0")"; pwd)

composer install

mkdir tmp

if [ $DB = "mysql" ]
then
    mysql < $SCRIPT_DIR/mysql/setup.sql
fi

if [ $DB = "sqlite" ]
then
    sqlite3 tmp/test.db < $SCRIPT_DIR/sqlite/setup.sql
fi
