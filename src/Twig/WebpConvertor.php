<?php declare(strict_types=1);

namespace Yireo\Webp\Twig;

use Shopware\Production\Kernel;

class WebpConvertor
{
    /**
     * @var Kernel
     */
    private Kernel $kernel;

    /**
     * WebpConvertor constructor.
     * @param Kernel $kernel
     */
    public function __construct(
        Kernel $kernel
    ) {
        $this->kernel = $kernel;
    }

    /**
     * @param string $image
     * @return string
     */
    public function convert(string $image): string
    {
        return $image;
    }

    /**
     * @return string
     */
    private function getMediaDirectory(): string
    {
        return $this->kernel->getProjectDir() . '/media/';
    }
}
