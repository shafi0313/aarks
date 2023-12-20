@if ($type == 'ajax-edit')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}" class='text-primary _btn' onclick="ajaxEdit(this)"
        title='@lang('app.edit')'><i class='fa fa-edit'></i></button>
@endif

@if ($type == 'edit')
    <a href="{{ route($route . '.edit', $row->id) }}" class='text-primary _btn' data-bs-placement="top" title='@lang('app.edit')'><i
            class='fa fa-edit'></i> </a>
@endif

@if ($type == 'delete')
    <button data-route="{{ route($route . '.destroy', $row->id) }}" class='_delete text-danger _btn' title='@lang('app.delete')'><i
            class='fa fa-trash'></i></button>
@endif
@if ($type == 'ajax-delete')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}"
        onclick="ajaxDelete(this, '{{ $src }}')" class='text-danger _btn' title='@lang('app.delete')'><i
            class='fa fa-trash' style="vertical-align: middle;"></i></button>
@endif
@if ($type == 'view')
    <a href="{{ route($route . '.show', $row->id) }}" class='text-info _btn' title="@lang('app.show-details')"><i
            class='fa fa-eye'></i></a>
@endif

@if ($type == 'impersonate')
    @canBeImpersonated($row)
    <a href="{{ route('panel.impersonate', $row->id) }}" class="text-success _btn" target="_blank"
        data-bs-placement="top" data-bs-toggle="tooltip" data-bs-original-title="@lang('user.impersonate-user')"
        title="@lang('user.impersonate-user')"><i class="fa btn-white fa-user-secret"></i></a>
    @endCanBeImpersonated
@endif

@if ($type == 'log')
    <a href="{{ route('panel.user.activitlog', $row->id) }}" class="text-warning _btn" target="_blank"
        data-bs-placement="top" data-bs-toggle="tooltip" data-bs-original-title="@lang('activity.log')"
        title="@lang('activity.log')"><i class="fa btn-white fa-history"></i></a>
@endif

@if ($type == 'status')
    <span data-route="{{ $route }}"
        style="font-size: 36px;line-height: 1;vertical-align: middle;cursor: pointer;" data-bs-placement="top"
        data-bs-toggle="tooltip" data-bs-original-title="status" data-value="{{ $row }}"
        onclick="changeStatus(this)" title="">
        @if ($row == 1)
            <i class="fa fa-toggle-on text-success" title="Active"></i>
        @else
            <i class="fa fa-toggle-off text-danger" title="Inactive"></i>
        @endif
    </span>
@endif

@if ($type == 'accept')
    <span data-route="{{ $route }}"
        style="font-size: 20px;line-height: 1;vertical-align: middle;cursor: pointer;" data-value="{{ $row }}"
        onclick="accept(this)" title="Accept">
        <i class="fa-regular fa-circle-check text-primary"></i>
    </span>
@endif

@if ($type == 'reject')
    <span data-route="{{ $route }}"
        style="font-size: 20px;line-height: 1;vertical-align: middle;cursor: pointer;" data-value="{{ $row }}"
        onclick="reject(this)" title="Reject">
        <i class="fa-regular fa-circle-xmark text-danger"></i>
    </span>
@endif

@if ($type == 'complete')
    <span data-route="{{ $route }}"
        style="font-size: 20px;line-height: 1;vertical-align: middle;cursor: pointer;" data-bs-placement="top"
        data-bs-toggle="tooltip" data-bs-original-title="Accept" data-value="{{ $row }}"
        onclick="accept(this)" title="">
        <i class="fa-solid fa-check-double"></i>
    </span>
@endif
