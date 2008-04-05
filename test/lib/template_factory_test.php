<?php

/*
 * template_factory_test.php - Testcase for TemplateFactory
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
 * Testcase for TemplateFactory.php.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id: template_test.php 4194 2006-10-24 14:52:31Z mlunzena $
 */

class TemplateFactoryTestCase extends UnitTestCase {

  var $factory;

  function setUp() {
    $this->factory =& new Flexi_TemplateFactory(TEST_DIR . '/templates/factory_tests');
  }

  function tearDown() {
    unset($this->factory);
  }

  function test_should_create_factory() {
    $factory =& new Flexi_TemplateFactory('.');
    $this->assertNotNull($factory);
  }

  function test_should_create_factory_using_path() {
    $path = TEST_DIR . '/templates/factory_tests';
    $factory =& new Flexi_TemplateFactory($path);
    $this->assertNotNull($factory);
  }

  function test_should_open_template_using_relative_path() {
    $foo =& $this->factory->open('foo');
    $this->assertNotNull($foo);
  }

  function test_should_open_template_using_absolute_path() {
    $foo =& $this->factory->open(TEST_DIR . '/templates/factory_tests/foo');
    $this->assertNotNull($foo);
  }

  function test_should_raise_a_warning_trying_to_open_a_missing_template() {
    $this->expectError(new PatternExpectation('/Could not find template/'));
    $bar =& $this->factory->open('bar');
    $this->assertNull($bar);
  }

  function test_should_open_template_using_extension() {
    $foo =& $this->factory->open('foo.php');
    $this->assertNotNull($foo);
    $this->assertIsA($foo, 'Flexi_PhpTemplate');
  }

  function test_should_raise_an_error_when_opening_a_template_with_unknown_extension() {
    $this->expectError(new PatternExpectation('/Could not find class/'));
    $baz =& $this->factory->open('baz');
    $this->assertNull($baz);
  }

  function test_should_render_template() {
    $template =& $this->factory->open('foo');
    $out = $template->render(array('whom' => 'bar'));
    $this->assertEqual('Hallo, bar!', $out);
  }
}
