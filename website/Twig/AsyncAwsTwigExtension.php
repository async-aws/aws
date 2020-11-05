<?php

declare(strict_types=1);

namespace Website;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
            new TwigFunction('asset', [$this, 'getAssetUrl']),
            new TwigFunction('renderStyle', [$this, 'renderStyle'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('renderScript', [$this, 'renderScript'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('asset', [$this, 'parseAssetUrls']),
            new TwigFilter('lcfirst', function($str) { return lcfirst($str);}),
            new TwigFilter('printHLJSUseStatement', [$this, 'printHighlightUseStatement'], [
                'is_safe' => ['html'],
            ]),
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

    public function renderStyle(string $name)
    {
        return $this->renderTag($name, 'css', '<link rel="stylesheet" href="%s">');
    }

    public function renderScript(string $name)
    {
        return $this->renderTag($name, 'js', '<script src="%s"></script>');
    }
    public function printHighlightUseStatement(string $name)
    {
        $parts = explode('\\', $name);
        $output = '<span class="hljs-keyword">use</span> ';
        foreach ($parts as $part) {
            $output.='<span class="hljs-title">'.$part.'</span>\\';
        }

        return substr($output, 0, -1).';';
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

    private function renderTag(string $name, string $prefix, string $template)
    {
        $output = '';

        $entrypoints = $this->getEntrypoints();
        if (!isset($entrypoints['entrypoints'][$name])) {
            throw new \RuntimeException(sprintf('Could not find entrypoints.json entry for "%s"', $name));
        }

        foreach ($entrypoints['entrypoints'][$name][$prefix] as $file) {
            $output .= sprintf($template, htmlspecialchars($file));
        }

        return $output;
    }
}
