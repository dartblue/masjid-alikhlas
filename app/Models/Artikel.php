<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class Artikel extends Model
    {
        protected $table = 'artikel';
        protected $fillable = ['tanggal', 'judul', 'kategori', 'konten', 'kunjungan', 'gambar'];
        public $timestamps = false;

        public static function boot()
        {
            parent::boot();

            static::creating(function ($artikel) {
                $artikel->slug = self::generateUniqueSlug($artikel->judul);
            });

            static::updating(function ($artikel) {
                $artikel->slug = self::generateUniqueSlug($artikel->judul, $artikel->id);
            });
        }

        private static function generateUniqueSlug($judul, $id = null)
        {
            $slug = Str::slug($judul);
            $originalSlug = $slug;
            $i = 1;

            while (self::where('slug', $slug)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            return $slug;
        }
    }

?>