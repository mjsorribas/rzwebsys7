<?php
namespace app\modules\main\widgets\menu;

use common\widgets\App;
use app\modules\main\models\Menu AS MenuModel;

/**
 * Class Menu
 * Виджет мен.
 * @package app\modules\main\widgets\menu
 * @author Churkin Anton <webadmin87@gmail.com>
 */

class Menu extends App {

    /**
     * @var int идентификатор родительского пункта меню
     */
    public $parentId;

    /**
     * @var string ссылка родительского пункта, если указана используем как корневой раздел.
     * Поиск по ссылке родитльского пункта осуществляется среди прямых потомков модели найденной по свойству $parentId
     */

    public $parentLink;

    /**
     * @var int уровень вложенности
     */
    public $level=1;

    /**
     * @var array html - атрибуты корневого тега ul меню
     */

    public $options = array();

    /**
     * @var string имя класса активного пункта меню
     */

    public $actClass = "active";

    /**
     * @var Menu[] массив моделей меню
     */

    protected $models = array();

    /**
     * @var int уровень вложенности родительского пункта меню
     */

    protected $parentLevel;

    /**
     * Поиск моделей меню
     * @return bool
     */

    protected function findModels() {

        $parent = MenuModel::find()->published()->where(["id"=>$this->parentId])->one();

        if(!$parent)
            return false;

        if(!empty($this->parentLink)) {

            $parent = $parent->children()->published()->where(["link"=>$this->parentLink])->one();

            if(!$parent)
                return false;

        }

        $this->parentLevel = $parent->level;

        $level = $parent->level + $this->level;

        $this->models = $parent->descendants()->published()->andWhere("level <= :level", [":level"=>$level])->all();

        return true;

    }

    /**
     * @inheritdoc
     * Инициализация
     */

    public function init() {

        if(!$this->isShow())
            return false;

        $this->findModels();

    }

    /**
     * @inheritdoc
     * Запуск виджета
     */

    public function run() {

        if(!$this->isShow() OR empty($this->models))
            return false;

        return $this->render($this->tpl, [
            "models"=>$this->models,
            "parentLevel"=>$this->parentLevel,
            "options"=>$this->options,
            "actClass"=>$this->actClass,
        ]);

    }

}