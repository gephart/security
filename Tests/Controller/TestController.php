<?php

class TestController
{

    /**
     * @Security ROLE_USER
     */
    public function roleUserAction() {
        return true;
    }

    /**
     * @Security ROLE_ADMIN
     */
    public function roleAdminAction() {
        return true;
    }
    
}