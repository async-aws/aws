<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * A complex type that contains information about a geographic location.
 */
final class GeoLocation
{
    /**
     * The two-letter code for the continent.
     *
     * Amazon Route 53 supports the following continent codes:
     *
     * - **AF**: Africa
     * - **AN**: Antarctica
     * - **AS**: Asia
     * - **EU**: Europe
     * - **OC**: Oceania
     * - **NA**: North America
     * - **SA**: South America
     *
     * Constraint: Specifying `ContinentCode` with either `CountryCode` or `SubdivisionCode` returns an `InvalidInput`
     * error.
     *
     * @var string|null
     */
    private $continentCode;

    /**
     * For geolocation resource record sets, the two-letter code for a country.
     *
     * Amazon Route 53 uses the two-letter country codes that are specified in ISO standard 3166-1 alpha-2 [^1].
     *
     * Route 53 also supports the country code **UA** for Ukraine.
     *
     * [^1]: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     *
     * @var string|null
     */
    private $countryCode;

    /**
     * For geolocation resource record sets, the two-letter code for a state of the United States. Route 53 doesn't support
     * any other values for `SubdivisionCode`. For a list of state abbreviations, see Appendix B: Two–Letter State and
     * Possession Abbreviations [^1] on the United States Postal Service website.
     *
     * If you specify `subdivisioncode`, you must also specify `US` for `CountryCode`.
     *
     * [^1]: https://pe.usps.com/text/pub28/28apb.htm
     *
     * @var string|null
     */
    private $subdivisionCode;

    /**
     * @param array{
     *   ContinentCode?: string|null,
     *   CountryCode?: string|null,
     *   SubdivisionCode?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->continentCode = $input['ContinentCode'] ?? null;
        $this->countryCode = $input['CountryCode'] ?? null;
        $this->subdivisionCode = $input['SubdivisionCode'] ?? null;
    }

    /**
     * @param array{
     *   ContinentCode?: string|null,
     *   CountryCode?: string|null,
     *   SubdivisionCode?: string|null,
     * }|GeoLocation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContinentCode(): ?string
    {
        return $this->continentCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getSubdivisionCode(): ?string
    {
        return $this->subdivisionCode;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->continentCode) {
            $node->appendChild($document->createElement('ContinentCode', $v));
        }
        if (null !== $v = $this->countryCode) {
            $node->appendChild($document->createElement('CountryCode', $v));
        }
        if (null !== $v = $this->subdivisionCode) {
            $node->appendChild($document->createElement('SubdivisionCode', $v));
        }
    }
}
