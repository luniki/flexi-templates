<?php

/*
 * php_template_test.php - Tests the php_template.
 *
 * Copyright (C) 2006 - Marcus Lunzenauer <mlunzena@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once dirname(__FILE__) . '/../flexi_tests.php';
Flexi_Tests::setup();

/**
 * Testcase for php_template.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id$
 */

class PhpTemplateTestCase extends UnitTestCase {

  var $factory;


  function setUp() {
    $this->factory =& new Flexi_TemplateFactory(TEST_DIR . '/templates/template_tests');
  }


  function tearDown() {
    unset($this->factory);
  }

  function test_render_partial() {
    $template =& $this->factory->open('foo_using_partial');
    $template->set_attribute('whom', 'bar');
    $output = $template->render(array('when' => 'now'));
    $spec = "Hallo, <h1>bar at now</h1>\n!\n";
    $this->assertEqual($output, $spec);
  }


  function test_render_partial_collection() {
    $template =& $this->factory->open('foo_with_partial_collection');
    var_dump($template->render_partial_collection('item', range(1, 5), 'spacer'));
  }

  function test_render_partial_with_a_template_object_instead_of_a_template_name() {
    $template =& $this->factory->open('foo_using_partial');
    $partial  =& $this->factory->open('foos_partial');
    $template->set_attribute('whom', 'bar');

    $output = $template->render(array('when' => 'now'));
    $spec = "Hallo, <h1>bar at now</h1>\n!\n";
    $this->assertEqual($output, $spec);
  }
}


class PhpTemplatePartialBugTestCase extends UnitTestCase {

  function test_partial_bug() {
    $factory = new Flexi_TemplateFactory(TEST_DIR . '/templates/layout_with_partials');
    $template = $factory->open('template');
    $template->set_layout('layout');
    $result = $template->render();
    $this->assertEqual($result, "template\n");
  }
}

