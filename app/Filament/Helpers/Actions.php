<?php

namespace App\Filament\Helpers;

use Filament\Tables;

class Actions
{

    public static function all(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
        ];
    }

}
