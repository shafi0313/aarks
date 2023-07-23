@component('mail::message')

To<br># {{$customer->name}}
<br>
<p>Your recurring ready to view.
    @php
        info('Recc => '.route('recurring.email_view_report',[($src == 'item'?'item':'service'),open_encrypt($invoice), open_encrypt($client->id)]));
    @endphp
    <a href="{{ route('recurring.email_view_report',[($src == 'item'?'item':'service'),open_encrypt($invoice), open_encrypt($client->id)]) }}"
        class="btn btn-primary pull-right" style="color: #fff;
    background-color: #204d74;
    border-color: #122b40; background-image: none; text-decoration: none; float: right!important;display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;">View Recurring</a>
</p>

{{-- @component('mail::button', ['url' => route('inv.email_view_report',['service',$invoice->inv_no, $client->id])])
View Invoice
@endcomponent --}}
<br><br>

------------

Please contact us immediately if you are unable to detach or download your Invoice.

------------

<br><br>

Thanks,<br>
{{$client->fullname}}<br>
@endcomponent
