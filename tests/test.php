<?php

namespace FpDbTest\tests;

use FpDbTest\Database;
use FpDbTest\DatabaseTest;
use FpDbTest\MysqlFactory;

require __DIR__ . '/bootstrap.php';

$factory = new MysqlFactory();
$db = new Database($factory->createConnection(getenv()));
$test = new DatabaseTest($db);
$test->testBuildQuery();

exit(0);
