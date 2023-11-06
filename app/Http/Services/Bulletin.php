<?php

namespace App\Http\Services;

use App\Models\Omitted;
use App\Models\User;
use Illuminate\Support\Str;

/**
 *
 */
final class Bulletin
{

    /**
     * Создать не подписанный бюллетень голосования
     * @param User|false $user
     * @param Omitted $omitted
     * @return string|null
     */
    public static function getBulletenUserByOmitted(User|false $user, Omitted $omitted, string|null $sign = null)
    {
        $config = config('company_details');
        if ($user) {
            $link = $config['root_catalog'] . '/public/storage/bulletins/' . $user->id . '/' . $omitted->id . '/' . Str::slug($omitted->name, '_') . '.pdf';
            if (file_exists($link)) {
                return str_replace($config['root_catalog'] . '/public', '', $link);
            }
            return DocumentService::createBlankBulletin($user, $omitted, $sign);
        }
        return null;
    }
}
