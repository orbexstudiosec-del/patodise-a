<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoPreviewController extends Controller
{
    /**
     * Sirve la versión PROTEGIDA (con marca de agua) de la foto.
     * - Imágenes locales → JPEG con texto en diagonal generado con GD (cacheado).
     * - URLs externas → redirect (la protección queda en la capa CSS).
     */
    public function __invoke(Photo $photo, string $size = 'thumb')
    {
        $size = in_array($size, ['thumb', 'full']) ? $size : 'thumb';
        $path = $photo->image_path;

        if (! $path) {
            abort(404);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return redirect()->away($size === 'thumb' ? $photo->thumbnail_url : $photo->image_url);
        }

        if (! extension_loaded('gd')) {
            return Storage::disk('public')->response($path);
        }

        $disk = Storage::disk('public');
        $cacheKey = "previews/{$size}/{$photo->id}-" . md5($photo->updated_at . $path) . '.jpg';

        if (! $disk->exists($cacheKey)) {
            $srcAbs = $disk->path($path);
            if (! file_exists($srcAbs)) {
                abort(404);
            }
            $jpeg = $this->renderWatermarked($srcAbs, $size === 'full' ? 1600 : 900);
            $disk->put($cacheKey, $jpeg);
        }

        return new Response($disk->get($cacheKey), 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=2592000',
            'X-Robots-Tag' => 'noimageindex',
        ]);
    }

    private function renderWatermarked(string $srcPath, int $maxWidth): string
    {
        $info = @getimagesize($srcPath);
        if (! $info) {
            throw new \RuntimeException('Imagen no válida.');
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($srcPath),
            IMAGETYPE_PNG => imagecreatefrompng($srcPath),
            IMAGETYPE_WEBP => imagecreatefromwebp($srcPath),
            default => throw new \RuntimeException('Formato no soportado.'),
        };

        $w = imagesx($src);
        $h = imagesy($src);
        if ($w > $maxWidth) {
            $newH = (int) round($h * $maxWidth / $w);
            $dst = imagecreatetruecolor($maxWidth, $newH);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);
            imagedestroy($src);
            $src = $dst;
            $w = $maxWidth;
            $h = $newH;
        }

        // === Sello con texto upscaleado para que se lea bien ===
        $text = 'PATO DISEÑA';
        $font = 5; // built-in font: 9×15 px
        $charW = imagefontwidth($font);
        $charH = imagefontheight($font);
        $textW = strlen($text) * $charW;

        // Rederizamos en pequeño y upscaleamos 4× para que el texto se vea grande sin TTF
        $scale = 4;
        $small = imagecreatetruecolor($textW, $charH);
        imagesavealpha($small, true);
        $transparent = imagecolorallocatealpha($small, 0, 0, 0, 127);
        imagefill($small, 0, 0, $transparent);
        $white = imagecolorallocate($small, 255, 255, 255);
        imagestring($small, $font, 0, 0, $text, $white);

        $bigW = $textW * $scale;
        $bigH = $charH * $scale;
        $big = imagecreatetruecolor($bigW, $bigH);
        imagesavealpha($big, true);
        $bigTransparent = imagecolorallocatealpha($big, 0, 0, 0, 127);
        imagefill($big, 0, 0, $bigTransparent);
        imagecopyresampled($big, $small, 0, 0, 0, 0, $bigW, $bigH, $textW, $charH);
        imagedestroy($small);

        // Aplicar “contorno” oscuro estampando una sombra negra detrás
        $stampW = $bigW + 8;
        $stampH = $bigH + 8;
        $stamp = imagecreatetruecolor($stampW, $stampH);
        imagesavealpha($stamp, true);
        $stTrans = imagecolorallocatealpha($stamp, 0, 0, 0, 127);
        imagefill($stamp, 0, 0, $stTrans);

        // Sombra: copiar el big en negro con offset
        $shadow = imagecreatetruecolor($bigW, $bigH);
        imagesavealpha($shadow, true);
        imagefill($shadow, 0, 0, $stTrans);
        // Pintar texto negro sobre shadow
        $blackText = imagecolorallocate($shadow, 0, 0, 0);
        for ($dx = -1; $dx <= 1; $dx++) {
            for ($dy = -1; $dy <= 1; $dy++) {
                imagestring($shadow, $font, $dx, $dy, $text, $blackText);
            }
        }
        $shadowBig = imagecreatetruecolor($bigW, $bigH);
        imagesavealpha($shadowBig, true);
        imagefill($shadowBig, 0, 0, $stTrans);
        imagecopyresampled($shadowBig, $shadow, 0, 0, 0, 0, $bigW, $bigH, $textW, $charH);
        imagedestroy($shadow);

        // componer sombra + texto blanco sobre el stamp
        imagecopy($stamp, $shadowBig, 4, 4, 0, 0, $bigW, $bigH);
        imagedestroy($shadowBig);
        imagecopy($stamp, $big, 4, 4, 0, 0, $bigW, $bigH);
        imagedestroy($big);

        // Rotar sello
        $rotated = imagerotate($stamp, 25, $stTrans);
        imagedestroy($stamp);
        imagesavealpha($rotated, true);
        imagealphablending($rotated, false);

        $rw = imagesx($rotated);
        $rh = imagesy($rotated);

        // Tiling — separación calculada para 3-4 columnas y 4-5 filas según el tamaño
        $stepX = (int) max($rw * 0.9, $w / 3.5);
        $stepY = (int) max($rh * 2.5, $h / 4.5);

        imagealphablending($src, true);
        for ($y = -$rh; $y < $h + $rh; $y += $stepY) {
            for ($x = -$rw; $x < $w + $rw; $x += $stepX) {
                imagecopymerge_alpha($src, $rotated, $x, $y, 0, 0, $rw, $rh, 75);
            }
        }
        imagedestroy($rotated);

        // === Crédito grande en la parte inferior ===
        $credit = '© patodisena.ec  ·  vista previa  ·  0968179682';
        $creditW = strlen($credit) * $charW * 2;
        $creditH = $charH * 2 + 12;
        $bgY = $h - $creditH;
        // Franja semi-transparente
        $stripe = imagecreatetruecolor($w, $creditH);
        $black = imagecolorallocate($stripe, 0, 0, 0);
        imagefill($stripe, 0, 0, $black);
        imagecopymerge($src, $stripe, 0, $bgY, 0, 0, $w, $creditH, 45);
        imagedestroy($stripe);

        // Texto del crédito (también upscaleado)
        $cSmall = imagecreatetruecolor(strlen($credit) * $charW, $charH);
        imagesavealpha($cSmall, true);
        $cTrans = imagecolorallocatealpha($cSmall, 0, 0, 0, 127);
        imagefill($cSmall, 0, 0, $cTrans);
        $cWhite = imagecolorallocate($cSmall, 255, 255, 255);
        imagestring($cSmall, $font, 0, 0, $credit, $cWhite);
        $cBig = imagecreatetruecolor(strlen($credit) * $charW * 2, $charH * 2);
        imagesavealpha($cBig, true);
        imagefill($cBig, 0, 0, $cTrans);
        imagecopyresampled($cBig, $cSmall, 0, 0, 0, 0, strlen($credit) * $charW * 2, $charH * 2, strlen($credit) * $charW, $charH);
        imagedestroy($cSmall);

        $cBigW = imagesx($cBig);
        $cBigH = imagesy($cBig);
        $cx = ($w - $cBigW) / 2;
        $cy = $h - $creditH + ($creditH - $cBigH) / 2;
        imagecopy($src, $cBig, (int) $cx, (int) $cy, 0, 0, $cBigW, $cBigH);
        imagedestroy($cBig);

        ob_start();
        imagejpeg($src, null, 82);
        $jpeg = ob_get_clean();
        imagedestroy($src);

        return $jpeg;
    }
}

/**
 * Helper: imagecopymerge respetando alpha del origen.
 * GD nativo pierde el canal alpha con imagecopymerge.
 */
if (! function_exists('imagecopymerge_alpha')) {
    function imagecopymerge_alpha($dst, $src, int $dstX, int $dstY, int $srcX, int $srcY, int $srcW, int $srcH, int $pct): void
    {
        $cut = imagecreatetruecolor($srcW, $srcH);
        imagesavealpha($cut, true);
        $transparent = imagecolorallocatealpha($cut, 0, 0, 0, 127);
        imagefill($cut, 0, 0, $transparent);
        // capturar el fondo subyacente
        imagecopy($cut, $dst, 0, 0, $dstX, $dstY, $srcW, $srcH);
        imagecopy($cut, $src, 0, 0, $srcX, $srcY, $srcW, $srcH);
        imagecopymerge($dst, $cut, $dstX, $dstY, 0, 0, $srcW, $srcH, $pct);
        imagedestroy($cut);
    }
}
