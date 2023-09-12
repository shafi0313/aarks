<?php

use Carbon\Carbon;
use App\Models\Client;
use App\Models\PeriodLock;
use App\Models\GeneralLedger;
use App\Models\Profession;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

if (!function_exists('getClientsWithPayment')) {
    function getClientsWithPayment()
    {
        return Client::with(['payment' => function ($q) {
            return $q->select('id', 'client_id')->latest();
        }])
            ->get(['id', 'company', 'first_name', 'last_name', 'email', 'phone', 'abn_number']);
    }
}

if (!function_exists('modificationFields')) {
    function modificationFields(Blueprint $table)
    {
        $table->unsignedBigInteger('created_by')->nullable();
        $table->string('created_by_type')->nullable()->comment('1 = Admin, 2 = Client');
        $table->string('created_by_name')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->string('updated_by_type')->nullable()->comment('1 = Admin, 2 = Client');
        $table->string('updated_by_name')->nullable();
    }
}

if (!function_exists('logo')) {
    function logo($logo = null)
    {
        return $logo ? asset($logo) : asset('frontend/assets/images/logo/focus-icon.png');
        if (auth()->guard('client')->check()) {
            return asset(client()->logo) ?? asset('frontend/assets/images/logo/focus-icon.png');
        }
        if (auth()->guard('admin')->check()) {
            return asset('frontend/assets/images/logo/focus-icon.png');
        }
        return asset('frontend/assets/images/logo/focus-icon.png');
    }
}
if (!function_exists('dropModificationFields')) {
    function dropModificationFields($table)
    {
        $table->dropColumn(['created_by', 'created_by_type', 'created_by_name', 'updated_by', 'updated_by_type', 'updated_by_name']);
    }
}
if (!function_exists('logo')) {
    function logo($client = null)
    {
        if ($client && $client->logo) {
            return asset($client->logo);
        }
        return asset('frontend/assets/images/logo/report_logo.jpeg');
    }
}

if (!function_exists('nF2')) {
    function nF2($data)
    {
        return number_format($data, 2);
    }
}

if (!function_exists('aarks')) {
    function aarks($key, $default = '')
    {
        return config('aarks.' . $key, $default);
    }
}

if (!function_exists('client')) {
    function client()
    {
        return auth()->guard('client')->user();
    }
}

if (!function_exists('client_name')) {
    function client_name()
    {
        return Client::find(auth()->guard('client')->user()->id)->fullname;
    }
}
if (!function_exists('clientName')) {
    function clientName($client)
    {
        return $client->company ?? $client->first_name . ' ' . $client->last_name;
    }
}
if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin')->user();
    }
}
if (!function_exists('transaction_id')) {
    function transaction_id($src = '', $lenght = 12)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        $istrxid = 0;
        do {
            if ($src != '') {
                $trxid   = strtoupper($src . '_' . substr(bin2hex($bytes), 0, $lenght));
            } else {
                $trxid   = strtoupper(substr(bin2hex($bytes), 0, $lenght));
            }
            $istrxid = DB::table('trxid')->where('trxid', $trxid)->count();
        } while ($istrxid > 0);
        return $trxid;
    }
}

if (!function_exists('invoice')) {
    function invoice($value, $length = 8, $source = '')
    {
        return $source . '#' . str_pad($value, $length, '0', STR_PAD_LEFT);
    }
}

// Number Format
if (!function_exists('abs_number')) {
    // function abs_number($value, $separetor = ',')
    function abs_number($num = 0, int $decimals = 2, ?string $decimal_separator = '.', ?string $thousands_separator = ''): string
    {
        // return number_format(abs($num), $decimals, $decimal_separator, $thousands_separator);
        return number_format(abs($num), $decimals, $decimal_separator);
    }
}

if (!function_exists('nF2')) {
    function nF2($num)
    {
        return number_format($num, 2);
    }
}

if (!function_exists('nFA2')) {
    function nFA2($num)
    {
        return number_format(abs($num), 2);
    }
}
// /Number Format

