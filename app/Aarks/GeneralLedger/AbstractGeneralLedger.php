<?php namespace App\Aarks\GeneralLedger;

use App\Models\Data_storage;
use App\Models\Client;
use App\Models\ClientAccountCode;
use App\Models\GeneralLedger;
use App\Jobs\CalculateGeneralLedgerBalance;
use App\Models\Profession;
use App\Models\Transaction;
use App\Models\TransactionNumber;

abstract class AbstractGeneralLedger
{
    protected $client;
    protected $profession;
    protected $source = '';
    protected $is_cash_gst_allowed = true;
    protected $is_accrued_gst_allowed = true;
    protected $data = [];
    protected $opposite_account_code;

    private $valid_sources = [
        'BST',
        'ADT',
        'JNL',
        'SIV',
        'SIVPMT',
        'BILL',
        'BILLPMT',
        'DEP',
        'PAY',
        'INV'
    ];

    private $valid_gst_codes = [];
    private $all_gst_codes = [];
    private $current_data;
    protected $gst_account_codes = [];
    protected $gst_account_code_objects = [];

    /**
     * @param mixed $profession
     * @return AbstractGeneralLedger
     */
    public function setProfession(Profession $profession): AbstractGeneralLedger
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @param array $data
     * @return AbstractGeneralLedger
     */
    public function setData(array $data): AbstractGeneralLedger
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param mixed $opposite_account_code
     * @return AbstractGeneralLedger
     */
    public function setOppositeAccountCode(ClientAccountCode $opposite_account_code): AbstractGeneralLedger
    {
        $this->opposite_account_code = $opposite_account_code;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidGstCodes(): array
    {
        return $this->valid_gst_codes;
    }

    /**
     * @return mixed
     */
    public function getCurrentData()
    {
        return $this->current_data;
    }

    /**
     * @param array $gst_account_codes
     * @return AbstractGeneralLedger
     */
    protected function setGstAccountCodes(array $gst_account_codes) : AbstractGeneralLedger
    {
        $this->gst_account_codes = $gst_account_codes;
        return $this;
    }

    /**
     * @param mixed $source
     * @return AbstractGeneralLedger
     * @throws InvalidValueException
     */
    protected function setSource($source): AbstractGeneralLedger
    {
        if (!in_array($source, $this->valid_sources)) {
            throw new InvalidValueException();
        }
        $this->source = $source;
        return $this;
    }

    /**
     * @param mixed $valid_gst_codes
     * @return AbstractGeneralLedger
     * @throws InvalidValueException
     */
    protected function setValidGstCodes(array $valid_gst_codes): AbstractGeneralLedger
    {
        $this->all_gst_codes = aarks('gst_code');
        if (!(array_intersect($valid_gst_codes, $this->all_gst_codes) === $valid_gst_codes)) {
            throw new InvalidValueException();
        }

        $this->valid_gst_codes = $valid_gst_codes;
        return $this;
    }

    /**
     * @param $gst_code
     * @param $account_code
     * @return AbstractGeneralLedger
     * @throws InvalidValueException
     */
    protected function setAccountCodeForGST($gst_code, $account_code)
    {
        if (!in_array($gst_code, $this->valid_gst_codes)) {
            throw new InvalidValueException();
        }

        $this->gst_account_codes[$gst_code] = (int) $account_code;
        return $this;
    }

    /**
     * @param mixed $client
     * @return AbstractGeneralLedger
     */
    public function setClient(Client $client): AbstractGeneralLedger
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @throws InvalidValueException
     */
    private function validateSource()
    {
        if (!in_array($this->source, $this->valid_sources)) {
            throw new InvalidValueException("General Ledger Source Is Not Valid");
        }
    }

    private function validateClient()
    {
        if (!($this->client instanceof Client)) {
            throw new InvalidValueException("Client is not set yet");
        }
    }

    private function validateProfession()
    {
        if (!($this->profession instanceof Profession)) {
            throw new InvalidValueException("Profession is not set yet");
        }
    }

    private function validateOppositeAccountCode()
    {
        if (!($this->opposite_account_code instanceof ClientAccountCode)) {
            throw new InvalidValueException("Opposite Account Code is not set yet");
        }
    }

    private function validateGstAccountCodesMapping()
    {
        if (count(array_diff(array_keys($this->gst_account_codes), $this->valid_gst_codes))) {
            throw new InvalidValueException("GST Account Code Mapping is not Correct");
        }
    }

    abstract protected function calculateGST();
    abstract protected function isGstApplicable();
    abstract protected function calculateGstAccountCode();

    private function validateBeforeLedgerGeneration()
    {
        $this->validateClient();
        $this->validateProfession();
        $this->validateSource();
        // $this->validateGstAccountCodesMapping();
        $this->validateOppositeAccountCode();
    }

    private function setGstAccountCodeObjects()
    {
        $client_account_codes = ClientAccountCode::where('client_id', $this->client->id)
            ->where('profession_id', $this->profession->id)
            ->whereIn('code', array_values($this->gst_account_codes))->get();
        $this->gst_account_code_objects = $client_account_codes;
//        foreach ($this->gst_account_codes as $gst_code => $account_code_id) {
//            $this->gst_account_codes[$gst_code] = $client_account_codes->where('code', $account_code_id)->first();
//        }
    }

    public function generateLedger()
    {
        // dd($this->data);
        $this->validateBeforeLedgerGeneration();
        $this->setGstAccountCodeObjects();

        foreach ($this->data as $data) {
            if (!is_null($data['account_code'])) {
                $gst = 0;
                $this->current_data = $data;
                if ($this->isGstApplicable()) {
                    $gst = $this->calculateGST();
                    $gst = number_format($gst, 2);
                }
                $transaction_number = TransactionNumber::create([]);
                $this->createGeneralLedger($data, $gst, $transaction_number->id);
                $this->createTransactions($data, $gst, $transaction_number->id);
            }
        }

        dispatch(new CalculateGeneralLedgerBalance($this->client, $this->profession));
    }

    private function createGeneralLedger($data, $gst, $transaction_number)
    {

        $main_ledger_data = [
            'transaction_id' => $transaction_number,
            'date' => $data['date'],
            'narration' => $data['narration'],
            // 'chart_id' => $data['account_code'],
            'source' => $this->source,
            'debit' => $data['debit'] > 0 ? ($data['debit'] - $gst) : 0,
            'credit' => $data['debit'] > 0 ? 0 : ($data['credit'] - $gst),
            'gst'   => $gst,
            'client_account_code_id' => $data['account_code'],
            'client_id' => $data['client_id'],
            'profession_id' => $data['profession_id'],
            'transaction_for' => aarks('general_ledger_transaction_for')['main']
        ];

        $opposite_ledger_data = [
            'transaction_id' => $transaction_number,
            'date' => $data['date'],
            'narration' => $data['narration'],
            // 'chart_id' => $data['account_code'],
            'source' => $this->source,
            'debit' => $data['credit'] > 0 ? $data['credit'] : 0,
            'credit' => $data['debit'] > 0 ? $data['debit'] : 0,
            'gst'   => 0,
            'client_account_code_id' => $this->opposite_account_code->id,
            'client_id' => $data['client_id'],
            'profession_id' => $data['profession_id'],
            'transaction_for' => aarks('general_ledger_transaction_for')['opposite']
        ];
        $ledger_date = [$main_ledger_data, $opposite_ledger_data];

        if ($gst) {
            $gst_account_code = $this->calculateGstAccountCode();
            $gst_ledger_data = [
                'transaction_id' => $transaction_number,
                'date' => $data['date'],
                'narration' => $data['narration'],
                // 'chart_id' => $data['account_code'],
                'source' => $this->source,
                'debit' => $data['debit'] > 0 ? $gst : 0,
                'credit' => $data['credit'] > 0 ? $gst : 0,
                'gst'   => 0,
                'client_account_code_id' => $gst_account_code->id,
                'client_id' => $data['client_id'],
                'profession_id' => $data['profession_id'],
                'transaction_for' => aarks('general_ledger_transaction_for')['gst']
            ];
            $ledger_date[] = $gst_ledger_data;
        }

         return GeneralLedger::insert($ledger_date);
    }

    private function createTransactions($data, $gst, $transaction_number)
    {
        $main_transaction_data = [
            'account_name' => $data['client_account_code']['name'],
            'transaction_date' => $data['date'],
            'transaction_id' => $transaction_number,
            'narration' => $data['narration'],
            'debit' => $data['debit'] > 0 ? ($data['debit'] - $gst) : 0,
            'credit' => $data['debit'] > 0 ? 0 : ($data['credit'] - $gst),
            'client_account_code_id' => $data['account_code'],
            'client_id' => $data['client_id'],
            'profession_id' => $data['profession_id'],
            'transaction_for' => aarks('general_ledger_transaction_for')['main']
        ];
        $opposite_transaction_data = [
            'account_name' => $this->opposite_account_code->name,
            'transaction_date' => $data['date'],
            'transaction_id' => $transaction_number,
            'narration' => $data['narration'],
            'debit' => $data['credit'] > 0 ? $data['credit'] : 0,
            'credit' => $data['debit'] > 0 ? $data['debit'] : 0,
            'client_account_code_id' => $this->opposite_account_code->id,
            'client_id' => $data['client_id'],
            'profession_id' => $data['profession_id'],
            'transaction_for' => aarks('general_ledger_transaction_for')['opposite']
        ];
        $transactions = [$main_transaction_data, $opposite_transaction_data];

        if ($gst) {
            $gst_account_code = $this->calculateGstAccountCode();

            $gst_transaction_data = [
                'account_name' => $gst_account_code->name,
                'transaction_date' => $data['date'],
                'transaction_id' => $transaction_number,
                'narration' => $data['narration'],
                'debit' => $data['debit'] > 0 ? $gst : 0,
                'credit' => $data['credit'] > 0 ? $gst : 0,
                'client_account_code_id' => $gst_account_code->id,
                'client_id' => $data['client_id'],
                'profession_id' => $data['profession_id'],
                'transaction_for' => aarks('general_ledger_transaction_for')['gst']
            ];
            $transactions[] = $gst_transaction_data;
        }

        Transaction::insert($transactions);
    }
}
