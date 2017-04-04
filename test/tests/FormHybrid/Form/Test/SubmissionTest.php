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

use Contao\Model\Registry;
use HeimrichHannot\EventRegistration\SubmissionModel;
use HeimrichHannot\FormHybrid\FormConfiguration;
use HeimrichHannot\FormHybrid\FormHelper;
use HeimrichHannot\FormHybrid\Test\TestPostForm;
use HeimrichHannot\Haste\Model\Model;
use HeimrichHannot\Request\Request;

class SubmissionTest extends \PHPUnit_Framework_TestCase
{
    public static $intSubmissionId = 3294967295;

    /**
     * @var SubmissionModel
     */
    public $objSubmission = null;

    /**
     * @test
     */
    public function testAdvancedXSSInputSubmission()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette2',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'testText',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $this->objSubmission               = Model::setDefaultsFromDca(new SubmissionModel());
        $this->objSubmission->id           = static::$intSubmissionId;
        $this->objSubmission->typeSelector = 'palette2';
        $this->objSubmission->save();

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id, $this->objSubmission->id));

        Request::set($objRequest);

        Request::setPost('testText', '<IMG SRC="javascript:alert(\'XSS\')"');

        $objForm = new TestPostForm($objConfig, $this->objSubmission->id);
        \Controller::loadDataContainer('tl_submission');

        $objForm->setReset(false);
        $objForm->generate();

        $objModel = $objForm->activeRecord;

        $this->assertNull($objModel->testText);
    }

    /**
     * @test
     */
    public function testXSSInputSubmission()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette2',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'testText',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $this->objSubmission               = Model::setDefaultsFromDca(new SubmissionModel());
        $this->objSubmission->id           = static::$intSubmissionId;
        $this->objSubmission->typeSelector = 'palette2';
        $this->objSubmission->save();

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id, $this->objSubmission->id));

        Request::set($objRequest);

        Request::setPost('testText', '<script>alert(\'xss\')</script>');

        $objForm = new TestPostForm($objConfig, $this->objSubmission->id);
        \Controller::loadDataContainer('tl_submission');

        $objForm->setReset(false);
        $objForm->generate();

        $objModel = $objForm->activeRecord;

        // xss inputs should be stored unescaped within db
        $this->assertSame('<script>alert(\'xss\')</script>', $objModel->testText);

        // xss and invalid tags must be escaped for presentation
        $this->assertSame(
            '&#60;script&#62;alert(\'xss\')&#60;/script&#62;',
            FormHelper::escapeAllEntities('tl_submission', 'testText', $objModel->testText)
        );
    }

    /**
     * @test
     */
    public function testAdvancedHtmlInputSubmission()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette2',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'testText',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $this->objSubmission               = Model::setDefaultsFromDca(new SubmissionModel());
        $this->objSubmission->id           = static::$intSubmissionId;
        $this->objSubmission->typeSelector = 'palette2';
        $this->objSubmission->save();

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id, $this->objSubmission->id));

        Request::set($objRequest);

        Request::setPost('testText', '<p><span SRC="javascript:alert(\'XSS\')"><b>Test <a href="http://example.org" onclick="alert(\'xss\')">Link</a></b></span></p>');

        $objForm = new TestPostForm($objConfig, $this->objSubmission->id);
        \Controller::loadDataContainer('tl_submission');

        $objForm->setReset(false);
        $objForm->generate();

        $objModel = $objForm->activeRecord;

        $this->assertSame('<p><span src="alert(\'XSS\')"><b>Test Link</b></span></p>', $objModel->testText);

        // xss and invalid tags must be escaped for presentation
        $this->assertSame(
            '<p><span src="alert(\'XSS\')">&#60;b&#62;Test Link&#60;/b&#62;</span></p>',
            FormHelper::escapeAllEntities('tl_submission', 'testText', $objModel->testText)
        );
    }

    /**
     * @test
     */
    public function testInvalidHtmlInputSubmission()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette2',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'testText',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $this->objSubmission               = Model::setDefaultsFromDca(new SubmissionModel());
        $this->objSubmission->id           = static::$intSubmissionId;
        $this->objSubmission->typeSelector = 'palette2';
        $this->objSubmission->save();

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id, $this->objSubmission->id));

        Request::set($objRequest);

        Request::setPost('testText', '<p><p>Test</p></p><script>alert(\'XSS\')<script>');

        $objForm = new TestPostForm($objConfig, $this->objSubmission->id);
        \Controller::loadDataContainer('tl_submission');

        $objForm->setReset(false);
        $objForm->generate();

        $objModel = $objForm->activeRecord;

        // value must be saved unescaped
        $this->assertSame('<p></p><p>Test</p><script>alert(\'XSS\')<script></script>', $objModel->testText);

        // xss and invalid tags must be escaped for presentation
        $this->assertSame(
            '<p></p><p>Test</p>&#60;script&#62;alert(\'XSS\')&#60;script&#62;&#60;/script&#62;',
            FormHelper::escapeAllEntities('tl_submission', 'testText', $objModel->testText)
        );
    }

    /**
     * @test
     */
    public function testHtmlInputSubmission()
    {
        $varConfig = [
            'formHybridDataContainer'    => 'tl_submission',
            'formHybridAddDefaultValues' => true,
            'formHybridDefaultValues'    => [
                [
                    'field' => 'typeSelector',
                    'value' => 'palette2',
                    'label' => '',
                ],
            ],
            'formHybridEditable'         => [
                'testText',
            ],
        ];

        $objModule     = new \ModuleModel();
        $objModule->id = 999999999;

        $objConfig = new FormConfiguration($varConfig);
        $objConfig->setModule($objModule);

        $this->objSubmission               = Model::setDefaultsFromDca(new SubmissionModel());
        $this->objSubmission->id           = static::$intSubmissionId;
        $this->objSubmission->typeSelector = 'palette2';
        $this->objSubmission->save();

        $objRequest = \Symfony\Component\HttpFoundation\Request::create('http://localhost', 'post');
        $objRequest->request->set('FORM_SUBMIT', FormHelper::getFormId('tl_submission', $objModule->id, $this->objSubmission->id));

        Request::set($objRequest);

        Request::setPost('testText', '<p>Test</p>');

        $objForm = new TestPostForm($objConfig, $this->objSubmission->id);
        \Controller::loadDataContainer('tl_submission');

        $this->assertSame('&#60;p&#62;Test&#60;/p&#62;', Request::getPost('testText'));

        $objForm->setReset(false);
        $objForm->generate();

        $objModel = $objForm->activeRecord;

        $this->assertSame('<p>Test</p>', $objModel->testText);

        // xss and invalid tags must be escaped for presentation
        $this->assertSame(
            '<p>Test</p>',
            FormHelper::escapeAllEntities('tl_submission', 'testText', $objModel->testText)
        );
    }

    protected function setUp()
    {
        // reset request parameter bag
        Request::set(new \Symfony\Component\HttpFoundation\Request());
        \Input::resetCache(); // reset input cache
        \Database::getInstance()->prepare('DELETE  FROM tl_submission WHERE id = ?')->execute(static::$intSubmissionId);
        \Database::getInstance()->execute('ALTER TABLE tl_submission AUTO_INCREMENT=1');
    }

    protected function tearDown()
    {
        if ($this->objSubmission instanceof \Model)
        {
            Registry::getInstance()->unregister($this->objSubmission);
        }
        \Database::getInstance()->prepare('DELETE FROM tl_submission WHERE id = ?')->execute(static::$intSubmissionId);
        \Database::getInstance()->execute('ALTER TABLE tl_submission AUTO_INCREMENT=1');
    }

    protected function onNotSuccessfulTest(\Exception $e)
    {
        if ($this->objSubmission instanceof \Model)
        {
            Registry::getInstance()->unregister($this->objSubmission);
        }
        \Database::getInstance()->prepare('DELETE FROM tl_submission WHERE id = ?')->execute(static::$intSubmissionId);
        \Database::getInstance()->execute('ALTER TABLE tl_submission AUTO_INCREMENT=1');

        throw $e;
    }
}