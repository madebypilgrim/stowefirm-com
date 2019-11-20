<?php

namespace modules\components\web\twig\variables;

use Craft;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\elements\MatrixBlock;
use modules\components\models\Link;
use modules\components\models\MediaItem;
use verbb\supertable\elements\SuperTableBlockElement;

class ComponentsVariable
{
    /**
     * Helper function to normalize links from CMS as SuperTable or Matrix
     * into a single model for component templates to reference.
     *
     * @param MatrixBlock|SuperTableBlockElement $cta
     */
    public function getLinkFromCta($cta = null): ?Link
    {
        if ($cta === null) {
            return null;
        }

        $title = $cta->ctaTitle;

        // Internal CMS page
        if (isset($cta->ctaPage)) {
            $page = method_exists($cta->ctaPage, 'one')
                ? $cta->ctaPage->one()
                : $cta->ctaPage[0] ?? null;

            if ($page !== null) {
                return new Link([
                    'title' => $title,
                    'url' => $page->url,
                ]);
            }
        }

        // External link
        if (isset($cta->ctaUrl) && $url = $cta->ctaUrl) {
            return new Link([
                'title' => $title,
                'url' => $url,
                'target' => '_blank',
                'rel' => 'noopener',
            ]);
        }

        // Anchor link
        if (isset($cta->ctaAnchor) && $anchor = $cta->ctaAnchor) {
            return new Link([
                'title' => $title,
                'url' => sprintf('%s#%s', Craft::$app->getRequest()->url, $anchor),
            ]);
        }

        // TODO: Mail Link
        if (isset($cta->ctaEmail) && $address = $cta->ctaEmail) {
            return new Link([
                'title' => $title,
                'url' => sprintf('mailto:%s', $address),
                'target' => '_blank',
                'rel' => 'noopener',
            ]);
        }

        // Asset download link
        if (isset($cta->ctaDownload)) {
            $asset = method_exists($cta->ctaDownload, 'one')
                ? $cta->ctaDownload->one()
                : $cta->ctaDownload[0] ?? null;

            return new Link([
                'title' => $title,
                'url' => $asset->url ?? '',
                'target' => '_blank',
                'rel' => 'noopener',
            ]);
        }

        return null;
    }

    /**
     * Helper function to normalize links from CMS as SuperTable or Matrix
     * to using a CTA field naming convention that will generate link.
     *
     * @param MatrixBlock|SuperTableBlockElement $cta
     * @param null|mixed                         $link
     */
    public function getLinkFromLink($link = null): ?Link
    {
        // Map link field names into CTA field names
        $cta = $link;
        $cta->ctaTitle = $link->linkTitle ?? '';
        $cta->ctaPage = $link->linkPage ?? null;
        // $cta->ctaUrl = $link->linkUrl ?? '';
        // $cta->ctaAnchor = $link->linkAnchor ?? '';
        // $cta->ctaEmail = $link->linkEmail ?? '';

        // Map CTA into Link model
        return $this->getLinkFromCta($cta);
    }

    public function getLinkFromEntry(Entry $entry = null, string $title = null): ?Link
    {
        if ($entry === null) {
            return null;
        }

        return new Link([
            'title' => $title ?? $entry->title,
            'url' => $entry->url,
        ]);
    }

    public function getMediaItemFromAsset(Asset $asset = null): ?MediaItem
    {
        if ($asset === null) {
            return null;
        }

        return new MediaItem([
            'imageSrc' => $asset->url,
            'videoSrc' => $asset->assetVideoUrl,
            'description' => $asset->title,
        ]);
    }
}
