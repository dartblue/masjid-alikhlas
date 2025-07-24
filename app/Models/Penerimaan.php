<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Penerimaan extends Model
    {
        protected $table = 'penerimaan';
        protected $fillable = ['tanggal', 'uraian', 'jenis', 'saldo_masuk', 'sisa_saldo'];
        public $timestamps = false;
    }

?>