<?php

namespace app\gii\generators\appmodule;

use yii\gii\CodeFile;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;

/**
 * Данный генератор генерит скелет модуля приложения. В дополнение к стандартному функционалу
 * генерит вложенный административный модуль, файл настроек и файл перевода.
 *
 * @property string $controllerNamespace пространство имен контроллеров модуля
 * @property string $adminControllerNamespace пространство имен контроллеров административного модуля
 * @property boolean $modulePath директория содержащая класс модуля
 * @property string $moduleNs пространство имен модуля
 * @property string $adminModuleNs пространство имен административного модуля
 * @property string $adminModuleClass класс административного модуля
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Churkin Anton <webadmin87@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{

    public $moduleClass;
    public $moduleID;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'App module Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a CMS module.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['moduleID', 'moduleClass'], 'filter', 'filter' => 'trim'],
            [['moduleID', 'moduleClass'], 'required'],
            [['moduleID'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['moduleClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['moduleClass'], 'validateModuleClass'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'moduleID' => 'Module ID',
            'moduleClass' => 'Module Class',
        ];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'moduleID' => 'This refers to the ID of the module, e.g., <code>admin</code>.',
            'moduleClass' => 'This is the fully qualified class name of the module, e.g., <code>app\modules\admin\Module</code>.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        if (Yii::$app->hasModule($this->moduleID)) {
            $link = Html::a('try it now', Yii::$app->getUrlManager()->createUrl($this->moduleID), ['target' => '_blank']);

            return "The module has been generated successfully. You may $link.";
        }

        $output = <<<EOD
<p>The module has been generated successfully.</p>
<p>To access the module, you need to add this to your application configuration:</p>
EOD;
        $code = <<<EOD
<?php
    ......
    'modules' => [
        '{$this->moduleID}' => [
            'class' => '{$this->moduleClass}',
        ],
    ],
    ......
EOD;

        return $output . '<pre>' . highlight_string($code, true) . '</pre>';
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['module.php', 'controller.php', 'view.php', 'config.php', 'messages.php', 'admin-module.php'];
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $modulePath = $this->getModulePath();
        $files[] = new CodeFile(
            $modulePath . '/' . StringHelper::basename($this->moduleClass) . '.php',
            $this->render("module.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/controllers/DefaultController.php',
            $this->render("controller.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/views/default/index.php',
            $this->render("view.php")
        );
		$files[] = new CodeFile(
			$modulePath . '/config/main.php',
			$this->render("config.php")
		);
		$files[] = new CodeFile(
			$modulePath . '/messages/ru/app.php',
			$this->render("messages.php")
		);
		$files[] = new CodeFile(
			$modulePath . '/modules/admin/Admin.php',
			$this->render("admin-module.php")
		);
        return $files;
    }

    /**
     * Validates [[moduleClass]] to make sure it is a fully qualified class name.
     */
    public function validateModuleClass()
    {
        if (strpos($this->moduleClass, '\\') === false || Yii::getAlias('@' . str_replace('\\', '/', $this->moduleClass), false) === false) {
            $this->addError('moduleClass', 'Module class must be properly namespaced.');
        }
        if (substr($this->moduleClass, -1, 1) == '\\') {
            $this->addError('moduleClass', 'Module class name must not be empty. Please enter a fully qualified class name. e.g. "app\\modules\\admin\\Module".');
        }
    }

    /**
     * @return boolean the directory that contains the module class
     */
    public function getModulePath()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\'))));
    }

    /**
     * @return string the controller namespace of the module.
     */
    public function getControllerNamespace()
    {
        return substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\')) . '\controllers';
    }

	/**
	 * @return string module namespace
	 */
	public function getModuleNs()
	{
		$pos = strrpos($this->moduleClass, '\\');
		$ns = ltrim(substr($this->moduleClass, 0, $pos), '\\');
		return $ns;
	}

	/**
	 * @return string admin module namespace
	 */
	public function getAdminModuleNs()
	{
		return $this->getModuleNs() . '\\modules\\admin';
	}


	/**
	 * @return string admin module class
	 */
	public function getAdminModuleClass()
	{
		return $this->getAdminModuleNs() . "\\Admin";
	}

	/**
	 * Returns class name without namespace
	 * @param string $class classname with namespace
	 * @return string
	 */
	public function getClassName($class) {
		$pos = strrpos($class, '\\');
		return substr($class, $pos + 1);
	}

	/**
	 * @return string admin module controllers namespace
	 */
	public function getAdminControllerNamespace() {
		return $this->getAdminModuleNs() . "\\controllers";
	}

}
