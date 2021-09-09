<?php

namespace App\MarkdownElements;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\Xml;

final class FencedCodeRenderer implements NodeRendererInterface
{
    /**
     * @param FencedCode               $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($node));
        }

        $attrs = $node->getData('attributes', []);

        $infoWords = $node->getInfoWords();

        if (\count($infoWords) !== 0 && \strlen($infoWords[0]) !== 0) {
            $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' ' : '';
            $attrs['class'] .= 'language-' . $infoWords[0];
        }

        return new HtmlElement(
            'pre',
            $attrs,
            new HtmlElement('code', $attrs, Xml::escape($node->getStringContent()))
        );
    }
}
