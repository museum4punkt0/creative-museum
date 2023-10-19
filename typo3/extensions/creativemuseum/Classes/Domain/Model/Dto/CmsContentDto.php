<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class CmsContentDto extends AbstractEntity
{
    protected string $aboutHtml = '';
    protected string $aboutVideo = '';

    protected string $simpleLanguageHtml = '';
    protected string $simpleLanguageVideo = '';

    protected string $signLanguageHtml = '';
    protected string $signLanguageVideo = '';

    protected string $faqHtml = '';
    protected string $faqVideo = '';

    protected string $imprintHtml = '';
    protected string $imprintVideo = '';

    protected string $accessibilityHtml = '';
    protected string $accessibilityVideo = '';
    /**
     * @param string $aboutHtml
     * @param string $aboutVideo
     * @param string $simpleLanguageHtml
     * @param string $simpleLanguageVideo
     * @param string $signLanguageHtml
     * @param string $signLanguageVideo
     * @param string $faqHtml
     * @param string $faqVideo
     * @param string $imprintHtml
     * @param string $imprintVideo
     * @param string $accessibilityHtml
     * @param string $accessibilityVideo
     */
    public function __construct(
        string $aboutHtml = '',
        string $aboutVideo = '',
        string $simpleLanguageHtml = '',
        string $simpleLanguageVideo = '',
        string $signLanguageHtml = '',
        string $signLanguageVideo = '',
        string $faqHtml = '',
        string $faqVideo = '',
        string $imprintHtml = '',
        string $imprintVideo = '',
        string $accessibilityHtml = '',
        string $accessibilityVideo = ''
    ) {
        $this->aboutHtml = $aboutHtml;
        $this->aboutVideo = $aboutVideo;
        $this->simpleLanguageHtml = $simpleLanguageHtml;
        $this->simpleLanguageVideo = $simpleLanguageVideo;
        $this->signLanguageHtml = $signLanguageHtml;
        $this->signLanguageVideo = $signLanguageVideo;
        $this->faqHtml = $faqHtml;
        $this->faqVideo = $faqVideo;
        $this->imprintHtml = $imprintHtml;
        $this->imprintVideo = $imprintVideo;
        $this->accessibilityHtml = $accessibilityHtml;
        $this->accessibilityVideo = $accessibilityVideo;
    }

    /**
     * @return string
     */
    public function getAboutHtml(): string
    {
        return $this->aboutHtml;
    }

    /**
     * @return string
     */
    public function getAboutVideo(): string
    {
        return $this->aboutVideo;
    }

    /**
     * @param string $aboutHtml
     * @return CmsContentDto
     */
    public function setAboutHtml(string $aboutHtml): CmsContentDto
    {
        $this->aboutHtml = $aboutHtml;
        return $this;
    }

    /**
     * @param string $aboutVideo
     * @return CmsContentDto
     */
    public function setAboutVideo(string $aboutVideo): CmsContentDto
    {
        $this->aboutVideo = $aboutVideo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSimpleLanguageHtml(): string
    {
        return $this->simpleLanguageHtml;
    }

    /**
     * @param string $simpleLanguageHtml
     * @return CmsContentDto
     */
    public function setSimpleLanguageHtml(string $simpleLanguageHtml): CmsContentDto
    {
        $this->simpleLanguageHtml = $simpleLanguageHtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignLanguageHtml(): string
    {
        return $this->signLanguageHtml;
    }

    /**
     * @param string $signLanguageHtml
     * @return CmsContentDto
     */
    public function setSignLanguageHtml(string $signLanguageHtml): CmsContentDto
    {
        $this->signLanguageHtml = $signLanguageHtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getFaqHtml(): string
    {
        return $this->faqHtml;
    }

    /**
     * @param string $faqHtml
     * @return CmsContentDto
     */
    public function setFaqHtml(string $faqHtml): CmsContentDto
    {
        $this->faqHtml = $faqHtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getImprintHtml(): string
    {
        return $this->imprintHtml;
    }

    /**
     * @param string $imprintHtml
     * @return CmsContentDto
     */
    public function setImprintHtml(string $imprintHtml): CmsContentDto
    {
        $this->imprintHtml = $imprintHtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessibilityHtml(): string
    {
        return $this->accessibilityHtml;
    }

    /**
     * @param string $accessibilityHtml
     * @return CmsContentDto
     */
    public function setAccessibilityHtml(string $accessibilityHtml): CmsContentDto
    {
        $this->accessibilityHtml = $accessibilityHtml;
        return $this;
    }


    /**
     * @return string
     *
     */
    public function getSimpleLanguageVideo(): string
    {
        return $this->simpleLanguageVideo;

    }

    /**
     * @param string $simpleLanguageVideo
     */
    public function setSimpleLanguageVideo(string $simpleLanguageVideo): CmsContentDto
    {
        $this->simpleLanguageVideo = $simpleLanguageVideo;
        return $this;
    }

    /**
     * @return string
     *
     */
    public function getSignLanguageVideo(): string
    {
        return $this->signLanguageVideo;

    }

    /**
     * @param string $signLanguageVideo
     * @return CmsContentDto
     */
    public function setSignLanguageVideo(string $signLanguageVideo): CmsContentDto
    {
        $this->signLanguageVideo = $signLanguageVideo;
        return $this;
    }


    /**
     *
     * @return string
     */
    public function getFaqVideo(): string
    {
        return $this->faqVideo;
    }


    /**
     * @param string $faqVideo
     * @return CmsContentDto
     */
    public function setFaqVideo(string $faqVideo): CmsContentDto
    {
        $this->faqVideo = $faqVideo;
        return $this;
    }


    /**
     * @return string
     */
    public function getImprintVideo(): string
    {
        return $this->imprintVideo;

    }

    /**
     * @param string $imprintVideo
     */
    public function setImprintVideo(string $imprintVideo): CmsContentDto
    {
        $this->imprintVideo = $imprintVideo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessibilityVideo(): string
    {
        return $this->accessibilityVideo;
    }

    /**
     * @param string $accessibilityVideo
     * @return CmsContentDto
     */
    public function setAccessibilityVideo(string $accessibilityVideo): CmsContentDto
    {
        $this->accessibilityVideo = $accessibilityVideo;
        return $this;
    }

}