if (!function_exists('assetVersion')) {
    function assetVersion($filename)
    {
        $aarks_key_name = 'asset_version.' . $filename;
        $default_asset_value = aarks('default_asset_version');
        return aarks($aarks_key_name, $default_asset_value);
    }
}

if (!function_exists('makeBackendCompatibleDate')) {
    function makeBackendCompatibleDate($date)
    {
        return Carbon::createFromFormat(aarks('frontend_date_format'), $date);
    }
}


if (!function_exists('financialYearInArray')) {
    function financialYearInArray($year)
    {
        $first_date = Carbon::create($year - 1, 7, 1);
        $last_date = Carbon::create($year, 7, 1); //date must be like this 2020,6,30

        return [
            'first' => $first_date,
            'last' => $last_date
        ];
    }
}

if (!function_exists('makeNineDigitNumber')) {
    function makeNineDigitNumber($id)
    {
        return $id + 100000000;
    }
}

if (!function_exists('getFinancialYearOf')) {
    function getFinancialYearOf($date)
    {
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }
        $financial_year = financialYearInArray($date->year);

        return $date->isBetween($financial_year['first'], $financial_year['last']) ? $date->year : $date->year + 1;
    }
}

if (!function_exists('withFinancialSign')) {
    function withFinancialSign($value)
    {
        return '$' . ' ' . $value;
    }
}

if (!function_exists('notEmpty')) {
    function notEmpty($value)
    {
        return !empty($value) ? true : false;
    }
}

if (!function_exists('periodLock')) {
    function periodLock(int $client_id, $date)
    {
        if (is_object($date)) {
            $lock = PeriodLock::where('client_id', $client_id)
                ->where('date', '>=', $date->format('Y-m-d'))
                ->first();
        } else {
            $lock = '';
            foreach ($date as $d) {
                $lock = PeriodLock::where('client_id', $client_id)
                    ->where('date', '>=', $d->format('Y-m-d'))
                    ->first();
            }
        }
        if (!empty($lock)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('niceSize')) {
    function niceSize($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

if (!function_exists('activeNav')) {
    function activeNav($route)
    {
        if (is_array($route)) {
            $rt = '';
            foreach ($route as $rut) {
                $rt .= request()->routeIs($rut) || '';
            }
            return $rt ? ' active ' : '';
        }
        return request()->routeIs($route) ? ' active ' : '';
    }
}
if (!function_exists('activeOpenNav')) {
    function activeOpenNav(array $routes)
    {
        $rt = '';
        foreach ($routes as $route) {
            $rt .= request()->routeIs($route) || '';
        }
        return $rt ? ' active open ' : '';
    }
}

if (!function_exists('open_encrypt')) {
    function open_encrypt(string $string)
    {
        $ciphering  = "AES-128-CTR";
        $iv_length  = openssl_cipher_iv_length($ciphering);
        $options    = 0;
        $iv         = '1234567891011121';
        $key        = "aarks";
        return openssl_encrypt($string, $ciphering, $key, $options, $iv);
    }
}

if (!function_exists('open_decrypt')) {
    function open_decrypt(string $string)
    {
        $ciphering  = "AES-128-CTR";
        $iv_length  = openssl_cipher_iv_length($ciphering);
        $options    = 0;
        $iv         = '1234567891011121';
        $key        = "aarks";
        return openssl_decrypt($string, $ciphering, $key, $options, $iv);
    }
}


// P/ L Calculations
if (!function_exists('pl')) {
    function pl($client, $profession, $date)
    {
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }
        if ($date->format('d-m') == '01-07') {
            return 0;
        }
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }
        // return $end_date;

        $incomes = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '1%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $expences = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '2%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $iCredit = $iDebit = $eDebit = $eCredit = 0;
        foreach ($incomes as $income) {
            if ($income->balance_type == 2) {
                if ($income->balance > 0) {
                    $iCredit += abs($income->balance);
                } else {
                    $iDebit += abs($income->balance);
                }
            }
            if ($income->balance_type == 1) {
                if ($income->balance > 0) {
                    $iDebit += abs($income->balance);
                } else {
                    $iCredit += abs($income->balance);
                }
            }
        }
        // return  $iCredit;
        foreach ($expences as $expense) {
            if ($expense->balance_type == 2) {
                if ($expense->balance < 0) {
                    $eDebit += abs($expense->balance);
                } else {
                    $eCredit += abs($expense->balance);
                }
            }
            if ($expense->balance_type == 1) {
                if ($expense->balance < 0) {
                    $eCredit += abs($expense->balance);
                } else {
                    $eDebit += abs($expense->balance);
                }
            }
        }
        $exp = abs($eDebit) - abs($eCredit); //Debit
        $inc = abs($iCredit) - abs($iDebit); //Credit
        return ($inc - $exp);
    }
}
// Accumulated P/L Calculations
if (!function_exists('consolePL')) {
    function consolePL($client, $date)
    {
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }
        if ($date->format('d-m') == '01-07') {
            return 0;
        }
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }

        $incomes = GeneralLedger::where('client_id', $client->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '1%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $expences = GeneralLedger::where('client_id', $client->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '2%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);
            
        $iCredit = $iDebit = $eDebit = $eCredit = 0;
        foreach ($incomes as $income) {
            if ($income->balance_type == 2) {
                if ($income->balance > 0) {
                    $iCredit += abs($income->balance);
                } else {
                    $iDebit += abs($income->balance);
                }
            }
            if ($income->balance_type == 1) {
                if ($income->balance > 0) {
                    $iDebit += abs($income->balance);
                } else {
                    $iCredit += abs($income->balance);
                }
            }
        }
        // return  $iCredit;
        foreach ($expences as $expense) {
            if ($expense->balance_type == 2) {
                if ($expense->balance < 0) {
                    $eDebit += abs($expense->balance);
                } else {
                    $eCredit += abs($expense->balance);
                }
            }
            if ($expense->balance_type == 1) {
                if ($expense->balance < 0) {
                    $eCredit += abs($expense->balance);
                } else {
                    $eDebit += abs($expense->balance);
                }
            }
        }
        $exp = abs($eDebit) - abs($eCredit); //Debit
        $inc = abs($iCredit) - abs($iDebit); //Credit
        return ($inc - $exp);
    }
}


