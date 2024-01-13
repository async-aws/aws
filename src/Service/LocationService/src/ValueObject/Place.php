<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains details about addresses or points of interest that match the search criteria.
 *
 * Not all details are included with all responses. Some details may only be returned by specific data partners.
 */
final class Place
{
    /**
     * The numerical portion of an address, such as a building number.
     *
     * @var string|null
     */
    private $addressNumber;

    /**
     * The Amazon Location categories that describe this Place.
     *
     * For more information about using categories, including a list of Amazon Location categories, see Categories and
     * filtering [^1], in the *Amazon Location Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/location/latest/developerguide/category-filtering.html
     *
     * @var string[]|null
     */
    private $categories;

    /**
     * A country/region specified using ISO 3166 [^1] 3-digit country/region code. For example, `CAN`.
     *
     * [^1]: https://www.iso.org/iso-3166-country-codes.html
     *
     * @var string|null
     */
    private $country;

    /**
     * @var PlaceGeometry
     */
    private $geometry;

    /**
     * `True` if the result is interpolated from other known places.
     *
     * `False` if the Place is a known place.
     *
     * Not returned when the partner does not provide the information.
     *
     * For example, returns `False` for an address location that is found in the partner data, but returns `True` if an
     * address does not exist in the partner data and its location is calculated by interpolating between other known
     * addresses.
     *
     * @var bool|null
     */
    private $interpolated;

    /**
     * The full name and address of the point of interest such as a city, region, or country. For example, `123 Any Street,
     * Any Town, USA`.
     *
     * @var string|null
     */
    private $label;

    /**
     * A name for a local area, such as a city or town name. For example, `Toronto`.
     *
     * @var string|null
     */
    private $municipality;

    /**
     * The name of a community district. For example, `Downtown`.
     *
     * @var string|null
     */
    private $neighborhood;

    /**
     * A group of numbers and letters in a country-specific format, which accompanies the address for the purpose of
     * identifying a location.
     *
     * @var string|null
     */
    private $postalCode;

    /**
     * A name for an area or geographical division, such as a province or state name. For example, `British Columbia`.
     *
     * @var string|null
     */
    private $region;

    /**
     * The name for a street or a road to identify a location. For example, `Main Street`.
     *
     * @var string|null
     */
    private $street;

    /**
     * An area that's part of a larger municipality. For example, `Blissville` is a submunicipality in the Queen County in
     * New York.
     *
     * > This property is only returned for a place index that uses Esri as a data provider. The property is represented as
     * > a `district`.
     *
     * For more information about data providers, see Amazon Location Service data providers [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/latest/developerguide/what-is-data-provider.html
     *
     * @var string|null
     */
    private $subMunicipality;

    /**
     * A county, or an area that's part of a larger region. For example, `Metro Vancouver`.
     *
     * @var string|null
     */
    private $subRegion;

    /**
     * Categories from the data provider that describe the Place that are not mapped to any Amazon Location categories.
     *
     * @var string[]|null
     */
    private $supplementalCategories;

    /**
     * The time zone in which the `Place` is located. Returned only when using HERE or Grab as the selected partner.
     *
     * @var TimeZone|null
     */
    private $timeZone;

    /**
     * For addresses with multiple units, the unit identifier. Can include numbers and letters, for example `3B` or `Unit
     * 123`.
     *
     * > This property is returned only for a place index that uses Esri or Grab as a data provider. It is not returned for
     * > `SearchPlaceIndexForPosition`.
     *
     * @var string|null
     */
    private $unitNumber;

    /**
     * For addresses with a `UnitNumber`, the type of unit. For example, `Apartment`.
     *
     * > This property is returned only for a place index that uses Esri as a data provider.
     *
     * @var string|null
     */
    private $unitType;

    /**
     * @param array{
     *   AddressNumber?: null|string,
     *   Categories?: null|string[],
     *   Country?: null|string,
     *   Geometry: PlaceGeometry|array,
     *   Interpolated?: null|bool,
     *   Label?: null|string,
     *   Municipality?: null|string,
     *   Neighborhood?: null|string,
     *   PostalCode?: null|string,
     *   Region?: null|string,
     *   Street?: null|string,
     *   SubMunicipality?: null|string,
     *   SubRegion?: null|string,
     *   SupplementalCategories?: null|string[],
     *   TimeZone?: null|TimeZone|array,
     *   UnitNumber?: null|string,
     *   UnitType?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->addressNumber = $input['AddressNumber'] ?? null;
        $this->categories = $input['Categories'] ?? null;
        $this->country = $input['Country'] ?? null;
        $this->geometry = isset($input['Geometry']) ? PlaceGeometry::create($input['Geometry']) : $this->throwException(new InvalidArgument('Missing required field "Geometry".'));
        $this->interpolated = $input['Interpolated'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->municipality = $input['Municipality'] ?? null;
        $this->neighborhood = $input['Neighborhood'] ?? null;
        $this->postalCode = $input['PostalCode'] ?? null;
        $this->region = $input['Region'] ?? null;
        $this->street = $input['Street'] ?? null;
        $this->subMunicipality = $input['SubMunicipality'] ?? null;
        $this->subRegion = $input['SubRegion'] ?? null;
        $this->supplementalCategories = $input['SupplementalCategories'] ?? null;
        $this->timeZone = isset($input['TimeZone']) ? TimeZone::create($input['TimeZone']) : null;
        $this->unitNumber = $input['UnitNumber'] ?? null;
        $this->unitType = $input['UnitType'] ?? null;
    }

    /**
     * @param array{
     *   AddressNumber?: null|string,
     *   Categories?: null|string[],
     *   Country?: null|string,
     *   Geometry: PlaceGeometry|array,
     *   Interpolated?: null|bool,
     *   Label?: null|string,
     *   Municipality?: null|string,
     *   Neighborhood?: null|string,
     *   PostalCode?: null|string,
     *   Region?: null|string,
     *   Street?: null|string,
     *   SubMunicipality?: null|string,
     *   SubRegion?: null|string,
     *   SupplementalCategories?: null|string[],
     *   TimeZone?: null|TimeZone|array,
     *   UnitNumber?: null|string,
     *   UnitType?: null|string,
     * }|Place $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAddressNumber(): ?string
    {
        return $this->addressNumber;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories ?? [];
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getGeometry(): PlaceGeometry
    {
        return $this->geometry;
    }

    public function getInterpolated(): ?bool
    {
        return $this->interpolated;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getSubMunicipality(): ?string
    {
        return $this->subMunicipality;
    }

    public function getSubRegion(): ?string
    {
        return $this->subRegion;
    }

    /**
     * @return string[]
     */
    public function getSupplementalCategories(): array
    {
        return $this->supplementalCategories ?? [];
    }

    public function getTimeZone(): ?TimeZone
    {
        return $this->timeZone;
    }

    public function getUnitNumber(): ?string
    {
        return $this->unitNumber;
    }

    public function getUnitType(): ?string
    {
        return $this->unitType;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
