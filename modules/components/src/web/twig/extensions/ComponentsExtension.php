<?php

namespace modules\components\web\twig\extensions;

use craft\helpers\StringHelper;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class ComponentsExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'Components Twig Extension';
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('annotate', [$this, 'annotate'], [
                'is_safe' => 'html',
            ]),
            new TwigFilter('exceprt', [$this, 'exceprt']),
            new TwigFilter('emphasize', [$this, 'emphasize'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function annotate(?string $content): Markup
    {
        return new Markup(
            preg_replace('/(\[(\d+)\])/', '<a href="#footnote-$2" class="footnote">[$2]</a>', $content),
            StringHelper::UTF8
        );
    }

    public function exceprt(?string $content, int $len = 255, $append = '...'): string
    {
        if ($content === null) {
            return '';
        }

        return StringHelper::safeTruncate(StringHelper::stripHtml($content), $len, $append);
    }

    public function emphasize(?string $content = ''): Markup
    {
        return new Markup(
            preg_replace(
                // theme color, italics, bold, superscript, subscript
                ['/\*(.*)\*/', '/\~(.*)\~/', '/(\*\*)(.*)(\*\*)/', '/\^(.*)\^/', '/\_(.*)\_/'],
                ['<i>$1</i>', '<em>$1</em>', '<strong>$2</strong>', '<sup>$1</sup>', '<sub>$1</sub>'],
                $content
            ),
            StringHelper::UTF8
        );
    }
}
