<?php

namespace SiteCore\Components\Template;

/**
 * Class View
 */
class View
{

    private $data = [];
    private $templateBaseDir = "";

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
    public function assign(string $variable, $data)
    {
        $this->data[$variable] = $data;
    }

    function fetch(string $template, array $data = []) {

        $templateFile = $this->templateBaseDir . $template;
        $templateData = array_merge($this->data, (array)$data);

        if (!is_file($templateFile)) {
            throw new \Exception('Template not found: ' . $templateFile);
        }

        if (!is_readable($templateFile)) {
            throw new \Exception('Template not readable: ' . $templateFile);
        }

        $result = function ($templateFile, $templateData) {
            ob_start();
            extract($templateData, EXTR_SKIP);
            try {

                include $templateFile;
            } catch (\Exception $e) {
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
     * @return Response
     * @throws Exception
     */
    function render(string $template, array $data = []) # : Response
    {
        echo $this->fetch($template, $data);
    }

}