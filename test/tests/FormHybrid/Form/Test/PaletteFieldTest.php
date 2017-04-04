<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\FormHybrid\Test\Form;

use HeimrichHannot\FormHybrid\FormConfiguration;
use HeimrichHannot\FormHybrid\FormHelper;
use HeimrichHannot\FormHybrid\Test\TestForm;
use HeimrichHannot\FormHybrid\Test\TestPostForm;
use HeimrichHannot\Request\Request;

class PaletteFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 'default' palette relation is active and field external_text is added to palette permanently
     * @test
     */
    public function testPermanentFieldNotInDefaultPaletteAndDefaultPaletteIsActive()
    {
        $varConfig = [
            'formHybridDataContainer'      => 'tl_submission',
            'formHybridAddDefaultValues'   => true,
            'formHybridDefaultValues'      => [
                [
                    'field' => 'typeSelector',
                    'value' => 'default',
                    'label' => '',
                ],
            ],
            'formHybridEditable'           => [
                'typeSelector',
                'gender',
                'firstname',
                'lastname',
                'internal_text',
                'external_text'
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);
        \Controller::loadDataContainer('tl_submission');

        // permanent fields must be part of formHybridEditable to determine order
        $objForm->addEditableField('external_text', $GLOBALS['TL_DCA']['tl_submission']['fields']['external_text'], true);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['typeSelector', 'gender', 'firstname', 'lastname', 'internal_text', 'external_text', 'submit'];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);
    }

    /**
     * type selector field is editable and 'palette1' palette is set active from request and 'default' palette is set to active within formHybridDefault
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndPalette1SubmittedAndOptionNoSelectorExternalSelectedAndExternalTextAddedAsPermanentField()
    {
        $varConfig = [
            'formHybridDataContainer'      => 'tl_submission',
            'formHybridAddDefaultValues'   => true,
            'formHybridDefaultValues'      => [
                [
                    'field' => 'typeSelector',
                    'value' => 'default',
                    'label' => '',
                ],
            ],
            'formHybridEditable'           => [
                'typeSelector',
                'optionSelector',
                'optionSelectorNoDefault',
                'gender',
                'academicTitle',
                'firstname',
                'lastname',
                'internal_text',
            ],
            'formHybridAddPermanentFields' => true,
            'formHybridPermanentFields'    => [
                'external_text',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('typeSelector', 'palette1');
        $objRequest->request->set('optionSelectorNoDefault', 'external');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['typeSelector', 'optionSelector', 'optionSelectorNoDefault', 'gender', 'academicTitle', 'firstname', 'lastname', 'submit'];
        $arrSubExpected = ['optionSelector' => ['internal_text'], 'optionSelectorNoDefault' => ['external_text']]; // optionSelectorNoDefault must be present as external_text is now added as permanent field

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
     * type selector field is editable and 'palette1' palette is set active from request and 'default' palette is set to active within formHybridDefault
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndPalette1SubmittedAndDefaultPalette()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'default',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'typeSelector',
                'optionSelector',
                'optionSelectorNoDefault',
                'gender',
                'academicTitle',
                'firstname',
                'lastname',
                'internal_text',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('typeSelector', 'palette1');
        $objRequest->request->set('optionSelectorNoDefault', 'external');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['typeSelector', 'optionSelector', 'optionSelectorNoDefault', 'gender', 'academicTitle', 'firstname', 'lastname', 'submit'];
        $arrSubExpected = ['optionSelector' => ['internal_text']]; // optionSelectorNoDefault must not be present as external_text is not an editable field

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
     * type selector field is editable and 'palette1' palette is set active from request and optionSelectorNoDefault is submitted with option external
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndPalette1SubmittedAndOptionNoSelectorExternalSelected()
    {
        $varConfig = [
            'formHybridDataContainer' => 'tl_submission',
            'formHybridEditable'      => [
                'typeSelector',
                'optionSelector',
                'optionSelectorNoDefault',
                'gender',
                'academicTitle',
                'firstname',
                'lastname',
                'internal_text',
                'external_text',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('typeSelector', 'palette1');
        $objRequest->request->set('optionSelectorNoDefault', 'external');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['typeSelector', 'optionSelector', 'optionSelectorNoDefault', 'gender', 'academicTitle', 'firstname', 'lastname', 'submit'];
        $arrSubExpected = ['optionSelector' => ['internal_text'], 'optionSelectorNoDefault' => ['external_text']];

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
     * type selector field is editable and 'palette1' palette is set active from request
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndPalette1Submitted()
    {
        $varConfig = [
            'formHybridDataContainer' => 'tl_submission',
            'formHybridEditable'      => [
                'typeSelector',
                'optionSelector',
                'optionSelectorNoDefault', // optionSelectorNoDefault must have no subpalette as it has no default value
                'gender',
                'academicTitle',
                'firstname',
                'lastname',
                'internal_text' // part of optionSelector must be present as subpalette field of optionSelector
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('typeSelector', 'palette1');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['typeSelector', 'optionSelector', 'optionSelectorNoDefault', 'gender', 'academicTitle', 'firstname', 'lastname', 'submit'];
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
     * type selector field is editable and 'default' palette is set active from request
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndDefaultPaletteSubmitted()
    {
        $varConfig = [
            'formHybridDataContainer' => 'tl_submission',
            'formHybridEditable'      => [
                'typeSelector',
                'gender',
                'firstname',
                'lastname',
                'internal_text',
                'optionSelector',  // optionSelector is not available within default palette
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        // set request parameter bag
        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('typeSelector', 'default');

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent  = array_keys($objForm->getActualFields());
        $arrExpected = ['typeSelector', 'gender', 'firstname', 'lastname', 'internal_text', 'submit'];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);
    }

    /**
     * type selector field is editable and  'default' palette is set to active within formHybridDefault
     *
     * @test
     */
    public function testWithTypeSelectorPresentAndDefaultPaletteActive()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'default',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'typeSelector',
                'gender',
                'firstname',
                'lastname',
                'internal_text',
                'optionSelector',  // optionSelector is not available within default palette
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent  = array_keys($objForm->getActualFields());
        $arrExpected = ['typeSelector', 'gender', 'firstname', 'lastname', 'internal_text', 'submit'];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);
    }

    /**
     * 'default' palette is set to active within formHybridDefault
     *
     * @test
     */
    public function testWithDefaultPaletteActive()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'default',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'gender',
                'firstname',
                'lastname',
                'subpaletteSelector', // subpaletteSelector is not available within `default` palette, must be removed
                'internal_text', // internal_text is rendered within parent palette, cause no subpalette with internal_text field is present in `default` palette
                'optionSelector', // optionSelector is not available within `default` palette and must be removed, internal_text is rendered within parent palette
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent  = array_keys($objForm->getActualFields());
        $arrExpected = ['gender', 'firstname', 'lastname', 'internal_text', 'submit'];

        sort($arrCurrent);
        sort($arrExpected);

        $this->assertEquals($arrExpected, $arrCurrent);
    }

    /**
     *  no active type selector, access to all fields, also subpalette fields that are present in palette
     *
     * @test
     */
    public function testWithoutActivePalette()
    {
        $varConfig = [
            'formHybridDataContainer' => 'tl_submission',
            'formHybridEditable'      => [
                'gender',
                'firstname',
                'lastname',
                'subpaletteSelector',
                'internal_text', // as long as optionSelector is present as editable field, internal_text is moved to the optionSelector_internal (default: internal) subpalette
                'optionSelector',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $objForm = new TestPostForm($objConfig);

        $objForm->generate();

        $arrCurrent     = array_keys($objForm->getActualFields());
        $arrExpected    = ['gender', 'firstname', 'lastname', 'subpaletteSelector', 'optionSelector', 'submit'];
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

    protected function setUp()
    {
        // reset request parameter bag
        Request::set(new \Symfony\Component\HttpFoundation\Request());
        \Input::resetCache(); // reset input cache
    }

    protected function tearDown()
    {
        \Database::getInstance()->execute('DELETE FROM tl_submission WHERE tstamp = 0');
    }

    protected function onNotSuccessfulTest(\Exception $e)
    {
        \Database::getInstance()->execute('DELETE FROM tl_submission WHERE tstamp = 0');

        throw $e;
    }
}
