<?php

declare(strict_types=1);

namespace Website;

use Twig\Extension\AbstractExtension;

class AsyncAwsTwigExtension extends AbstractExtension
{
    /**
     * @var array|null
     */
    private $manifestCache;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'getAssetUrl'])
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('asset', [$this, 'parseAssetUrls'])
        ];
    }


    public function getAssetUrl(string $name)
    {
        $manifest = $this->getManigest();

        if (!isset($manifest[$name])) {
            throw new \RuntimeException(sprintf('Could not find manifest entry for "%s"', $name));
        }

        return $manifest[$name];
    }

    public function parseAssetUrls(string $content)
    {
        $manifest = $this->getManigest();

        foreach ($manifest as $search => $replace) {
            $content = str_replace('/'.$search, $replace, $content);
        }

        return $content;
    }

    private function getManigest(): array
    {
        if (null === $this->manifestCache) {
            $file = dirname(__DIR__).'/template/assets/manifest.json';
            $this->manifestCache = json_decode(file_get_contents($file), true);
        }

        return $this->manifestCache;
    }
}
