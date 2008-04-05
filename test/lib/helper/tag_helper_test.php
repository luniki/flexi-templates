<?php

/*
 * tag_helper_test.php - Testcase for TagHelper.
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
require_once dirname(__FILE__) . '/../../../lib/helper/tag_helper.php';

/**
 * Testcase for TagHelper.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id: tag_helper_test.php 4194 2006-10-24 14:52:31Z mlunzena $
 */

class TagHelperTestCase extends UnitTestCase {

  function setUp() {
  }

  function tearDown() {
  }

  function test_tag() {
    $foo = TagHelper::tag('img', array('src' => 'test.gif'));
    $this->assertEqual('<img src="test.gif" />', $foo);
  }

  function test_content_tag() {
    $foo = TagHelper::content_tag('a', '#', array('href' => '#'));
    $this->assertEqual('<a href="#">#</a>', $foo);
  }

  function test_cdata_section() {
    $foo = TagHelper::cdata_section('foo');
    $this->assertEqual('<![CDATA[foo]]>', $foo);
  }
}
