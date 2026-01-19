<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Clients;

final readonly class ClientAttributes
{
    /**
     * @param  array<string, mixed>  $extraFields
     */
    public function __construct(
        public ?string $webUrl = null,
        public ?string $created = null,
        public ?string $updated = null,
        public ?string $ref = null,
        public ?string $name = null,
        public ?bool $isActive = null,
        public ?string $contactName = null,
        public ?string $contactOrganisation = null,
        public ?string $contactMobile = null,
        public ?string $contactPhoneBh = null,
        public ?string $contactPhoneAh = null,
        public ?string $contactEmail = null,
        public ?string $contactEmailCc = null,
        public ?string $contactAddress = null,
        public ?string $autocompLabel = null,
        public ?string $businessHours = null,
        public ?string $reportRequirements = null,
        public ?string $reportAttention = null,
        public ?string $reportEmailTo = null,
        public ?string $reportEmailCc = null,
        public ?bool $reportManual = null,
        public ?bool $reportMerge = null,
        public ?bool $reportWhitelabel = null,
        public ?string $reportOrganisation = null,
        public ?string $reportAddress = null,
        public ?string $billingOrganisation = null,
        public ?string $billingRequirements = null,
        public ?string $billingAttention = null,
        public ?string $billingEmailTo = null,
        public ?string $billingEmailCc = null,
        public ?bool $billingManual = null,
        public ?string $billingAddress = null,
        public ?bool $billingFixedprice = null,
        public ?string $quotingAttention = null,
        public ?string $quotingEmailTo = null,
        public ?string $quotingEmailCc = null,
        public ?bool $quotingAutoremindersEnabled = null,
        public ?string $quotingRequirements = null,
        public ?string $notes = null,
        public ?string $address = null,
        public ?string $materialMarkup = null,
        public array $extraFields = [],
        public ?string $primaryContactEmail = null,
        public ?string $sector = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            webUrl: $data['__web_url__'] ?? null,
            created: $data['created'] ?? null,
            updated: $data['updated'] ?? null,
            ref: $data['ref'] ?? null,
            name: $data['name'] ?? null,
            isActive: $data['is_active'] ?? null,
            contactName: $data['contact_name'] ?? null,
            contactOrganisation: $data['contact_organisation'] ?? null,
            contactMobile: $data['contact_mobile'] ?? null,
            contactPhoneBh: $data['contact_phone_bh'] ?? null,
            contactPhoneAh: $data['contact_phone_ah'] ?? null,
            contactEmail: $data['contact_email'] ?? null,
            contactEmailCc: $data['contact_email_cc'] ?? null,
            contactAddress: $data['contact_address'] ?? null,
            autocompLabel: $data['autocomp_label'] ?? null,
            businessHours: $data['business_hours'] ?? null,
            reportRequirements: $data['report_requirements'] ?? null,
            reportAttention: $data['report_attention'] ?? null,
            reportEmailTo: $data['report_email_to'] ?? null,
            reportEmailCc: $data['report_email_cc'] ?? null,
            reportManual: $data['report_manual'] ?? null,
            reportMerge: $data['report_merge'] ?? null,
            reportWhitelabel: $data['report_whitelabel'] ?? null,
            reportOrganisation: $data['report_organisation'] ?? null,
            reportAddress: $data['report_address'] ?? null,
            billingOrganisation: $data['billing_organisation'] ?? null,
            billingRequirements: $data['billing_requirements'] ?? null,
            billingAttention: $data['billing_attention'] ?? null,
            billingEmailTo: $data['billing_email_to'] ?? null,
            billingEmailCc: $data['billing_email_cc'] ?? null,
            billingManual: $data['billing_manual'] ?? null,
            billingAddress: $data['billing_address'] ?? null,
            billingFixedprice: $data['billing_fixedprice'] ?? null,
            quotingAttention: $data['quoting_attention'] ?? null,
            quotingEmailTo: $data['quoting_email_to'] ?? null,
            quotingEmailCc: $data['quoting_email_cc'] ?? null,
            quotingAutoremindersEnabled: $data['quoting_autoreminders_enabled'] ?? null,
            quotingRequirements: $data['quoting_requirements'] ?? null,
            notes: $data['notes'] ?? null,
            address: $data['address'] ?? null,
            materialMarkup: $data['material_markup'] ?? null,
            extraFields: $data['extra_fields'] ?? [],
            primaryContactEmail: $data['primary_contact__email'] ?? null,
            sector: $data['sector'] ?? null,
        );
    }
}
