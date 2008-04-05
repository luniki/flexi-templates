<?php

/*
 * index.php - first example for flexi templates
 *
 * Copyright (C) 2007 - Marcus Lunzenauer <mlunzena@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */


# load flexi lib
require_once dirname(__FILE__) . '/../../lib/flexi.php';

# where are the templates
$path_to_the_templates = dirname(__FILE__) . '/templates';

# we need a template factory
$factory = new Flexi_TemplateFactory($path_to_the_templates);

# open template
$template = $factory->open('hello_world');

# set name of the greetee
$template->set_attribute('name', 'Axel');

# render template
echo $template->render();
