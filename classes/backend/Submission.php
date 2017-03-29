<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\FormHybrid\Test\Backend;

use HeimrichHannot\Request\Request;

class Submission extends \Backend
{
    public function getRelatedFieldChildOptions(\DataContainer $dc)
    {
        $courtAg = Request::getPost('updateFieldParent');

        if ($courtAg == '' && $dc->activeRecord !== null && $dc->activeRecord->updateFieldParent) {
            $courtAg = $dc->activeRecord->updateFieldParent;
        }

        $arrOptions = array();

        if ($courtAg) {
            if ($courtAg == 'FOO') {
                $arrOptions = array(1 => 'FooOption1', 2 => 'FooOption2');
            } else {
                if ($courtAg == 'BAR') {
                    $arrOptions = array(1 => 'BarOption1', 2 => 'BarOption2');
                }
            }
        } else {
            $arrOptions = array(1 => 'unRelatedOption1', 2 => 'unRelatedOption2');
        }

        return $arrOptions;
    }
}