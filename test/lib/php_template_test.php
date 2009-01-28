<?php

# Copyright (c)  2008 - Marcus Lunzenauer <mlunzena@uos.de>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

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
    $this->factory = new Flexi_TemplateFactory(TEST_DIR . '/templates/template_tests');
  }


  function tearDown() {
    unset($this->factory);
  }

  function test_render_partial() {
    $template = $this->factory->open('foo_using_partial');
    $template->set_attribute('whom', 'bar');
    $output = $template->render(array('when' => 'now'));
    $spec = "Hallo, <h1>bar at now</h1>\n!\n";
    $this->assertEqual($output, $spec);
  }


  function test_render_partial_collection() {
    $template = $this->factory->open('foo_with_partial_collection');
    var_dump($template->render_partial_collection('item', range(1, 5), 'spacer'));
  }

  function test_render_partial_with_a_template_object_instead_of_a_template_name() {
    $template = $this->factory->open('foo_using_partial');
    $partial  = $this->factory->open('foos_partial');
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

