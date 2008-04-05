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
 * A template engine that uses PHP to render templates.
 *
 * @package   flexi
 *
 * @author    Marcus Lunzenauer (mlunzena@uos.de)
 * @copyright (c) Authors
 * @version   $Id$
 */

class Flexi_PhpTemplate extends Flexi_Template {

  /**
   * Parse, render and return the presentation.
   *
   * @return string A string representing the rendered presentation.
   */
  function _render() {

    extract($this->attributes);

    # include template, parse it and get output
    ob_start();
    require $this->template;
    $content_for_layout = ob_get_clean();


    # include layout, parse it and get output
    if (isset($this->layout)) {
      $defined = get_defined_vars();
      unset($defined['this']);
      $content_for_layout = $this->layout->render($defined);
    }

    return $content_for_layout;
  }


  /**
   * Parse, render and return the presentation of a partial template.
   *
   * @param string A name of a partial template.
   * @param array  An optional associative array of attributes and their
   *               associated values.
   *
   * @return string A string representing the rendered presentation.
   */
  function render_partial($partial, $attributes = array()) {
    return $this->factory->render($partial, $attributes + $this->attributes);
  }


  /**
   * TODO
   *
   * @param string A name of a partial template.
   * @param array  The collection to be rendered.
   * @param string Optional a name of a partial template used as spacer.
   * @param array  An optional associative array of attributes and their
   *               associated values.
   *
   * @return string A string representing the rendered presentation.
   */
  function render_partial_collection($partial, $collection,
                                     $spacer = NULL, $attributes = array()) {

    $template =& $this->factory->open($partial);
    $template->set_attributes($this->attributes);
    $template->set_attributes($attributes);

    $collected = array();
    $iterator_name = array_pop(explode('/', $partial));
    foreach ($collection as $element)
      $collected[] = $template->render(array($iterator_name => $element));

    $spacer = isset($spacer) ? $this->render_partial($spacer, $attributes) : '';

    return join($spacer, $collected);
  }
}
