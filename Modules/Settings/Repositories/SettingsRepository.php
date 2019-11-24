<?php namespace Modules\Settings\Repositories;

use Exception;
use Modules\Settings\Entities\Setting as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use stdClass;
use Modules\Core\Facades\FileUpload;

class SettingsRepository implements SettingsInterface
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        $data = new stdClass();
        foreach ($this->model->get() as $model) {
            $value = is_numeric($model->value) ? (int)$model->value : $model->value;
            $key_name = $model->key_name;
            $data->$key_name = $value;
        }

        return $data;
    }

    public function store(array $data)
    {
        $data = array_except($data, ['_token', 'exit']);

        if (isset($data['image']) && $data['image']== 'delete') {
            $data['image'] = null;
        }

        if (Request::hasFile('image')) {
            $file = FileUpload::handle(Request::file('image'), 'uploads/settings');
            $data['image'] = $file['filename'];
        }

        foreach ($data as $key_name => $value) {
            $model = $this->model->where('key_name', $key_name)->first();
            $model = $model ? $model : new $this->model();
            $model->key_name = $key_name;
            $model->value = $value;
            $model->save();
        }

        return true;
    }

    /**
     * Delete image.
     *
     * @return void
     */
    public function deleteImage()
    {
        $row = $this->model->where('key_name', 'image')->first();
        $filedir = '/uploads/settings/';
        $filename = $row->value;
        $row->value = null;
        $row->save();
        try {
            // Croppa::delete($filedir . $filename);
            File::delete(public_path() . $filedir . $filename);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Build Settings Array.
     *
     * @return array
     */
    public function allToArray()
    {
        $config = [];
        try {
            foreach (DB::table('settings')->get() as $object) {
                $key = $object->key_name;
                $config[$key] = $object->value;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $config;
    }
}
