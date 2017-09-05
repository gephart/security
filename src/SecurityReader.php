<?php

namespace Gephart\Security;

use Gephart\Annotation\Reader;
use Gephart\Security\Configuration\SecurityConfiguration;

/**
 * Security reader
 *
 * @package Gephart\Security
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.3
 */
class SecurityReader
{

    /**
     * @var Reader
     */
    private $annotation_reader;

    /**
     * @param Reader $annotation_reader
     */
    public function __construct(Reader $annotation_reader)
    {
        $this->annotation_reader = $annotation_reader;
    }

    /**
     * Return must have role for access.
     *
     * @param string $controller
     * @param string $method
     * @return string
     */
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