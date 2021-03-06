<?php
/**
 * @var \common\components\View $this
 * @var string $id идентификатор виджета
 * @var \yii\data\ActiveDataProvider $dataProvider провайдер данных
 */

use yii\helpers\Html;
use yii\widgets\Pjax;
use common\widgets\ListView;

echo Html::beginTag('div', ['id' => $id]);

Pjax::begin(["id" => "$id-pjax"]);

echo ListView::widget([
	'id' => "$id-list-view",
	'dataProvider' => $dataProvider,
	'itemView' => '_item',
	'options' => [
		'class' => 'list-view reviews-list-view',
	],
	'summary' => '',
	'emptyText' => '',
]);

Pjax::end();

echo Html::endTag('div');