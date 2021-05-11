<?php

namespace SiteCore\Components\Template;

use Exception;

/**
 * Class View
 */
class View
{

    private $data = [];
    private $templateBaseDir;

    /**
     * View constructor.
     * @param string $templateBaseDir
     */
    public function __construct($templateBaseDir = "") {
        $this->templateBaseDir = $templateBaseDir;
    }

    /**
     * @param string $variable
     * @param mixed $data
     */
    public function assign(string $variable, $data): void
    {
        $this->data[$variable] = $data;
    }

    /**
     * @param string $template
     * @param array $data
     * @return false|string
     * @throws Exception
     */
    public function fetch(string $template, array $data = []) {

        $templateFile = $this->templateBaseDir . $template;
        $templateData = array_merge($this->data, $data);

        if (!is_file($templateFile)) {
            throw new Exception('Template not found: ' . $templateFile);
        }

        if (!is_readable($templateFile)) {
            throw new Exception('Template not readable: ' . $templateFile);
        }

        $result = static function ($templateFile, $templateData) {
            ob_start();
            extract($templateData, EXTR_SKIP);
            try {
                include $templateFile;
            } catch (Exception $e) {
                ob_end_clean();
                throw $e;
            }
            return ob_get_clean();
        };

        return $result($templateFile, $templateData);
    }

    /**
     * @param string $template
     * @param array $data
     * @throws Exception
     */
    public function render(string $template, array $data = []) : void # : Response
    {
        echo $this->fetch($template, $data);
    }

}