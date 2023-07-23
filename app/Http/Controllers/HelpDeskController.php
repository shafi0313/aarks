<?php

namespace App\Http\Controllers;

use App\Models\HelpDesk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\StoreHelpDeskRequest;
use App\Http\Requests\UpdateHelpDeskRequest;

class HelpDeskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.index')) {
            return $error;
        }
        $desks = HelpDesk::with('subCategories')->whereParentId(0)->orderBy('title')->get();
        return view('admin.helpdesk.index', compact('desks'));
    }
    public function subcategory(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.index')) {
            return $error;
        }
        if ($request->ajax()) {
            $id = $request->id;
            $subcategories = HelpDesk::whereParentId($id)->orderBy('title')->get();
            $html = view('admin.helpdesk.sub-cat', compact(['subcategories', 'id']))->render();
            return response()->json(['tr' => $html, 'status' => 200, 'message' => 'success']);
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreHelpDeskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHelpDeskRequest $request)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.store')) {
            return $error;
        }
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);
        // Store thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/'.$request->file('thumbnail')->store('helpdesk/thumbnail', 'public');
        }
        try {
            HelpDesk::create($data);
            Alert::success('Help Topic Created');
        } catch (\Exception $e) {
            return $e->getMessage();
            Alert::error('Oops Server Side Error');
        }
        return redirect()->back();
    }
    public function category(Request $request)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.store')) {
            return $error;
        }
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:help_desks',
        ]);
        $data['parent_id'] = 0;
        $data['slug'] = Str::slug($request->name);
        // Store thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/'.$request->file('thumbnail')->store('helpdesk/thumbnail', 'public');
        }
        try {
            HelpDesk::create($data);
            Alert::success('Help Desk Category Created');
        } catch (\Exception $e) {
            // return $e->getMessage();
            Alert::error('Oops Server Side Error');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HelpDesk  $helpdesk
     * @return \Illuminate\Http\Response
     */
    public function show(HelpDesk $helpdesk)
    {
        //
    }
    /**
     * Display the specified resource by category.
     *
     * @return \Illuminate\Http\Response
     */
    public function byItem(HelpDesk $helpdesk)
    {
        return view('frontend.helpdesk.index', compact('helpdesk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HelpDesk  $helpdesk
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, HelpDesk $helpdesk)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $desks = HelpDesk::orderBy('name')->whereParentId(0)->get(['name', 'id']);
            return view('admin.helpdesk.edit', compact(['helpdesk','desks']))->render();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHelpDeskRequest  $request
     * @param  \App\HelpDesk  $helpdesk
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHelpDeskRequest $request, HelpDesk $helpdesk)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.update')) {
            return $error;
        }
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/'.$request->file('thumbnail')->store('helpdesk/thumbnail', 'public');
        } else {
            $data['thumbnail'] = $helpdesk->thumbnail;
        }
        try {
            $helpdesk->update($data);
            Alert::success('Help Desk Updated');
        } catch (\Exception $e) {
            return $e->getMessage();
            Alert::error('Oops Server Side Error');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HelpDesk  $helpdesk
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpDesk $helpdesk)
    {
        if ($error = $this->sendPermissionError('admin.helpdesk.delete')) {
            return $error;
        }
        try {
            $helpdesk->delete();
            Alert::success('Help Topic Deleted');
        } catch (\Exception $e) {
            return $e->getMessage();
            Alert::error('Oops Server Side Error');
        }
        return redirect()->back();
    }
}
