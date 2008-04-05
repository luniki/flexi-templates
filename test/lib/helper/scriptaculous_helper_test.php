<?php

/*
 * scriptaculous_helper_test.php - Tests the ScriptaculousHelper.
 *
 * Copyright (C) 2006 - Marcus Lunzenauer <mlunzena@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once dirname(__FILE__) . '/../../flexi_tests.php';
Flexi_Tests::setup();
require_once dirname(__FILE__) . '/../../../lib/helper/scriptaculous_helper.php';

/**
 * Testcase for ScriptaculousHelper.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id$
 */

class ScriptaculousHelperTestCase extends UnitTestCase {

  function setUp() {
  }

  function tearDown() {
  }

  function test_sortable_element() {
    var_dump(ScriptaculousHelper::sortable_element('list', array('url' => '/')));
  }
}
