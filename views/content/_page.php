<?php

use humanized\contenttoolspage\components\editor\ContentTools;
use yii\helpers\Url;
use yii\helpers\Html;

if ($enableUpdate) {
    Yii::$container->set('humanized\contenttoolspage\components\editor\ContentTools', [
        'imagesEngine' => [
            'upload' => Url::to([$context . '/content-tools-image-upload']),
            'rotate' => Url::to([$context . '/content-tools-image-rotate']),
            'insert' => Url::to([$context . '/content-tools-image-insert']),
        ],
        'saveEngine' => [
            'save' => Url::to([$context . '/update']),
        ],
    ]);

    echo '<div class="well">';
    echo '<strong>Page Settings</strong><div class="pull-right">';
    echo Html::a(Yii::t('app', $page->is_published ? 'Unpublish' : 'Publish'), [ '/' . $context . '/toggle-publish-page', 'id' => $page->id, 'context' => $context, 'sector' => $sector->id], [
        'class' => 'btn btn-' . ($page->is_published ? 'danger' : 'success'),
        'data' => [
            'method' => 'post',
        ],
    ]);
    echo ' ';
    echo Html::a(Yii::t('app', 'Add Container'), [ '/' . $context . '/create-container', 'id' => $page->id, 'context' => $context, 'sector' => $sector->id, 'caller' => $caller], [
        'class' => 'btn btn-warning',
        'data' => [

            'method' => 'post',
    ]]);
    echo '</div></div>';
}

//Load page containers

foreach ($page->containers as $container) {


    if ($enableUpdate) {
        ContentTools::begin(['id' => $container->id]);
    }
    $is_published = $container->is_published;
    if ($enableUpdate || $is_published) {

        echo '<p>';
        echo $container->data;
        echo '</p>';
    }
    if ($enableUpdate) {
        ContentTools ::end();
        echo '<br>';
        echo Html::a(Yii::t('app', $is_published ? 'Unpublish' : 'Publish'), [ '/' . $context . '/toggle-publish-container', 'id' => $container->id, 'context' => $context, 'sector' => $sector->id], [
            'class' => 'btn btn-' . ($is_published ? 'danger' : 'success'),
            'data' => [

                'method' => 'post',
            ],
        ]);
        echo ' ';
        echo Html::a(Yii::t('app', 'Delete Container'), [ '/' . $context . '/delete-container', 'id' => $container->id, 'context' => $context, 'sector' => $sector->id], [
            'class' => 'btn btn-danger',
            'data' => [ 'confirm' => Yii::t('app', 'Are you sure you want to delete this container?'),
                'method' => 'post',
            ],
        ]);
    }
}
                