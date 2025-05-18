<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Modules\Masters\App\Models\MastersBanner;
use Modules\Masters\App\Models\MasterReferences;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

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
        // dd($this->createImage($users[0]->name));
        DB::beginTransaction();
        try {
            if($this->references->is_active == 1){
                $users = User::where('birth_date','!=', NULL)->orderBy('birth_date', 'DESC')->get();
                $now = date("m-d");
                // $now = "04-01"; // test ex:05-14 (bulan 5 tgl 14)
                
                /* Delete birthday yg sudah ter-create di db jika ada. */
                $check = $this->banner->where('flag_auto','!=',NULL);
                if($check->get()->count() > 0){
                    $check->delete();
                }

                /* Delete temporary images di storage folder */
                $file = new Filesystem;
                $file->cleanDirectory('storage/app/public/tmp');
                
                /* Create new row birthday */
                foreach($users as $u){
                    $birth_date = substr($u->birth_date,5);
                    
                    /* Bulan tgl hari ini sama dengan bulan tgl lahir dan belum tutup usia */
                    if($birth_date == $now && $u->pass_away_status == false){
                        
                        /* Success Generate Image */
                        if($this->createImage($u->name) == true){
                            $this->banner->create([
                                'name' => 'birthday: '.$u->name,
                                'image' => URL::asset('storage/tmp/bday_'.$u->name.'.jpeg'),
                                'on_view' => true,
                                'flag_auto' => $this->references->id,
                                'description' => $this->references->value.' '.$u->name,
                            ]);
                        }
                    }
                }
                DB::commit();
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

    public function createImage($username)
    {
        try {
            $nama = $username;
            $gambar = storage_path('app/public/img/birthday_template.jpeg');
    
            // Load an image from jpeg URL
            $im = imagecreatefromjpeg($gambar);
    
            // font
            $font = storage_path('app/public/font/AncizarSans-VariableFont_wght.ttf');
            $ukuran_font = 27;
    
            // position
            $x = 155;
            $y = 285;
    
            // merangkai text
            $teks = wordwrap("Selamat ulang tahun $nama. Semoga Panjang umur dan selalu diberi kesehatan dan kebahagiaan",35,"\n");

            // Menambahkan teks ke gambar
            imagettftext($im, $ukuran_font, 0, $x, $y, 
                imagecolorallocate($im, 255, 255, 255), 
                $font, $teks);
    
            // simpan
            Imagejpeg($im, storage_path('app/public/tmp/bday_'.$nama.'.jpeg'), 100);
    
            // free memory
            imagedestroy($im);

            return true;

        } catch (\Exception $e) {
            report($e);
            return 'failed create image';
        }
    }
}