if (!function_exists('accum_pl')) {
    function accum_pl($client, $date)
    {
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }
        if ($date->format('d-m') == '01-07') {
            return 0;
        }
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }

        $incomes = GeneralLedger::where('client_id', $client->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '1%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $expences = GeneralLedger::where('client_id', $client->id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->where('chart_id', 'like', '2%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);
            
        $iCredit = $iDebit = $eDebit = $eCredit = 0;
        foreach ($incomes as $income) {
            if ($income->balance_type == 2) {
                if ($income->balance > 0) {
                    $iCredit += abs($income->balance);
                } else {
                    $iDebit += abs($income->balance);
                }
            }
            if ($income->balance_type == 1) {
                if ($income->balance > 0) {
                    $iDebit += abs($income->balance);
                } else {
                    $iCredit += abs($income->balance);
                }
            }
        }
        // return  $iCredit;
        foreach ($expences as $expense) {
            if ($expense->balance_type == 2) {
                if ($expense->balance < 0) {
                    $eDebit += abs($expense->balance);
                } else {
                    $eCredit += abs($expense->balance);
                }
            }
            if ($expense->balance_type == 1) {
                if ($expense->balance < 0) {
                    $eCredit += abs($expense->balance);
                } else {
                    $eDebit += abs($expense->balance);
                }
            }
        }
        $exp = abs($eDebit) - abs($eCredit); //Debit
        $inc = abs($iCredit) - abs($iDebit); //Credit
        return ($inc - $exp);
    }
}

