<?php

use humanized\contenttoolspage\components\editor\ContentTools;
use humanized\contenttoolspage\models\core\ContainerType;
use humanized\contenttoolspage\components\StaticHelper;
use yii\helpers\Url;
use yii\helpers\Html;

$context = $this->context->id;
$contextParams = ['sector' => $sector->id];


echo "<b> Orphan Page: </b>";
\yii\helpers\VarDumper::dump($page->isOrphan());


if ($enableUpdate) {
    echo '<div class="well">';
    echo '<strong>Page Settings</strong><div class="pull-right">';
    echo Html::a(Yii::t('app', $page->is_published ? 'Unpublish' : 'Publish'), array_merge([ '/' . $context . '/toggle-publish-page', 'id' => $page->id], $contextParams), [
        'class' => 'btn btn-' . ($page->is_published ? 'danger' : 'success'),
        'data' => [
            'method' => 'post',
        ],
    ]);
    echo ' ';
    echo Html::a(Yii::t('app', 'Add Content Container'), [ '/' . $context . '/create-container', 'id' => $page->id, 'sector' => $sector->id, 'caller' => $caller, 'type' => ContainerType::getIdByName(ContainerType::CONTENT_CONTAINER)], [
        'class' => 'btn btn-warning',
        'data' => [
            'method' => 'post',
    ]]);
    echo ' ';
    echo Html::a(Yii::t('app', 'Add Static Container'), [ '/' . $context . '/create-container', 'id' => $page->id, 'sector' => $sector->id, 'caller' => $caller, 'type' => ContainerType::getIdByName(ContainerType::STATIC_CONTAINER)], [
        'class' => 'btn btn-danger',
        'data' => [
            'method' => 'post',
    ]]);
    echo '</div></div>';
}

//Load page containers

foreach ($page->containers as $container) {


    if ($container->type->name == ContainerType::STATIC_CONTAINER) {
        $caller = $container->staticContainer->name;
        if (isset($staticConfig[$caller])) {
            $config = $staticConfig[$caller];
            echo '<div id="static-' . $caller . '">';
            echo $this->render($config[StaticHelper::VIEW], (isset($config[StaticHelper::VIEWPARAMS])) ? $config[StaticHelper::VIEWPARAMS] : []);
            echo '</div>';
        }
    }




    if ($container->type->name == ContainerType::CONTENT_CONTAINER) {

        if ($enableUpdate) {
            ContentTools::begin(['id' => $container->id]);
        }
        $is_published = $container->is_published;
        if ($enableUpdate || $is_published) {

            echo '<p>';
            echo $container->contentContainer->data;
            echo '</p>';
        }
        if ($enableUpdate) {
            ContentTools ::end();
            echo '<br>';
            echo Html::a(Yii::t('app', $is_published ? 'Unpublish' : 'Publish'), [ '/' . $context . '/toggle-publish-container', 'id' => $container->id, 'sector' => $sector->id], [
                'class' => 'btn btn-' . ($is_published ? 'danger' : 'success'),
                'data' => [

                    'method' => 'post',
                ],
            ]);
            echo ' ';
            echo Html::a(Yii::t('app', 'Delete Container'), [ '/' . $context . '/delete-container', 'id' => $container->id, 'sector' => $sector->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this container?'),
                    'data-method' => 'post                ',
                ],
            ]);
        }
    }
}

                