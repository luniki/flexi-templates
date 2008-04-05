<?php

/*
 * js_helper_test.php - Testcase for JsHelper.
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
require_once dirname(__FILE__) . '/../../../lib/helper/js_helper.php';

/**
 * Testcase for JsHelper.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id: js_helper_test.php 4194 2006-10-24 14:52:31Z mlunzena $
 */

class JsHelperTestCase extends UnitTestCase {

  function setUp() {
  }

  function tearDown() {
  }


  function test_link_to_function() {
    $foo = JsHelper::link_to_function('Greeting', "alert('Hello world!')",
                                      array('title' => 'hello'));
    $exp = '<a title="hello" href="#" onclick="alert(\'Hello world!\'); '.
           'return false;">Greeting</a>';
    $this->assertEqual($exp, $foo);
  }

  function test_button_to_function() {
    $foo = JsHelper::button_to_function("Greeting", "alert('Hello world!')");
    $exp = '<input type="button" value="Greeting" '.
           'onclick="alert(\'Hello world!\');" />';
    $this->assertEqual($exp, $foo);

    $foo = JsHelper::button_to_function("Greeting", "alert('Hello world!')",
                                        array('onclick' => 'alert(\'One\')'));
    $exp = '<input onclick="alert(\'One\'); alert(\'Hello world!\');" '.
           'type="button" value="Greeting" />';
    $this->assertEqual($exp, $foo);
  }

  function test_escape_javascript() {
    $foo = JsHelper::escape_javascript("\r\n\n\r\"\\'");
    $this->assertEqual('\\n\\n\\n\\"\\\\\'', $foo);
  }

  function test_javascript_tag() {
    $foo = JsHelper::javascript_tag("alert('All is good')");
    $exp = <<<EXPECTED
<script type="text/javascript">
//<![CDATA[
alert('All is good')
//]]>
</script>
EXPECTED;
    $this->assertEqual($exp, $foo);
  }
}
