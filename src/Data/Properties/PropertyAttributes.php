<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Properties;

use DateTimeImmutable;

final readonly class PropertyAttributes
{
    /**
     * @param  array<string, mixed>  $buildingClasses
     * @param  array<string, mixed>  $extraFields
     */
    public function __construct(
        public ?string $webUrl = null,
        public ?DateTimeImmutable $created = null,
        public ?DateTimeImmutable $updated = null,
        public ?DateTimeImmutable $inactiveDate = null,
        public ?Address $address = null,
        public ?Coordinates $coords = null,
        public ?string $timezone = null,
        public ?string $accessCode = null,
        public ?bool $accessInductionreq = null,
        public ?bool $accessLogbookreq = null,
        public ?string $accessNote = null,
        public ?string $accessProcedure = null,
        public ?string $accessSchedule = null,
        public ?bool $accessSignaturereq = null,
        public ?DateTimeImmutable $agmDate = null,
        public ?string $autocompLabel = null,
        public ?bool $billingManual = null,
        public ?string $billingRequirements = null,
        public array $buildingClasses = [],
        public ?string $buildingConstructionClass = null,
        public ?string $buildingEra = null,
        public ?string $buildingPart = null,
        public ?string $buildingSize = null,
        public ?float $buildingSizesqm = null,
        public ?int $buildingStoreysAboveGround = null,
        public ?int $buildingStoreysBelowGround = null,
        public ?string $buildingType = null,
        public ?int $buildingTenancies = null,
        public ?string $clientRef = null,
        public ?bool $clientSignatureRequired = null,
        public ?DateTimeImmutable $cofoDate = null,
        public ?string $cofoLocation = null,
        public ?string $cofoNumber = null,
        public ?string $internalNote = null,
        public ?bool $isCompliant = null,
        public ?string $localGovernmentArea = null,
        public ?string $name = null,
        public ?string $ocspNumber = null,
        public ?string $organisation = null,
        public ?string $ref = null,
        public ?bool $reportManual = null,
        public ?string $reportRequirements = null,
        public ?string $roundNumber = null,
        public ?string $status = null,
        public ?string $authorisationRef = null,
        public ?string $serviceRequirements = null,
        public ?string $safetyRequirements = null,
        public ?DateTimeImmutable $reviewDate = null,
        public ?DateTimeImmutable $certificationDate = null,
        public ?string $bsecureLatestStickerGuid = null,
        public ?string $bsecureResolvedGuid = null,
        public ?string $restrictedAccessHours = null,
        public ?string $getAbsoluteUrl = null,
        public array $extraFields = [],
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            webUrl: $data['__web_url__'] ?? null,
            created: self::parseDateTime($data['created'] ?? null),
            updated: self::parseDateTime($data['updated'] ?? null),
            inactiveDate: self::parseDateTime($data['inactive_date'] ?? null),
            address: isset($data['address']) && is_array($data['address']) ? Address::fromArray($data['address']) : null,
            coords: isset($data['coords']) && is_array($data['coords']) ? Coordinates::fromArray($data['coords']) : null,
            timezone: $data['timezone'] ?? null,
            accessCode: $data['access_code'] ?? null,
            accessInductionreq: $data['access_inductionreq'] ?? null,
            accessLogbookreq: $data['access_logbookreq'] ?? null,
            accessNote: $data['access_note'] ?? null,
            accessProcedure: $data['access_procedure'] ?? null,
            accessSchedule: $data['access_schedule'] ?? null,
            accessSignaturereq: $data['access_signaturereq'] ?? null,
            agmDate: self::parseDateTime($data['agm_date'] ?? null),
            autocompLabel: $data['autocomp_label'] ?? null,
            billingManual: $data['billing_manual'] ?? null,
            billingRequirements: $data['billing_requirements'] ?? null,
            buildingClasses: $data['building_classes'] ?? [],
            buildingConstructionClass: $data['building_construction_class'] ?? null,
            buildingEra: $data['building_era'] ?? null,
            buildingPart: $data['building_part'] ?? null,
            buildingSize: $data['building_size'] ?? null,
            buildingSizesqm: self::parseFloat($data['building_sizesqm'] ?? null),
            buildingStoreysAboveGround: self::parseInt($data['building_storeys_above_ground'] ?? null),
            buildingStoreysBelowGround: self::parseInt($data['building_storeys_below_ground'] ?? null),
            buildingType: $data['building_type'] ?? null,
            buildingTenancies: self::parseInt($data['building_tenancies'] ?? null),
            clientRef: $data['client_ref'] ?? null,
            clientSignatureRequired: $data['client_signature_required'] ?? null,
            cofoDate: self::parseDateTime($data['cofo_date'] ?? null),
            cofoLocation: $data['cofo_location'] ?? null,
            cofoNumber: $data['cofo_number'] ?? null,
            internalNote: $data['internal_note'] ?? null,
            isCompliant: $data['is_compliant'] ?? null,
            localGovernmentArea: $data['local_government_area'] ?? null,
            name: $data['name'] ?? null,
            ocspNumber: $data['ocsp_number'] ?? null,
            organisation: $data['organisation'] ?? null,
            ref: $data['ref'] ?? null,
            reportManual: $data['report_manual'] ?? null,
            reportRequirements: $data['report_requirements'] ?? null,
            roundNumber: $data['round_number'] ?? null,
            status: $data['status'] ?? null,
            authorisationRef: $data['authorisation_ref'] ?? null,
            serviceRequirements: $data['service_requirements'] ?? null,
            safetyRequirements: $data['safety_requirements'] ?? null,
            reviewDate: self::parseDate($data['review_date'] ?? null),
            certificationDate: self::parseDate($data['certification_date'] ?? null),
            bsecureLatestStickerGuid: $data['bsecure_latest_sticker_guid'] ?? null,
            bsecureResolvedGuid: $data['bsecure_resolved_guid'] ?? null,
            restrictedAccessHours: $data['restricted_access_hours'] ?? null,
            getAbsoluteUrl: $data['get_absolute_url'] ?? null,
            extraFields: $data['extra_fields'] ?? [],
        );
    }

    private static function parseDateTime(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        $parsed = DateTimeImmutable::createFromFormat(DATE_ATOM, $value) ?: null;

        if ($parsed === null) {
            $parsed = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $value) ?: null;
        }

        return $parsed;
    }

    private static function parseDate(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        return DateTimeImmutable::createFromFormat('Y-m-d', $value) ?: null;
    }

    private static function parseFloat(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private static function parseInt(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
