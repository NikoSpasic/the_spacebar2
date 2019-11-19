<?php


namespace App\Service;


use Michelf\MarkdownInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
    }

    public function parse(string $sorce): string
    {
        $item = $this->cache->getItem('markdown_'.md5($sorce));
        if(!$item->isHit()) {
            $item->set($this->markdown->transform($sorce));
            $this->cache->save($item);
        }

        return  $item->get();
    }
}