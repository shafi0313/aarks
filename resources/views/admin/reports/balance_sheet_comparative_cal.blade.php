@php
    $preBlncType = '';
    if ($accountCodeCategory->code == 5) {
        if ($preLedger->balance_type == 1 && $preLedgerBalance > 0) {
            $preGtBalance += abs($preLedgerBalance);
            $preSubSubGrpBalance += abs($preLedgerBalance);
            $preBlncType = '';
        } else {
            $preGtBalance -= abs($preLedgerBalance);
            $preSubSubGrpBalance -= abs($preLedgerBalance);
            $preBlncType = '-';
        }
    }
    if ($accountCodeCategory->code == 9 && !in_array($accountCode->code, [999999, 999998, 912101])) {
        if ($preLedger->balance_type == 2 && $preLedgerBalance > 0) {
            $preGtBalance = $preGtBalance += abs($preLedgerBalance);
            $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
            $preBlncType = '';
        } else {
            $preGtBalance = $preGtBalance -= abs($preLedgerBalance);
            $preSubSubGrpBalance = $preSubSubGrpBalance -= abs($preLedgerBalance);
            $preBlncType = '-';
        }
    }
    
    // For GST Clearing Account (912101)
    if ($accountCodeCategory->code == 9) {
        if ($ledger->chart_id == 912101) {
            if ($preLedger->balance_type == 2 && $preLedgerBalance > 0) {
                $preGtBalance = $preGtBalance -= abs($preLedgerBalance);
                $preSubSubGrpBalance = $preSubSubGrpBalance -= abs($preLedgerBalance);
                $preBlncType = '-';
            } else {
                $preGtBalance = $preGtBalance += abs($preLedgerBalance);
                $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
                $preBlncType = '';
            }
        }
    }
    
    if ($subCategory->id == 16 && $accountCode->code == 999998) {
        $preSubSubGrpBalance += $prePlRetain;
    } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
        $preSubSubGrpBalance += $preRetain;
    }
@endphp
