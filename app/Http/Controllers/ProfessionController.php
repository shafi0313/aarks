<?php namespace App\Http\Controllers;

use http\Client;
use http\Exception;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\IndustryCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\ProfessionActions\AddProfession;
use App\Actions\ProfessionActions\EditProfession;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.profession.index')) {
		return $error;
        }
        $professions = Profession::with('industryCategories')->get();
        return view('admin.profession.index', compact('professions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($error = $this->sendPermissionError('admin.profession.create')) {
            return $error;
        }
        $industry_categories = IndustryCategory::all();
        return view('admin.profession.create', compact('industry_categories'));
    }


    public function store(Request $request, AddProfession $addProfession)
    {
        if ($error = $this->sendPermissionError('admin.profession.create')) {
            return $error;
        }
        $this->validate($request, [
            'name'              => 'required',
            'industry_category' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $addProfession->setData(['name' => $request->name, 'industry_category' => $request->industry_category])->execute();
            DB::commit();
            Alert::success('Profession Add', 'Profession added to the system system successfully')->autoClose(3000);
        }catch (\Exception $exception) {
            DB::rollBack();
            Alert::error('Error', $exception->getMessage())->autoClose(3000);
        }
        return redirect()->route('profession.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function show(Profession $profession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function edit(Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.profession.edit')) {
            return $error;
        }
        $industry_categories = IndustryCategory::all();
        $profession->load('industryCategories');
         return view('admin.profession.edit', compact('profession', 'industry_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profession $profession, EditProfession $editProfession)
    {
        if ($error = $this->sendPermissionError('admin.profession.edit')) {
            return $error;
        }
        $this->validate($request, ['name' => 'required']);

        $editProfession->setInstance($profession)
            ->setData(['name' => $request->name])
            ->execute();

        Alert::success('Profession Update', 'Profession updated successfully')->autoClose(3000);
        return redirect()->route('profession.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profession  $profession
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profession $profession)
    {
        if ($error = $this->sendPermissionError('admin.profession.delete')) {
            return $error;
        }
        // Check Client
//        $number_of_clients = $profession->clients->count();
//        if ($number_of_clients) {
//            Alert::error('Profession Delete', "$number_of_clients client(s) exists in this profession");
//        }  else {
//            DB::table('industry_category_profession')->where('profession_id', $profession->id)->delete();
//            DB::table('account_code_profession')->where('profession_id', $profession->id)->delete();
//            DB::table('client_professions')->where('profession_id', $profession->id)->delete();
//            DB::table('account_code_category_profession')->where('profession_id', $profession->id)->delete();
//            DB::table('client_account_code')->where('profession_id', $profession->id)->delete();
//            DB::table('industry_category_profession_account_code')->where('profession_account_code_id',$profession->id)->delete();
            $profession->delete();
            Alert::success('Profession Deleted', 'Profession deleted successfully')->autoClose(3000);
//        }
        return redirect()->route('profession.index');
    }
}