// Retained Earnings Calculations
if (!function_exists('retain')) {
    /**
     * Retained Earnings Calculations
     * @param Client $client
     * @param Profession $profession
     * @param DateTime $date
     * @return mixed
     * @throws Exception
     **/
    function retain(Client $client, Profession $profession, DateTime $date)
    {
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }

        $incomes = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $start_date)
            ->where('chart_id', 'like', '1%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $expences = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $start_date)
            ->where('chart_id', 'like', '2%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $iCredit = $iDebit = $eDebit = $eCredit = 0;
        foreach ($incomes as $income) {
            if ($income->balance_type == 2) {
                if ($income->balance > 0) {
                    $iCredit += abs($income->balance);
                } else {
                    $iDebit += abs($income->balance);
                }
            }
            if ($income->balance_type == 1) {
                if ($income->balance > 0) {
                    $iDebit += abs($income->balance);
                } else {
                    $iCredit += abs($income->balance);
                }
            }
        }
        foreach ($expences as $expense) {
            if ($expense->balance_type == 2) {
                if ($expense->balance < 0) {
                    $eDebit += abs($expense->balance);
                } else {
                    $eCredit += abs($expense->balance);
                }
            }
            if ($expense->balance_type == 1) {
                if ($expense->balance < 0) {
                    $eCredit += abs($expense->balance);
                } else {
                    $eDebit += abs($expense->balance);
                }
            }
        }
        $exp    = abs($eDebit) - abs($eCredit);  //Debit
        $inc    = abs($iCredit) - abs($iDebit);  //Credit
        $retain = ($inc - $exp);

        $opn = GeneralLedger::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '<', $start_date)
            ->where('source', 'OPN')
            ->where('chart_id', '999999')
            ->selectRaw('id,chart_id,debit,credit,balance,balance_type, sum(balance) as total_balance')
            ->groupBy('chart_id')
            ->first()?->total_balance;

        if ($retain == 0) {
            return $opn;
        } elseif ($opn == 0) {
            return $retain;
        } else {
            if ($retain > 0 && $opn > 0) {
                return $retain + $opn;
            } elseif ($retain > 0 && $opn < 0) {
                if (abs($retain) < abs($opn)) {
                    return $opn - $retain;
                } else {
                    return $retain - abs($opn);
                }
            } elseif ($retain < 0 && $opn > 0) {
                if (abs($retain) > abs($opn)) {
                    return $retain + abs($opn);
                } else {
                    return $opn - abs($retain);
                }
            } elseif ($retain < 0 && $opn < 0) {
                return $retain + $opn;
            }
        }
    }
}
// Console Retained Earnings Calculations
if (!function_exists('console_retain')) {
    /**
     * Console Retained Earnings Calculations
     * @param Client $client
     * @param DateTime $date
     * @return mixed
     * @throws Exception
     **/
    function console_retain(Client $client, DateTime $date)
    {
        $end_date = $date->format('Y-m-d');
        if ($date->format('m') >= 07 & $date->format('m') <= 12) {
            $start_date = $date->format('Y') . '-07-01';
        } else {
            $start_date = $date->format('Y') - 1 . '-07-01';
        }

        $incomes = GeneralLedger::where('client_id', $client->id)
            ->where('date', '<', $start_date)
            ->where('chart_id', 'like', '1%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $expences = GeneralLedger::where('client_id', $client->id)
            ->where('date', '<', $start_date)
            ->where('chart_id', 'like', '2%')
            ->get(['debit', 'credit', 'balance', 'balance_type']);

        $iCredit = $iDebit = $eDebit = $eCredit = 0;
        foreach ($incomes as $income) {
            if ($income->balance_type == 2) {
                if ($income->balance > 0) {
                    $iCredit += abs($income->balance);
                } else {
                    $iDebit += abs($income->balance);
                }
            }
            if ($income->balance_type == 1) {
                if ($income->balance > 0) {
                    $iDebit += abs($income->balance);
                } else {
                    $iCredit += abs($income->balance);
                }
            }
        }
        foreach ($expences as $expense) {
            if ($expense->balance_type == 2) {
                if ($expense->balance < 0) {
                    $eDebit += abs($expense->balance);
                } else {
                    $eCredit += abs($expense->balance);
                }
            }
            if ($expense->balance_type == 1) {
                if ($expense->balance < 0) {
                    $eCredit += abs($expense->balance);
                } else {
                    $eDebit += abs($expense->balance);
                }
            }
        }
        $exp    = abs($eDebit) - abs($eCredit);  //Debit
        $inc    = abs($iCredit) - abs($iDebit);  //Credit
        $retain = ($inc - $exp);

        $opn = GeneralLedger::where('client_id', $client->id)
            ->where('date', '<', $start_date)
            ->where('source', 'OPN')
            ->where('chart_id', '999999')
            ->selectRaw('id,chart_id,debit,credit,balance,balance_type, sum(balance) as total_balance')
            ->groupBy('chart_id')
            ->first()?->total_balance;

        if ($retain == 0) {
            return $opn;
        } elseif ($opn == 0) {
            return $retain;
        } else {
            if ($retain > 0 && $opn > 0) {
                return $retain + $opn;
            } elseif ($retain > 0 && $opn < 0) {
                if (abs($retain) < abs($opn)) {
                    return $opn - $retain;
                } else {
                    return $retain - abs($opn);
                }
            } elseif ($retain < 0 && $opn > 0) {
                if (abs($retain) > abs($opn)) {
                    return $retain + abs($opn);
                } else {
                    return $opn - abs($retain);
                }
            } elseif ($retain < 0 && $opn < 0) {
                return $retain + $opn;
            }
        }
    }
}
if (!function_exists('numberToRoman')) {
    //Converts a number to its roman presentation.
    function numberToRoman($num)
    {
        // Be sure to convert the given parameter into an integer
        $n = intval($num);
        $result = '';
        // Declare a lookup array that we will use to traverse the number:
        $lookup = array(
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        );
        foreach ($lookup as $roman => $value) {
            // Look for number of matches
            $matches = intval($n / $value);
            // Concatenate characters
            $result .= str_repeat($roman, $matches);
            // Substract that from the number
            $n = $n % $value;
        }
        return $result;
    }
}

if (!function_exists('ledgerHidden')) {
    function ledgerHidden()
    {
        return ['narration', 'loan', 'client_account_code_id', 'transaction_for', 'payable_liabilty', 'batch', 'updated_at', 'created_at', 'created_by', 'created_by_type', 'created_by_name', 'updated_by', 'updated_by_type', 'updated_by_name'];
    }
}

if (!function_exists('ledgerSetVisible')) {
    function ledgerSetVisible()
    {
        return ['id', 'chart_id', 'date', 'narration', 'source', 'debit', 'credit', 'gst', 'balance', 'balance_type', 'client_id', 'profession_id', 'client_account_code_id', 'transaction_id'];
    }
}

if (!function_exists('clientAccountCodeSetVisible')) {
    function clientAccountCodeSetVisible()
    {
        return ['id', 'name', 'code', 'type'];
    }
}

if (!function_exists('clientSetVisible')) {
    function clientSetVisible()
    {
        return ['id', 'company', 'first_name', 'last_name', 'abn_number', 'email'];
    }
}

if (!function_exists('fullFormOfSource')) {
    function fullFormOfSource($src)
    {
        return match ($src) {
            'ADT'   => 'Add/Edit Entry',
            'BST'   => 'Import Bank Statement',
            'INP'   => 'Input Bank Statement',
            'JNP'   => 'Journal Entry',
            'CBE'   => 'Cash Book Entry',
            'DEP'   => 'Depreciation',
            'INV'   => 'Invoice',
            'PIN'   => 'Invoice Payment',
            'PBP'   => 'Creditor',
            'PBN'   => 'Creditor Payment Receive',
            default => 'Unknown Source',
        };
    }
}
