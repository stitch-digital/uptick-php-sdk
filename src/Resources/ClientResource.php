<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Resources;

use Saloon\Http\BaseResource;
use Uptick\PhpSdk\Uptick\Data\Clients\Sector;
use Uptick\PhpSdk\Uptick\Paginators\UptickPaginator;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;
use Uptick\PhpSdk\Uptick\Uptick;

/**
 * @property Uptick $connector
 */
final class ClientResource extends BaseResource
{
    /**
     * List clients with any supported filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): UptickPaginator
    {
        $request = new ListClientsRequest;

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return $this->connector->paginate($request);
    }

    public function listActive(): UptickPaginator
    {
        return $this->list(['is_active' => true]);
    }

    public function listInactive(): UptickPaginator
    {
        return $this->list(['is_active' => false]);
    }

    public function findById(int|string $clientId): UptickPaginator
    {
        return $this->list(['id' => $clientId]);
    }

    public function findByName(string $clientName, bool $strict = false): UptickPaginator
    {
        return $this->list(
            $strict ? ['name' => $clientName] : ['name_contains' => $clientName]
        );
    }

    public function search(string $query): UptickPaginator
    {
        return $this->list(['text_contains' => $query]);
    }

    public function whereAccountManager(int|array $ids): UptickPaginator
    {
        return $this->list(['account_manager' => $ids]);
    }

    public function whereNotAccountManager(int|array $ids): UptickPaginator
    {
        return $this->list(['not_account_manager' => $ids]);
    }

    public function whereBillingCard(int|array $ids): UptickPaginator
    {
        return $this->list(['billingcard' => $ids]);
    }

    public function whereNotBillingCard(int|array $ids): UptickPaginator
    {
        return $this->list(['not_billingcard' => $ids]);
    }

    public function createdBefore(string $isoDateTime): UptickPaginator
    {
        return $this->list(['created_before' => $isoDateTime]);
    }

    public function createdAfter(string $isoDateTime): UptickPaginator
    {
        return $this->list(['created_after' => $isoDateTime]);
    }

    public function updatedBefore(string $isoDateTime): UptickPaginator
    {
        return $this->list(['updated_before' => $isoDateTime]);
    }

    public function updatedAfter(string $isoDateTime): UptickPaginator
    {
        return $this->list(['updated_after' => $isoDateTime]);
    }

    public function wherePriceTier(int|array $ids): UptickPaginator
    {
        return $this->list(['pricetier' => $ids]);
    }

    public function whereNotPriceTier(int|array $ids): UptickPaginator
    {
        return $this->list(['not_pricetier' => $ids]);
    }

    public function whereIsActive(bool $isActive = true): UptickPaginator
    {
        return $this->list(['is_active' => $isActive]);
    }

    public function whereReportWhitelabel(bool $value): UptickPaginator
    {
        return $this->list(['report_whitelabel' => $value]);
    }

    public function whereReportManual(bool $value): UptickPaginator
    {
        return $this->list(['report_manual' => $value]);
    }

    public function whereBillingManual(bool $value): UptickPaginator
    {
        return $this->list(['billing_manual' => $value]);
    }

    public function whereBillingFixedPrice(bool $value): UptickPaginator
    {
        return $this->list(['billing_fixedprice' => $value]);
    }

    public function whereQuotingAutoRemindersEnabled(bool $value): UptickPaginator
    {
        return $this->list(['quoting_autoreminders_enabled' => $value]);
    }

    /**
     * Filter clients by sector.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     */
    public function whereSector(Sector|array|string $sectors): UptickPaginator
    {
        $sectorValues = $this->normalizeSectorValues($sectors);

        return $this->list(['sector' => $sectorValues]);
    }

    /**
     * Filter clients by excluding sectors.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     */
    public function whereNotSector(Sector|array|string $sectors): UptickPaginator
    {
        $sectorValues = $this->normalizeSectorValues($sectors);

        return $this->list(['not_sector' => $sectorValues]);
    }

    /**
     * Normalize sector values to strings for API requests.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     * @return string|array<string>
     */
    private function normalizeSectorValues(Sector|array|string $sectors): string|array
    {
        if ($sectors instanceof Sector) {
            return $sectors->value;
        }

        if (is_array($sectors)) {
            return array_map(function ($sector) {
                return $sector instanceof Sector ? $sector->value : (string) $sector;
            }, $sectors);
        }

        return (string) $sectors;
    }

    public function whereHasProperties(bool $value = true): UptickPaginator
    {
        return $this->list(['has_properties' => $value]);
    }

    public function whereIsDuplicated(bool $value = true): UptickPaginator
    {
        return $this->list(['is_duplicated' => $value]);
    }

    public function whereParentClientGroup(int|string $clientGroupId): UptickPaginator
    {
        return $this->list(['parent_clientgroup' => $clientGroupId]);
    }

    public function whereClientGroup(int|string $clientGroupId): UptickPaginator
    {
        return $this->list(['clientgroup' => $clientGroupId]);
    }

    public function whereNotClientGroup(int|string $clientGroupId): UptickPaginator
    {
        return $this->list(['not_clientgroup' => $clientGroupId]);
    }

    public function whereBranch(int|array $branchIds): UptickPaginator
    {
        return $this->list(['branch' => $branchIds]);
    }

    public function whereNotBranch(int|array $branchIds): UptickPaginator
    {
        return $this->list(['not_branch' => $branchIds]);
    }

    public function whereHasAccount(bool $value = true): UptickPaginator
    {
        return $this->list(['has_account' => $value]);
    }

    public function whereHasActiveProperty(bool $value = true): UptickPaginator
    {
        return $this->list(['has_active_property' => $value]);
    }

    public function whereTags(int|array $tagIds): UptickPaginator
    {
        return $this->list(['tags' => $tagIds]);
    }

    public function whereNotTags(int|array $tagIds): UptickPaginator
    {
        return $this->list(['not_tags' => $tagIds]);
    }

    public function whereHasBusinessHours(bool $value = true): UptickPaginator
    {
        return $this->list(['has_business_hours' => $value]);
    }

    public function wherePhoneNumberContains(string $needle): UptickPaginator
    {
        return $this->list(['phone_number_contains' => $needle]);
    }

    public function updatedSince(string $isoDateTime): UptickPaginator
    {
        return $this->list(['updatedsince' => $isoDateTime]);
    }

    public function whereExtraFields(array $extraFields): UptickPaginator
    {
        return $this->list(['extra_fields' => $extraFields]);
    }
}
