<?php

namespace pointdnd\comments\traits;

use Yii;
use pointdnd\comments\Module;

/**
 * Class ModuleTrait
 *
 * @package pointdnd\comments\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('comment');
    }
}
