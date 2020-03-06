<?php
namespace Yjtec\Socialite\Contracts;

interface User
{
    /**
     * Get the unique identifier for the user
     * @return string
     */
    public function getId();
    /**
     * get the nickname/username for the user
     * @return string
     */
    public function getNickName();

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get the avatar / image URL for the user.
     *
     * @return string
     */
    public function getAvatar();
}
