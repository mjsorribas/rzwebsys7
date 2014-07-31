<?php
/**
 * @var \app\modules\news\models\News $model модель новостей
 * @var int $detailImageWidth ширина детального изображения
 */
$file = $model->getFirstFile('image');
?>

<h1><?=$model->title?></h1>
<?if($file):?>
    <a rel="news-item" class="photogallery" href="<?=$file->getRelPath()?>">
        <img style="margin: 0 10px 5px 0" src="<?=Yii::$app->resizer->resize($file->getPath(), $detailImageWidth)?>" alt="" align="left" class="img-thumbnail" />
    </a>
<?endif;?>
<p class="date"><?=Yii::$app->formatter->asDate($model->date)?></p>
<?=$model->text?>
<?=\app\modules\main\widgets\gallery\Gallery::widget(["files"=>$model->getFiles('image'), "skipFromStart"=>1, "rel"=>"news-item"]);