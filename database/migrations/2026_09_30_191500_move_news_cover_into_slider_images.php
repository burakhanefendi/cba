<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $news = DB::table('news')->whereNotNull('cover_image')->where('cover_image', '!=', '')->get();

        foreach ($news as $item) {
            $exists = DB::table('news_images')
                ->where('news_id', $item->id)
                ->where('type', 'slider')
                ->where('image', $item->cover_image)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('news_images')->where('news_id', $item->id)->where('type', 'slider')->increment('order');

            DB::table('news_images')->insert([
                'news_id'    => $item->id,
                'image'      => $item->cover_image,
                'type'       => 'slider',
                'order'      => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
