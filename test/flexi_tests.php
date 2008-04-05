<?php

/*
 * flexi_tests.php - Setup Tool for tests
 *
 * Copyright (C) 2006 - Marcus Lunzenauer <mlunzena@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once 'simpletest/unit_tester.php';
require_once 'simpletest/mock_objects.php';

/**
 * Setting up tests for flexi.
 *
 * @package     flexi
 * @subpackage  test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id$
 */

class Flexi_Tests {

  /**
   * Calling this function initializes everything necessary to test flexi.
   *
   * @return void
   */
  function setup() {
    static $once;

    if (!$once) {

      # define TEST_DIR
      define('TEST_DIR', dirname(__FILE__));

      # load required files
      require_once TEST_DIR . '/../lib/flexi.php';

      $once = TRUE;
    }
  }
}
