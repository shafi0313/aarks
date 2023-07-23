@php
    $blncType = '';
    if ($accountCodeCategory->code == 5) {
        if ($ledger->balance_type == 1 && $ledgerBalance > 0) {
            $gtBalance += abs($ledgerBalance);
            $subSubGrpBalance += abs($ledgerBalance);
            $blncType = '';
        } else {
            $gtBalance -= abs($ledgerBalance);
            $subSubGrpBalance -= abs($ledgerBalance);
            $blncType = '-';
        }
    }
    if ($accountCodeCategory->code == 9 && !in_array($accountCode->code, [999999, 999998, 912101])) {
        if ($ledger->balance_type == 2 && $ledgerBalance > 0) {
            $gtBalance = $gtBalance += abs($ledgerBalance);
            $subSubGrpBalance = $subSubGrpBalance += abs($ledgerBalance);
            $blncType = '';
        } else {
            $gtBalance = $gtBalance -= abs($ledgerBalance);
            $subSubGrpBalance = $subSubGrpBalance -= abs($ledgerBalance);
            $blncType = '-';
        }
    }
    
    // For GST Clearing Account (912101)
    if ($accountCodeCategory->code == 9) {
        if ($ledger->chart_id == 912101) {
            if ($ledger->balance_type == 1 && $ledgerBalance > 0) {
                $gtBalance = $gtBalance -= abs($ledgerBalance);
                $subSubGrpBalance = $subSubGrpBalance -= abs($ledgerBalance);
                $blncType = '-';
            } else {
                $gtBalance = $gtBalance += abs($ledgerBalance);
                $subSubGrpBalance = $subSubGrpBalance += abs($ledgerBalance);
                $blncType = '';
            }
        }
    }
    
    if ($subCategory->id == 16 && $accountCode->code == 999998) {
        $subSubGrpBalance += $plRetain;
    } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
        $subSubGrpBalance += $totalRetain;
    }
@endphp
