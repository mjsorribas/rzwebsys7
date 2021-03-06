<?php

namespace common\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class HiddenInput
 * Текстовое поле
 * @package common\inputs
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class HiddenInput extends BaseInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr), $this->widgetOptions)->hiddenInput($options);
    }


} 