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
 * Abstract template class representing the presentation layer of an action.
 * Output can be customized by supplying attributes, which a template can
 * manipulate and display.
 *
 * @package   flexi
 *
 * @author    Marcus Lunzenauer (mlunzena@uos.de)
 * @copyright (c) Authors
 * @version   $Id$
 */

class Flexi_Template {

  /**
   * @ignore
   */
  var
    $attributes, $factory, $layout, $template;


  /**
   * Constructor
   *
   * @param string A name of a template.
   *
   * @return void
   */
  function Flexi_Template($template, &$factory) {

    # set template
    $this->template = $template;

    # set factory
    $this->factory =& $factory;

    # init attributes
    $this->clear_attributes();

    # set layout
    $this->set_layout(NULL);
  }


  /**
   * Parse, render and return the presentation.
   *
   * @param array  An optional associative array of attributes and their
   *               associated values.
   * @param string A name of a layout template.
   *
   * @return string A string representing the rendered presentation.
   */
  function render($attributes = null, $layout = null) {

    if ($layout)
      $this->set_layout($layout);

    # merge attributes
    $this->set_attributes($attributes);

    return $this->_render();
  }



  /**
   * Parse, render and return the presentation.
   *
   * @return string A string representing the rendered presentation.
   */
  function _render() {
    trigger_error('Flexi_Template::render() must be overridden', E_USER_ERROR);
    exit;
  }


  /**
   * Returns the value of an attribute.
   *
   * @param string An attribute name.
   * @param mixed  An attribute value.
   *
   * @return mixed  An attribute value.
   */
  function get_attribute($name) {
    return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
  }


  /**
   * Set an array of attributes.
   *
   * @return array An associative array of attributes and their associated
   *               values.
   */
  function get_attributes() {
    return $this->attributes;
  }


  /**
   * Set an attribute.
   *
   * @param string An attribute name.
   * @param mixed  An attribute value.
   *
   * @return void
   */
  function set_attribute($name, $value) {
    $this->attributes[$name] = $value;
  }


  /**
   * Set an array of attributes.
   *
   * @param array An associative array of attributes and their associated
   *              values.
   *
   * @return void
   */
  function set_attributes($attributes) {
    $this->attributes = (array)$attributes + (array)$this->attributes;
  }


  /**
   * Clear all attributes associated with this template.
   *
   * @return void
   */
  function clear_attributes() {
    $this->attributes = array();
  }


  /**
   * Clear an attribute associated with this template.
   *
   * @param string The name of the attribute to be cleared.
   *
   * @return void
   */
  function clear_attribute($name) {
    unset($this->attributes[$name]);
  }


  /**
   * Set the template's layout.
   *
   * @param mixed A name of a layout template or a layout template.
   *
   * @return void
   */
  function set_layout($layout) {
    $this->layout =& $this->factory->open($layout);
  }
}
