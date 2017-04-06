<?php

namespace Gephart\Security;

use Gephart\Annotation\Reader;
use Gephart\Security\Configuration\SecurityConfiguration;

class SecurityReader
{

    /**
     * @var Reader
     */
    private $annotation_reader;

    public function __construct(Reader $annotation_reader)
    {
        $this->annotation_reader = $annotation_reader;
    }

    public function getMustHaveRole(string $controller, string $method)
    {
        $security_controller = $this->annotation_reader->get("Security", $controller);
        $security_method = $this->annotation_reader->get("Security", $controller, $method);

        if ($security_method) {
            return $security_method;
        }
        return $security_controller;
    }

}