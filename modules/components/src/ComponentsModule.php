<?php

namespace modules\components;

use Craft;
use craft\web\twig\variables\CraftVariable;
use modules\components\web\twig\extensions\ComponentsExtension;
use modules\components\web\twig\variables\ComponentsVariable;
use yii\base\Event;
use yii\base\Module;

class ComponentsModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set web variable for front end templates
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, [$this, 'registerVariableClass']);

        // Add custom twig extensions and filters
        if (Craft::$app->request->getIsSiteRequest()) {
            Craft::$app->getView()->registerTwigExtension(new ComponentsExtension());
        }
    }

    public function registerVariableClass(Event $event)
    {
        // Attach variables class to craft variable
        $event->sender->set('components', ComponentsVariable::class);
    }
}
