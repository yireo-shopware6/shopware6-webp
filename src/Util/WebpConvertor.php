<?php declare(strict_types=1);

namespace Yireo\Webp\Util;

use League\Flysystem\FileNotFoundException;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\HttpKernel\KernelInterface;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

class WebpConvertor
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var UrlPackage
     */
    private $urlPackage;

    /**
     * WebpConvertor constructor.
     * @param KernelInterface $kernel
     * @param UrlPackage $urlPackage
     */
    public function __construct(
        KernelInterface $kernel,
        UrlPackage $urlPackage
    ) {
        $this->kernel = $kernel;
        $this->urlPackage = $urlPackage;
    }

    /**
     * @param string $imageUrl
     * @return string
     */
    public function convertImageUrl(string $imageUrl): string
    {
        $imagePath = $this->getFileFromImageUrl($imageUrl);
        $webpPath = preg_replace('/\.(png|jpg)$/', '.webp', $imagePath);
        if ($this->shouldConvert($imagePath, $webpPath) === false) {
            return $imageUrl;
        }

        $options = $this->getOptions();

        try {
            WebPConvert::convert($imagePath, $webpPath, $options);
        } catch (ConversionFailedException $e) {
            return $imageUrl;
        }

        $webpUrl = preg_replace('/\.(png|jpg)$/', '.webp', $imageUrl);
        return $webpUrl;
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [];
        $options['metadata'] = 'none';
        $options['quality'] = 100;

        return $options;
    }

    /**
     * @param $imagePath
     * @param $webpPath
     * @return bool
     */
    private function shouldConvert($imagePath, $webpPath): bool
    {
        if ($imagePath === $webpPath) {
            return false;
        }

        if (!file_exists($webpPath)) {
            return true;
        }

        if (filemtime($imagePath) < filemtime($webpPath)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $imageUrl
     * @return string
     * @throws FileNotFoundException
     */
    private function getFileFromImageUrl(string $imageUrl): string
    {
        $imagePath = $this->getPublicDirectory() . str_replace($this->urlPackage->getBaseUrl($imageUrl), '', $imageUrl);
        if (!file_exists($imagePath)) {
            throw new FileNotFoundException($imagePath);
        }

        return $imagePath;
    }

    /**
     * @return string
     */
    private function getPublicDirectory(): string
    {
        return $this->kernel->getProjectDir() . '/public';
    }
}
