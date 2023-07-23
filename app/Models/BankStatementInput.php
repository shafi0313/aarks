<?php
namespace App\Models;

use App\Models\ClientAccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankStatementInput extends Model
{
    use SoftDeletes;
    protected $table = 'bank_statement_inputs';
    protected $guarded = ['id'];
    protected $dates = ['date'];

    public function client_account_code()
    {
        return $this->belongsTo(ClientAccountCode::class, 'account_code', 'id')->withDefault([
            'name' => 'Not Found',
        ]);
    }
}
