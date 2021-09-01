<?php

namespace App\MarkdownElements;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\Xml;

final class FencedCodeRenderer implements BlockRendererInterface
{
    /**
     * @param FencedCode               $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $attrs = $block->getData('attributes', []);

        $infoWords = $block->getInfoWords();
        if (\count($infoWords) !== 0 && \strlen($infoWords[0]) !== 0) {
            $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' ' : '';
            $attrs['class'] .= 'language-' . $infoWords[0];
        }

        return new HtmlElement(
            'pre',
            $attrs,
            new HtmlElement('code', $attrs, Xml::escape($block->getStringContent()))
        );
    }
}
