<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Modules\Masters\App\Models\MastersBanner;
use Modules\Masters\App\Models\MasterReferences;


class Birthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $banner;
    protected $references;

    public function __construct(MastersBanner $banner, MasterReferences $references)
    {
        parent::__construct();
        $this->banner = $banner;
        $this->references = $references->where('parameter', 'm_automation')
            ->where('description','birthday')->first();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            if($this->references->is_active == 1){
                $users = User::where('birth_date','!=', NULL)->orderBy('birth_date', 'DESC')->get();
                $now = date("m-d");
                // $now = "04-01"; // test ex:05-14 (bulan 5 tgl 14)
                
                /* Hapus birthday yg sudah ter-create jika ada. */
                $check = $this->banner->where('flag_auto', $this->references->id)->get()->count();
                if($check > 0){
                    $this->banner->where('flag_auto', $this->references->id)->delete();
                }
                
                /* Create new row birthday */
                foreach($users as $u){
                    $birth_date = substr($u->birth_date,5);
                    
                    /* Bulan tgl hari ini sama dengan bulan tgl lahir dan belum tutup usia*/
                    if($birth_date == $now && $u->pass_away_status == false){
                        $this->banner->create([
                            'name' => 'birthday: '.$u->name,
                            'image' => URL::asset('images/events/birthday.jpeg'),
                            'on_view' => true,
                            'flag_auto' => $this->references->id,
                            'description' => $this->references->value.' '.$u->name,
                        ]);
                        DB::commit();
                    }
                }
                echo 'success';
            }else{
                echo 'inactive';
            }

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            echo 'failed';
        }
    }
}
