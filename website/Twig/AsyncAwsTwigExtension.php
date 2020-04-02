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

    /**
     * @var array|null
     */
    private $entrypointsCache;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'getAssetUrl']),
            new \Twig_SimpleFunction('renderStyle', [$this, 'renderStyle'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('renderScript', [$this, 'renderScript'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('asset', [$this, 'parseAssetUrls']),
        ];
    }

    public function getAssetUrl(string $name)
    {
        $manifest = $this->getManifest();

        if (!isset($manifest[$name])) {
            throw new \RuntimeException(sprintf('Could not find manifest entry for "%s"', $name));
        }

        return $manifest[$name];
    }

    public function parseAssetUrls(string $content)
    {
        $manifest = $this->getManifest();

        foreach ($manifest as $search => $replace) {
            $content = str_replace('/' . $search, $replace, $content);
        }

        return $content;
    }

    private function getManifest(): array
    {
        if (null === $this->manifestCache) {
            $file = \dirname(__DIR__) . '/template/assets/manifest.json';
            $this->manifestCache = json_decode(file_get_contents($file), true);
        }

        return $this->manifestCache;
    }

    private function getEntrypoints(): array
    {
        if (null === $this->entrypointsCache) {
            $file = \dirname(__DIR__) . '/template/assets/entrypoints.json';
            $this->entrypointsCache = json_decode(file_get_contents($file), true);
        }

        return $this->entrypointsCache;
    }

    public function renderStyle(string $name)
    {
        return $this->renderTag($name, 'css', '<link rel="stylesheet" href="%s">');
    }

    public function renderScript(string $name)
    {
        return $this->renderTag($name, 'js', '<script src="%s"></script>');
    }

    private function renderTag(string $name, string $prefix, string $template)
    {
        $output = '';

        $entrypoints = $this->getEntrypoints();
        if (!isset($entrypoints['entrypoints'][$name])) {
            throw new \RuntimeException(sprintf('Could not find entrypoints.json entry for "%s"', $name));
        }

        foreach ($entrypoints['entrypoints'][$name][$prefix] as $file) {
            $output.= sprintf($template, htmlspecialchars($file));
        }

        return $output;
    }
}
