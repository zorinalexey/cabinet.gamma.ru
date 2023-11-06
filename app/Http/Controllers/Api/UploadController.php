<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class UploadController extends Controller
{
    public function editorUpload(string $path, Request $request): void
    {
        $fileUrl = false;
        $message = 'Ошибка загрузки файла на сервер. Попробуйте еще раз или обратитесь к системному администратору';
        $config = config('company_details');
        if (isset($_FILES['upload']) && ! $_FILES['upload']['error']) {
            Log::debug('file', $_FILES);
            $ext = preg_replace('~^(.+)\.(\w{2,5})$~u', '$2', $_FILES['upload']['name']);
            $filePath = $config['root_catalog'].'/public/storage/files/'.$path.'/'.date('d.m.Y').'/'.time().rand().'.'.$ext;
            $dir = dirname($filePath);
            if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
            if (file_put_contents($filePath, file_get_contents($_FILES['upload']['tmp_name']))) {
                $message = 'Файл успешно загружен';
                $fileUrl = str_replace($config['root_catalog'].'/public', '', $filePath);
            }

        }
        echo '<script type="text/javascript">
        window.parent.CKEDITOR.tools.callFunction("'.$request->query('CKEditorFuncNum').'", "'.$fileUrl.'", "'.$message.'" );
        </script>';
    }
}
