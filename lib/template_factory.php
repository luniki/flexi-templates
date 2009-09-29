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
   * @var string
   */
  protected $path;


  /**
   * Constructor of TemplateFactory.
   *
   * @param string the template include path
   *
   * @return void
   */
  function __construct($path) {
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
   * Open a template of the given name using the factory method pattern.
   * If a string was given, the path of the factory is searched for a matching
   * template.
   * If this string starts with a slash or with /\w+:\/\//, the string is
   * interpreted as an absolute path. Otherwise the path of the factory will be
   * prepended.
   * After that the factory searches for a file extension in this string. If
   * there is none, the directory where the template is supposed to live is
   * searched for a file starting with the template string and a supported
   * file extension.
   * At last the factory instantiates a template object of the matching template
   * class.
   *
   * Examples:
   *
   *   $factory->open('/path/to/template')
   *     does not prepend the factory's path but searches for "template.*" in
   *     "/path/to"
   *
   *   $factory->open('template')
   *     prepends the factory's path and searches there for "template.*"
   *
   *  $factory->open('template.php')
   *     prepends the factory's path but does not search and instantiates a
   *     PHPTemplate instead
   *
   * This method returns it's parameter, if it is not a string. This
   * functionality is useful for helper methods like #render_partial
   *
   * @throws Flexi_TemplateNotFoundException
   * @throws Flexi_TemplateClassNotFoundException
   *
   * @param string A name of a template.
   *
   * @return mixed the factored object
   */
  function open($template) {

    # if it is not a string, this method behaves like identity
    if (!is_string($template)) {
      return $template;
    }

    # get file
    $file = $this->get_template_file($template);
    if ($file === NULL) {
      throw new Flexi_TemplateNotFoundException(
          sprintf('Could not find template: "%s".', $template));
    }

    # retrieve class
    $class = $this->get_template_class($file);
    if ($class === NULL) {
        throw new Flexi_TemplateClassNotFoundException(
          sprintf('Could not find class of "%s"', $template));
    }

    return new $class($file, $this);
  }


  /**
   * This method returns the absolute filename of the template
   *
   * @param  string     a template string
   *
   * @return mixed      an absolute filename or NULL if the template could not
   *                    be found
   */
  function get_template_file($template) {

    $template = $this->get_absolute_path($template);

    # no extension defined, find it
    if ($this->get_extension($template) === NULL) {
      return $this->find_template($template);
    }

    return file_exists($template) ? $template : NULL;
  }


  /**
   * Matches an extension to a template class.
   *
   * @param  string     the template
   *
   * @return string     a string containing the class name of a matched
   *                    extension or NULL if the extension did not match
   */
  function get_template_class($template) {

    $classes = array(
      'php' => 'Flexi_PhpTemplate',
      'pjs' => 'Flexi_JsTemplate'
    );

    $extension = $this->get_extension($template);
    return isset($classes[$extension]) ? $classes[$extension] : NULL;
  }


  /**
   * Returns the absolute path to the template. If the given argument starts
   * with a slash or with a protocoll, this method just returns its arguments.
   *
   * @param  string     an incomplete template name
   *
   * @return string     an absolute path to the incomplete template name
   */
  function get_absolute_path($template) {
    return preg_match('#^(/|\w+://)#', $template)
           ? $template
           : $this->get_path() . $template;
  }


  /**
   * Find template given w/o extension.
   *
   * @param  string     the template's filename w/o extension
   *
   * @return mixed      NULL if there no such file could be found, a string
   *                    containing the complete file name otherwise
   */
  function find_template($template) {

    $file = basename($template);
    $dir = substr($template, 0, strlen($template) - strlen($file) - 1);
    $file .= '.';
    $len = strlen($file);

    if (!is_dir($dir)) {
      return NULL;
    }

    $handle = opendir($dir);
    if (!$handle) {
      return NULL;
    }

    while(($name = readdir($handle)) !== FALSE) {
      if (!strncmp($name, $file, $len)) {
        return $dir . '/' . $name;
      }
    }
    closedir($handle);
    return NULL;
  }


  /**
   * Returns the file extension if there is one.
   *
   * @param  string     an possibly incomplete template file name
   *
   * @return mixed      a string containing the file extension if there is one,
   *                    NULL otherwise
   */
  function get_extension($file) {
    $matches = array();
    $matched = ereg('\.([^/.]+)$', $file, $matches);
    return $matched ? $matches[1] : NULL;
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
    return $this->open($name)->render($attributes, $layout);
  }
}
