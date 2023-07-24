<table id="dynamic-table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="center">SN</th>
            <th>Company Name</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th class="hidden-480">E-mail</th>
            <th width="63px">Pay Status</th>
            <th>{{ $from == 'client_index' ? 'Mobile' : 'ABN Number' }}</th>
            @canany(['admin.client.edit', 'admin.bs_import.edit'])
                <th style="width: 75px">Action</th>
            @endcanany
        </tr>
    </thead>

    <tbody>
        @php $x=1 @endphp
        @foreach ($clients as $index => $row)
            <tr>
                <td class="center">{{ $x++ }}</td>
                <td>{{ $row->company }}</td>
                <td>{{ $row->first_name }}</td>
                <td>{{ $row->last_name }}</td>
                <td>{{ $row->email }}</td>                
                <td class="text-center">
                    @isset ($row->payment)
                    <span class="label label-sm label-success">Active</span>
                @else                    
                    <span class="label label-sm label-danger">Expired</span>
                @endisset</td>
                @if ($from == 'client_index')   
                <td>{{ $row->phone }}</td>                 
                    @canany(['admin.client.edit'])
                        <td>
                            <div>
                                <a class="green" title="Client Edit" href="{{ route('client.edit', $row->id) }}"
                                    title="Edit">
                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                </a> ||
                                <a class="_delete" title="Client Delete"
                                    data-route="{{ route('client.destroy', $row->id) }}" title="Delete"><i
                                        class="ace-icon fa fa-trash-o bigger-130 red"></i></a> ||
                                <a class="blue" title="Client Impersonate" href="{{ route('impersonate', $row->id) }}"
                                    target="_blank" title="Client panel">
                                    <i class="ace-icon fa fa-eye bigger-130"></i>
                                </a> ||
                                <a class="purple" title="Client Check 2FA" href="{{ route('2fa.index', $row->id) }}"
                                    target="_blank" title="Show/Set QR Code">
                                    <i class="ace-icon fa fa-qrcode bigger-130"></i>
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'import_index')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.edit', 'admin.bs_import.create'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('bs_import.professions', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'trial_balance_index')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.edit', 'admin.bs_import.create'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('trial-balance.professions', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'console_trial_balance_index')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.edit', 'admin.bs_import.create'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('console_trial_balance.date', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'verify_account_index')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.verify_account.index'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('verify_account.profession', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'input_index')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.edit', 'admin.bs_import.create'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('bs_input.professions', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'imp_tran_list')
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.edit', 'admin.bs_import.create', 'admin.bs_input.edit',
                        'admin.bs_input.create'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('bs_tran_list.profession', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @elseif($from == 'period_lock')
                    <td>{{ $row->abn_number }}</td>
                    @can(['admin.period_lock.index'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('period_lock.client', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcan
                @elseif($from == 'client_fixed_code')
                    <td>{{ $row->abn_number }}</td>
                    {{-- @can(['admin.client_fixed_code.index']) --}}
                    <td>
                        <div>
                            <a class="orange" href="{{ route('client_fixed_code.profession', $row->id) }}">
                                Select Client
                            </a>
                        </div>
                    </td>
                    {{-- @endcan --}}
                @elseif($from == 'client_data_delete')
                    <td>{{ $row->abn_number }}</td>
                    @can(['admin.client_data_delete.delete'])
                        <td>
                            <div>
                                <form action="{{ route('client.data.destroy', $row->id) }}" method="post">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="confirm('You will never restore data.')">Delete</button>
                                </form>
                            </div>
                        </td>
                    @endcan
                @else
                    <td>{{ $row->abn_number }}</td>
                    @canany(['admin.bs_import.create', 'admin.bs_import.edit'])
                        <td>
                            <div>
                                <a class="orange" href="{{ route('general_ledger.date', $row->id) }}">
                                    Select Client
                                </a>
                            </div>
                        </td>
                    @endcanany
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
