<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Test
	'HeimrichHannot\FormHybrid\Test\Form\ToggleSubPaletteAjaxTest' => 'system/modules/formhybrid_tests/test/tests/FormHybrid/Form/Test/ToggleSubPaletteAjaxTest.php',
	'HeimrichHannot\FormHybrid\Test\Form\PaletteFieldTest'         => 'system/modules/formhybrid_tests/test/tests/FormHybrid/Form/Test/PaletteFieldTest.php',
	'HeimrichHannot\FormHybrid\Test\Form\SubmissionTest'           => 'system/modules/formhybrid_tests/test/tests/FormHybrid/Form/Test/SubmissionTest.php',
	'HeimrichHannot\FormHybrid\Test\Form\ToggleSubPaletteTest'     => 'system/modules/formhybrid_tests/test/tests/FormHybrid/Form/Test/ToggleSubPaletteTest.php',

	// Classes
	'HeimrichHannot\FormHybrid\Test\Backend\Submission'            => 'system/modules/formhybrid_tests/classes/backend/Submission.php',
	'HeimrichHannot\FormHybrid\Test\TestPostForm'                  => 'system/modules/formhybrid_tests/classes/TestPostForm.php',
));
