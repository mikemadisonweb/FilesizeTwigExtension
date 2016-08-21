<?php

namespace Madison\Twig;

/**
 * Class FileSizeExtension
 * @package AppBundle\Twig
 */
class FileSizeExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $suffixes = [
            // Binary
            'iec' => [
                'bits' => ["b", "Kib", "Mib", "Gib", "Tib", "Pib", "Eib", "Zib", "Yib"],
                'bytes' => ["B", "KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"]
            ],
            'jedec' => [
                'bits' => ["b", "Kb", "Mb", "Gb", "Tb", "Pb", "Eb", "Zb", "Yb"],
                'bytes' => ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"]
            ]
        ];

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('human_readable', array($this, 'getHumanReadable')),
        ];
    }

    /**
     * Get human readable string from (bytes|bits)
     * @param string|int $input
     * @param int $decimals
     * @param string $spacer
     * @param bool $isBytes
     * @param bool $isJedec
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    public function getHumanReadable($input, $decimals = 2, $isBytes = true, $isJedec = true, $spacer = ' ', $decPoint = ".", $thousandsSep = ",")
    {
        $divisor = 1024;
        $standard = $isJedec ? 'jedec' : 'iec';
        $standardOption = $isBytes ? 'bytes' : 'bits';
        $suffixes = $this->suffixes[$standard][$standardOption];
        if($input == 0) {
            return '0'.$spacer.$suffixes[0];
        }
        if(!is_numeric($input)) {
            return false;
        }
        if($input < 0) {
            // Inverting negative values
            $input *= -1;
        }

        $units = (int) floor(log10($input)/log10($divisor));
        $maxUnits = count($suffixes) - 1;
        if($units == 0) {
            $decimals = 0;
        } else if ($units > $maxUnits) {
            $units = $maxUnits;
        }

        $output = $input / pow(2, $units * 10);
        if (!$isBytes) {
            $output *= 8;
            if ($output > $divisor) {
                $output = $output / $divisor;
                $units++;
            }
        }

        return number_format($output, $decimals, $decPoint, $thousandsSep).$spacer.$suffixes[$units];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file_size_extension';
    }
}