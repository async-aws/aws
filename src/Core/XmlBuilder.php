<?php

declare(strict_types=1);

namespace AsyncAws\Core;

/**
 * Build great API requests bodies.
 *
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class XmlBuilder
{
    /**
     * @var array input data
     */
    private $data;

    /**
     * @var array configuration how the output should look like
     */
    private $config;

    public function __construct(array $data, array $config)
    {
        $this->data = $data;
        $this->config = $config;
    }

    public function getXml()
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        $root = $document->createElement($this->config['_root']['xmlName']);
        $document->appendChild($root);
        if (isset($this->config['_root']['uri'])) {
            $root->setAttribute('xmlns', $this->config['_root']['uri']);
        }

        // Build children
        $this->buildXml($document, $root, $this->data, $this->config['_root']['type']);

        return $document->saveXML();
    }

    /**
     * Here is an examples what $this->config[$shapeName] might look like:.
     *
     * 'AccessControlPolicy' => [
     *      'type' => 'structure',
     *      'members' => [
     *          'Grants' => ['shape' => 'Grants', 'locationName' => 'AccessControlList',],
     *          'Owner' => ['shape' => 'Owner',],
     *      ],
     *  ],
     *
     * $parentElement is the DOM element representing AccessControlPolicy, Our job is to create
     * the members.
     */
    private function buildXml(\DOMDocument $document, \DOMElement $parentElement, array $data, string $shapeName)
    {
        $shape = $this->config[$shapeName];

        foreach ($data as $name => $value) {
            if (!isset($shape['members'][$name])) {
                // Ignoring data that is not in $config
                continue;
            }
            $member = $this->config[$shape['members'][$name]['shape']];
            $el = $document->createElement($shape['members'][$name]['locationName'] ?? $name);
            $parentElement->appendChild($el);

            if (\is_array($value)) {
                if ('list' !== $member['type']) {
                    $this->buildXml($document, $el, $value, $name);

                    continue;
                }
                foreach ($value as $listItem) {
                    $this->buildXml($document, $el, $listItem, $member['member']['shape']);
                }

                continue;
            }

            // TODO do some data type checks with $this->config[$name]['type']
            // TODO if $member['timestamp'] we need to configure the value from DateTimeImmutable to "2009-10-28T22:32:00"
            $el->nodeValue = $value;
        }
    }
}
