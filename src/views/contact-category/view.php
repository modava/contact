<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\contact\widgets\NavbarWidgets;
use modava\contact\ContactModule;

/* @var $this yii\web\View */
/* @var $model modava\contact\models\ContactCategory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
<div class="container-fluid px-xxl-25 px-xl-10">
    <?= NavbarWidgets::widget(); ?>

    <!-- Title -->
    <div class="hk-pg-header">
        <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                        class="ion ion-md-apps"></span></span><?=Yii::t('backend', 'Chi tiết'); ?>: <?= Html::encode($this->title) ?>
        </h4>
        <p>
            <a class="btn btn-outline-light" href="<?= Url::to(['create']); ?>"
                title="<?= Yii::t('backend', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
            <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
						'id',
						'title',
						'slug',
						'description:html',
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                return Yii::$app->getModule('contact')->params['status'][$model->status];
                            }
                        ],
						'created_at:date',
						'updated_at:date',
                        [
                            'attribute' => 'userCreated.userProfile.fullname',
                            'label' => Yii::t('backend', 'Created By')
                        ],
                        [
                            'attribute' => 'userUpdated.userProfile.fullname',
                            'label' => Yii::t('backend', 'Updated By')
                        ],
                    ],
                ]) ?>
            </section>
        </div>
    </div>
</div>
