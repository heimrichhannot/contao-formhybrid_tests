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

class ToggleSubPaletteAjaxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * toggle `optionSelector` subpalette and hide subpalette fields
     *
     * @test
     */
    public function toggleConcatenatedTypeSelectorOptionSelectorExternalAndHideSubPaletteFields()
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
                'external_text',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost' . AjaxAction::generateUrl(Form::FORMHYBRID_NAME, 'toggleSubpalette'), 'post');

        $objRequest->headers->set('X-Requested-With', 'XMLHttpRequest'); // xhr request
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('optionSelector', 'external');
        $objRequest->request->set('subId', 'sub_optionSelector');
        $objRequest->request->set('subField', 'optionSelector');
        $objRequest->request->set('subLoad', 0);

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        try
        {
            new TestPostForm($objConfig);
            // unreachable code: if no exception is thrown after form was created, something went wrong
            $this->expectException(\HeimrichHannot\Ajax\Exception\AjaxExitException::class);
        } catch (AjaxExitException $e)
        {
            $objJson = json_decode($e->getMessage());

            $this->assertNull($objJson->result);
        }
    }

    /**
     * toggle `optionSelector` subpalette and show subpalette fields
     *
     * @test
     */
    public function toggleConcatenatedTypeSelectorOptionSelectorExternalAndShowSubPaletteFields()
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
                'external_text',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost' . AjaxAction::generateUrl(Form::FORMHYBRID_NAME, 'toggleSubpalette'), 'post');

        $objRequest->headers->set('X-Requested-With', 'XMLHttpRequest'); // xhr request
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('optionSelector', 'external');
        $objRequest->request->set('subId', 'sub_optionSelector');
        $objRequest->request->set('subField', 'optionSelector');
        $objRequest->request->set('subLoad', 1);

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        try
        {
            new TestPostForm($objConfig);
            // unreachable code: if no exception is thrown after form was created, something went wrong
            $this->expectException(\HeimrichHannot\Ajax\Exception\AjaxExitException::class);
        } catch (AjaxExitException $e)
        {
            $objJson = json_decode($e->getMessage());

            $this->assertTrue(strpos($objJson->result->html, 'id="sub_optionSelector"') > 0); // check that subpalette wrapper is present
            $this->assertTrue(strpos($objJson->result->html, '<input type="text" name="external_text"') > 0); // check that external_text is present
            $this->assertFalse(strpos($objJson->result->html, '<input type="text" name="external_text2"') > 0); // check that external_text is not present
        }
    }


    /**
     * toggle `subpaletteSelector` subpalette and hide subpalette fields
     *
     * @test
     */
    public function toggleCheckBoxSubPaletteSelectorAndHideSubPaletteFields()
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

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost' . AjaxAction::generateUrl(Form::FORMHYBRID_NAME, 'toggleSubpalette'), 'post');

        $objRequest->headers->set('X-Requested-With', 'XMLHttpRequest'); // xhr request
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('optionSelectorNoDefault', 'external');
        $objRequest->request->set('subId', 'sub_subpaletteSelector');
        $objRequest->request->set('subField', 'subpaletteSelector');
        $objRequest->request->set('subLoad', 0);

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        try
        {
            new TestPostForm($objConfig);
            // unreachable code: if no exception is thrown after form was created, something went wrong
            $this->expectException(\HeimrichHannot\Ajax\Exception\AjaxExitException::class);
        } catch (AjaxExitException $e)
        {
            $objJson = json_decode($e->getMessage());

            $this->assertNull($objJson->result);
        }
    }

    /**
     * toggle `subpaletteSelector` subpalette and show subpalette fields
     *
     * @test
     */
    public function toggleCheckBoxSubPaletteSelectorAndShowSubPaletteFields()
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

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost' . AjaxAction::generateUrl(Form::FORMHYBRID_NAME, 'toggleSubpalette'), 'post');

        $objRequest->headers->set('X-Requested-With', 'XMLHttpRequest'); // xhr request
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id));
        $objRequest->request->set('optionSelectorNoDefault', 'external');
        $objRequest->request->set('subId', 'sub_subpaletteSelector');
        $objRequest->request->set('subField', 'subpaletteSelector');
        $objRequest->request->set('subLoad', 1);

        Request::set($objRequest);

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        try
        {
            new TestPostForm($objConfig);
            // unreachable code: if no exception is thrown after form was created, something went wrong
            $this->expectException(\HeimrichHannot\Ajax\Exception\AjaxExitException::class);
        } catch (AjaxExitException $e)
        {
            $objJson = json_decode($e->getMessage());

            $this->assertTrue(strpos($objJson->result->html, 'id="sub_subpaletteSelector"') > 0); // check that subpalette wrapper is present
            $this->assertTrue(strpos($objJson->result->html, '<input type="text" name="subpaletteField1"') > 0); // check that subpaletteField1 is present
        }
    }

    protected function setUp()
    {
        // reset request parameter bag
        Request::set(new \Symfony\Component\HttpFoundation\Request());
    }
}