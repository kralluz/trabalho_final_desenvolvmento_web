<?php
// Policies/AdsensePolicy.php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Adsense.php';

class AdsensePolicy
{
    public static function view(User $user, Adsense $post): bool
    {
        return true; // everyone can view
    }

    public static function update(User $user, Adsense $post): bool
    {
        return $user->id === $post->user_id;
    }

    public static function delete(User $user, Adsense $post): bool
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }
}
