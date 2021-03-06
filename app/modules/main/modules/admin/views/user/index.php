<?php

use common\widgets\admin\CrudLinks;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\main\models\User $searchModel
 */

$this->title = \Yii::t($this->context->tFile, 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_filter', ['model' => $searchModel]); ?>

    <hr/>

    <p>
        <?= CrudLinks::widget(["action" => CrudLinks::CRUD_LIST, "model" => $searchModel]) ?>
    </p>

    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, "searchModel" => $searchModel]); ?>


</div>
