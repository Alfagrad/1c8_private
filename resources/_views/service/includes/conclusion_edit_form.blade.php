<div class="make-conclusion">
    <div class="container">

        <div class="make-conclusion_title">
            Откорректируйте данные и сохраните:
        </div>

        <form method="post" action="{{ asset('service/conclusion/update') }}">

            <div class="make-conclusion_document-block">

                {{ csrf_field() }}

                <div class="service-requisites">
                    <textarea name="service_requisites" required>{{ $conclusion->requisites }}</textarea>
                </div>

                <div class="document-header">
                    ЗАКЛЮЧЕНИЕ №{{ $conclusion->order_id }}
                </div>

                <div class="info-block-1">

                    <div class="line">
                        <div>От:</div>
                        <div>
                            <input type="date" name="document_date" value="{{ $conclusion->document_date }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Изделие:</div>
                        <div>
                            <textarea name="item_name" required>{{ $conclusion->item_name }}</textarea>
                        </div>
                    </div>

                    <div class="line">
                        <div>Серийный номер:</div>
                        <div>
                            <input type="text" name="item_sn" value="{{ $conclusion->item_sn }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Дата продажи:</div>
                        <div>
                            <input type="date" name="item_sale_date" value="{{ $conclusion->item_sale_date }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Дата поступления изделия в ремонт:</div>
                        <div>
                            <input type="date" name="item_repare_receiving" value="{{ $conclusion->item_receiving_date }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Фамилия и инициалы потребителя:</div>
                        <div>
                            <input type="text" name="client_name" value="{{ $conclusion->client_name }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Телефон:</div>
                        <div>
                            <input type="text" name="client_phone" value="{{ $conclusion->client_phone }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Наличие гарантийного талона:</div>
                        <div>
                            Есть - <input type="radio" name="guarantee_availability" value=1 required @if($conclusion->guarantee_availability){{ 'checked' }}@endif>, 
                            Нет - <input type="radio" name="guarantee_availability" value=0 required @if(!$conclusion->guarantee_availability){{ 'checked' }}@endif>
                        </div>
                    </div>

                    <div class="line">
                        <div>Отметка продавца в гарантийном талоне:</div>
                        <div>
                            Есть - <input type="radio" name="seller_record" value=1 required @if($conclusion->seller_record){{ 'checked' }}@endif>, 
                            Нет - <input type="radio" name="seller_record" value=0 required @if(!$conclusion->seller_record){{ 'checked' }}@endif>
                        </div>
                    </div>

                    <div class="line">
                        <div>Наличие внешних мех.повреждений:</div>
                        <div>
                            <textarea name="external_damage" required>{{ $conclusion->external_damage }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="info-block-2">

                    <div class="block-2-element">
                        <div class="header">Выявленные потребителем повреждения, неисправности:</div>
                        <div>
                            <textarea name="client_damage_detected" required>{{ $conclusion->client_damage_detected }}</textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Результат осмотра:</div>
                        <div>
                            <textarea name="item_diagnostic" required>{{ $conclusion->diagnostic }}</textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Предполагаемая причина повреждения, неисправности:</div>
                        <div>
                            <textarea name="item_damage_reason" required>{{ $conclusion->damage_reason }}</textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Предложения по восстановлению работоспособности, внешнего вида или замене изделия:</div>
                        <div>
                            <textarea name="item_recovery_recommendation" required>{{ $conclusion->recovery_recommendation }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="signature-block">
                    <div class="signature-wrapper">
                        <div>
                            Директор ______________________
                            <input type="text" name="signature_name" value="{{ $conclusion->signature_name }}" required>
                        </div>                        
                    </div>
                </div>

            </div>

            <input type="hidden" name="order_id" value="{{ $conclusion->order_id }}">
            <input type="hidden" name="buh" value="@if(isset($buh)){{ $buh }}@endif">

            <div class="submit-block">
                <button type="submit" class="js-update_butt">Сохранить</button>
            </div>

        </form>
       
    </div>
</div>
