<?php

namespace Gephart\Security;

use Gephart\Annotation\Reader;
use Gephart\Security\Configuration\SecurityConfiguration;

class SecurityReader
{

    /**
     * @var SecurityConfiguration
     */
    private $security_configuration;

    /**
     * @var Reader
     */
    private $annotation_reader;

    public function __construct(SecurityConfiguration $security_configuration, Reader $annotation_reader)
    {
        $this->security_configuration = $security_configuration;
        $this->annotation_reader = $annotation_reader;
    }

    public function getMustHaveRole(string $controller, string $method)
    {
        $annotation = $this->annotation_reader->get("Security", $controller, $method);
        print_r($annotation);
    }

}