<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\FormHybrid\Test;

class TestPostForm extends \HeimrichHannot\FormHybrid\Form
{
    protected $strMethod = FORMHYBRID_METHOD_POST;

    public function __construct($varConfig = null, $intId = 0)
    {
        parent::__construct($varConfig, $intId);
    }

    protected function compile()
    {
        // TODO: Implement compile() method.
    }
}