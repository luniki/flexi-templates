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
    $this->setUpFS();
    $this->factory = new Flexi_TemplateFactory('var://');
  }


  function tearDown() {
    unset($this->factory);

    stream_wrapper_unregister("var");
    unset($this->dispatcher);
  }

  function setUpFS() {
    ArrayFileStream::set_filesystem(array(
      'foo_using_partial.php' =>
        'Hello, <?= $this->render_partial("foos_partial.php") ?>!',

      'foos_partial.php' =>
        '<h1><?= $whom ?> at <?= $when ?></h1>',

      'foo_with_partial_collection' =>
        '[<?= $this->render_partial_collection("item.php", $items, "spacer.php") ?>]',

      'item.php' =>
        '"<?= $item ?>"',

      'spacer.php' =>
        ', ',

      'attributes.php' =>
        '<? foreach (get_defined_vars() as $name => $value) : ?>' .
        '<?= $name ?><?= $value ?>'.
        '<? endforeach ?>',

      'foo.php' =>
        'Hello, <?= $whom ?>!',

      'layout.php' =>
        '[<?= $content_for_layout ?>]',
    ));
    stream_wrapper_register("var", "ArrayFileStream") or die("Failed to register protocol");
  }

#  function test_render_partial() {
#    $template = $this->factory->open('foo_using_partial.php');
#    $template->set_attribute('whom', 'bar');
#    $output = $template->render(array('when' => 'now'));
#    $spec = "Hello, <h1>bar at now</h1>!";
#    $this->assertEqual($output, $spec);
#  }


#  function test_render_partial_collection() {
#    $template = $this->factory->open('foo_with_partial_collection.php');
#    $result = $template->render_partial_collection('item.php',
#                                                   range(1, 3),
#                                                   'spacer.php');
#    $this->assertEqual('"1", "2", "3"', $result);
#  }

#  function test_render_partial_with_a_template_object_instead_of_a_template_name() {
#    $template = $this->factory->open('foo_using_partial.php');
#    $partial  = $this->factory->open('foos_partial.php');
#    $template->set_attribute('whom', 'bar');

#    $output = $template->render(array('when' => 'now'));
#    $spec = "Hello, <h1>bar at now</h1>!";
#    $this->assertEqual($output, $spec);
#  }


#   function test_should_override_attributes_with_those_passed_to_render() {

#     $template = $this->factory->open('attributes.php');
#     $template->set_attribute('foo',  'baz');

#     $template->render(array('foo' => 'bar'));

#     $bar = $template->get_attribute('foo');
#     $this->assertEqual($bar, 'bar');

#     $out = $template->render();

#     $bar = $template->get_attribute('foo');
#     $this->assertEqual($bar, 'bar');
#   }

#   function test_render_without_layout() {
#     $foo = $this->factory->open('foo.php');
#     $foo->set_attribute('whom', 'bar');
#     $out = $foo->render();
#     $this->assertEqual('Hello, bar!', $out);
#   }

#   function test_render_with_layout() {
#     $foo = $this->factory->open('foo.php');
#     $foo->set_attribute('whom', 'bar');
#     $foo->set_layout('layout.php');
#     $out = $foo->render();
#     $this->assertEqual('[Hello, bar!]', $out);
#   }

   function test_render_with_missing_layout() {
     $foo = $this->factory->open('foo.php');
     $this->expectException("Flexi_TemplateNotFoundException");
     $foo->set_layout('nosuchlayout.php');
   }

#   function test_render_with_attributes() {
#     $foo = $this->factory->open('foo');
#     $foo->set_attribute('whom', 'bar');
#     $foo->set_layout('layouts/layout');
#     $foo_out = $foo->render();

#     $bar = $this->factory->open('foo');
#     $bar_out = $bar->render(array('whom' => 'bar'), 'layouts/layout');

#     $this->assertEqual($foo_out, $bar_out);
#   }
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
