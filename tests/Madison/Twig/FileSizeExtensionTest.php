<?php

namespace Madison\Twig;

/**
 * Class FileSizeExtensionTest
 * @package Madison\Twig\Extensions
 */
class FileSizeExtensionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param string $template
     * @return \Twig_Environment
     */
    private function buildEnv($template) {
        $loader = new \Twig_Loader_Array(array(
            'template' => $template,
        ));
        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new FileSizeExtension());
        return $twig;
    }

    /**
     * @param string $template
     * @param array $context
     * @return string
     */
    private function process($template, $context) {
        $twig = $this->buildEnv($template);
        $result = $twig->render('template', $context);
        return $result;
    }

    /**
     * @param int $expected
     * @param string $template
     * @param array $context
     * @internal param string $input
     */
    private function check($expected, $template, $context) {
        $result = $this->process($template, $context);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider provider
     * @param int|string $input
     * @param int $output
     * @param string $template
     */
    public function testFileSize($input, $output, $template) {
        $this->check($output, $template, ['input' => $input]);
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            [12534, '12.24 KB', '{{ input|human_readable() }}'],
            ['456658678000', '425 GB', '{{ input|human_readable(0) }}'],
            ['23434545', '179Mb', '{{ input|human_readable(0, false, true, "") }}'],
            [0, '0 B', '{{ input|human_readable(0) }}'],
            ['0000000', '0b', '{{ input|human_readable(0, false, true, "") }}'],
            ['9897545665', '9 GiB', '{{ input|human_readable(0, true, false) }}'],
            [435456457567567, '396.05 TB', '{{ input|human_readable(2, true, true) }}'],
            ['09687657455', '9.0223 GB', '{{ input|human_readable(4) }}'],
            [1765856546534665756456456476575, '1,460,682.3 YB', '{{ input|human_readable(1) }}'],
            [34566, '270,0 Kib', '{{ input|human_readable(1, false, false, " ", ",") }}'],
            ["-12432", '12,1 KiB', '{{ input|human_readable(1, true, false, " ", ",") }}'],
        ];
    }
}