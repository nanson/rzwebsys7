<?php
namespace app\modules\main\widgets\elist;

use common\widgets\App;
use Yii;

/**
 * Class EList
 * Виджет для вывода элементов. Отображаемая сущность должна наследовать \common\db\ActiveRecord
 * @package app\modules\main\widgets\elist
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class EList extends App
{

    /**
     * @var string имя класса модели
     */
    public $modelClass;

    /**
     * @var int количество выводимых элементов
     */

    public $limit = 3;

    /**
     * @var array сортировка элементов
     */

    public $order = ['id' => 'DESC'];

    /**
     * @var callable функция возвращающая url модели. Принимает аргументом модель для которой необходимо создать url
     */
    public $urlCreate;

    /**
     * @var callable функция для модификации запроса. Принимает аргументом \common\db\TActiveQuery
     */
    public $queryModify;

    /**
     * @var array массив атрибутов html тега
     */

    public $options = array();

    /**
     * @var array массив моделей
     */
    public $models;

    /**
     * @var string имя выводимого атрибута
     */
    public $labelAttr = "title";

    /**
     * @var int глубина родительского раздела
     */

    protected $parentLevel;

    /**
     * @inheritdoc
     */

    public function init()
    {

        if (!$this->isShow())
            return false;

        if($this->models === null) {

            $class = $this->modelClass;

            $query = $class::find()->published()->orderBy($this->order)->limit($this->limit);

            if (is_callable($this->queryModify)) {

                $func = $this->queryModify;

                $func($query);

            }

            $this->models = $query->all();

        }
    }

    /**
     * @inheritdoc
     */

    public function run()
    {

        if (!$this->isShow() OR empty($this->models))
            return false;

        return $this->render($this->tpl, [
            "models" => $this->models,
            "options" => $this->options,
            "urlCreate" => $this->urlCreate,
            "labelAttr"=>$this->labelAttr,
        ]);

    }

}