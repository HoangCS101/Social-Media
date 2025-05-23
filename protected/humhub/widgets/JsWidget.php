<?php

namespace humhub\widgets;

use humhub\components\Widget;
use humhub\libs\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Description of JsWidget
 *
 * @author buddha
 * @since 1.2
 */
class JsWidget extends Widget
{
    /**
     * Defines the select input field id
     *
     * @var string
     */
    public $id;

    /**
     * Js Widget namespace
     * @var string
     */
    public $jsWidget;

    /*
     * Used to overwrite select input field attributes. This array can be used for overwriting
     * texts, or other picker settings.
     *
     * @var []
     */
    public $options = [];

    /**
     * Event action handler.
     * @var []
     */
    public $events = [];

    /**
     * Auto init flag.
     * @var mixed
     */
    public $init = false;

    /**
     * Used to hide/show the actual input element.
     * @var bool
     */
    public $visible = true;

    /**
     * @var string html container element.
     */
    public $container = 'div';

    /**
     * If set to true or 'fast', 'slow' or a integer duration in milliseconds the jsWidget will fade in the root element after initialization.
     * This can be handy for widgets which need some time to initialize.
     *
     * @var bool|string|int
     * @since 1.2.2
     */
    public $fadeIn = false;

    /**
     * @var string html content.
     */
    public $content;

    /**
     * Default implementation of JsWidget.
     * This will render a widget html element specified by $container and $content and the given $options/$event attributes.
     * This function should be overwritten for widgets with a more complex rendering.
     *
     * @return string
     */
    public function run()
    {
        return Html::tag($this->container, $this->content, $this->getOptions());
    }

    /**
     * Assembles all widget attributes and data settings of this widget.
     * Those attributes/options are are normally transfered to the js client by ordinary html attributes
     * or by using data-* attributes.
     *
     * @return array
     */
    protected function getOptions()
    {
        $attributes = $this->getAttributes();
        $attributes['data'] = $this->getData();
        $attributes['id'] = $this->getId();

        $this->setDefaultOptions();

        $result = ArrayHelper::merge($attributes, $this->options);

        if (!$this->visible) {
            Html::addCssStyle($result, 'display:none');
        }

        return $result;
    }

    /**
     * Sets some default data options required by all widgets as the widget implementation
     * and the widget events and initialization trigger.
     */
    public function setDefaultOptions()
    {
        // Set event data
        foreach ($this->events as $event => $handler) {
            $this->options['data']['widget-action-' . $event] = $handler;
        }

        if ($this->jsWidget) {
            $this->options['data']['ui-widget'] = $this->jsWidget;
        }

        if ($this->fadeIn) {
            $fadeIn = $this->fadeIn === true ? 'fast' : $this->fadeIn;
            $this->options['data']['widget-fade-in'] = $fadeIn;
            $this->visible = false;
        }

        if (!empty($this->init)) {
            $this->options['data']['ui-init'] = $this->init;
        }

        if ($this instanceof Reloadable) {
            $reloadUrl = $this->getReloadUrl();

            if (is_array($reloadUrl)) {
                $reloadUrl['reload'] = true;
            }

            $this->options['data']['widget-reload-url'] = is_array($reloadUrl) ? Url::toRoute($reloadUrl) : $reloadUrl;
        }
    }

    /**
     * Returns the html id of this widget, if no id is set this function will generate
     * an id if $autoGenerate is set to true (default).
     *
     * Note that the id is automatically included within the <code>getOptions()<code> function.
     *
     * @param bool $autoGenerate
     * @return string
     */
    public function getId($autoGenerate = true)
    {
        if ($this->id) {
            return $this->id;
        }

        return $this->id = parent::getId($autoGenerate);
    }

    /**
     * Returns an array of data-* attributes to configure your clientside js widget.
     * Note that this function does not require to add the data- prefix. This will be done by Yii.
     *
     * The data-* attributes should be inserted to the widgets root element.
     *
     * @return []
     */
    protected function getData()
    {
        return [];
    }

    /**
     * Returns all html attributes for used by this widget and will normally inserted in the widgets root html element.
     * @return []
     */
    protected function getAttributes()
    {
        return [];
    }
}
