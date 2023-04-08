<?php

namespace App\Helpers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageSaver
{
    /**
     * Сохраняет изображение при создании или редактировании категории,
     * бренда или товара; создает два уменьшенных изображения.
     *
     * @param Request $request — объект HTTP-запроса
     * @param Item $item — модель категории, бренда или товара
     * @param string $dir — директория, куда будем сохранять изображение
     * @return string|null — имя файла изображения для сохранения в БД
     */
    public function upload(Request $request, Item $item, string $dir): ?string
    {
        $name = $item->image ?? null;
        if ($item && $request->remove) {
            $this->remove($item, $dir);
            $name = null;
        }
        $source = $request->file('image');
        if ($source) {
            if ($item && $item->image) {
                $this->remove($item, $dir);
            }
            $ext = $source->extension();
            $path = $source->store('catalog/' . $dir . '/source', 'public');
            $path = Storage::disk('public')->path($path);
            $name = basename($path);
            $dst = 'catalog/' . $dir . '/image/';
            $this->resize($path, $dst, 400, 400, $ext);
            $dst = 'catalog/' . $dir . '/thumb/';
            $this->resize($path, $dst, 400, 200, $ext);
        }
        return $name;
    }

    /**
     * Создает уменьшенную копию изображения
     *
     * @param string $src — путь к исходному изображению
     * @param string $dst — куда сохранять уменьшенное
     * @param int $width — ширина в пикселях
     * @param int $height — высота в пикселях
     * @param string $ext — расширение уменьшенного
     */
    private function resize(string $src, string $dst, int $width, int $height, string $ext): void
    {
        $image = Image::make($src)
            ->heighten($height)
            ->resizeCanvas($width, $height, 'center', false, 'eeeeee')
            ->encode($ext, 100);
        $name = basename($src);
        Storage::disk('public')->put($dst . $name, $image);
        $image->destroy();
    }

    /**
     * Удаляет изображение при удалении категории, бренда или товара
     *
     * @param Item $item — модель категории, бренда или товара
     * @param string $dir — директория, в которой находится изображение
     */
    public function remove(Item $item, string $dir): void
    {
        $old = $item->image;
        if ($old) {
            Storage::disk('public')->delete('catalog/' . $dir . '/source/' . $old);
            Storage::disk('public')->delete('catalog/' . $dir . '/image/' . $old);
            Storage::disk('public')->delete('catalog/' . $dir . '/thumb/' . $old);
        }
    }
}
