<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class CmsContentDto extends AbstractEntity
{
    protected string $aboutHtml = '';

    protected string $simpleLanguageHtml = '';

    protected string $signLanguageHtml = '';

    protected string $faqHtml = '';

    protected string $imprintHtml = '';

    /**
     * @param string $aboutHtml
     * @param string $simpleLanguageHtml
     * @param string $signLanguageHtml
     * @param string $faqHtml
     * @param string $imprintHtml
     */
    public function __construct(
        string $aboutHtml = '',
        string $simpleLanguageHtml = '',
        string $signLanguageHtml = '',
        string $faqHtml = '',
        string $imprintHtml = ''
    ) {
        $this->aboutHtml = $aboutHtml;
        $this->simpleLanguageHtml = $simpleLanguageHtml;
        $this->signLanguageHtml = $signLanguageHtml;
        $this->faqHtml = $faqHtml;
        $this->imprintHtml = $imprintHtml;
    }

    /**
     * @return string
     */
    public function getAboutHtml(): string
    {
        return $this->aboutHtml;
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
}