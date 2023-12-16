<?php
namespace App\Models;

use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankStatementImport extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'date' => 'datetime'
    ];

    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'account_code', 'id')->withDefault([
            'name' => 'Not Found',
        ]);
    }
}
