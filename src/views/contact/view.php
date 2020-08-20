<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\contact\widgets\NavbarWidgets;
use modava\contact\ContactModule;

/* @var $this yii\web\View */
/* @var $model modava\contact\models\Contact */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => ContactModule::t('contact', 'Contacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
<div class="container-fluid px-xxl-25 px-xl-10">
    <?= NavbarWidgets::widget(); ?>

    <!-- Title -->
    <div class="hk-pg-header">
        <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                        class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
        </h4>
        <p>
            <a class="btn btn-outline-light" href="<?= Url::to(['create']); ?>"
                title="<?= ContactModule::t('contact', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= ContactModule::t('contact', 'Create'); ?></a>
            <?= Html::a(ContactModule::t('contact', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(ContactModule::t('contact', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => ContactModule::t('contact', 'Are you sure you want to delete this item?'),
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
						'fullname',
						'phone',
						'email:email',
						'address',
						'title',
						'content:html',
						'ip_address',
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                return Yii::$app->getModule('contact')->params['status'][$model->status];
                            }
                        ],
						'created_at:datetime',x
                    ],
                ]) ?>
            </section>
        </div>
    </div>
</div>
