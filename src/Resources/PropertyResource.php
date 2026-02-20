<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Resources;

use Saloon\Http\BaseResource;
use Uptick\PhpSdk\Uptick\Paginators\UptickPaginator;
use Uptick\PhpSdk\Uptick\Requests\Properties\ListPropertiesRequest;
use Uptick\PhpSdk\Uptick\Uptick;

/**
 * @property Uptick $connector
 */
final class PropertyResource extends BaseResource
{
    /**
     * List properties with any supported filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): UptickPaginator
    {
        $request = new ListPropertiesRequest;

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

    public function findById(int|string $propertyId): UptickPaginator
    {
        return $this->list(['id' => $propertyId]);
    }

    public function search(string $query): UptickPaginator
    {
        return $this->list(['text_contains' => $query]);
    }

    public function whereClient(int|array $ids): UptickPaginator
    {
        return $this->list(['client' => $ids]);
    }

    public function whereNotClient(int|array $ids): UptickPaginator
    {
        return $this->list(['not_client' => $ids]);
    }

    public function whereAccountManager(int|array $ids): UptickPaginator
    {
        return $this->list(['account_manager' => $ids]);
    }

    public function whereNotAccountManager(int|array $ids): UptickPaginator
    {
        return $this->list(['not_account_manager' => $ids]);
    }

    public function whereBranch(int|array $ids): UptickPaginator
    {
        return $this->list(['branch' => $ids]);
    }

    public function whereNotBranch(int|array $ids): UptickPaginator
    {
        return $this->list(['not_branch' => $ids]);
    }

    public function whereZone(int|array $ids): UptickPaginator
    {
        return $this->list(['zone' => $ids]);
    }

    public function whereNotZone(int|array $ids): UptickPaginator
    {
        return $this->list(['not_zone' => $ids]);
    }

    /**
     * @param  string|array<string>  $statuses
     */
    public function whereStatus(string|array $statuses): UptickPaginator
    {
        return $this->list(['status' => $statuses]);
    }

    public function whereParentProperty(int|array $ids): UptickPaginator
    {
        return $this->list(['parent_property' => $ids]);
    }

    public function whereNotParentProperty(int|array $ids): UptickPaginator
    {
        return $this->list(['not_parent_property' => $ids]);
    }

    public function whereBillingCard(int|array $ids): UptickPaginator
    {
        return $this->list(['billingcard' => $ids]);
    }

    public function whereNotBillingCard(int|array $ids): UptickPaginator
    {
        return $this->list(['not_billingcard' => $ids]);
    }

    public function wherePriceTier(int|array $ids): UptickPaginator
    {
        return $this->list(['pricetier' => $ids]);
    }

    public function whereTags(int|array $tagIds): UptickPaginator
    {
        return $this->list(['tagged' => $tagIds]);
    }

    public function whereNotTags(int|array $tagIds): UptickPaginator
    {
        return $this->list(['not_tagged' => $tagIds]);
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

    public function updatedSince(string $isoDateTime): UptickPaginator
    {
        return $this->list(['updatedsince' => $isoDateTime]);
    }

    public function whereNameContains(string $needle): UptickPaginator
    {
        return $this->list(['name_contains' => $needle]);
    }

    public function whereNameExcludes(string $needle): UptickPaginator
    {
        return $this->list(['name_excludes' => $needle]);
    }

    public function whereAddressContains(string $needle): UptickPaginator
    {
        return $this->list(['address_contains' => $needle]);
    }

    public function whereAddressExcludes(string $needle): UptickPaginator
    {
        return $this->list(['address_excludes' => $needle]);
    }

    public function whereIsCompliant(?bool $value): UptickPaginator
    {
        return $this->list(['is_compliant' => $value]);
    }

    public function whereHasContact(bool $value = true): UptickPaginator
    {
        return $this->list(['has_contact' => $value]);
    }

    public function whereHasAssets(bool $value = true): UptickPaginator
    {
        return $this->list(['has_assets' => $value]);
    }

    public function whereHasRoutines(bool $value = true): UptickPaginator
    {
        return $this->list(['has_routines' => $value]);
    }

    /**
     * @param  string|array<string>  $states
     */
    public function whereState(string|array $states): UptickPaginator
    {
        return $this->list(['state' => $states]);
    }

    /**
     * @param  string|array<string>  $states
     */
    public function whereNotState(string|array $states): UptickPaginator
    {
        return $this->list(['not_state' => $states]);
    }

    public function whereRefContains(string $needle): UptickPaginator
    {
        return $this->list(['ref_contains' => $needle]);
    }

    public function whereRefExcludes(string $needle): UptickPaginator
    {
        return $this->list(['ref_excludes' => $needle]);
    }
}
