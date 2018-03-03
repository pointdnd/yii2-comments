<?php

use yii2mod\editable\Editable;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View */
/* @var $model \pointdnd\comments\models\CommentModel */
/* @var $maxLevel null|integer comments max level */
?>
<li class="comment" id="comment-<?php echo $model->id; ?>">
    <div class="comment-content" data-comment-content-id="<?php echo $model->id; ?>">
        <div class="comment-author-avatar">
            <?php echo Html::img($model->getAvatar(), ['alt' => $model->getAuthorName()]); ?>
        </div>
        <div class="comment-details">
            <div class="comment-action-buttons">
                <?php if (Yii::$app->getUser()->can('admin')) : ?>
                    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('yii2mod.comments', 'Delete'), '#', ['class' => 'delete-comment-btn', 'data' => ['action' => 'delete', 'url' => Url::to(['/comment/default/delete', 'id' => $model->id]), 'comment-id' => $model->id]]); ?>
                <?php endif; ?>
                <?php if (!Yii::$app->user->isGuest && ($model->level < $maxLevel || is_null($maxLevel))) : ?>
                    <?php echo Html::a("<span class='glyphicon glyphicon-share-alt'></span> " . Yii::t('yii2mod.comments', 'Reply'), '#', ['class' => 'reply-comment-btn', 'data' => ['action' => 'reply', 'comment-id' => $model->id]]); ?>
                <?php endif; ?>
            </div>
            <div class="comment-author-name">
                <span><?php echo $model->getAuthorName(); ?></span>
                <?php
                echo \yii2mod\rating\StarRating::widget([
                    'name' => $model->id,
                    'value' => $model->rate,

                ]);
                ?>
                <?php echo Html::a($model->getPostedDate(), $model->getAnchorUrl(), ['class' => 'comment-date']); ?>
            </div>
            <div class="comment-body">
                <?php if (Yii::$app->getModule('comment')->enableInlineEdit && Yii::$app->getUser()->can('admin')): ?>
                    <?php echo Editable::widget([
                        'model' => $model,
                        'attribute' => 'content',
                        'url' => Url::to(['/comment/default/quick-edit']),
                        'options' => [
                            'id' => 'editable-comment-' . $model->id,
                        ],
                    ]); ?>
                <?php else: ?>
                    <?php echo $model->getContent(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php if ($model->hasChildren()) : ?>
    <ul class="children">
        <?php foreach ($model->getChildren() as $children) : ?>
            <?php echo $this->render('_list', ['model' => $children, 'maxLevel' => $maxLevel]); ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
