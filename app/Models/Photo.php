<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * @OA\Schema(
 *     title="Photo",
 *     description="Photo album model",
 *     @OA\Xml(
 *         name="Photo"
 *     )
 * )
 */
class Photo extends Model
{
    use HasFactory;

    protected $table = 'photo_albume';

    const CREATED_AT = 'date_create';

    private $basedir = 'albums/foto/';
    private $fileextentions = ['JPG', 'jpg', 'gif'];
    private $_facefile = 'fase.JPG';


    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Date Create",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $date_create;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Album name",
     *      example="Some example name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Name EN",
     *      description="Album name english version",
     *      example="Some example name"
     * )
     *
     * @var string
     */
    public $name_en;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Album description",
     *      example="Some description long text"
     * )
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="Description EN",
     *      description="Album description english version",
     *      example="Some description long text"
     * )
     *
     * @var string
     */
    public $description_en;
    /**
     * @OA\Property(
     *      title="Cover",
     *      description="Album cover image linc",
     *      example="http://a-potap.test/albums/foto/lisbon/fase.JPG"
     * )
     *
     * @var string
     */
    public $cover;

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
                    $files[] = ["file" => $file, "path" => url('/' . $a_cur_dir . "/" . $file)];
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
        return url('/albums/foto/'.$this->folder.'/fase.JPG');
    }
}
