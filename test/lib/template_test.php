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

Mock::generate('Flexi_TemplateFactory');


class AnEmptyTemplate extends UnitTestCase {


  var $factory;


  function setUp() {
    $this->factory =& new MockFlexi_TemplateFactory(TEST_DIR . '/templates/template_tests');
    $this->factory->setReturnValue('open', new Flexi_Template('foo', $this->factory));
  }


  function tearDown() {
    unset($this->factory);
  }


  function test_should_have_no_attributes() {
    $template =& $this->factory->open('');
    $this->assertEqual(0, sizeof($template->get_attributes()));
  }


  function test_should_not_be_empty_after_setting_an_attribute() {
    $template =& $this->factory->open('');
    $template->set_attribute('foo', 'bar');
    $this->assertNotEqual(0, sizeof($template->get_attributes()));
  }


  function test_should_be_empty_after_clear() {

    $template =& $this->factory->open('foo');

    $this->assertEqual(0, sizeof($template->get_attributes()));

    $template->clear_attributes();
    $this->assertEqual(0, sizeof($template->get_attributes()));
  }
}


class ATemplate extends UnitTestCase {


  var $factory;


  function setUp() {
    $this->factory =& new MockFlexi_TemplateFactory(TEST_DIR . '/templates/template_tests');
    $this->factory->setReturnValue('open', new Flexi_Template('foo', $this->factory));
  }


  function tearDown() {
    unset($this->factory);
  }


  function test_should_return_an_previously_set_attribute() {
    $template =& $this->factory->open('foo');
    $template->set_attribute('whom', 'bar');
    $this->assertEqual('bar', $template->get_attribute('whom'));
  }


  function test_should_return_previously_set_attributes() {

    $template =& $this->factory->open('foo');
    $template->set_attributes(array('whom' => 'bar', 'foo' => 'baz'));

    $attributes = $template->get_attributes();
    $this->assertIsA($attributes, 'array');
    $this->assertEqual(2, sizeof($attributes));
    $this->assertEqual('bar', $attributes['whom']);
    $this->assertEqual('baz', $attributes['foo']);
  }


  function test_should_merge_attributes_with_set_attributes() {

    $template =& $this->factory->open('foo');
    $template->set_attributes(array('a' => 1, 'b' => 2));

    $this->assertEqual(2, sizeof($template->get_attributes()));
    $this->assertEqual(1, $template->get_attribute('a'));
    $this->assertEqual(2, $template->get_attribute('b'));

    $template->set_attributes(array('b' => 8, 'c' => 9));

    $this->assertEqual(3, sizeof($template->get_attributes()));
    $this->assertEqual(1, $template->get_attribute('a'));
    $this->assertEqual(8, $template->get_attribute('b'));
    $this->assertEqual(9, $template->get_attribute('c'));
  }


  function test_should_be_empty_after_clear() {

    $template =& $this->factory->open('foo');

    $template->set_attributes(array('a' => 1, 'b' => 2));
    $this->assertNotEqual(0, sizeof($template->get_attributes()));

    $template->clear_attributes();
    $this->assertEqual(0, sizeof($template->get_attributes()));
  }


#   function test_should_override_attributes_with_those_passed_to_render() {

#     $t =& $this->factory->open('attributes');
#     $t->set_attribute('foo',  'baz');

#     $t->render(array('foo' => 'bar'));

#     $bar = $t->get_attribute('foo');
#     $this->assertEqual($bar, 'bar');

#     $out = $t->render();

#     $bar = $t->get_attribute('foo');
#     $this->assertEqual($bar, 'bar');
#   }

#   function test_render_without_layout() {
#     $foo =& $this->factory->open('foo');
#     $foo->set_attribute('whom', 'bar');
#     $out = $foo->render();
#     $this->assertEqual('Hallo, bar!', $out);
#   }

#   function test_render_with_layout() {
#     $foo =& $this->factory->open('foo');
#     $foo->set_attribute('whom', 'bar');
#     $foo->set_layout('layouts/layout');
#     $out = $foo->render();
#     $this->assertEqual('[Hallo, bar!]', $out);
#   }

#   function test_render_with_missing_layout() {
#     $foo =& $this->factory->open('foo');

#     $this->expectError(new PatternExpectation('/Could not find template/'));
#     $foo->set_layout('layouts/nosuchlayout');
#   }

#   function test_render_with_attributes() {
#     $foo =& $this->factory->open('foo');
#     $foo->set_attribute('whom', 'bar');
#     $foo->set_layout('layouts/layout');
#     $foo_out = $foo->render();

#     $bar =& $this->factory->open('foo');
#     $bar_out = $bar->render(array('whom' => 'bar'), 'layouts/layout');

#     $this->assertEqual($foo_out, $bar_out);
#   }
}
