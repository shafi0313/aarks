<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class WebBackupController extends Controller
{
    public function load()
    {
        session()->forget('pass');
        return view('admin.web-backup.password');
    }
    public function setPass(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        session()->put('pass', $request->password);
        $admin    = Admin::find(1);
        if (Hash::check($request->password, $admin->password)) {
            return redirect()->route('backup.index');
        }
        Alert::error('Your password is incorrect');
        return redirect()->back();
    }
    public function index()
    {
        $password = session()->get('pass');
        $admin    = Admin::find(1);
        if ($password && Hash::check($password, $admin->password)) {
            return view('admin.web-backup.index', [
                'backups' => $this->getBackups(),
            ]);
        }
        return redirect()->route('backup.load');
    }

    /**
     * Run Application Files Backup
     * @param  string $value
     * @return [type]        [description]
     */
    public function backupFiles()
    {
        Artisan::call('backup:run', ['--only-files' => true, '--filename' => now()->format('Y-m-d-H-i-s').'_FILES.zip']);
        $output = Artisan::output();

        if (Str::contains($output, 'Backup completed!')) {
            toast('Application Files Backed-up successfully', 'success');
        } else {
            toast('Application Files Backed-up failed', 'error');
        }

        $db = $this->getBackups()->last();
        return $this->downloadBackup($db['filename'], $db['extension']);
        // return redirect()->back();
    }

    /**
     * Run Application DB Backup
     * @param  string $value
     * @return [type]        [description]
     */
    public function backupDb()
    {
        Artisan::call('backup:run', ['--only-db' => true, '--filename' => now()->format('Y-m-d-H-i-s').'_DATABASE.zip']);
        $output = Artisan::output();

        if (Str::contains($output, 'Backup completed!')) {
            toast('Application Database Backed-up successfully', 'success');
        } else {
            toast('Application Database Backed-up failed', 'error');
        }
        $db = $this->getBackups()->last();
        return $this->downloadBackup($db['filename'], $db['extension']);
    }

    private function getBackups()
    {
        $path = public_path('backups');

        // Check if backup-file-path already exist
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $files = File::allFiles($path);
        $backups = collect([]);
        foreach ($files as $dt) {
            $backups->push([
                'filename' => pathinfo($dt->getFilename(), PATHINFO_FILENAME),
                'extension' => pathinfo($dt->getFilename(), PATHINFO_EXTENSION),
                'path' => $dt->getPath(),
                'size' => $dt->getSize(),
                'time' => $dt->getMTime(),
            ]);
        }
        return $backups;
    }

    public function downloadBackup($name, $ext)
    {
        $path = public_path('backups');
        $file = $path . '/' . $name . '.' . $ext;
        $status = Storage::disk('backup')->download($name . '.' . $ext, $name . '.' . $ext);
        return $status;
    }
    public function deleteBackup($name, $ext)
    {
        $path = public_path('backups');
        $file = $path . '/' . $name . '.' . $ext;
        $status = File::delete($file);
        if ($status) {
            toast('Backup deleted successfully', 'success');
        } else {
            toast('Ops! an error occurred, Try Again', 'error');
        }
        return redirect()->back();
    }
    public function restoreLoad(Request $request)
    {
        return view('admin.web-backup.restore');
    }
    public function restore(Request $request)
    {
        $sql         = $request->file('sql');
        $mimeType    = $sql->getClientMimeType();
        $orginalName = $sql->getClientOriginalName();
        if ($mimeType == 'application/sql') {
            // $cmd = "mysql -h localhost -u deshi -p deshi laravel_aarks < $sql";
            // exec($cmd);
            // Artisan::call('migrate:reset', ['--force' => true]);
            $rawSql = file_get_contents($sql);
            // DB::unprepared($rawSql);
        }
        try {
            toast('Backup updated successfully', 'success');
        } catch (\Exception $e) {
            // toast('Ops! an error occurred, Try Again', 'error');
            return $e->getMessage();
        }
        return redirect()->back();
    }



    public function dbdump()
    {
        // return 'k';
        MySql::create()
            ->setDbName(env('DB_DATABASE', 'forge'))
            ->setUserName(env('DB_USERNAME', 'forge'))
            ->setPassword(env('DB_PASSWORD', ''))
            ->dumpToFile(base_path('aarks' . now() . '.sql'));
        Alert::success('Database Dump Successful!', 'Please Check your root directory.');
        return back();
    }
}
