<?php

/*
 * index.php - third example for flexi templates
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
$template = $factory->open('quotes');


# set layout
$template->set_layout('layout');

# set quotes
$quotes = array(
  array('author' => 'August Strindberg',
        'quote'  => 'Der Mensch ist ein wunderliches Tier.'),
  array('author' => 'Pierre Reverdy',
        'quote'  => 'Der Mensch ist ein Tier, das sich selbst gezähmt hat.'),
  array('author' => 'Thomas Niederreuther',
        'quote'  => 'Der Mensch ist das einzige Tier, das sich für einen Menschen hält.'),
  array('author' => 'Durs Grünbein',
        'quote'  => 'Der Mensch ist das Tier, das Kaugummi kaut.'),
  array('author' => 'Mark Twain',
        'quote'  => 'Der Mensch ist das einzige Tier, das erröten kann - oder sollte.'));

# select one randomly
shuffle($quotes);
$quote_of_the_day = array_shift($quotes);

$template->set_attributes(array('quotes'           => $quotes,
                                'quote_of_the_day' => $quote_of_the_day));


# set current time
$time = time();
$template->set_attribute('time', $time);


# render template
echo $template->render();
