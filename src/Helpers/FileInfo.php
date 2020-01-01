<?php


namespace App\Helpers;

use App\Helpers\DTO\RemoteFileInfo;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mime\MimeTypes;

/**
 * Class FileInfo
 * @package App\Helpers
 */
class FileInfo
{
    /**
     * @param string $url
     * @return RemoteFileInfo
     * @throws Exception
     */
    public static function getRemoteFileInfoByUrl(string $url): RemoteFileInfo
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        curl_exec($ch);

        $mimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        $result = new RemoteFileInfo(
            self::getUniqueFileName(), // fixme get original filename from url
            MimeTypes::getDefault()->getExtensions($mimeType)[0] ?? null,
            $mimeType,
            curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD)
        );

        curl_close($ch);

        return $result;
    }

    /**
     * @param string|null $extension
     * @return string
     * @throws Exception
     */
    public static function getUniqueFileName(string $extension = null): string
    {
        return Uuid::uuid4()->toString() . ($extension ? '.' . $extension : '');
    }
}
