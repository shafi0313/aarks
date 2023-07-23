<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
    style="text-align:center; border-bottom:2px solid #999999;">
    <tr>
        <td style="text-align: center;width: 20%;">
            <img style="margin-right: 25px;" src="{{ asset(client()->logo ?? 'frontend/assets/images/logo/focus-icon.png') }}" />
        </td>
        <td style="text-align: center;width: 60%;">
            <span style="font-size:22px; font-weight:800;">
                {{ client()->company ?? client()->first_name . ' ' . client()->last_name }}</span> <br />
                ABN : {{ client()->abn_number }}<br>
                Address : {{ client()->street_address }}, {{ client()->suburb }}, {{ client()->state }}<br />
                Phone: {{ client()->phone }}<br /><span style="font-size:12px;">
                E-mail:{{ client()->email }}, visit : <a href="{{ client()->website }}">{{ client()->website }}</a>
            </span>
        </td>
        <td style="text-align: center; width: 20%; visibility:hidden">
        </td>
    </tr>
</table>
