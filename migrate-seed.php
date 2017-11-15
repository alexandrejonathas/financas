<?php

  exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx rollback -t=0");
  exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx migrate");
  //exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx seed:run -s UsersSeeder");
  exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx seed:run -s CategoryCostsSeeder");
  exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx seed:run -s BillReceivesSeeder");
  exec(__DIR__ . "/vendor/robmorgan/phinx/bin/phinx seed:run -s BillPaysSeeder");