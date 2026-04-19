<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSession extends Model
{
    use HasFactory;

    // Veritabanı tablo adın farklıysa (opsiyonel)
    protected $table = 'active_sessions';

    /**
     * Toplu atama yapılabilecek sütunlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',   
        'platform', 
        'browser'  
    ];

    /**
     * Bu oturumun ait olduğu kullanıcı.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}