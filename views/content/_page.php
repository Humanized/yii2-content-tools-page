<?php

use humanized\contenttoolspage\components\editor\ContentTools;
use humanized\contenttoolspage\models\core\ContainerType;
use humanized\contenttoolspage\components\StaticHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/*
  use yii\jui\Sortable;

  $script = <<< JS
  $(function() {
  var component = ContentTools.ComponentUI();
  componenet.addEventListener();

  });
  JS;
  $this->registerJs($script);

  $script = <<< JS
  $(function() {
  $("#sortable-content").sortable();
  });
  JS;
  $this->registerJs($script);
 */

$context = $this->context->id;
$contextParams = ['sector' => $sector->id];



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
echo '<ul id="sortable-content">';
//Load page containers

foreach ($page->containers as $container) {
    echo '<li class="sortable-container" id="container-' . $container->id . '">';
    $is_published = $container->is_published;

    /**
     * Render Static Content
     * 
     * staticConfig Param is required
     */
    if ($container->type->name == ContainerType::STATIC_CONTAINER) {
        $caller = $container->staticContainer->name;
        if ($enableUpdate || $container->is_published) {

            if ($enableUpdate) {
                echo 'Static Container Registered as ' . kartik\helpers\Html::label($caller, ['class' => 'label-primary']) . ' ';
            }
            //Output Static Content when container is published


            if (isset($staticConfig[$caller])) {
                $config = $staticConfig[$caller];
                echo $this->render($config[StaticHelper::VIEW], (isset($config[StaticHelper::VIEWPARAMS])) ? $config[StaticHelper::VIEWPARAMS] : []);
            }
        }
    }

    if ($container->type->name == ContainerType::CONTENT_CONTAINER) {
        if ($enableUpdate) {
            ContentTools::begin(['id' => $container->id]);
        }
        if ($enableUpdate || $is_published) {
            echo '<p>';
            echo $container->contentContainer->data;
            echo '</p>';
        }
        if ($enableUpdate) {
            ContentTools ::end();
        }
    }
    if ($enableUpdate) {
        echo Html::a(Yii::t('app', $is_published ? kartik\helpers\Html::icon('eye-close') : kartik\helpers\Html::icon('eye-open')), [ '/' . $context . '/toggle-publish-container', 'id' => $container->id, 'sector' => $sector->id], [
            'class' => 'btn btn-' . ($is_published ? 'danger' : 'success'),
            'data' => [

                'method' => 'post',
            ],
        ]);
        echo ' ';
        echo Html::a(kartik\helpers\Html::icon('trash'), [ '/' . $context . '/delete-container', 'id' => $container->id, 'sector' => $sector->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this container?'),
                'data-method' => 'post',
            ],
        ]);
    }
    echo '</li>';
}
echo '</ul>';

/*
echo Sortable::widget([
    'items' => [
    ],
    'options' => ['tag' => 'ul'],
    'itemOptions' => ['tag' => 'li'],
    'clientOptions' => ['cursor' => 'move'],
]);
*/