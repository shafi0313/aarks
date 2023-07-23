<div class="row d-flex justify-content-center">
    <div class="col-lg-3"></div>
    <div class="col-lg-8">
        <form id="rollover" action="{{route('client.dep_rollover.post')}} " method="post">
            @csrf
            <input type="hidden" name="client_id" value="{{$depreciation->client_id}}">
            <input type="hidden" name="profession_id" value="{{$depreciation->profession_id}}">
            <div class="form-group row my-4">
                <label for="rollover_year" class="col-sm-3 control-label">Rollover to the Financial Year(New Year)</label>
                <div class="col-sm-9">
                    <select required class="form-control" id="rollover_year" name="rollover_year">
                        <option value="">--Year--</option>
                        @foreach ($periods as $period)
                        <option data-client_id="{{$depreciation->client_id}}" data-profession_id="{{$depreciation->profession_id}}" value="{{$period->year}}">{{$period->year}} </option>
                        @endforeach
                    </select>
                    @error('rollover_year')
                    <span class="text-danger">{{$message}} </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="rollover_asset" class="col-sm-3 control-label">Asset Name</label>
                <div class="col-sm-9">
                    <select required class="form-control @error('rollover_asset') is-invalid @enderror" id="rollover_asset" name="rollover_asset">
                        <option value="">--Plase Select Asset Name--</option>
                    </select>
                    @error('rollover_asset')
                    <span class="text-danger">{{$message}} </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="year" class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                    <input type="submit" value="Submit" class=" btn btn-info btn-sm">
                </div>
            </div>
            <br>
        </form>
        <form id="reinstated" action="{{route('client.dep_reinstated.post')}} " method="post">
            <hr>
            @csrf
            @method('delete')
            <br>
            <div class="form-group row my-4">
                <label for="reinstated_year" class="col-sm-3 control-label">Reinstated form the Financial Year</label>
                <div class="col-sm-9">
                    <select class="form-control" id="reinstated_year" name="reinstated_year">
                        <option value="">--Year--</option>
                        @foreach ($periods as $period)
                        <option value="{{$period->year}}" data-client_id="{{$depreciation->client_id}}" data-profession_id="{{$depreciation->profession_id}}">{{$period->year}} </option>
                        @endforeach
                    </select>
                    @error('reinstated_year')
                    <span class="text-danger">{{$message}} </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="reinstated_asset" class="col-sm-3 control-label">Asset Name</label>
                <div class="col-sm-9">
                    <select class="form-control @error('reinstated_asset') is-invalid @enderror" id="reinstated_asset"
                        name="reinstated_asset">
                        <option value="">--Plase Select Asset Name--</option>
                    </select>
                    @error('reinstated_asset')
                    <span class="text-danger">{{$message}} </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="year" class="col-sm-3 control-label"></label>
                <div class="col-sm-9">
                    <input type="submit" value="Submit" class=" btn btn-info btn-sm">
                </div>
            </div>
        </form>
    </div>
</div>
