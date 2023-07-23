<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddServiceRequest;
use RealRashid\SweetAlert\Facades\Alert;

class ServiceController extends Controller
{
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.service.index')) {
            return $error;
        }
        $services = Service::all();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        if ($error = $this->sendPermissionError('admin.service.create')) {
            return $error;
        }

        return view('admin.service.create');
    }

    public function store(AddServiceRequest $request)
    {
        if ($error = $this->sendPermissionError('admin.service.create')) {
            return $error;
        }
        DB::beginTransaction();

        try {
            Service::create(['name' => $request->name]);
            DB::commit();
            Alert::success('Service Add', 'Service added to the system system successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Error', $exception->getMessage());
        }
        return redirect()->route('service.index');
    }

    public function edit(Service $service)
    {
        if ($error = $this->sendPermissionError('admin.service.edit')) {
            return $error;
        }
        return view('admin.service.edit', compact('service'));
    }

    public function update(AddServiceRequest $request, Service $service)
    {
        if ($error = $this->sendPermissionError('admin.service.edit')) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $service->update([
              'name' => $request->name,
            ]);
            DB::commit();
            Alert::success('Service Update', 'Service updated successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Service Update', $exception->getMessage());
        }
        return redirect()->route('service.index');
    }
}
