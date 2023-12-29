<?php

namespace App\Repositories;

use App\Models\AgreementProduct;
use App\Models\Agreement;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AgreementTypeRepository
{

    public function personal(\Illuminate\Support\Collection $partner_uuids): Collection
    {
        $personal_agreements = Agreement::whereIn('partner', $partner_uuids)
            ->beforeDate()
            ->activeDate()
            ->get();
//            ->sortBy('name');

        return $this->applyAgreements($personal_agreements);
    }

    public function common(): Collection
    {
        $common_agreements = Agreement::wherePartner('')->beforeDate()->activeDate()->get();

        return $this->applyAgreements($common_agreements);
    }

    public function find(string $uuidOrId): Agreement
    {
        return Agreement::whereUuid($uuidOrId)->orWhere('id', $uuidOrId)->first();
    }

    public function findAvailable(int $id, array $partnersUuids = []): Agreement
    {
        $type = Agreement::whereId($id)->where(
            fn(Builder $query) => $query->wherePartner('')->orWhereIn('partner', $partnersUuids)
        );
        return $type;
    }

    private function applyAgreements(Collection $agreements): Collection
    {
        if ($agreements->count()) {

            foreach ($agreements as $key => $agreement) {

                // берем товары соглашения
                $agreement_items = AgreementProduct::where('agreement_uuid', $agreement->uuid)->get();

                // если есть
                if ($agreement_items->count()) {

                    // собираем строку код товара - цена
                    $items_str = '';

                    foreach ($agreement_items as $agr_item) {

                        // берем данные по товару
                        $item = Item::where('uuid', $agr_item->product_uuid)->first(['id_1c', 'adjustable', 'price_rub', 'price_usd']);

                        // если есть
                        if ($item) {

                            // берем стандартную цену
                            if ($item->adjustable == 1) { // если товар регулируемый
                                $price = $item->price_rub;
                            } else {
                                // считаем по оптовому курсу
                                $price = number_format($item->price_usd * setting('header_usd'), 2, '.', '');
                            }

                            // берем 1с код
                            $item_1c_id = $item->id_1c;

                            // если цена зафиксирована
                            if (doubleval($agr_item->price_rub)) {

                                // определяем цену
                                $item_price = $agr_item->price_rub;

                            } elseif ($agr_item->formula) { // если цена расчетная

                                // определяем цену
                                $item_price = number_format($price * (1 + $agr_item->formula / 100), 2, '.', '');

                            } else {

                                // определяем цену
                                $item_price = $price;

                            }

                            // добавляем в строку товар-цена
                            $items_str .= "{$item_1c_id}-{$item_price};";

                        } else {
                            // пропускаем итерацию
                            continue;
                        }
                    }

                    // удаляем оконечную точку с запятой
                    $items_str = preg_replace('/;$/', '', $items_str);

                    // добавляем в коллекцию
                    $agreements[$key]->items_str = $items_str;
                }
            }
        }
        return $agreements;
    }

}
