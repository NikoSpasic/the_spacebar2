<?php


namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
    }

    public function parse(string $sorce): string
    {
        if (stripos($sorce, 'bacon') !== false) {
            $this->logger->info('They talking about bacon again!');
        }

        $item = $this->cache->getItem('markdown_'.md5($sorce));
        if(!$item->isHit()) {
            $item->set($this->markdown->transform($sorce));
            $this->cache->save($item);
        }

        return  $item->get();
    }
}