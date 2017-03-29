<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_submission'];

$arrDca['palettes']['__selector__'][] = 'typeSelector';
$arrDca['palettes']['__selector__'][] = 'optionSelector';
$arrDca['palettes']['__selector__'][] = 'optionSelectorNoDefault';
$arrDca['palettes']['__selector__'][] = 'subpaletteSelector';

$arrDca['subpalettes']['subpaletteSelector']      = 'subpaletteField1';
$arrDca['subpalettes']['optionSelector_internal'] = 'internal_text';
$arrDca['subpalettes']['optionSelector_external'] = 'external_text,external_text2';

$arrDca['subpalettes']['optionSelectorNoDefault_internal'] = 'internal_text';
$arrDca['subpalettes']['optionSelectorNoDefault_external'] = 'external_text';

$arrDca['palettes']['default']  = 'typeSelector;{submission_legend},gender,firstname,lastname,email,internal_text;';
$arrDca['palettes']['default1']  = 'typeSelector';
$arrDca['palettes']['palette1'] = 'typeSelector,optionSelector,optionSelectorNoDefault,subpaletteSelector,updateFieldParent,updateFieldChild;{submission_legend},gender,academicTitle,firstname,lastname,attachments;';
$arrDca['palettes']['palette2'] = 'typeSelector;{submission_legend},firstname,lastname,attachments;';

/**
 * Fields
 */
$arrFields = array
(
	'typeSelector'       => array
	(
		'inputType' => 'select',
		'default'   => 'default',
		'options'   => array('default', 'default1', 'palette1', 'palette2'),
		'eval'      => array('submitOnChange' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'optionSelector'     => array
	(
		'inputType' => 'radio',
		'default'   => 'internal',
		'options'   => array('internal', 'external'),
		'eval'      => array('submitOnChange' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'optionSelectorNoDefault'     => array
	(
		'inputType' => 'radio',
		'options'   => array('internal', 'external'),
		'eval'      => array('submitOnChange' => true, 'mandatory' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'internal_text'      => array
	(
		'inputType' => 'text',
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'external_text'      => array
	(
		'inputType' => 'text',
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'external_text2'      => array
	(
		'inputType' => 'text',
		'sql'       => "varchar(32) NOT NULL default ''",
		'eval'      => array('mandatory' => true),
	),
	'subpaletteSelector' => array
	(
		'inputType' => 'checkbox',
		'eval'      => array('submitOnChange' => true),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'subpaletteField1'   => array
	(
		'inputType' => 'text',
		'eval'      => array('mandatory' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'updateFieldParent'  => array
	(
		'inputType' => 'select',
		'options'   => array('FOO', 'BAR'),
		'eval'      => array('submitOnChange' => true, 'includeBlankOption' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'updateFieldChild'   => array
	(
		'inputType'        => 'select',
		'options_callback' => array('HeimrichHannot\FormHybrid\Test\Backend\Submission', 'getRelatedFieldChildOptions'),
		'eval'             => array('includeBlankOption' => true),
		'sql'              => "varchar(32) NOT NULL default ''",
	),
);

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);