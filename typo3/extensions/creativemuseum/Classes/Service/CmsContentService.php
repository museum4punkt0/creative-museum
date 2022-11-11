<?php

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\CmsContentDto;

class CmsContentService extends CmApiService
{
    const ENDPOINT = 'v1/cms_contents';

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getContentForIdentifier(string $identifier): string
    {
        $item = $this->getSingle($identifier);
        $content = json_decode($item['content']);
        return $content ?? '';
    }

    public function updateContent(CmsContentDto $cmsContentDto)
    {
        $this->patch([
            'id' => 'about',
            'content' => json_encode($cmsContentDto->getAboutHtml(), JSON_UNESCAPED_SLASHES)
        ]);
        $this->patch([
            'id' => 'faq',
            'content' => json_encode($cmsContentDto->getFaqHtml(), JSON_UNESCAPED_SLASHES)
        ]);
        $this->patch([
            'id' => 'signLanguage',
            'content' => json_encode($cmsContentDto->getSignLanguageHtml(), JSON_UNESCAPED_SLASHES)
        ]);
        $this->patch([
            'id' => 'simpleLanguage',
            'content' => json_encode($cmsContentDto->getSimpleLanguageHtml(), JSON_UNESCAPED_SLASHES)
        ]);
        $this->patch([
            'id' => 'imprint',
            'content' => json_encode($cmsContentDto->getImprintHtml(), JSON_UNESCAPED_SLASHES)
        ]);
    }
}