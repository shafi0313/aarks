@extends('frontend.layout.master')
@section('title', 'Card List')
@section('content')
    <?php $p = 'cl';
    $mp = 'cf'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Card Lists of Customer</h3>
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Cust Ref</th>
                                        <th>Card Type</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>A.B.N</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 ;@endphp
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->customer_ref }}</td>
                                            <td>{{ $customer->customer_type }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->abn }}</td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="{{ route('customer.edit', $customer->id) }}" class="edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a><span> || </span>
                                                    <a href="{{ route('customer.delete', $customer->id) }}" class="trash text-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
        
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">
                                                <h1 class="display-1 text-danger">Table Empty</h1>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->
    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": []
            });
        });
    </script>

@stop
