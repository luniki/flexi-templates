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

/**
 * Using this factory you can create new Template objects.
 *
 * @package   flexi
 *
 * @author    Marcus Lunzenauer (mlunzena@uos.de)
 * @copyright (c) Authors
 * @version   $Id$
 */

class Flexi_TemplateFactory {


  /**
   * include path for templates
   *
   * @access private
   * @var string
   */
  var $path;


  /**
   * Constructor of TemplateFactory.
   *
   * @param string the template include path
   *
   * @return void
   */
  function Flexi_TemplateFactory($path) {
    $this->set_path($path);
  }


  /**
   * Sets a new include path for the factory and returns the old one.
   *
   * @param string the new path
   *
   * @return string the old path
   */
  function set_path($path) {

    $old_path = $this->get_path();

    if (substr($path, -1) != '/')
      $path .= '/';

    $this->path = $path;

    return $old_path;
  }


  /**
   * Returns the include path of the factory
   *
   * @return string the current include path
   */
  function get_path() {
    return $this->path;
  }


  /**
   * Open a template of the given name using the factory method pattern. This
   * method returns it's parameter, if it is not a string. This functionality is
   * useful for helper methods like #render_partial
   *
   * @param string A name of a template.
   *
   * @return mixed the factored object
   */
  function &open($template0) {

    if (!is_string($template0)) {
      return $template0;
    }

    # if it starts with a slash, it's an absolute path
    $template = $template0[0] != '/'
                ? $this->get_path() . $template0
                : $template0;

    $matches = array();
    $matched = ereg('\.([^/.]+)$', $template, $matches);

    # no extension defined, find it
    if ($matched === FALSE) {

      # find templates matching pattern
      $files = glob($template . '.*');

      # no such template
      if (0 == sizeof($files)) {
        trigger_error(sprintf('Could not find template: "%s" (searching "%s").',
                              $template0, $this->get_path()),
                      E_USER_WARNING);
        $null = NULL;
        return $null;
      }

      $template = current($files);
      ereg('\.([^/.]+)$', $template, $matches);
    }

    switch ($matches[1]) {

      case 'php':
        $class = 'Flexi_PhpTemplate'; break;

      case 'pjs':
        $class = 'Flexi_JsTemplate'; break;

      default:
        trigger_error(sprintf('Could not find class of "%s": "%s".',
                              $template, $matches[1]),
                      E_USER_ERROR);
        $null = NULL;
        return $null;
    }

    $template =& new $class($template, $this);

    return $template;
  }


  /**
   * Class method to parse, render and return the presentation of a
   * template.
   *
   * @param string A name of a template.
   * @param array  An associative array of attributes and their associated
   *               values.
   * @param string A name of a layout template.
   *
   * @return string A string representing the rendered presentation.
   */
  function render($name, $attributes = null, $layout = null) {
    $template =& $this->open($name);
    return $template->render($attributes, $layout);
  }
}
