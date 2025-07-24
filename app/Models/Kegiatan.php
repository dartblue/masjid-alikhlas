<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class Kegiatan extends Model
    {
        protected $table = 'kegiatan';
        protected $fillable = ['tanggal', 'kegiatan', 'keterangan', 'kunjungan', 'gambar'];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();

            static::creating(function ($kegiatan) {
                $kegiatan->slug = self::generateUniqueSlug($kegiatan->kegiatan);
            });

            static::updating(function ($kegiatan) {
                $kegiatan->slug = self::generateUniqueSlug($kegiatan->kegiatan, $kegiatan->id);
            });
        }
        private static function generateUniqueSlug($kegiatan, $id = null)
        {
            $slug = Str::slug($kegiatan);
            $originalSlug = $slug;
            $i = 1;

            while (self::where('slug', $slug)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            return $slug;
        }
    }

?>