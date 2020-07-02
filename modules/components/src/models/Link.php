<?php

namespace modules\components\models;

use craft\base\Model;
use craft\helpers\Html;

class Link extends Model
{
    public $url;
    public $title;
    public $target;
    public $rel;

    public function render(string $content = '', $options = []): void
    {
        $text = $content;

        if ($text === '') {
            $text = $this->title;
        }

        if ($this->target !== null) {
            $options['target'] = $this->target;
        }

        if ($this->rel !== null) {
            $options['rel'] = $this->rel;
        }

        echo Html::a($text, $this->url, $options);
    }
}
