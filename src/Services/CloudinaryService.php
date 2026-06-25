<?php

declare(strict_types=1);

namespace Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use GuzzleHttp\Client as GuzzleClient;

class CloudinaryService
{
    private ?UploadApi $uploadApi = null;
    private int $maxFileSize;

    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    private bool $enabled = false;

    public function __construct(?int $maxFileSize = null)
    {
        $this->maxFileSize = $maxFileSize ?? 5 * 1024 * 1024;

        $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
        $apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
        $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

        if ($cloudName === '' || $apiKey === '' || $apiSecret === '' || $apiSecret === 'tu-secret') {
            return;
        }

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]);

        $this->uploadApi = $cloudinary->uploadApi();
        $this->configureSsl();
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Sube una imagen a Cloudinary convertida a WebP.
     *
     * @param array $file Datos del archivo (tmp_name, name, size, error, type)
     * @param string $folder Carpeta en Cloudinary donde guardar (default 'menu')
     * @return array{url: string, public_id: string}
     */
    public function upload(array $file, string $folder = 'menu'): array
    {
        if (!$this->enabled) {
            throw new \RuntimeException('Cloudinary is not configured. Set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET in .env');
        }

        $this->validateFile($file);

        try {
            $result = $this->uploadApi->upload($file['tmp_name'], [
                'folder'          => $folder,
                'format'          => 'webp',
                'resource_type'   => 'image',
                'quality'         => 'auto',
                'overwrite'       => true,
                'unique_filename' => true,
            ]);
        } catch (ApiError $e) {
            throw new \RuntimeException('Error uploading to Cloudinary: ' . $e->getMessage(), 0, $e);
        }

        $url = $result['secure_url'] ?? null;
        $publicId = $result['public_id'] ?? null;

        if (!$url || !$publicId) {
            throw new \RuntimeException('Cloudinary did not return a valid response');
        }

        return ['url' => $url, 'public_id' => $publicId];
    }

    /**
     * Reemplaza la imagen de un producto: sube la nueva y, solo si tiene éxito,
     * borra la anterior. Evita perder la imagen vieja si la nueva subida falla.
     *
     * @param array $file Datos del nuevo archivo
     * @param string|null $oldPublicId public_id de la imagen anterior
     * @param string $folder Carpeta en Cloudinary (default 'menu')
     * @return array{url: string, public_id: string}
     */
    public function replace(array $file, ?string $oldPublicId, string $folder = 'menu'): array
    {
        $newImage = $this->upload($file, $folder);

        if ($oldPublicId !== null && $oldPublicId !== '') {
            try {
                $this->destroy($oldPublicId);
            } catch (\RuntimeException $e) {
                error_log('[CloudinaryService] Could not delete old image (' . $oldPublicId . '): ' . $e->getMessage());
            }
        }

        return $newImage;
    }

    /**
     * Borra una imagen de Cloudinary por su public_id.
     * Trata "not found" como éxito silencioso (idempotente).
     *
     * @throws \RuntimeException si ocurre un error real de API (red, credenciales, etc.)
     */
    public function destroy(string $publicId): bool
    {
        if (!$this->enabled) {
            return false;
        }

        if (trim($publicId) === '') {
            throw new \InvalidArgumentException('public_id is empty, cannot delete');
        }

        try {
            $result = $this->uploadApi->destroy($publicId, [
                'resource_type' => 'image',
            ]);
        } catch (ApiError $e) {
            throw new \RuntimeException('Error deleting image from Cloudinary: ' . $e->getMessage(), 0, $e);
        }

        $status = $result['result'] ?? '';

        return in_array($status, ['ok', 'not found'], true);
    }

    public function extractPublicId(string $secureUrl): ?string
    {
        $path = parse_url($secureUrl, PHP_URL_PATH);

        if ($path === null) {
            return null;
        }

        $parts = explode('/', $path);

        // Find the Cloudinary version marker (v12345)
        $versionIndex = null;
        $lastIndex = count($parts) - 1;

        foreach ($parts as $i => $part) {
            if (preg_match('/^v\d+$/', $part)) {
                $versionIndex = $i;
                break;
            }
        }

        if ($versionIndex === null || !isset($parts[$lastIndex])) {
            return null;
        }

        $filename = $parts[$lastIndex];
        $publicId = pathinfo($filename, PATHINFO_FILENAME);

        // Elements between version and filename form the folder path
        $folderParts = array_slice($parts, $versionIndex + 1, $lastIndex - $versionIndex - 1);

        if (!empty($folderParts)) {
            $folder = implode('/', $folderParts);
            return "$folder/$publicId";
        }

        return $publicId;
    }

    private function configureSsl(): void
    {
        $iniCainfo = ini_get('curl.cainfo');
        $iniCafile = ini_get('openssl.cafile');

        if ($iniCainfo !== '' || $iniCafile !== '') {
            return;
        }

        $cacertPath = __DIR__ . '/../../cacert.pem';

        if (!file_exists($cacertPath)) {
            $context = stream_context_create([
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);

            $content = @file_get_contents('https://curl.se/ca/cacert.pem', false, $context);

            if ($content !== false) {
                file_put_contents($cacertPath, $content);
            }
        }

        if (!file_exists($cacertPath)) {
            return;
        }

        try {
            $ref = new \ReflectionProperty($this->uploadApi, 'apiClient');
            $ref->setAccessible(true);
            $apiClient = $ref->getValue($this->uploadApi);

            $apiClient->httpClient = new GuzzleClient([
                'base_uri' => $apiClient->getBaseUri(),
                'verify'   => $cacertPath,
                'auth'     => [
                    $_ENV['CLOUDINARY_API_KEY'] ?? '',
                    $_ENV['CLOUDINARY_API_SECRET'] ?? '',
                ],
                'connect_timeout' => 30,
                'timeout'         => 60,
                'http_errors'     => false,
                'headers'         => ['User-Agent' => 'CloudinaryPHP/'],
            ]);
        } catch (\ReflectionException $e) {
            // SSL config failed; will rely on system defaults
        }
    }

    private function validateFile(array $file): void
    {
        if (!isset($file['tmp_name'])) {
            throw new \InvalidArgumentException('No file was uploaded or file is invalid');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $messages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds server upload max size',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form max size',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            ];
            throw new \RuntimeException($messages[$file['error']] ?? 'Unknown upload error');
        }

        if ($file['size'] > $this->maxFileSize) {
            $maxMb = $this->maxFileSize / 1024 / 1024;
            throw new \InvalidArgumentException("File size exceeds maximum allowed size of {$maxMb}MB");
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }
    }
}
