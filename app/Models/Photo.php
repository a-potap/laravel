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
 *     ),
 *     @OA\Property(
 *          property="id",
 *          title="ID",
 *          description="ID",
 *          format="int64",
 *          example=1,
 *          type="integer"
 *      ),
 *     @OA\Property(
 *          property="date_create",
 *          title="Date Create",
 *          description="Created at",
 *          format="datetime",
 *          example="2020-01-27 17:50:45",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="name",
 *          title="Name",
 *          description="Album name",
 *          example="Some example name",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="name_en",
 *          title="Name EN",
 *          description="Album name english version",
 *          example="Some example name",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="description",
 *          title="Description",
 *          description="Album description",
 *          example="Some description long text",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="description_en",
 *          title="Description EN",
 *          description="Album description english version",
 *          example="Some description long text",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="cover",
 *          title="Cover",
 *          description="Album cover image linc",
 *          example="https://a-potap.ru/albums/foto/lisbon/fase.JPG",
 *          type="string"
 *      )
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

    private string $cover;
    private array $files;

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
    public function getFiles(): array {
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

    public function getCover(): string {
        return url('/albums/foto/'.$this->folder.'/fase.JPG');
    }
}
