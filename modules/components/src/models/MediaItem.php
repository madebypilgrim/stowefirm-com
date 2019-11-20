<?php

namespace modules\components\models;

use craft\base\Model;
use craft\helpers\Html;

class MediaItem extends Model
{
    public $imageSrc;
    public $videoSrc;
    public $description;

    public function getImage(): void
    {
        echo Html::img($this->imageSrc, [
            'alt' => $this->description,
        ]);
    }

    public function getThumbnail(): void
    {
        echo Html::img($this->imageSrc, [
            'alt' => $this->description,
        ]);
    }

    public function getLink(string $content): void
    {
        $text = $content;

        if ($text === '') {
            $text = $this->description;
        }

        echo Html::a($text, $this->videoSrc, [
            'target' => '_blank',
            'rel' => 'noopener noreferrer',
        ]);
    }
}
