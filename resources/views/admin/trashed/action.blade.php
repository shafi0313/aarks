<div class="action">
    <form action="{{ route('trash.restore', [$item->client_id, $item->id, $src]) }}" method="post" style="display: inline">
        @csrf @method('put')
        <button title="Restore" type="submit" class="btn btn-sm btn-warning"
            onclick="return confirm('Are you sure?')">
            <i class="fas fa-recycle"></i>
        </button>
    </form>
    <form action="{{ route('trash.restore', [$item->client_id, $item->id, $src]) }}" method="post" style="display: inline">
        @csrf @method('delete')
        <button title="Permanent Delete" type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('Are you sure?')">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</div>
