<?php

namespace Database\Seeders;

use App\Models\ProfileEntry;
use Illuminate\Database\Seeder;

class ProfileEntrySeeder extends Seeder
{
    public function run(): void
    {
        if (ProfileEntry::exists()) {
            return;
        }

        $items = [
            [
                'type'         => 'publication',
                'title'        => 'Kılıçali Paşa Hamamı',
                'title_en'     => 'Kilic Ali Pasa Hamam',
                'subtitle'     => 'ArchDaily Website',
                'subtitle_en'  => 'ArchDaily Website',
                'image'        => 'media/P2hoB0UEZZjHwSl4RWF9i8NmJWVat79EUTTXhwwd.jpg',
                'external_url' => 'https://www.archdaily.com',
                'order'        => 1,
                'projects'     => [7],
            ],
            [
                'type'        => 'publication',
                'title'       => 'Kılıç Ali Paşa Hamamı Restorasyon Projesi',
                'title_en'    => 'Kilic Ali Pasa Hamam Restoration Project',
                'subtitle'    => 'Yapı Dergisi Sayı 380, Temmuz 2013 sf. 110-117',
                'subtitle_en' => 'Yapi Magazine Issue 380, July 2013 pp. 110-117',
                'image'       => 'media/P2hoB0UEZZjHwSl4RWF9i8NmJWVat79EUTTXhwwd.jpg',
                'order'       => 2,
                'projects'    => [7],
            ],
            [
                'type'        => 'publication',
                'title'       => 'Süreyya Operası Dönüşüm Süreci’nin Kent Merkezi Bağlamında İrdelenmesi',
                'title_en'    => 'Sureyya Opera Transformation in the Urban Centre Context',
                'subtitle'    => 'Yapı Dergisi Sayı 323, Ekim 2008 sf. 70-77',
                'subtitle_en' => 'Yapi Magazine Issue 323, October 2008 pp. 70-77',
                'image'       => 'media/dirH4BVQXr8YcGnD5qAsAU0wH0kumQgvqRj80GUo.jpg',
                'order'       => 3,
                'projects'    => [8],
            ],
            [
                'type'         => 'publication',
                'title'        => 'Vitra Çağdaş Mimarlık Dizisi 4',
                'title_en'     => 'Vitra Contemporary Architecture Series 4',
                'subtitle'     => 'Yapı Endüstri Merkezi Yayınları, 2015 sf.118-123, sf. 290-295',
                'subtitle_en'  => 'Yapi Endustri Merkezi Publications, 2015 pp.118-123, 290-295',
                'image'        => 'media/1rAp3dEb6CCyiBnVxVOvkF4s0D6Uxoh2VAaskJVX.jpg',
                'external_url' => 'https://www.yemkitabevi.com',
                'order'        => 4,
                'projects'     => [2, 8],
            ],
            [
                'type'        => 'award',
                'title'       => 'Saki Ali Paşa Hamamı Restorasyonu — Europa Nostra',
                'title_en'    => 'Saki Ali Pasa Hamam Restoration — Europa Nostra',
                'subtitle'    => 'Europa Nostra Awards 2017, Panorama Kategorisi',
                'subtitle_en' => 'Europa Nostra Awards 2017, Panorama Category',
                'image'       => 'media/P2hoB0UEZZjHwSl4RWF9i8NmJWVat79EUTTXhwwd.jpg',
                'order'       => 1,
                'projects'    => [7],
            ],
            [
                'type'        => 'award',
                'title'       => 'TSMD Ulusal Mimarlık Ödülü',
                'title_en'    => 'TSMD National Architecture Award',
                'subtitle'    => 'Süreyyapaşa Konser ve Opera Binası',
                'subtitle_en' => 'Sureyyapasa Concert and Opera House',
                'image'       => 'media/dirH4BVQXr8YcGnD5qAsAU0wH0kumQgvqRj80GUo.jpg',
                'order'       => 2,
                'projects'    => [8],
            ],
            [
                'type'        => 'award',
                'title'       => 'The Chicago Athenaeum International Architecture Awards',
                'title_en'    => 'The Chicago Athenaeum International Architecture Awards',
                'subtitle'    => 'Atatürk Kültür Merkezi',
                'subtitle_en' => 'Ataturk Cultural Center',
                'image'       => 'media/1rAp3dEb6CCyiBnVxVOvkF4s0D6Uxoh2VAaskJVX.jpg',
                'external_url'=> 'https://www.chi-athenaeum.org',
                'order'       => 3,
                'projects'    => [2],
            ],
        ];

        foreach ($items as $item) {
            $projectIds = $item['projects'];
            unset($item['projects']);
            $item['is_active'] = true;

            $entry = ProfileEntry::create($item);
            $entry->projects()->sync($projectIds);
        }
    }
}
