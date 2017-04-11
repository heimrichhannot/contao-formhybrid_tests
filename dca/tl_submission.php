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
$arrDca['palettes']['default1'] = 'typeSelector';
$arrDca['palettes']['palette1'] =
    'typeSelector,optionSelector,optionSelectorNoDefault,subpaletteSelector,updateFieldParent,updateFieldChild;{submission_legend},gender,academicTitle,firstname,lastname,attachments;';
$arrDca['palettes']['palette2'] = 'typeSelector;{submission_legend},firstname,lastname,attachments,testText;';
$arrDca['palettes']['palette3'] = '{submission_legend},firstname,lastname,attachments,testText,optionSelector;';

/**
 * Fields
 */
$arrFields = [
    'typeSelector'            => [
        'inputType' => 'select',
        'default'   => 'default',
        'options'   => ['default', 'default1', 'palette1', 'palette2'],
        'eval'      => ['submitOnChange' => true],
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'optionSelector'          => [
        'inputType' => 'radio',
        'default'   => 'internal',
        'options'   => ['internal', 'external'],
        'eval'      => ['submitOnChange' => true],
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'optionSelectorNoDefault' => [
        'inputType' => 'radio',
        'options'   => ['internal', 'external'],
        'eval'      => ['submitOnChange' => true, 'mandatory' => true],
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'internal_text'           => [
        'inputType' => 'text',
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'external_text'           => [
        'inputType' => 'text',
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'external_text2'          => [
        'inputType' => 'text',
        'sql'       => "varchar(32) NOT NULL default ''",
        'eval'      => ['mandatory' => true],
    ],
    'subpaletteSelector'      => [
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'subpaletteField1'        => [
        'inputType' => 'text',
        'eval'      => ['mandatory' => true],
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'updateFieldParent'       => [
        'inputType' => 'select',
        'options'   => ['FOO', 'BAR'],
        'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true],
        'sql'       => "varchar(32) NOT NULL default ''",
    ],
    'updateFieldChild'        => [
        'inputType'        => 'select',
        'options_callback' => ['HeimrichHannot\FormHybrid\Test\Backend\Submission', 'getRelatedFieldChildOptions'],
        'eval'             => ['includeBlankOption' => true],
        'sql'              => "varchar(32) NOT NULL default ''",
    ],
    'testText'                => [
        'inputType'   => 'textarea',
        'eval'        => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true, 'allowedTags' => '<p><span><a>'],
        'explanation' => 'insertTags',
        'sql'         => "mediumtext NULL",
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);