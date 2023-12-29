<?php

namespace App\Repositories;

use App\Models\Partner;
use Illuminate\Support\Collection;

class PartnerRepository
{

    public function partners(): Collection
    {
        $partners = collect([profile()->partner()->with('childs')->get()]);
        $childs = $partners->first()->first()?->childs()->with('childs')->get();
        while ($childs->count() > 0){
            $partners->push($childs);
            $childs = Partner::whereIn('uuid', $childs->pluck('uuid'))->with('childs')->get()->pluck('childs')->collapse();
        }
        return $partners->collapse();
    }

    public function addresses(?Collection $partners = null): Collection
    {
        $partners = $partners ?? $this->partners();
        $addresses = collect([$partners->pluck('address'), $partners->pluck('warehouse')]);
        return $addresses->collapse();
    }

    public function get(?string $uid = null): array
    {
        $uid = $uid ?? auth()->user()->profile->partner_uuid;

        $partner_uuids = collect([]);
        $delivery_addresses = collect([]);

        $partner = $this->getPartner('uuid', $uid)->first();

        while ($partner) {

            if ($partner->address) {
                $delivery_addresses = $this->pushAddress($delivery_addresses, $partner);
            }

            if ($partner->warehouse) {
                $delivery_addresses = $this->pushAddress($delivery_addresses, $partner);
            }

            if ($partner->address || $partner->warehouse) {
                $partner_uuids[] = $uid;
            }

            $partner = $this->getPartner('parent_uuid', $uid)->where('parent_uuid', '!=', '')->first();

        }


        return [$partner_uuids, $delivery_addresses];
    }

    private
    function getPartner(string $key, string $uid)
    {
        return Partner::where($key, $uid)->select(['uuid', 'address', 'warehouse']);
    }

    private
    function pushAddress(Collection $addresses, Partner $partner)
    {
        if ($partner->address) {
            $addresses->push([
                'uuid' => $partner->uuid,
                'address' => $partner->address,
            ]);
        }
        return $addresses;
    }

}
