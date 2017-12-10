<?php

namespace pointdnd\comments;

use Yii;

/**
 * Class Module
 *
 * @package pointdnd\comments
 */
class Module extends \yii\base\Module
{
    /**
     * @var string the class name of the [[identity]] object
     */
    public $userIdentityClass;

    /**
     * @var string the class name of the comment model object, by default its pointdnd\comments\models\CommentModel
     */
    public $commentModelClass = 'pointdnd\comments\models\CommentModel';

    /**
     * @var string the namespace that controller classes are in
     */
    public $controllerNamespace = 'pointdnd\comments\controllers';

    /**
     * @var bool when admin can edit comments on frontend
     */
    public $enableInlineEdit = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (null === $this->userIdentityClass) {
            $this->userIdentityClass = Yii::$app->getUser()->identityClass;
        }
    }
}
