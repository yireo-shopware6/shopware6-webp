<?php declare(strict_types=1);

namespace Yireo\Webp\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WebpExtension extends AbstractExtension
{
    /**
     * @var WebpConvertor
     */
    private WebpConvertor $webpConvertor;

    /**
     * WebpExtension constructor.
     * @param WebpConvertor $webpConvertor
     */
    public function __construct(
        WebpConvertor $webpConvertor
    ) {
        $this->webpConvertor = $webpConvertor;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('webp', [$this->webpConvertor, 'convert']),
        ];
    }
}
