@extends('admin.layout.master')
@section('title', 'Select Profession')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-md-3">

                </div>

                <div class="col-md-4" style="padding-top:20px;">
                    <h2>Select Professions</h2>
                    <form action="#" name="topform">
                        <div class="form-group">
                            <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value">
                                <option>Select Profession</option>
                                @foreach($client->professions as $profession)
                                    <option value="{{route('trial-balance.selectDate',['client' => $client->id,'profession' => $profession->id])}}">{{$profession->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
