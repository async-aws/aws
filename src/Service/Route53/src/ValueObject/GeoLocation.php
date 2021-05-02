<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * *Geolocation resource record sets only:* A complex type that lets you control how Amazon Route 53 responds to DNS
 * queries based on the geographic origin of the query. For example, if you want all queries from Africa to be routed to
 * a web server with an IP address of `192.0.2.111`, create a resource record set with a `Type` of `A` and a
 * `ContinentCode` of `AF`.
 *
 * > Although creating geolocation and geolocation alias resource record sets in a private hosted zone is allowed, it's
 * > not supported.
 *
 * If you create separate resource record sets for overlapping geographic regions (for example, one resource record set
 * for a continent and one for a country on the same continent), priority goes to the smallest geographic region. This
 * allows you to route most queries for a continent to one resource and to route queries for a country on that continent
 * to a different resource.
 * You can't create two geolocation resource record sets that specify the same geographic location.
 * The value `*` in the `CountryCode` element matches all geographic locations that aren't specified in other
 * geolocation resource record sets that have the same values for the `Name` and `Type` elements.
 *
 * ! Geolocation works by mapping IP addresses to locations. However, some IP addresses aren't mapped to geographic
 * ! locations, so even if you create geolocation resource record sets that cover all seven continents, Route 53 will
 * ! receive some DNS queries from locations that it can't identify. We recommend that you create a resource record set
 * ! for which the value of `CountryCode` is `*`. Two groups of queries are routed to the resource that you specify in
 * ! this record: queries that come from locations for which you haven't created geolocation resource record sets and
 * ! queries from IP addresses that aren't mapped to a location. If you don't create a `*` resource record set, Route 53
 * ! returns a "no answer" response for queries from those locations.
 *
 * You can't create non-geolocation resource record sets that have the same values for the `Name` and `Type` elements as
 * geolocation resource record sets.
 */
final class GeoLocation
{
    /**
     * The two-letter code for the continent.
     */
    private $continentCode;

    /**
     * For geolocation resource record sets, the two-letter code for a country.
     */
    private $countryCode;

    /**
     * For geolocation resource record sets, the two-letter code for a state of the United States. Route 53 doesn't support
     * any other values for `SubdivisionCode`. For a list of state abbreviations, see Appendix B: Two–Letter State and
     * Possession Abbreviations on the United States Postal Service website.
     *
     * @see https://pe.usps.com/text/pub28/28apb.htm
     */
    private $subdivisionCode;

    /**
     * @param array{
     *   ContinentCode?: null|string,
     *   CountryCode?: null|string,
     *   SubdivisionCode?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->continentCode = $input['ContinentCode'] ?? null;
        $this->countryCode = $input['CountryCode'] ?? null;
        $this->subdivisionCode = $input['SubdivisionCode'] ?? null;
    }

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
    public function requestBody(\DomElement $node, \DomDocument $document): void
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
