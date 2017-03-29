<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\FormHybrid\Test\Form;


use HeimrichHannot\Ajax\AjaxAction;
use HeimrichHannot\Ajax\Exception\AjaxExitException;
use HeimrichHannot\FormHybrid\Form;
use HeimrichHannot\FormHybrid\FormConfiguration;
use HeimrichHannot\FormHybrid\FormHelper;
use HeimrichHannot\FormHybrid\Test\TestPostForm;
use HeimrichHannot\Request\Request;

class ToggleSubPaletteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * toggle `optionSelectorNoDefault` subpalette with no active state and palette `palette1` active
     *
     * @test
     */
    public function toggleConcatenatedTypeSelectorOptionSelectorNoDefaultWithExternalSelectedAndTypeSelectorPalette1Active()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette1',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'optionSelectorNoDefault',
                'firstname', // must not be active, cause only subpalette is generated
                'lastname', // must not be active, cause only subpalette is generated
                'internal_text', // must not be in subplattes fields, as it is not active
                'external_text'
            ],
        ];


        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('optionSelectorNoDefault', 'external');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);
        $objForm->generateFields('sub_optionSelectorNoDefault');

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['optionSelectorNoDefault'];
        $arrSubExpected = ['optionSelectorNoDefault' => ['external_text']];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);

        $arrSubCurrent = $objForm->getActualSubFields();

        $arrSubCurrentParent  = array_keys($arrSubCurrent);
        $arrSubExpectedParent = array_keys($arrSubExpected);

        $this->assertEquals($arrSubExpectedParent, $arrSubCurrentParent);
    }

    /**
     * toggle `optionSelectorNoDefault` subpalette with no active state and palette `palette1` active
     *
     * @test
     */
    public function toggleConcatenatedTypeSelectorOptionSelectorNoDefaultWithTypeSelectorPalette1Active()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette1',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'optionSelectorNoDefault',
                'firstname', // must not be active, cause only subpalette is generated
                'lastname', // must not be active, cause only subpalette is generated
                'internal_text', // must not be in subplattes fields, as it is not active by default
                'external_text' // must not be in subplattes fields, as it is not active by default
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);
        $objForm->generateFields('sub_optionSelectorNoDefault');

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['optionSelectorNoDefault'];
        $arrSubExpected = [];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);

        $arrSubCurrent = $objForm->getActualSubFields();

        $arrSubCurrentParent  = array_keys($arrSubCurrent);
        $arrSubExpectedParent = array_keys($arrSubExpected);

        $this->assertEquals($arrSubExpectedParent, $arrSubCurrentParent);
    }

    /**
     * toggle `optionSelector` subpalette with palette `palette1` active
     *
     * @test
     */
    public function toggleConcatenatedTypeSelectorOptionSelectorExternalWithTypeSelectorPalette1Active()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette1',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'optionSelector',
                'firstname', // must not be active, cause only subpalette is generated
                'lastname', // must not be active, cause only subpalette is generated
                'internal_text',
                'external_text' // must not be in subplattes fields, as it is not active by default, internal_text is in default subpalette
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);
        $objForm->generateFields('sub_optionSelector');

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['optionSelector'];
        $arrSubExpected = ['optionSelector' => ['internal_text']];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);

        $arrSubCurrent = $objForm->getActualSubFields();

        $arrSubCurrentParent  = array_keys($arrSubCurrent);
        $arrSubExpectedParent = array_keys($arrSubExpected);

        $this->assertEquals($arrSubExpectedParent, $arrSubCurrentParent);

        foreach ($arrSubCurrentParent as $strParent)
        {
            $arrSubFieldsCurrent  = array_keys($arrSubCurrent[$strParent]);
            $arrSubFieldsExpected = $arrSubExpected[$strParent];

            sort($arrSubFieldsCurrent);
            sort($arrSubFieldsExpected);

            $this->assertEquals($arrSubFieldsExpected, $arrSubFieldsCurrent);
        }
    }

    /**
     * toggle `subpaletteSelector` subpalette with palette `palette1` active
     *
     * @test
     */
    public function toggleCheckBoxSubPaletteSelectorWithTypeSelectorPalette1Active()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette1',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'subpaletteSelector',
                'firstname', // must not be active, cause only subpalette is generated
                'lastname', // must not be active, cause only subpalette is generated
                'subpaletteField1',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);
        $objForm->generateFields('sub_subpaletteSelector');

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['subpaletteSelector'];
        $arrSubExpected = ['subpaletteSelector' => ['subpaletteField1']];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);

        $arrSubCurrent = $objForm->getActualSubFields();

        $arrSubCurrentParent  = array_keys($arrSubCurrent);
        $arrSubExpectedParent = array_keys($arrSubExpected);

        $this->assertEquals($arrSubExpectedParent, $arrSubCurrentParent);

        foreach ($arrSubCurrentParent as $strParent)
        {
            $arrSubFieldsCurrent  = array_keys($arrSubCurrent[$strParent]);
            $arrSubFieldsExpected = $arrSubExpected[$strParent];

            sort($arrSubFieldsCurrent);
            sort($arrSubFieldsExpected);

            $this->assertEquals($arrSubFieldsExpected, $arrSubFieldsCurrent);
        }
    }

    protected function setUp()
    {
        // reset request parameter bag
        Request::set(new \Symfony\Component\HttpFoundation\Request());
    }
}