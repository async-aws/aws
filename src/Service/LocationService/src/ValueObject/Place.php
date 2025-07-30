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
     * The full name and address of the point of interest such as a city, region, or country. For example, `123 Any Street,
     * Any Town, USA`.
     *
     * @var string|null
     */
    private $label;

    /**
     * @var PlaceGeometry
     */
    private $geometry;

    /**
     * The numerical portion of an address, such as a building number.
     *
     * @var string|null
     */
    private $addressNumber;

    /**
     * The name for a street or a road to identify a location. For example, `Main Street`.
     *
     * @var string|null
     */
    private $street;

    /**
     * The name of a community district. For example, `Downtown`.
     *
     * @var string|null
     */
    private $neighborhood;

    /**
     * A name for a local area, such as a city or town name. For example, `Toronto`.
     *
     * @var string|null
     */
    private $municipality;

    /**
     * A county, or an area that's part of a larger region. For example, `Metro Vancouver`.
     *
     * @var string|null
     */
    private $subRegion;

    /**
     * A name for an area or geographical division, such as a province or state name. For example, `British Columbia`.
     *
     * @var string|null
     */
    private $region;

    /**
     * A country/region specified using ISO 3166 [^1] 3-digit country/region code. For example, `CAN`.
     *
     * [^1]: https://www.iso.org/iso-3166-country-codes.html
     *
     * @var string|null
     */
    private $country;

    /**
     * A group of numbers and letters in a country-specific format, which accompanies the address for the purpose of
     * identifying a location.
     *
     * @var string|null
     */
    private $postalCode;

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
     * The time zone in which the `Place` is located. Returned only when using HERE or Grab as the selected partner.
     *
     * @var TimeZone|null
     */
    private $timeZone;

    /**
     * For addresses with a `UnitNumber`, the type of unit. For example, `Apartment`.
     *
     * > Returned only for a place index that uses Esri as a data provider.
     *
     * @var string|null
     */
    private $unitType;

    /**
     * For addresses with multiple units, the unit identifier. Can include numbers and letters, for example `3B` or `Unit
     * 123`.
     *
     * > Returned only for a place index that uses Esri or Grab as a data provider. Is not returned for
     * > `SearchPlaceIndexForPosition`.
     *
     * @var string|null
     */
    private $unitNumber;

    /**
     * The Amazon Location categories that describe this Place.
     *
     * For more information about using categories, including a list of Amazon Location categories, see Categories and
     * filtering [^1], in the *Amazon Location Service developer guide*.
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/category-filtering.html
     *
     * @var string[]|null
     */
    private $categories;

    /**
     * Categories from the data provider that describe the Place that are not mapped to any Amazon Location categories.
     *
     * @var string[]|null
     */
    private $supplementalCategories;

    /**
     * An area that's part of a larger municipality. For example, `Blissville ` is a submunicipality in the Queen County in
     * New York.
     *
     * > This property supported by Esri and OpenData. The Esri property is `district`, and the OpenData property is
     * > `borough`.
     *
     * @var string|null
     */
    private $subMunicipality;

    /**
     * @param array{
     *   Label?: null|string,
     *   Geometry: PlaceGeometry|array,
     *   AddressNumber?: null|string,
     *   Street?: null|string,
     *   Neighborhood?: null|string,
     *   Municipality?: null|string,
     *   SubRegion?: null|string,
     *   Region?: null|string,
     *   Country?: null|string,
     *   PostalCode?: null|string,
     *   Interpolated?: null|bool,
     *   TimeZone?: null|TimeZone|array,
     *   UnitType?: null|string,
     *   UnitNumber?: null|string,
     *   Categories?: null|string[],
     *   SupplementalCategories?: null|string[],
     *   SubMunicipality?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->label = $input['Label'] ?? null;
        $this->geometry = isset($input['Geometry']) ? PlaceGeometry::create($input['Geometry']) : $this->throwException(new InvalidArgument('Missing required field "Geometry".'));
        $this->addressNumber = $input['AddressNumber'] ?? null;
        $this->street = $input['Street'] ?? null;
        $this->neighborhood = $input['Neighborhood'] ?? null;
        $this->municipality = $input['Municipality'] ?? null;
        $this->subRegion = $input['SubRegion'] ?? null;
        $this->region = $input['Region'] ?? null;
        $this->country = $input['Country'] ?? null;
        $this->postalCode = $input['PostalCode'] ?? null;
        $this->interpolated = $input['Interpolated'] ?? null;
        $this->timeZone = isset($input['TimeZone']) ? TimeZone::create($input['TimeZone']) : null;
        $this->unitType = $input['UnitType'] ?? null;
        $this->unitNumber = $input['UnitNumber'] ?? null;
        $this->categories = $input['Categories'] ?? null;
        $this->supplementalCategories = $input['SupplementalCategories'] ?? null;
        $this->subMunicipality = $input['SubMunicipality'] ?? null;
    }

    /**
     * @param array{
     *   Label?: null|string,
     *   Geometry: PlaceGeometry|array,
     *   AddressNumber?: null|string,
     *   Street?: null|string,
     *   Neighborhood?: null|string,
     *   Municipality?: null|string,
     *   SubRegion?: null|string,
     *   Region?: null|string,
     *   Country?: null|string,
     *   PostalCode?: null|string,
     *   Interpolated?: null|bool,
     *   TimeZone?: null|TimeZone|array,
     *   UnitType?: null|string,
     *   UnitNumber?: null|string,
     *   Categories?: null|string[],
     *   SupplementalCategories?: null|string[],
     *   SubMunicipality?: null|string,
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
