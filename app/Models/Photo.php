<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photo_albume';

    const CREATED_AT = 'date_create';

    private $basedir = 'albums/foto/';
    private $fileextentions = ['JPG', 'jpg', 'gif'];
    private $_facefile = 'fase.JPG';

    public function getLocalizedName() {
        if(App::isLocale('en') && $this->name_en) {
            return $this->name_en;
        }
        return $this->name;
    }
    public function getLocalizedDescription() {
        if(App::isLocale('en') && $this->description_en) {
            return $this->description_en;
        }
        return $this->description;
    }
    public function getFiles() {
        $files = [];
        $a_cur_dir = $this->basedir . $this->folder;
        if (is_dir($a_cur_dir) && $handledir = opendir($a_cur_dir)) {
            while (false !== ($file = readdir($handledir))) {
                if (!(($file == ".") ||
                        ($file == "..") ||
                        ($file == $this->_facefile)
                    ) &&
                    in_array(substr($file, strlen($file) - 3, 3), $this->fileextentions)
                )
                    $files[] = ["file" => $file, "path" => '/' . $a_cur_dir . "/" . $file];
            }

            closedir($handledir);

            usort($files, function ($v1, $v2) {
                if ($v1["file"] == $v2["file"]) return 0;
                return ($v1["file"] < $v2["file"]) ? -1 : 1;
            });
        }

        return $files;
    }

    public function getCover() {
        return '';
    }
}
