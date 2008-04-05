<?php

/*
 * all_tests.php - testing trails
 *
 * Copyright (C) 2006 - Marcus Lunzenauer <mlunzena@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

# set error reporting
error_reporting(E_ALL);

# load required files
require_once 'simpletest/unit_tester.php';
require_once 'simpletest/reporter.php';
require_once 'simpletest/collector.php';


# collect all tests
$all =& new GroupTest('All tests');

$lib =& new GroupTest('lib tests');
$lib->collect(dirname(__FILE__).'/lib', new SimplePatternCollector('/test.php$/'));
$all->addTestCase($lib);

$helper =& new GroupTest('helper tests');
$helper->collect(dirname(__FILE__).'/lib/helper', new SimplePatternCollector('/test.php$/'));
$all->addTestCase($helper);

# use text reporter if cli
if (sizeof($_SERVER['argv']))
  $all->run(new TextReporter());

# use html reporter if cgi
else
  $all->run(new HtmlReporter());
